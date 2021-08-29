{
  /** @type{HTMLDivElement} */
  const popup = document.querySelector(".insta-popup");
  const background = document.querySelector(".insta-background");
  /** @type{HTMLButtonElement} */
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

    if (trigger == "hover") {
      opener.addEventListener("mouseenter", () => {
        popup.classList.add("popup-active");
        background.classList.add("popup-active");

        insta.start();
      });

      popup.addEventListener("mouseenter", () => {
        popup.classList.add("popup-active");
        background.classList.add("popup-active");

        insta.start();
      });

      popup.addEventListener("mouseleave", () => {
        popup.classList.remove("popup-active");
        background.classList.remove("popup-active");

        insta.stop();
      });
    }
  }

  function toggle_insta() {
    is_open = !is_open;
    if (is_open) insta.start();
    else insta.stop();
  }
}
