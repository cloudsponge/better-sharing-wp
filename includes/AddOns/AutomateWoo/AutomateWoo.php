<?php
/**
 * Automate Woo Addon
 *
 * @package AutomateWoo
 */

namespace BetterSharingWP\AddOns\AutomateWoo;

use BetterSharingWP\AddOns\BetterSharingAddOn;

/**
 * AutomateWoo
 */
class AutomateWoo extends BetterSharingAddOn {

	/**
	 * Referrals Page
	 *
	 * @var int referal page.
	 */
	private $referrals_page;

	/**
	 * Initialize AutomateWoo AddOn
	 *
	 * @return int|\WP_Error
	 */
	public function init() {
		$this->referrals_page = (int) get_site_option( 'aw_referrals_referrals_page', false );
		if ( ! $this->referrals_page ) {
			return new \WP_Error( '400', __( 'No Referrals Page Found' ) );
		}
		$init_return = parent::init_addon(
			'AutomateWoo',
			'Better Sharing WP AddOn for AutomateWoo',
			false
		);

		$this->support_url = 'https://cloudsponge.com';

		if ( $this->is_active() ) {
			add_filter( 'wc_get_template', array( $this, 'template_init' ), 10, 5 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		$this->settingsPageInit();

		return is_wp_error( $init_return ) ? $init_return : $this->is_active();
	}

	/**
	 * Set up settings page
	 */
	private function settingsPageInit() {
		$this->has_settings          = true;
		$this->settings_template_path = __DIR__ . '/templates/automatewoo-settings.php';
		add_action( 'admin_init', array( $this, 'save_settings' ) );
	}

	/**
	 * Save Settings
	 */
	public function save_settings() {
		if ( ! $this->check_if_addon_save() ) {
			return;
		}

		//phpcs:ignore
		if ( ! isset( $_POST['share_link_toggle'], $_POST['share_email_preview_toggle'] ) ) {
			return;
		}

		//phpcs:ignore
		$share_link_toggle = rest_sanitize_boolean( wp_unslash( $_POST['share_link_toggle'] ) );
		$this->option_data->save( 'share_link_toggle', $share_link_toggle );

		//phpcs:ignore
		$preview_email_toggle = rest_sanitize_boolean( wp_unslash( $_POST['share_email_preview_toggle'] ) );
		$this->option_data->save( 'preview_email_toggle', $preview_email_toggle );

	}

	/**
	 * Enqueue Scripts
	 *
	 * @return mixed
	 */
	public function enqueue_scripts() {
		global $post;

		if ( $post && $post->ID !== $this->referrals_page ) {
			return false;
		}

		wp_enqueue_script(
			'cloudsponge-js',
			'//api.cloudsponge.com/widget/' . $this->api_key . '.js',
			array( 'jquery' ),
			BETTER_SHARING_VERSION,
			false
		);

		wp_enqueue_script(
			'bswp-addons-automatewoo',
			BETTER_SHARING_URI . 'dist/addons/automatewoo.js',
			array( 'cloudsponge-js' ),
			BETTER_SHARING_VERSION,
			false
		);
	}

	/**
	 * Change Template Path
	 *
	 * @param string $template current template.
	 * @param string $template_name template name.
	 * @param mixed  $args data.
	 * @param string $template_path path to template.
	 * @param string $default_path default path.
	 *
	 * @return string
	 */
	public function template_init( $template, $template_name, $args, $template_path, $default_path ) {

		// If not AutomateWoo return.
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
	public function is_plugin_active() {
		return class_exists( 'AW_Referrals_Loader' );
	}

}
