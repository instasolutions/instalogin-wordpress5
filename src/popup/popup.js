{
  const containers = document.querySelectorAll(".insta-popup-container");
  let is_open = false;

  for (const container of containers) {
    /** @type{HTMLDivElement} */
    const popup = container.querySelector(".insta-popup");
    console.log(popup);

    const background = container.querySelector(".insta-background");
    /** @type{NodeListOf<HTMLButtonElement>} */
    const openers = container.querySelectorAll(".insta-opener");

    if (popup && openers.length > 0 && background) {
      insta.stop();

      background.addEventListener("click", (event) => {
        event.preventDefault();
        if (!event.target.closest(".insta-popup")) {
          popup.classList.toggle("popup-active");
          background.classList.toggle("popup-active");
          toggle_insta(popup);
        }
      });

      for (const opener of openers) {
        opener.addEventListener("click", (event) => {
          event.preventDefault();
          popup.classList.toggle("popup-active");
          background.classList.toggle("popup-active");
          toggle_insta(popup);
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
    }
  }

  function toggle_insta(popup) {
    popup.is_open = !popup.is_open;
    if (popup.is_open) insta.start();
    else insta.stop();
  }
}
