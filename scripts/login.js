function init_insta(id, api_key, display_type) {
  console.log("insta: creating qr code");

  /** @type {HTMLButtonElement} */
  let add_after_element = document.querySelector("p.submit");

  if (!add_after_element) {
    console.warn(
      "insta: could not find Button `Log In`. Trying to find shortcode container."
    );
  } else {
    const div = document.createElement("div");
    id = "instalogin";
    div.id = id;
    add_after_element.after(div);
  }

  if (!id) {
    console.error(`Html container id missing.`);
    return;
  }
  if (!api_key) {
    console.error(`Insta api key missing.`);
    return;
  }

  if (!display_type) {
    console.warn(`Display type missing. Using qr code type.`);
    display_type = "qr";
  }

  // instance may be used by other scripts
  return new Instalogin.Auth({
    id,
    key: api_key,
    useAuthHeader: false,
    authenticationUrl: "/index.php/wp-json/instalogin/v1/login-controller",
    type: display_type,
  }).start();
}
