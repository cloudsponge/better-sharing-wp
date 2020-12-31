import EmailForm from './block/modules/emailForm';
import CopyToClipboard from './block/modules/copyToClipboard';

window.addEventListener('DOMContentLoaded', () => {
  new EmailForm();
  new CopyToClipboard();
})
