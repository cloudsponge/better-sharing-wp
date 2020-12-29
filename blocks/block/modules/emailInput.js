// create an array of emails
export default class EmailInput {
  constructor() {
    this.emailInput = document.getElementById('bswp-share-email-input');
    this.submit = document.querySelector('.bswp-submit');
    this.events();
  }

  events() {
    this.submit.addEventListener('click', (e) => {
      e.preventDefault();
      console.log(this.handleEmails(this.emailInput.value));
    });
  }

  // returns an array of emails from the input that were separated by a comma
  handleEmails = (emails) => emails.split(',').map((email) => email.trim());
}