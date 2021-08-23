{
  const popup = document.querySelector(".insta-popup");
  const background = document.querySelector(".insta-background");
  const opener = document.querySelector(".insta-opener");

  let is_open = false;

  if (popup && opener && background) {
    insta.stop();

    background.addEventListener("click", (event) => {
      if (!event.target.closest(".insta-popup")) {
        popup.classList.toggle("popup-active");
        background.classList.toggle("popup-active");
        toggle_insta();
      }
    });

    opener.addEventListener("click", () => {
      popup.classList.toggle("popup-active");
      background.classList.toggle("popup-active");
      toggle_insta();
    });
  }

  function toggle_insta() {
    is_open = !is_open;
    if (is_open) insta.start();
    else insta.stop();
  }
}
