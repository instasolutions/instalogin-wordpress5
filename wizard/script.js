function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true,
    });
  } else {
    obj[key] = value;
  }
  return obj;
}
/**
 * 😐 I just came back to look at this.... What terrible code :( @TODO: REDO THIS!
 * This module is meant to add functionlity to a form stepper.
 * Currently it is just the UI and doesn't add anything on the backend.
 *
 * This project was more about practicing vanilla JS using classes and trying
 * to learn more about the `this` keyword within classes.
 *
 * @author Robert Todar <robert@roberttodar.com>
 * @license MIT
 */

/**
 * This is the main stepper class.
 * This expects an HTML element contains `.steps` container
 * that has `.step` elements. Also expects to find needed buttons.
 *
 * The way this class works is that it keeps track of the current active
 * steps index, and it emmits events `stepChanged` and `completed` to
 * the `step` class and all the button classes.
 *
 * Each of those classes then updates their UI as needed.
 *
 * @author Robert Todar <robert@roberttodar.com>
 */
class Stepper {
  constructor(stepperElement) {
    _defineProperty(this, "setActiveStep", (index) => {
      // Only run if actually changing the current step.
      if (this.activeIndex !== index) {
        // Change to specific index only if within steps range.
        if (index > -1 && index < this.steps.length) {
          this.activeIndex = index;
          // If not in range then default to first step.
        } else {
          this.activeIndex = 0;
        }
        this.emmitEvent("stepChanged");
        if (this.isComplete) this.emmitEvent("completed");
      }
    });
    this.stepperElement = stepperElement;
    this.events = { stepChanged: [], completed: [] };
    this.stepsContainer = stepperElement.querySelector(".steps");
    this.steps = [...this.stepsContainer.querySelectorAll(".step")].map(
      (step, index) => {
        return new Step(step, index, this);
      }
    );
    this.buttons = stepperElement.querySelector(".buttons");
    this.next = new NextButton(this.buttons.querySelector(".step-next"), this);
    this.back = new BackButton(this.buttons.querySelector(".step-back"), this);
    this.complete = new CompleteButton(
      this.buttons.querySelector(".step-complete"),
      this
    );
    this.finish = new FinishButton(
      this.buttons.querySelector(".step-finish"),
      this
    );
    this.setActiveStep(0);
    this.buttons.querySelector(".step-complete").focus();
  }
  nextStep() {
    this.setActiveStep(this.activeIndex + 1);
  }
  previousStep() {
    this.setActiveStep(this.activeIndex - 1);
  } // Add event listners to event object.
  listenForEvent(eventName, callback) {
    this.events[eventName].push(callback);
  } // @returns {boolean} True if all steps are complete.
  get isComplete() {
    return (
      this.steps.filter((step) => step.isComplete).length === this.steps.length
    );
  } // Runs all callbacks under the specified event key.
  emmitEvent(eventName) {
    this.events[eventName].forEach((callback) => {
      callback(this);
    });
  }
  /**
   * This is the main function for setting the active step. This also emmits all
   * Event listners for children classes.
   */
}
/**
 * Each step get's it's own logic and functionality. This will listen for
 * changes made to the current active step.
 *
 * It will update it's UI when it is: focused, blured, or completed.
 *
 * On init and reset() it set's its icon element to it's proper index.
 *
 * @author Robert Todar <robert@roberttodar.com>
 */ class Step {
  constructor(step, index, stepper) {
    _defineProperty(
      this,
      "stepChanged",

      ({ activeIndex }) => {
        if (activeIndex === this.index) {
          this.setFocus();
        } else {
          this.looseFocus();
        }
      }
    );
    this.step = step;
    this.index = index;
    this.stepper = stepper;
    this.icon = step.querySelector(".icon");
    this.isComplete = false;
    this.isActive = false;
    stepper.listenForEvent("stepChanged", this.stepChanged);
    this.mouseEvent = this.step.addEventListener("mouseup", () => {
      this.stepper.setActiveStep(this.index);
    });
    this.reset();
  } // Event - sees if UI should be updated from getting or loosing focus.

  setFocus() {
    if (!this.isActive) {
      this.step.classList.add("active");
      this.isActive = true;
    }
  }

  looseFocus() {
    if (this.isActive) {
      this.step.classList.remove("active");
      this.isActive = false;
    }
  }

  complete() {
    this.isComplete = true;
    this.icon.innerHTML = '<i class="material-icons">done</i>';
  }

  reset() {
    this.isComplete = false;
    this.icon.innerHTML = this.index + 1;
  }
}

