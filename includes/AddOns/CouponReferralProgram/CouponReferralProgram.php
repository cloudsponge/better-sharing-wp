<?php

namespace BetterSharingWP\AddOns\CouponReferralProgram;

use BetterSharingWP\AddOns\BetterSharingAddOn;
use BetterSharingWP\BSWP_Mail;

/**
 * Class CouponReferralProgram - Coupon Referral Program Plugin Add On
 * @package BetterSharingWP
 */
class CouponReferralProgram extends BetterSharingAddOn {

	private $hookName;
	private $addOnPath;
	private $mailSuccess;

	/**
	 * Init
	 *
	 * @return mixed
	 */
	public function init() {
		// path
		$this->addOnPath = BETTER_SHARING_PATH . 'includes/AddOns/CouponReferralProgram';

		// used to remove default display and add form
		$this->hookName = 'woocommerce_account_dashboard';

		// init
		$initReturn = parent::initAddOn(
			'Coupon Referral Program',
			'Better Sharing WP AddOn for Coupon Referral Program',
			false
		);

		$this->supportUrl = 'https://cloudsponge.com';

		if ( $this->isActive() ) {
			// remove coupon widget.
			$this->remove_widget();
			add_action( 'init', [ $this, 'submit_mailer' ], 99 );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
			add_action( 'bwp_form_before', [ $this, 'show_coupon_code' ], 10 );
			add_action( $this->hookName, [ $this, 'bswp_form' ], 10 );
		}

		// settings page in admin
		$this->settingsPageInit();

		return is_wp_error( $initReturn ) ? $initReturn : $this->isActive();
	}

	/**
	 * Check if Coupon Referral Program is Installed & Active
	 *
	 * @return bool
	 */
	public function isPluginActive() {
		return class_exists( 'Coupon_Referral_Program' );
	}

	/**
	 * Set up settings page
	 *
	 */
	private function settingsPageInit() {
		$this->hasSettings = true;
		$this->settingsTemplatePath = __DIR__ . '/templates/coupon-referral-settings.php';
		add_action( 'admin_init', [ $this, 'save_settings' ] );
	}

	/**
	 * Save Admin Settings - admin init callback
	 *
	 */
	public function save_settings() {
		if ( isset( $_POST['coupon_referral_email_subject'] ) ) {
			$this->optionData->save( 'emailSubject', sanitize_text_field( $_POST['coupon_referral_email_subject'] ) );
		}

		if ( isset( $_POST['coupon_referral_email_content'] ) ) {
			$this->optionData->save( 'emailContent', sanitize_text_field( $_POST['coupon_referral_email_content'] ) );
		}
	}

	/**
	 * Remove Widget on my account dashboard
	 *
	 */
	public function remove_widget() {
		global $wp_filter;
		if ( isset( $wp_filter[$this->hookName]->callbacks[10] ) && is_array( $wp_filter[$this->hookName]->callbacks[10] ) ) {
			$key = key( $wp_filter[$this->hookName]->callbacks[10] );
			if ( is_array( $wp_filter[$this->hookName]->callbacks[10][$key]['function'] ) ) {
				if ( is_array( $wp_filter[$this->hookName]->callbacks[10][$key]['function'] ) ) {
					remove_action( $this->hookName, [ $wp_filter[$this->hookName]->callbacks[10][$key]['function'][0], $wp_filter[$this->hookName]->callbacks[10][$key]['function'][1] ] );
				}
			}
		}
	}

	/**
	 * Enqueue Scripts
	 *
	 * @return bool
	 */
	public function enqueue_scripts() {
		global $post;

		$myAccountPageId = (int) get_site_option( 'woocommerce_myaccount_page_id', false );

		if ( $myAccountPageId !== $post->ID ) {
			return false;
		}

		wp_enqueue_script(
			'cloudsponge-js',
			'//api.cloudsponge.com/widget/' . $this->apiKey . '.js',
			[ 'jquery' ],
			BETTER_SHARING_VERSION,
			false
		);

		wp_enqueue_script(
			'bswp-addons-automatewoo',
			BETTER_SHARING_URI . 'dist/addons/couponref.js',
			['cloudsponge-js'],
			BETTER_SHARING_VERSION,
			false
		);
	}

