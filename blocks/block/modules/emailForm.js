export default class emailForm {
  constructor() {
    this.emailInput = document.getElementById('bswp-share-email-input');
    this.subject = document.getElementById('bswp-share-email-subject');
    this.message = document.getElementById('bswp-share-email-content');
    this.submit = document.querySelector('.bswp-submit');

    this.errorMsg = document.querySelector('.coreblock-error-msg');
    this.errorText = 'Error! Email not sent.';

    this.events();
  }

  events() {
    this.submit.addEventListener('click', (e) => {
      this.submitMail(e);
    });
  }

  submitMail(e) {
    e.preventDefault();
    const data = {
      emails: this.handleEmails(this.emailInput.value),
      subject: this.subject.value,
      message: this.message.value,
    };

    const request = new XMLHttpRequest();
    request.open(
      'POST',
      `${window.location.origin}/wp-json/bswp/v1/bswp_email`,
      true
    );
    request.setRequestHeader(
      'Content-Type',
      'application/x-www-form-urlencoded; charset=UTF-8'
    );
    request.onreadystatechange = () => {
      if (request.readyState === 4 && request.status === 200) {
        const response = JSON.parse(request.responseText);
        this.afterSubmit(response.mail);
      }
    };
    request.send(JSON.stringify(data));
  }

  // returns an array of emails from the input that were separated by a comma
  handleEmails = (emails) => emails.split(',').map((email) => email.trim());

  // indicate in the UI if emails sent successfully or not.
  afterSubmit = (response) => {
    if (response) {
      this.submit.value = 'Success!';
      this.submit.disabled = true;
      this.submit.style.backgroundColor = 'green';
      this.submit.style.color = 'white';
      setTimeout(() => {
        this.submit.value = 'Send';
        this.submit.disabled = false;
        this.submit.style = 'inherit';
        this.emailInput.value = '';
      }, 3000);
    } else {
      this.errorMsg.innerText = this.errorText;
      this.errorMsg.style.display = 'block';
      setTimeout(() => {
        this.errorMsg.innerText = '';
        this.errorMsg.style.display = 'none';
      }, 3000);
    }
  };
}
