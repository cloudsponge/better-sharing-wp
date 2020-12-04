<?php
/**
 * Better Sharing AddOn Core Class
 *
 * @package BetterSharingAddOn
 */

namespace BetterSharingWP\AddOns;

use BetterSharingWP\AddOnsCore;
use BetterSharingWP\OptionData;

/**
 * BetterSharingAddon Core Class
 */
abstract class BetterSharingAddOn {

	/**
	 * Name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Slug
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Description
	 *
	 * @var string
	 */
	public $description;

	/**
	 * Current Status
	 *
	 * @var mixed
	 */
	public $status;

	/**
	 * Api Key
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * Has Settings
	 *
	 * @var boolean
	 */
	public $has_settings;

	/**
	 * Settings Template Path
	 *
	 * @var string
	 */
	public $settings_template_path;

	/**
	 * Option Data
	 *
	 * @var OptionData
	 */
	public $option_data;

	/**
	 * Support URL
	 *
	 * @var string
	 */
	public $support_url;

	/**
	 * Initialize AddOn
	 *
	 * @param string $name name of AddOn.
	 * @param string $description description of AddON.
	 * @param bool   $requires_api AddOn Requires API.
	 *
	 * @return int|\WP_Error
	 */
	public function init_addon( $name, $description, $requires_api = false ) {
		$this->has_settings = false;
		$this->name        = sanitize_text_field( $name );
		$this->slug        = sanitize_title( $name );
		$this->description = sanitize_text_field( $description );
		$this->api_key      = get_site_option( '_bswp_option_core_apiKey', false );

		if ( ! $this->api_key && $requires_api ) {
			return new \WP_Error( '400', __( 'No API Key Set' ) );
		}

		$this->option_data = new OptionData( $this->slug );
		if ( ! $this->option_data ) {
			return new \WP_Error( '400', __( 'Error Creating OptionData Object' ) );
		}

		// Set Active State if not set.
		if ( ! $this->option_data->get( 'status' ) ) {
			$this->option_data->save( 'status', 'inactive' );
		}

		$this->status = $this->option_data->get( 'status' );

		// Add to list of addOns.
		return AddOnsCore::add( $this );
	}

	/**
	 * Init actions
	 */
	public function init() {
	}

	/**
	 * Is AddOn Active
	 *
	 * @return bool
	 */
	public function is_active() {
		return 'active' === $this->status;
	}

	/**
	 * Check if related plugin is active
	 *
	 * @return bool
	 */
	public function is_plugin_active() {
		return true;
	}

	/**
	 * Activate AddOn
	 *
	 * @return string
	 */
	public function activate() {
		$this->option_data->save( 'status', 'active' );
		$this->status = $this->option_data->get( 'status' );
		return $this->status;
	}

	/**
	 * Deactivate AddOn
	 *
	 * @return string
	 */
	public function deactivate() {
		$this->option_data->save( 'status', 'inactive' );
		$this->status = $this->option_data->get( 'status' );
		return $this->status;
	}

	/**
	 * Toggle Add On
	 */
	public function toggle_addon() {
		if ( ! $this->is_active() ) {
			$this->activate();
		} else {
			$this->deactivate();
		}
	}

	/**
	 * Display Settings Template
	 *
	 * @return void
	 */
	public function display_settings() {
		if ( $this->has_settings ) {
			include_once $this->settings_template_path;
		}
	}

	/**
	 * Check if save add on set and true
	 *
	 * @return bool
	 */
	public function check_if_addon_save() {
		// phpcs:ignore
		return ! isset( $_POST['save_addon'] ) || ( isset( $_POST['save_addon'] ) && 'true' !== $_POST['save_addon'] );
	}

	/**
	 * Inject form
	 */
	public function bswp_form() {
		include_once BETTER_SHARING_PATH . 'includes/templates/bswp-form.php';
	}

}