/**
 * This is a generic Button class meant to be extended to all the other
 * buttons.
 *
 * It really just updates the UI from being disabled|enabled and displayed.
 *
 * @note I can't figure out how to have this.stepper.listenForEvent("stepChanged", this.stepChanged);
 *  within this class??
 */
class Button {
  constructor(el, stepper) {
    this.stepper = stepper;
    this.el = el;
    this.el.addEventListener("click", this.mouseup);
  }

  disable() {
    this.el.disabled = true;
  }

  enable() {
    this.el.disabled = false;
  }

  hide() {
    this.el.style.display = "none";
  }

  show() {
    this.el.style.display = "inline-block";
  }
}

/**
 * Next button moves the active step forward.
 * It is disabled if at the end of the steps.
 */
class NextButton extends Button {
  constructor(el, stepper) {
    super(el, stepper);
    _defineProperty(
      this,
      "stepChanged",

      ({ activeIndex, steps }) => {
        if (activeIndex === steps.length - 1) this.disable();
        if (activeIndex < steps.length - 1) this.enable();
      }
    );
    this.stepper.listenForEvent("stepChanged", this.stepChanged);
  }
  mouseup() {
    const { setActiveStep, activeIndex } = stepper;
    setActiveStep(activeIndex + 1);
  }
}

/**
 * Back button moves the active step backward.
 * It is disabled if at the start of the steps.
 */
class BackButton extends Button {
  constructor(el, stepper) {
    super(el, stepper);
    _defineProperty(
      this,
      "stepChanged",

      ({ activeIndex }) => {
        if (activeIndex === 0) this.disable();
        if (activeIndex > 0) this.enable();
      }
    );
    this.stepper.listenForEvent("stepChanged", this.stepChanged);
  }
  mouseup() {
    const { setActiveStep, activeIndex } = stepper;
    setActiveStep(activeIndex - 1);
  }
}

/**
 * Complete button calls to replace the active step index with a checkmark icon.
 * It is disabled if current step is complete already.
 * Is also hidden once all steps are complete.
 */
class CompleteButton extends Button {
  constructor(el, stepper) {
    super(el, stepper);
    _defineProperty(
      this,
      "stepChanged",

      ({ steps, activeIndex }) => {
        if (steps[activeIndex].isComplete) {
          this.disable();
        } else {
          this.enable();
        }
      }
    );
    _defineProperty(
      this,
      "completed",

      () => {
        this.hide();
      }
    );
    this.stepper.listenForEvent("stepChanged", this.stepChanged);
    this.stepper.listenForEvent("completed", this.completed);
  }
  mouseup() {
    const { setActiveStep, activeIndex, steps } = stepper;
    steps[activeIndex].complete();
    setActiveStep(activeIndex + 1);
  }
}

/**
 * Finish button currently just resets everything back to normal.
 * It is hidden by default and shown once all steps are complete.
 */
class FinishButton extends Button {
  constructor(el, stepper) {
    super(el, stepper);
    _defineProperty(
      this,
      "completed",

      () => {
        this.show();
        this.el.focus();
      }
    );
    this.stepper.listenForEvent("completed", this.completed);
  }
  mouseup() {
    const { setActiveStep, steps, complete, finish } = stepper;
    steps.forEach((step) => {
      step.reset();
    });
    finish.hide();
    complete.show();
    complete.enable();
    setActiveStep(0);
  }
}

// Initialize the stepper.
const el = document.querySelector(".stepper");
const stepper = new Stepper(el);
