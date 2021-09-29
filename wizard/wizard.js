/** @type{HTMLButtonElement} */
const next = document.querySelector("#next");
/** @type{HTMLButtonElement} */
const back = document.querySelector("#back");
/** @type{HTMLButtonElement} */
const finish = document.querySelector("#finish");

/** @type{HTMLElement} */
const slider = document.querySelector("main");

const slides = slider.querySelectorAll("section");
const total_slides = slides.length;

const titles = document.querySelectorAll(".step-label");

let current_slide = 0;

const SLIDE = {
  welcome: 0,
  setup: 1,
  license: 2,
  finalize: 3,
  connect: 4,
  done: 5,
};

set_slide(current_slide);

next.addEventListener("click", () => {
  next_slide();
});

back.addEventListener("click", () => {
  prev_slide();
});

function next_slide() {
  // save settings if current slide was license settings
  if (current_slide == SLIDE.license) save_settings();

  // mark current slider as done
  if (current_slide < titles.length) {
    titles[current_slide].classList.add("done");
  }

  // change slide
  current_slide = ++current_slide % total_slides;
  set_slide(current_slide);

  // disable next if license inactive
  next.disabled = current_slide == SLIDE.license && !license_active;
}

function prev_slide() {
  current_slide = Math.max(0, --current_slide);
  set_slide(current_slide);

  // enable next if slide changed
  next.disabled = current_slide == SLIDE.license && !license_active;
}

function set_slide(num) {
  // change slide
  for (const slide of slides) {
    slide.style.transform = `translateX(-${num}00%)`;
    slide.classList.remove("active");
  }

  slides[num].classList.add("active");

  // next and finish button
  if (num == total_slides - 1) {
    next.classList.add("hidden");
    finish.classList.remove("hidden");
  } else {
    next.classList.remove("hidden");
    finish.classList.add("hidden");
  }

  //   Back button
  if (num == 0) {
    back.disabled = true;
  } else {
    back.disabled = false;
  }

  //   Slider Titles
  for (const title of titles) {
    title.classList.remove("active");
  }

  if (num < titles.length) {
    titles[num].classList.add("active");
  }

  // New Slide Actions
  if (num == SLIDE.connect) {
    send_mail();
  }
}
