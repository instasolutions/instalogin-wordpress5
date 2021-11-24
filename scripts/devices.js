{
  // script requires insta_nonce, is_frontend, show_activation provided by php
  const { __ } = wp.i18n;

  // Load devices and hide activation or management options
  async function setup(__) {
    if (!wpv.is_frontend) draw_table_backend(__);

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

    const devices = await fetch_devices();
    if (!wpv.is_frontend) {
      if (devices && devices.length > 0 && wpv.show_activation !== "1") {
        for (const activate of activators) {
          activate.style.display = "none";
        }
      } else {
        for (const refresh of refreshers) {
          // refresh.style.display = "none";
        }
      }
    }

    // add device button

    if (wpv.is_frontend) {
      /** @type{HTMLDivElement} */
      const modal = document.createElement("div");
      modal.classList.add("instalogin-modal");
      modal.innerHTML = `
        <span class="instalogin-modal-text">
          ${__("An email will be sent to", "instalogin-me")} <br>
          <b>${wpv.email}</b>
          <br>${__("Are you sure?", "instalogin-me")}
        </span>  
        <div style="margin-top: 1rem;">
          <button class="instalogin-modal-button no instalogin-device-button">${__(
            "Cancel",
            "instalogin-me"
          )}</button>
          <button class="instalogin-modal-button yes instalogin-device-button">${__(
            "Send Mail",
            "instalogin-me"
          )}</button>
       </div>
     `;

      const modal_button_no = modal.querySelector(".no");
      const modal_button_yes = modal.querySelector(".yes");

      modal_button_no.addEventListener("click", async (event) => {
        event.preventDefault();
        modal.classList.remove("active");
      });

      modal_button_yes.addEventListener("click", async (event) => {
        event.preventDefault();

        const old_label = modal_button_yes.innerText;
        modal_button_yes.innerText = "...";
        const response = await fetch(insta_api + "device/add", {
          method: "post",
          body: JSON.stringify({ user_id: wpv.user_id }),
          headers: {
            "Content-Type": "application/json",
            "X-WP-NONCE": wpv.insta_nonce,
          },
        });

        modal_button_yes.innerText = old_label;
        modal_button_yes.style.display = "none";
        modal_button_no.innerText = "Close";

        const info_area = modal.querySelector(".instalogin-modal-text");
        if (response.ok) {
          const json = await response.json();
          // info_area.innerText = __(`Email has been sent to ${json.sent_to} !`, 'instalogin');
          info_area.innerHTML = `<p>${__(
            "Email has been sent!",
            "instalogin-me"
          )}</p>`;
        } else {
          const body = await response.text();
          // info_area.innerText = __(`Email has been sent to ${json.sent_to} !`, 'instalogin');
          info_area.innerHTML = `${__(
            "Email could not be sent!",
            "instalogin-me"
          )}<br>${__(
            "Please try again later or contact an administrator.",
            "instalogin-me"
          )}<br>${body}`;
          console.error("instalogin: could not send mail.", response);
        }

        // modal.classList.remove("active");
      });

      const devices_container = document.querySelector(".instalogin-devices");

      devices_container.appendChild(modal);

      const add_device_buttons = document.querySelectorAll(
        ".instalogin-add-device"
      );

      for (const button of add_device_buttons) {
        button.addEventListener("click", (event) => {
          event.preventDefault();
          modal.classList.add("active");
        });
      }
    }
  }

  function draw_table_backend(__) {
    const device_container = document.querySelector(
      ".instalogin-devices-admin"
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
                    <th class="row-title">Created at</th>
                </tr>
            </thead>

            <tbody class="instalogin-device-table">
            </tbody>

        </table>
        <button class="insta-button instalogin-send-mail">${__(
          "Add Device",
          "instalogin-me"
        )}</button>
        <button class="insta-button instalogin-refresh">${__(
          "Refresh",
          "instalogin-me"
        )}</button>
    </div>`;
    }
  }

  // (Re)build list HTML
  function draw_devices_backend(devices) {
    const device_table = document.querySelector(".instalogin-device-table");
    // const device_list = document.querySelector(".instalogin-device-list");
    if (device_table) {
      device_table.innerHTML = "";

      for (const device of devices) {
        const tr = document.createElement("tr");

        const label = document.createElement("td");
        label.innerText = device.label;

        const model = document.createElement("td");
        model.innerText = device.model;

        const created_at = document.createElement("td");
        const date = new Date(device.created_at);
        created_at.innerText = `${date.toLocaleTimeString()} - ${date.toLocaleDateString()}`;

        const delete_td = document.createElement("td");

        const delete_button = document.createElement("a");
        delete_button.href = "#";
        delete_button.classList.add("insta-delete");
        delete_button.innerText = "Delete";

        delete_td.appendChild(delete_button);

        tr.appendChild(label);
        tr.appendChild(model);
        tr.appendChild(created_at);
        tr.appendChild(delete_td);

        // delete
        // const actions_div = document.createElement("div");
        // actions_div.classList.add("row-actions");

        // const delete_span = document.createElement("span");
        // delete_span.classList.add("trash");

        // label.appendChild(actions_div);
        // actions_div.appendChild(delete_span);
        // delete_span.appendChild(delete_button);

        let clicked_once = false;

        //
        delete_button.addEventListener("click", async (event) => {
          event.preventDefault();

          if (!clicked_once) {
            clicked_once = true;
            delete_button.innerText = __("Confirm deletion", "instalogin-me");
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
    }
  }

  // (Re)build list HTML
  function draw_devices_frontend(devices) {
    const device_lists = document.querySelectorAll(".instalogin-device-list");

    for (const device_list of device_lists) {
      device_list.innerHTML = "";

      if (devices.length == 0) {
        device_list.innerHTML = __("No devices connected.", "instalogin-me");
      }

      for (const device of devices) {
        const li = document.createElement("li");
        li.innerHTML = `
          <div class="instalogin-device-entry">
            <span class='instalogin-device-label'>${device.label}</span>
            <small class='instalogin-device-model'>${device.model}</small>
          </div>
        `;

        // Delete Button
        /** @type{HTMLButtonElement} */
        const delete_button = document.createElement("button");
        delete_button.innerHTML = `<svg class="svg-inline--fa fa-times fa-w-10" data-prefix="far" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M207.6 256l107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z"></path></svg>`;

        // Modal

        /** @type{HTMLDivElement} */
        const delete_modal = document.createElement("div");
        delete_modal.classList.add("instalogin-modal");
        delete_modal.innerHTML = `
          <b>Remove device?</b><br>
          ${device.model}
          <div style="margin-top: 1rem;">
            <button class="instalogin-modal-button no instalogin-device-button">${__(
              "No",
              "instalogin-me"
            )}</button>
            <button class="instalogin-modal-button yes instalogin-device-button">${__(
              "Yes",
              "instalogin-me"
            )}</button>
          </div>
        `;

        delete_button.addEventListener("click", (event) => {
          event.preventDefault();

          delete_modal.classList.add("active");
        });

        const modal_button_no = delete_modal.querySelector(".no");
        const modal_button_yes = delete_modal.querySelector(".yes");

        modal_button_no.addEventListener("click", async (event) => {
          event.preventDefault();
          delete_modal.classList.remove("active");
        });

        modal_button_yes.addEventListener("click", async (event) => {
          event.preventDefault();

          if (await delete_device(device.id)) {
            await fetch_devices();
          } else {
            // TODO: Show error
            console.error("Could not remove device.");
          }

          delete_modal.classList.remove("active");
        });

        li.appendChild(delete_button);
        device_list.appendChild(li);
        device_list.appendChild(delete_modal);
      }
    }
  }

  // API call to delete a device
  async function delete_device(id) {
    const response = await fetch(insta_api + "device/remove", {
      method: "post",
      body: JSON.stringify({ device_id: id }),
      headers: {
        "Content-Type": "application/json",
        "X-WP-NONCE": wpv.insta_nonce,
      },
    });

    return response.ok;
  }

  // API call to fetch enabled devices
  async function fetch_devices() {
    const response = await fetch(insta_api + "devices", {
      method: "get",
      headers: { "X-WP-NONCE": wpv.insta_nonce },
    });

    if (response.ok) {
      const json = await response.json();
      if (wpv.is_frontend) draw_devices_frontend(json);
      else draw_devices_backend(json);
      return json;
    } else {
      // TODO: show error message
    }
  }

  //
  setup(__);
}
