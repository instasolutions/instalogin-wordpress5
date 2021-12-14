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
        const response = await fetch(insta_api + "device/add", {
          method: "post",
          body: JSON.stringify({ user_id }),
          headers: {
            "Content-Type": "application/json",
            "X-WP-NONCE": insta_nonce,
          },
        });

        button.innerText = old_label;

        // const info_area = document.querySelector(".instalogin-info-area");
        if (response.ok) {
          const json = await response.json();

          /** @type{HTMLDivElement} */
          const notification = document.querySelector(
            ".insta-success-notification"
          );

          notification.style.display = "block";

          notification.innerHTML = `<p>${__(
            "Email has been sent to",
            "instalogin-me"
          )} <b>${json.sent_to}</b> !</p>`;

          // info_area.appendChild(box);
        } else {
          const body = await response.text();

          /** @type{HTMLDivElement} */
          const notification = document.querySelector(
            ".insta-error-notification"
          );
          notification.style.display = "block";

          console.error("instalogin: could not send mail", response);
          notification.innerHTML = `${__(
            "Email could not be sent!",
            "instalogin-me"
          )}!<br> ${__(
            "Please try again later or contact an administrator",
            "instalogin-me"
          )}.<br>${body}`;

          console.error(response);
        }
      });
    }
  }

  setup();
}
