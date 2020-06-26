<?php
global $post;

$ajax = $ajax ? $ajax : false;
$apiKey = get_site_option( '_bswp_option_core_apiKey', false );
$emailSubject = $emailSubject ? $emailSubject : 'Sharing';
$emailContent = $emailContent ? $emailContent : 'Email Content';

$addOn = $addOn ? sanitize_title_with_dashes( $addOn ) : false;
$actionData = [
	'addOn' => $addOn,
];

do_action( 'bwp_form_before', $actionData );

?>
<form <?php echo ! $ajax ? 'action="' . get_permalink( $post->ID ) . '"' : ''; ?> accept-charset="UTF-8" method="post">

	<?php do_action( 'bwp_form_inner_before', $actionData ); ?>
    <input type="hidden" name="bswp_form_addon" value="<?php echo $addOn; ?>" />

	<h3>Share via Email</h3>
	<p>
		Invite people to use your referral code.
	</p>

	<div class="bswp-share-buttons bswp-share-emails">
		<input type="text" name="bswp-share-email-input" id="bswp-share-email-input" placeholder="To: enter contact emails separated by comma (,)">
		<?php if ( $apiKey ) : ?>
			<a href="#" class="add-from-address-book-init btn button">
				<span class="dashicons dashicons-book-alt"></span>
				<?php esc_attr_e( 'Add From Address Book', 'better-sharing-wp' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<hr/>

	<?php if ( $previewEmailToggle ) : ?>
		<div class="bswp-share-email-preview">
			<h4>Email Preview</h4>
			<p>
				This is the email that your referrals will see.
			</p>
			<div class="bswp-share-email-preview-subject">
				<strong>Subject</strong>
				<div class="box"><?php echo $emailSubject; ?></div>
                <input type="hidden" name="bswp-share-email-subject" value="<?php echo $emailSubject; ?>" />
			</div>
			<div class="bswp-share-email-preview-message">
				<strong>Message</strong>
				<div class="box"><?php echo $emailContent; ?></div>
				<input type="hidden" name="bswp-share-email-content" value="<?php echo $emailContent; ?>" />
			</div>
		</div>
	<?php endif; ?>

	<div id="referral-emails-wrapper" data-max="<?php echo $field_count; ?>"></div>

	<?php do_action( 'bwp_form_inner_after', $actionData ); ?>

	<?php if ( $ajax ) : ?>
        <div class="bswp-share-buttons">
            <a href="#" class="bswp-submit btn button"><?php esc_attr_e( 'Send', 'better-sharing-wp' ) ?></a>
        </div>
    <?php else : ?>
        <div class="bswp-share-buttons">
            <input type="submit" class="bswp-submit btn button" value="<?php esc_attr_e( 'Send', 'better-sharing-wp' ) ?>" />
        </div>
    <?php endif; ?>

</form>

<?php do_action( 'bwp_form_after', $actionData );
