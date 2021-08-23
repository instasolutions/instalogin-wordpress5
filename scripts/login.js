console.log("insta: loading qr code");
/** @type {HTMLButtonElement} */
let add_after_element = document.querySelector("p.submit");

if (!add_after_element) {
  console.warn(
    "insta: could not find Button `Log In`. Trying to find shortcode container."
  );
} else {
  const div = document.createElement("div");
  div.id = "instalogin";
  add_after_element.after(div);
}

//api_key, display_type passed via wp_localize_script
const insta = new Instalogin.Auth({
  key: api_key,
  useAuthHeader: false,
  authenticationUrl: "/index.php/wp-json/instalogin/v1/login-controller",
  type: display_type,
}).start();

console.log(insta);
