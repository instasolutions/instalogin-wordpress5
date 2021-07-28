{
  // requires insta_nonce, user_id, is_frontend via php

  // Backend(Profile page): Scans page for `instalogin-send-mail` buttons and adds notice on mail send
  async function setup() {
    const { user_id, insta_nonce } = wpv_mail;
    const { __ } = wp.i18n;

    const send_mail_buttons = document.querySelectorAll(
      ".instalogin-send-mail"
    );

    for (const button of send_mail_buttons) {
      button.addEventListener("click", async (event) => {
        event.preventDefault();
        const old_label = button.innerText;
        button.innerText = "...";
        const response = await fetch(
          "/index.php/wp-json/instalogin/v1/device/add",
          {
            method: "post",
            body: JSON.stringify({ user_id }),
            headers: {
              "Content-Type": "application/json",
              "X-WP-NONCE": insta_nonce,
            },
          }
        );

        button.innerText = old_label;

        const info_area = document.querySelector(".instalogin-info-area");
        if (response.ok) {
          const json = await response.json();
          const box = document.createElement("div");
          box.classList.add(
            "notice",
            "notice-info",
            "is-dismissible",
            "inline"
          );
          box.innerHTML = `<p>${__("Email has been sent to", "instalogin")} <b>${json.sent_to}</b> !</p>`;
          info_area.appendChild(box);
        } else {
          const body = await response.text();
          const box = document.createElement("div");
          box.classList.add(
            "notice",
            "notice-error",
            "is-dismissible",
            "inline"
          );
          error.error("instalogin: could not send mail", response);
          // box.innerText = __(`Email has been sent to ${json.sent_to} !`, 'instalogin');
          box.innerHTML = `${__("Email could not be sent!", "instalogin")}!<br> ${__("Please try again later or contact an administrator", "instalogin")}.<br>${body}`;
          info_area.appendChild(box);
          console.error(response);
        }
      });
    }
  }

  setup();
}
