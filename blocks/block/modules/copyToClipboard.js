export default class copyToClipboard {
  constructor() {
    this.button = document.getElementById('referral-btn-copy');
    this.input = document.getElementById('referral-link');
    this.msg = document.getElementById('copy-success');
    this.events();
  }

  events() {
    this.button.addEventListener('click', (e) => {
      e.preventDefault();

      const text = this.input;
      text.select();
      text.setSelectionRange(0, 99999);
      document.execCommand('copy');

      this.msg.style.display = 'block';

      setTimeout(() => {
        this.msg.style.display = 'none';
      }, 1500);
    });
  }

  displaySuccessMsg() {
    this.msg.style.display = 'inline-block';
    setTimeout(() => {
      this.msg.style.display = 'none';
    }, 1000);
  }
}
