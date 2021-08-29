console.log("Instalogin installation wizard");

// const { user_id, nonce } = wiard_data;

let settings_saved = false;

// Form

/** @type {HTMLInputElement} */
const e_api_key = document.querySelector("#api_key");
/** @type {HTMLInputElement} */
const e_api_secret = document.querySelector("#api_secret");
/** @type {HTMLInputElement} */
const e_enable_registration = document.querySelector("#enable_registration");
/** @type {HTMLInputElement} */
const e_code_type = document.querySelector("#code_type");

/** @type {HTMLButtonElement} */
const button_save = document.querySelector(".btn-save");

async function save_options() {
  // Check values

  const api_key = e_api_key.value.trim();
  const api_secret = e_api_secret.value.trim();

  const e_info_key = document.querySelector(".info-key");
  const e_info_secret = document.querySelector(".info-secret");

  e_info_key.style.display = "none";
  e_info_secret.style.display = "none";

  if (api_key.length != 32) {
    if (e_info_key) e_info_key.style.display = "block";
    return;
  }

  if (api_secret.length != 64) {
    if (e_info_secret) e_info_secret.style.display = "block";
    return;
  }

  // Save

  const response = await fetch(
    "/index.php/wp-json/instalogin/v1/wizard/settings",
    {
      method: "post",
      body: JSON.stringify({
        api_key,
        api_secret,
        enable_registration: e_enable_registration.checked,
        code_type: e_code_type.value,
      }),
      headers: {
        "Content-Type": "application/json",
        "X-WP-NONCE": nonce,
      },
    }
  );

  if (response.ok) {
    console.log("Options saved successfully.");
  } else {
    console.error("Could not save options", response);
  }

  return response.ok;
}

button_save.addEventListener("click", async (event) => {
  event.preventDefault();
  const success = await save_options();

  // TODO: show error
  if (success) {
    button_next.disabled = false;
    settings_saved = true;
    button_save.disabled = true;
    button_save.blur();
    button_save.innerText = "Einstellungen gespeichert!";

    e_api_key.disabled = true;
    e_api_secret.disabled = true;
    e_enable_registration.disabled = true;
    e_code_type.disabled = true;
  } else console.error("Could not save");
});

// Send Mail

const button_mail = document.querySelector(".btn-mail");

button_mail.addEventListener("click", async (event) => {
  event.preventDefault();
  button_mail.innerText = "...";
  button_mail.disabled = true;
  button_mail.blur();

  const response = await fetch("/index.php/wp-json/instalogin/v1/device/add", {
    method: "post",
    body: JSON.stringify({ user_id }),
    headers: {
      "Content-Type": "application/json",
      "X-WP-NONCE": nonce,
    },
  });

  if (response.ok) {
    const json = await response.json();
    // button_mail.innerHTML = __("Email has been sent!", "instalogin");
    button_mail.innerHTML = "Email versendet!";
  } else {
    const body = await response.text();
    console.error("instalogin: could not send mail", response);

    // button_mail.innerHTML = `${__(
    //   "Email could not be sent!",
    //   "instalogin"
    // )}<br>${body}`;

    button_mail.innerHTML = `Email konnte nicht versendet werden.<br>${body}`;
  }
});

// Stepper

/** @type {HTMLDivElement[]} */
const step_indicators = document.querySelectorAll(".step");
/** @type {HTMLDivElement[]} */
const step_contents = document.querySelectorAll(".step-content");

/** @type {HTMLButtonElement} */
const button_next = document.querySelector(".step-next");
/** @type {HTMLButtonElement} */
const button_back = document.querySelector(".step-back");

// Used to know on which page to start the saving process.
const SETTINGS_PAGE_INDEX = 1;

let current_step = 0;
const max_step_index = step_indicators.length - 1;

if (step_contents.length !== step_indicators.length)
  console.error(
    `Amount of contents(${step_contents.length}) does not match amount of indicators(${step_indicators.length})!`
  );

// Setup

// set icon numbers
for (let i = 0; i < step_indicators.length; i++) {
  const step = step_indicators[i];
  const icon = step.querySelector(".icon");
  icon.innerHTML = i + 1;

  step.index = i;
  step.addEventListener("click", (event) => {
    event.preventDefault();
    current_step = step.index;
    set_active_step(current_step);
  });
}

set_active_step(0);

// Next / Back

button_next.addEventListener("click", async (event) => {
  event.preventDefault();

  // Switch page
  current_step = Math.min(max_step_index, current_step + 1);
  set_active_step(current_step);

  // switched to settings page
  if (current_step == SETTINGS_PAGE_INDEX && !settings_saved) {
    button_next.disabled = true;
    button_next.blur();
  }
});

button_back.addEventListener("click", (event) => {
  event.preventDefault();
  current_step = Math.max(0, current_step - 1);
  set_active_step(current_step);
});

function set_active_step(index) {
  console.log(`Setting active step to ${index}`);

  for (const step of step_indicators) {
    step.classList.remove("active");
  }

  for (const step of step_contents) {
    step.style.display = "none";
  }

  button_back.disabled = index === 0;
  button_next.disabled = index === max_step_index;
  button_back.blur();
  button_next.blur();

  const step_content = step_contents[index];
  step_content.style.display = "block";

  const step_indicator = step_indicators[index];
  step_indicator.classList.add("active");
}
