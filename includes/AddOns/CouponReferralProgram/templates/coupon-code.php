<?php
/**
 * Coupon Code Template
 *
 * @package template
 */

$referral_link = $referral_link ? $referral_link : false;
if ( ! $referral_link ) {
	return false;
}
?>

<div class="bswp-coupon-referral-coupon-code">
	<h3>
		<?php echo esc_html( __( 'Coupon Referral Program', 'better-sharing-wp' ) ); ?>
	</h3>
	<strong>Your referral link:</strong>
	<input readonly type="text" value="<?Php echo esc_html( $referral_link ); ?>" id="bswp-coupon-referral-copy" />
	<span class="bswp-copy button btn">
		<span class="dashicons dashicons-admin-page"></span>
		Copy Link
	</span>
	<p class="bswp-copy-confirm">
		<em>Copied</em>
	</p>
</div>
