import EmailInput from './modules/emailInput';
import CopyToClipboard from './modules/copyToClipboard';

// observes for existence of block parent div and loads modules
// avoids console errors on editor side
export default class frontendScripts {
  constructor() {
    this.waitForParentDiv(this.params());
  }

  waitForParentDiv(params) {
    new MutationObserver(function (mutations) {
      let el = document.querySelector(params.id);
      if (el) {
        this.disconnect();
        window.addEventListener('DOMContentLoaded', () => params.done());
      }
    }).observe(params.parent || document, {
      subtree: !!params.recursive || !params.parent,
      childList: true,
    });
  }

  params() {
    return {
      id: '.wp-block-cgb-block-ea-better-sharing',
      parent: document.body,
      recursive: false,
      done: () => this.loadModules(),
    };
  }

  loadModules() {
    const emailInput = new EmailInput();
    const copyToClipboard = new CopyToClipboard();
  }
}
