<?php

namespace BetterSharingWP\AddOns\AutomateWoo;

use BetterSharingWP\AddOns\BetterSharingAddOn;

class AutomateWoo extends BetterSharingAddOn{

	private $referralsPage;

	/**
	 * @return int|\WP_Error
	 */
	public function init() {
		$this->referralsPage = (int) get_site_option( 'aw_referrals_referrals_page', false );
		if ( ! $this->referralsPage ) {
			return new \WP_Error( '400', __( 'No Referrals Page Found' ) );
		}
		$initReturn = parent::initAddOn(
			'AutomateWoo',
			'Better Sharing WP AddOn for AutomateWoo',
			false
		);

		$this->supportUrl = 'https://cloudsponge.com';

		if ( $this->isActive() ) {
			add_filter( 'wc_get_template', [ $this, 'template_init' ], 10, 5 );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		}

		$this->settingsPageInit();

		return is_wp_error( $initReturn ) ? $initReturn : $this->isActive();
	}

	/**
	 * Set up settings page
	 *
	 */
	private function settingsPageInit() {
		$this->hasSettings = true;
		$this->settingsTemplatePath = __DIR__ . '/templates/automatewoo-settings.php';
		add_action( 'admin_init', [ $this, 'save_settings' ] );
	}

	/**
	 * Save Settings
	 */
	public function save_settings() {
		if ( ! $this->checkIfAddOnSave() ) {
			return;
		}

		if ( ! isset( $_POST['share_link_toggle'], $_POST['share_email_preview_toggle'] ) ) {
			return;
		}

		$shareLinkToggle = rest_sanitize_boolean( $_POST['share_link_toggle'] );
		$this->optionData->save( 'shareLinkToggle', $shareLinkToggle );

		$previewEmailToggle = rest_sanitize_boolean( $_POST['share_email_preview_toggle'] );
		$this->optionData->save( 'previewEmailToggle', $previewEmailToggle );

	}

	public function enqueue_scripts() {
		global $post;

		if ( $post->ID !== $this->referralsPage ) {
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
			BETTER_SHARING_URI . 'dist/addons/automatewoo.js',
			['cloudsponge-js'],
			BETTER_SHARING_VERSION,
			false
		);
	}

	/**
	 * Change Template Path
	 *
	 * @param string $template
	 * @param string $template_name
	 * @param mixed $args
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return string
	 */
	public function template_init( $template, $template_name, $args, $template_path, $default_path ) {

		// If not AutomateWoo return
		if ( 'automatewoo/referrals' !== $template_path ) {
			return $template;
		}

		if ( 'share-page-form.php' === $template_name ) {
			$template = __DIR__ . '/templates/automatewoo-form.php';
		}

		return $template;
	}

	/**
	 * Check if AutomateWoo Referrals is active
	 *
	 * @return bool
	 */
	public function isPluginActive() {
		return class_exists( 'AW_Referrals_Loader' );
	}

}