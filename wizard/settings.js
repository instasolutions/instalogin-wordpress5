let mail_sent = false;
let license_active = false;

/** @type{HTMLButtonElement} */
const activate_button = document.querySelector(".activate");
activate_button.addEventListener("click", async () => {
  await activate();
});

async function send_mail() {
  if (mail_sent) {
    console.warn("Mail has been sent already");
    return;
  }

  const response = await fetch(insta_api + "device/add", {
    method: "post",
    body: JSON.stringify({ user_id }),
    headers: {
      "Content-Type": "application/json",
      "X-WP-NONCE": nonce,
    },
  });

  if (response.ok) mail_sent = true;

  console.log("Mail sent:", response);
}

async function save_settings() {
  const enable_instalogin =
    document.querySelector("#enable_instalogin").checked;
  const enable_registration = document.querySelector(
    "#enable_registration"
  ).checked;
  const redirect = document.querySelector("#redirect").value;
  const code_type = document.querySelector("#code_type").value;

  const response = await fetch(insta_api + "wizard/settings", {
    method: "post",
    body: JSON.stringify({
      enable_instalogin,
      enable_registration,
      code_type,
      redirect,
    }),
    headers: {
      "Content-Type": "application/json",
      "X-WP-NONCE": nonce,
    },
  });

  console.log("save settings", response);
}

async function activate() {
  const key = document.querySelector("#api_key");
  const secret = document.querySelector("#api_secret");
  /** @type{HTMLButtonElement} */
  const button = document.querySelector(".activate");

  const response = await fetch(insta_api + "verify_credentials", {
    method: "post",
    body: JSON.stringify({
      key: key.value,
      secret: secret.value,
    }),
    headers: {
      "Content-Type": "application/json",
      "X-WP-NONCE": nonce,
    },
  });

  if (response.ok) {
    license_active = true;
    button.disabled = true;
    button.textContent = licence_active_text;
    button.style.background = "var(--green)";
    button.style.color = "white";
    button.style.border = "none";
    button.style.opacity = "1";
    document.querySelector("#next").disabled = false;
  } else {
    license_active = false;
    button.disabled = false;
    button.textContent = licence_bad_text;
    button.style.background = "var(--red-dark)";
    button.style.color = "white";
    button.style.border = "none";
    button.style.opacity = "1";
  }
  console.log("license activation", response);
}
