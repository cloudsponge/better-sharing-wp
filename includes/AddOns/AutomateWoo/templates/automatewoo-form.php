<?php

if ( ! defined( 'ABSPATH' ) ) exit;

use BetterSharingWP\OptionData;

$optionData = new OptionData('automatewoo' );
$shareLinkToggle = (bool) rest_sanitize_boolean( $optionData->get('shareLinkToggle') );

$field_count = absint( apply_filters( 'automatewoo/referrals/share_form/email_field_count', 5 ) );

if ( $shareLinkToggle ) {
	include_once 'automatewoo-share-link.php';
}
?>


<form class="aw-email-referral-form" action="" accept-charset="UTF-8" method="post">

    <div class="aw-referrals-share-buttons bswp-share-buttons">
        <a href="#" class="add-from-address-book-init btn button">
            <span class="dashicons dashicons-book-alt"></span>
            <?php esc_attr_e( 'Add From Address Book', 'better-sharing-wp' ); ?>
        </a>
    </div>

    <input type="hidden" name="action" value="aw-referrals-email-share">
    <div id="referral-emails-wrapper">
        <?php for( $i = 0; $i < $field_count; $i++ ): ?>
            <p class="form-row form-row-wide ">
                <input autocomplete="off" placeholder="<?php esc_attr_e( 'Enter email address', 'automatewoo-referrals' ) ?>" type="email" name="emails[]" class="woocommerce-Input input-text">
            </p>
        <?php endfor; ?>
    </div>

    <div class="aw-referrals-share-buttons bswp-share-buttons">
        <a href="#" class="bswp-submit btn button"><?php esc_attr_e( 'Send', 'automatewoo-referrals' ) ?></a>
    </div>
</form>