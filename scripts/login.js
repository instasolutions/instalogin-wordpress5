function init_insta(id, api_key, display_type, redirect) {
  console.log("insta: creating qr code");

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

  let url = insta_api + "login-controller";
  console.log(redirect);
  if (redirect && redirect != "") {
    if (redirect == "stayonpage") {
      redirect = window.location.href;
    }

    url += "?redirect=" + redirect;
  }

  // instance may be used by other scripts
  return new Instalogin.Auth({
    id,
    key: api_key,
    useAuthHeader: false,
    authenticationUrl: url,
    type: display_type,
    removeLabel: true,
  }).start();
}
