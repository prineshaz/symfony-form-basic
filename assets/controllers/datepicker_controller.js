import { Controller } from "@hotwired/stimulus";
import flatpickr from "flatpickr";
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect/index.js";
import "flatpickr/dist/flatpickr.min.css";

export default class extends Controller {
  static targets = ["input"];

  connect() {
    this.picker = flatpickr(this.inputTarget, {
      plugins: [
        new monthSelectPlugin({
          shorthand: true,
          dateFormat: "m/Y",
          altFormat: "F Y",
        }),
      ],
    });
  }
}
