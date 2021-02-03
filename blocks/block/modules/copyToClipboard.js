export default class copyToClipboard {
  constructor() {
    this.button = document.getElementById('referral-btn-copy');
    this.input = document.getElementById('referral-link');
    this.msg = document.getElementById('copy-success');
    this.events();
  }

  events() {
    this.button.addEventListener('click', async (e) => {
      if (!navigator.clipboard) {
        // Clipboard API not available
        return;
      }
      const text = this.input.value;
      try {
        await navigator.clipboard.writeText(text);
        this.displaySuccessMsg();
      } catch (err) {
        console.error('Failed to copy!', err);
      }
    });
  }

  displaySuccessMsg() {
    this.msg.style.display = 'inline-block';
    setTimeout(() => {
      this.msg.style.display = 'none';
    }, 1000);
  }
}
