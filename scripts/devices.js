{
  // script requires insta_nonce provided by php

  // Load devices and hide activation or management options
  async function setup() {
    draw_table();

    const refreshers = document.querySelectorAll(".instalogin-refresh");
    const activators = document.querySelectorAll(".instalogin-activate");

    for (const refresh of refreshers) {
      refresh.addEventListener("click", async (event) => {
        event.preventDefault();
        let old_label = refresh.innerText;
        refresh.innerText = "...";

        await fetch_devices();
        refresh.innerText = old_label;
      });
    }

    console.log(wpv);
    console.log(wpv.show_activation !== "1");
    const devices = await fetch_devices();
    if (devices && devices.length > 0 && wpv.show_activation !== "1") {
      console.log("doing it", wpv.show_activation !== "1");
      console.log(wpv);
      for (const activate of activators) {
        activate.style.display = "none";
      }
    } else {
      for (const refresh of refreshers) {
        refresh.style.display = "none";
      }
    }
  }

  function draw_table() {
    const device_container = document.querySelector(
      ".instalogin-devices-container"
    );
    if (device_container) {
      // TODO: translate
      device_container.innerHTML = `
      <div>
        <table class="widefat" role="presentation">
            <thead>
                <tr>
                    <th class="row-title">Label</th>
                    <th class="row-title">Device</th>
                    <th class="row-title">Create at</th>
                </tr>
            </thead>

            <tbody class="instalogin-device-table">
            </tbody>

            <tfoot>
                <tr>
                    <th>
                        <button class="button instalogin-refresh">Refresh</button>
                        <button class="button-primary instalogin-send-mail">Add Device</button>
                    </th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>

        </table>
    </div>`;
    }
  }

  // (Re)build list HTML
  function draw_devices(devices) {
    const device_table = document.querySelector(".instalogin-device-table");
    // const device_list = document.querySelector(".instalogin-device-list");
    if (device_table) {
      device_table.innerHTML = "";

      for (const device of devices) {
        const tr = document.createElement("tr");
        // tr.innerText = `${device.label} ${device.model}`;

        const label = document.createElement("td");
        label.innerText = device.label;

        const model = document.createElement("td");
        model.innerText = device.model;

        const created_at = document.createElement("td");
        const date = new Date(device.created_at);
        created_at.innerText = `${date.toLocaleTimeString()} - ${date.toLocaleDateString()}`;
        console.log(device);

        tr.appendChild(label);
        tr.appendChild(model);
        tr.appendChild(created_at);

        // delete
        const actions_div = document.createElement("div");
        actions_div.classList.add("row-actions");

        const delete_span = document.createElement("span");
        delete_span.classList.add("trash");

        const delete_button = document.createElement("a");
        delete_button.href = "#";
        delete_button.classList.add("submitdelete");
        // TODO: translation
        delete_button.innerText = "Delete";

        label.appendChild(actions_div);
        actions_div.appendChild(delete_span);
        delete_span.appendChild(delete_button);

        let clicked_once = false;

        //
        delete_button.addEventListener("click", async (event) => {
          event.preventDefault();

          if (!clicked_once) {
            clicked_once = true;
            delete_button.innerText = "Confirm deletion";
            return;
          }

          delete_button.innerText = "...";
          delete_button.disabled = true;

          if (await delete_device(device.id)) {
            await fetch_devices();
          } else {
            // TODO: Show error
          }
        });

        device_table.appendChild(tr);
      }
      // for (const device of devices) {
      //   const li = document.createElement("li");
      //   li.innerText = `${device.label} ${device.model}`;

      //   const button = document.createElement("button");
      //   button.innerText = "x";
      //   let clicked_once = false;

      //   button.addEventListener("click", async (event) => {
      //     event.preventDefault();

      //     if (!clicked_once) {
      //       clicked_once = true;
      //       button.innerText = "Confirm deletion";
      //       return;
      //     }

      //     button.innerText = "...";
      //     button.disabled = true;

      //     if (await delete_device(device.id)) {
      //       await fetch_devices();
      //     } else {
      //       // TODO: Show error
      //     }
      //   });

      //   li.appendChild(button);
      //   device_list.appendChild(li);
      // }
    }
  }

  // API call to delete a device
  async function delete_device(id) {
    const response = await fetch(
      "/index.php/wp-json/instalogin/v1/device/remove",
      {
        method: "post",
        body: JSON.stringify({ device_id: id }),
        headers: {
          "Content-Type": "application/json",
          "X-WP-NONCE": wpv.insta_nonce,
        },
      }
    );

    return response.ok;
  }

  // API call to fetch enabled devices
  async function fetch_devices() {
    const response = await fetch(
      "/index.php/wp-json/instalogin/v1/devices?wp_nonce",
      {
        method: "get",
        headers: { "X-WP-NONCE": wpv.insta_nonce },
      }
    );

    if (response.ok) {
      const json = await response.json();
      draw_devices(json);
      return json;
    } else {
      // TODO: show error message
    }
  }

  //
  setup();
}
