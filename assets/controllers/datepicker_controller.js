import { Controller } from "@hotwired/stimulus";
import flatpickr from "flatpickr";
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect/index.js";
import "flatpickr/dist/flatpickr.min.css";

export default class extends Controller {
  static targets = ["input"];

  connect() {
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1); // First day of next month
    this.picker = flatpickr(this.inputTarget, {
      defaultDate: nextMonth,
      minDate: nextMonth,
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
