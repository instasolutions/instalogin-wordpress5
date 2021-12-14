// scans page for media selectors and activates them
{
  const selectors = document.querySelectorAll(".media-selector");
  for (const selector of selectors) {
    /** @type {HTMLButtonElement} */
    const button = selector.querySelector("button");
    /** @type {HTMLInputElement} */
    const idInput = selector.querySelector("input[type=hidden]");
    /** @type {HTMLImageElement} */
    let img = selector.querySelector("#selected-media");

    if (!img) img = selector.querySelector("img");

    setupMediaSelect(button, idInput, img);
  }

  /**
   *
   * @param {HTMLButtonElement} button
   * @param {HTMLInputElement} idInput
   * @param {HTMLImageElement} img
   */
  function setupMediaSelect(button, idInput, img) {
    let meta_image_frame;

    button.addEventListener("click", (event) => {
      event.preventDefault();

      if (meta_image_frame) {
        meta_image_frame.open();
        return;
      }

      meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
        title: idInput.title,
        button: { text: button.textContent },
        library: { type: "image" },
      });

      meta_image_frame.on("select", () => {
        let attachment = meta_image_frame
          .state()
          .get("selection")
          .first()
          .toJSON();
        idInput.value = attachment.url;
        console.log(attachment);
        img.src = attachment.url;
      });

      meta_image_frame.open();
    });
  }
}
