{
  const { __ } = wp.i18n;

  /** @type {HTMLFormElement[]} */
  const forms = document.querySelectorAll(".instalogin-register");

  for (const form of forms) {
    /** @type {HTMLInputElement} */
    const e_username = form.querySelector(".instalogin-username");
    /** @type {HTMLInputElement} */
    const e_email = form.querySelector(".instalogin-email");
    /** @type HTMLInputElement */
    const e_submit = form.querySelector(".instalogin-submit");
    /** @type HTMLParagraphElement */
    const e_error = form.querySelector(".instalogin-error");
    /** @type HTMLParagraphElement */
    const e_info = form.querySelector(".instalogin-info");

    form.addEventListener("submit", async (event) => {
      event.preventDefault();
      e_error.innerText = "";

      let username = null;
      if (e_username) username = e_username.value;
      const email = e_email.value;

      const body = { username, email };

      const response = await fetch(
        "/index.php/wp-json/instalogin/v1/register",
        {
          method: "post",
          body: JSON.stringify(body),
          headers: { "Content-Type": "application/json" },
        }
      );

      if (response.ok) {
        // TODO: Translation
        e_info.innerText = __(
          "Account created! Please check your inbox.",
          "instalogin"
        );
        e_info.style.display = "block";
        if (e_username) e_username.disabled = true;
        if (e_submit) e_submit.disabled = true;
        e_email.disabled = true;
      } else {
        const data = await response.json();
        console.error(data);
        e_error.style.display = "block";
        e_error.innerText = data.message;
      }
    });
  }
}
