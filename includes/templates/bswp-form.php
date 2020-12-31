<?php
/**
 * BSWP - FORM
 *
 * @package form
 */

global $post;

$ajax         = $ajax ? $ajax : false;
$api_key      = get_site_option( '_bswp_option_core_apiKey', false );
$email_subject = $email_subject ? $email_subject : 'Sharing';
$email_content = $email_content ? $email_content : 'Email Content';
$field_count  = isset( $field_count ) ? absint( apply_filters( 'automatewoo/referrals/share_form/email_field_count', 5 ) ) : 5;

$addon      = $addon ? sanitize_title_with_dashes( $addon ) : false;
$action_data = array(
	'addon' => $addon,
);

do_action( 'bwp_form_before', $action_data );

?>
<form <?php echo ! $ajax ? 'action="' . esc_html( get_permalink( $post->ID ) ) . '"' : ''; ?> accept-charset="UTF-8" method="post">

	<?php do_action( 'bwp_form_inner_before', $action_data ); ?>
	<input type="hidden" name="bswp_form_addon" value="<?php echo esc_html( $addon ); ?>" />

	<h3>Share via Email</h3>
	<p>
		Invite people to use your referral code.
	</p>

	<div class="bswp-share-buttons bswp-share-emails">
		<input type="text" name="bswp-share-email-input" id="bswp-share-email-input" placeholder="To: enter contact emails separated by comma (,)">
		<?php if ( $api_key ) : ?>
			<a href="#" class="add-from-address-book-init btn button">
				<span class="dashicons dashicons-book-alt"></span>
			<?php esc_attr_e( 'Add From Address Book', 'better-sharing-wp' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<hr/>

	<?php if ( $preview_email_toggle ) : ?>
		<div class="bswp-share-email-preview">
			<h4>Email Preview</h4>
			<p>
				This is the email that your referrals will see.
			</p>
			<div class="bswp-share-email-preview-subject">
				<strong>Subject</strong>
				<div class="box"><?php echo esc_html( $email_subject ); ?></div>
				<input type="hidden" name="bswp-share-email-subject" id="bswp-share-email-subject" value="<?php echo esc_html( $email_subject ); ?>" />
			</div>
			<div class="bswp-share-email-preview-message">
				<strong>Message</strong>
				<div class="box"><?php echo esc_html( $email_content ); ?></div>
				<input type="hidden" name="bswp-share-email-content" id="bswp-share-email-content" value="<?php echo esc_html( $email_content ); ?>" />
			</div>
		</div>
	<?php endif; ?>

	<div id="referral-emails-wrapper" data-max="<?php echo esc_html( $field_count ); ?>"></div>

	<?php do_action( 'bwp_form_inner_after', $action_data ); ?>

	<?php if ( $ajax ) : ?>
		<div class="bswp-share-buttons">
			<a href="#" class="bswp-submit btn button"><?php esc_attr_e( 'Send', 'better-sharing-wp' ); ?></a>
		</div>
	<?php else : ?>
		<div class="bswp-share-buttons">
			<input type="submit" class="bswp-submit btn button" value="<?php esc_attr_e( 'Send', 'better-sharing-wp' ); ?>" />
		</div>
	<?php endif; ?>

</form>

<?php
do_action( 'bwp_form_after', $action_data );
