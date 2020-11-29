<?php
$subject = $this->optionData->get('emailSubject');
$content = $this->optionData->get('emailContent');
?>

<h4>Email Subject</h4>
<div class="bswp__text">
    <p class="description">
        Subject of email being sent out with your coupon referral link
    </p>
    <label for="coupon_referral_email_subject" class="hidden">Email Subject</label>
    <input type="text" id="coupon_referral_email_subject" name="coupon_referral_email_subject" placeholder="Save today with this coupon code" value="<?php echo $subject; ?>" />
</div>

<h4>Email Content</h4>
<div class="bswp__textarea">
    <p class="description">
        Use <strong>{{link}}</strong> to insert the coupon hyperlink, or it will be added at the end
    </p>
    <label for="coupon_referral_email_content" class="hidden">Email Content</label>
    <textarea type="text" id="coupon_referral_email_content" name="coupon_referral_email_content" placeholder="Use the {{link}} to save!"><?php echo $content; ?></textarea>
</div>
