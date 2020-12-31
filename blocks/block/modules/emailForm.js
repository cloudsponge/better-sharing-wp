export default class emailForm {
  constructor() {
    this.emailInput = document.getElementById('bswp-share-email-input');
    this.subject = document.getElementById('bswp-share-email-subject');
    this.message = document.getElementById('bswp-share-email-content');
    this.submit = document.querySelector('.bswp-submit');
    this.events();
  }

  events() {
    this.submit.addEventListener('click', (e) => {
      this.submitMail(e);
    });
  }

  submitMail(e) {
    e.preventDefault()
    const data = {
      emails: this.handleEmails(this.emailInput.value),
      subject: this.subject.value,
      message: this.message.value
    };

    const request = new XMLHttpRequest();
    request.open('POST', `${window.location.origin}/wp-json/bswp/v1/bswp_email`, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.send(JSON.stringify(data));
  }

  // returns an array of emails from the input that were separated by a comma
  handleEmails = (emails) => emails.split(',').map((email) => email.trim());
}
