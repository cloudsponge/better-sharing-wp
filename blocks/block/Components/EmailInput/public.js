// create an array of emails
class EmailInput {
  constructor() {
    this.emailInput = document.getElementById('email-input');
    this.submit = document.getElementById('email-form-submit');
    this.events();
  }

  events() {
    this.submit.addEventListener('click', () =>
      console.log(this.handleEmails(this.emailInput.value))
    );
  }

  // returns an array of emails from the input that were separated by a comma
  handleEmails = (emails) => emails.split(',').map((email) => email.trim());
}

document.addEventListener('DOMContentLoaded', function () {
  const emailInput = new EmailInput();
});
