<?php

if (! defined('ABSPATH') ) { exit;
}

use BetterSharingWP\OptionData;
use AutomateWoo\Referrals\Invite_Email;
use AutomateWoo\Referrals\Advocate_Factory;

$option_data = new OptionData('automatewoo');
$shareLinkToggle = (bool) rest_sanitize_boolean($option_data->get('shareLinkToggle'));
$preview_email_toggle = (bool) rest_sanitize_boolean($option_data->get('preview_email_toggle'));
$field_count = absint(apply_filters('automatewoo/referrals/share_form/email_field_count', 5));
$api_key = get_site_option('_bswp_option_core_apiKey', false);


$user = get_user_by('id', get_current_user_id());
$email = new Invite_Email($user->user_email, Advocate_Factory::get($user->ID));
$emailSubject = $email->get_subject();
$emailContent = $email->get_content();


if ($shareLinkToggle ) {
    include_once 'automatewoo-share-link.php';
}
?>


<form class="aw-email-referral-form" action="" accept-charset="UTF-8" method="post">
    <input type="hidden" name="action" value="aw-referrals-email-share">

    <h3>Share via Email</h3>
    <p>
       Invite people to use your referral code.
    </p>

    <div class="bswp-share-buttons bswp-share-emails">
        <input type="text" name="bswp-share-email-input" id="bswp-share-email-input" placeholder="To: enter contact emails separated by comma (,)">
        <?php if ($api_key ) : ?>
            <a href="#" class="add-from-address-book-init btn button">
                <span class="dashicons dashicons-book-alt"></span>
                <?php esc_attr_e('Add From Address Book', 'better-sharing-wp'); ?>
            </a>
        <?php endif; ?>
    </div>

    <hr/>

    <?php if ($preview_email_toggle ) : ?>
    <div class="bswp-share-email-preview">
        <h4>Email Preview</h4>
        <p>
            This is the email that your referrals will see.
        </p>
        <div class="bswp-share-email-preview-subject">
            <strong>Subject</strong>
            <div class="box"><?php echo $emailSubject; ?></div>
        </div>
        <div class="bswp-share-email-preview-message">
            <strong>Message</strong>
            <div class="box"><?php echo $emailContent; ?></div>
        </div>
    </div>
    <?php endif; ?>

    <div id="referral-emails-wrapper" data-max="<?php echo $field_count; ?>"></div>

    <div class="aw-referrals-share-buttons bswp-share-buttons">
        <a href="#" class="bswp-submit btn button"><?php esc_attr_e('Send', 'automatewoo-referrals') ?></a>
    </div>
</form>