	private function get_referral_link() {
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$referral_key = get_user_meta($user_id,'referral_key',true);
		if(empty($referral_key)) {
			$referral_key = $this->set_referral_key($user_id);
		}
		return site_url().'?ref='.$referral_key;
	}

	/**
	 * Show Coupon Code
	 */
	public function show_coupon_code() {
		$referral_link = $this->get_referral_link();
		include_once $this->addOnPath . '/templates/coupon-code.php';
	}

	/**
	 * Inject Form
	 */
	public function bswp_form() {
		$addOn = 'Coupon Referral Program';
		$previewEmailToggle = true;
		$ajax = false;

		// subject
		$emailSubject = $this->optionData->get( 'emailSubject' );
		$emailSubject = $emailSubject ? $emailSubject : 'Save today with this coupon code';

		// email content
		$emailContent = $this->optionData->get( 'emailContent' );
		$emailContent = $emailContent ? $emailContent : 'Use the {{link}} to save!';
		// email content - replace {{link}} or add to bottom
		$emailContent = $this->replace_link( $emailContent );

		include_once BETTER_SHARING_PATH . 'includes/templates/bswp-form.php';
	}

	/**
	 * Replace {{link}} in the email message (will add to end if missing)
	 *
	 * @param $text
	 *
	 * @return string|string[]
	 */
	private function replace_link( $text ) {

		if ( false === strpos( $text, '{{link}}' ) ) {
			$text .= ' You can save today by using the link: ' . $this->get_referral_link();
		} else {
			$text = str_replace( '{{link}}', $this->get_referral_link(), $text );
		}

		return $text;
	}


	/**
	 * Submit mailer on form submit - init callback
	 *
	 */
	public function submit_mailer() {
		global $post;
		if ( ! isset( $_POST['bswp_form_addon'] ) ) {
			return false;
		}
		$user = wp_get_current_user();
		$userData = get_userdata( $user->ID );
		$myAccountPageId = (int) get_site_option( 'woocommerce_myaccount_page_id', false );

		$emails = sanitize_text_field( $_POST['bswp-share-email-input'] );
		if ( '' === $emails ) {
			$this->mailSuccess = false;
			add_action( 'bwp_form_before', [ $this, 'show_email_send_message' ], 9 );
			return false;
		}

		$emails = explode( ',', $emails );
		$emails = array_map( function( $email ) {
			return str_replace( ' ', '', $email );
		}, $emails );

		$mailer = new BSWP_Mail();
		$mailer->setMessage( sanitize_text_field( $_POST['bswp-share-email-content'] ) );
		$mailer->setSubject( sanitize_text_field( $_POST['bswp-share-email-subject'] ) );
		$mailer->setTo( $emails );
		$mailer->setFrom([
			'email' => $userData->user_email,
			'name' => $userData->display_name
		]);

		$sent = $mailer->send();

		if ( is_wp_error( $sent ) ) {
			$this->mailSuccess = false;
			add_action( 'bwp_form_before', [ $this, 'show_email_send_message' ], 9 );
			return false;
		}

		if ( $sent ) {
			$this->mailSuccess = true;
			add_action( 'bwp_form_before', [ $this, 'show_email_send_message' ], 9 );
		}
	}

	/**
	 * Show email send message (success or fail0
	 *
	 */
	public function show_email_send_message() {

		if ( ! isset( $this->mailSuccess ) ) {
			return;
		}

		$emailSent = $this->mailSuccess;

		if ( true === $emailSent ) {
			echo '<div class="bswp-coupon-referral-emailSent success">Sent Successfully</div>';
		} elseif ( false === $emailSent ) {
			echo '<div class="bswp-coupon-referral-emailSent fail">Errors sending message, please try again</div>';
		}

		unset( $this->mailSuccess );

	}

}
