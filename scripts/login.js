/** @type {HTMLButtonElement} */
const add_after_element = document.querySelector("p.submit");

if (add_after_element) {
  const div = document.createElement("div");
  div.id = "instalogin";
  add_after_element.after(div);

  let insta;

  document
    .getElementById("instalogin-js")
    .addEventListener("load", function () {
      insta = new Instalogin.Auth({
        key: "VluObzy1BoNiFcgm5OXSQun42pF9pFNx", // TODO: use setting variable
        authenticationUrl: "/wp-json/instalogin/v1/login-controller", // The authentication controller to process the authentication
      }).start();
    });
} else {
  console.error(
    "instalog.in - could not find Button `Log In`. Aborted adding QR code."
  );
}
