<?php
    $shareLinkToggle = $this->optionData->get('shareLinkToggle');
    $previewEmailToggle = $this->optionData->get('previewEmailToggle');
?>


<h4>Display Share Link</h4>
<div class="bswp__checkbox">
  <p class="description">
    Show "Share Your Link" on the referral page.
  </p>
  <label for="share_link_toggle_true">
    Yes
    <input type="radio" id="share_link_toggle_true" name="share_link_toggle" value="true" <?php checked($shareLinkToggle); ?>>
  </label>

  <label for="share_link_toggle_false">
    No
    <input type="radio" id="share_link_toggle_false" name="share_link_toggle" value="false" <?php checked(! $shareLinkToggle); ?>>
  </label>
</div>


<h4>Display Email Preview</h4>
<div class="bswp__checkbox">
  <p class="description">
    Preview email and subject on referral page.
  </p>
  <label for="share_email_preview_toggle_true">
    Yes
    <input type="radio" id="share_email_preview_toggle_true" name="share_email_preview_toggle" value="true" <?php checked($previewEmailToggle); ?>>
  </label>

  <label for="share_link_toggle_false">
    No
    <input type="radio" id="share_link_toggle_false" name="share_email_preview_toggle" value="false" <?php checked(! $previewEmailToggle); ?>>
  </label>
</div>