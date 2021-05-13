/** @type {HTMLButtonElement} */
const add_after_element = document.querySelector("p.submit");

if (add_after_element) {
  const div = document.createElement("div");
  div.id = "instalogin";
  add_after_element.after(div);

  //api_key passed via wp_localize_script
  let insta;

  document
    .getElementById("instalogin-js")
    .addEventListener("load", function () {
      insta = new Instalogin.Auth({
        key: api_key,
        authenticationUrl: "/wp-json/instalogin/v1/login-controller",
      }).start();
    });
} else {
  console.error(
    "instalog.in - could not find Button `Log In`. Aborted adding QR code."
  );
}
