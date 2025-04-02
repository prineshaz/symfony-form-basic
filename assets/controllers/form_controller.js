import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = { url: String };
  static targets = ["submit"];

  // Handles form submission
  submit(event) {
    console.log("Form submission initiated");
    event.preventDefault();
    const form = event.target;
    const url = this.urlValue || form.action;

    this.submitTarget.disabled = true;

    fetch(url, {
      method: "POST",
      body: new FormData(form),
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.text())
      .then((html) => {
        this.element.outerHTML = html;
      })
      .catch((err) => alert(err));
  }

  // Handles the "Back" button
  back(event) {
    console.log("Back button clicked");
    event.preventDefault();

    // Fetch the previous step's content
    const url = event.target.dataset.backUrl;
    console.log(url);
    fetch(url, {
      method: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.text())
      .then((html) => {
        this.element.outerHTML = html;
      })
      .catch((err) => alert(err));
  }
}
