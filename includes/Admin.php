<?php
/**
 * Admin Class
 *
 * @package Admin
 */
namespace BetterSharingWP;

use BetterSharingWP\AdminScreens\GeneralSettings;
use BetterSharingWP\AdminScreens\AddOns;
use BetterSharingWP\AddOnsCore;

/**
 * Main Admin Class
 */
class Admin {


	private $generalSettings;
	private $addOnsPage;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'better_sharing_menu_init' ), 10 );
		$this->generalSettings = new GeneralSettings();
		$this->addOnsPage      = new AddOns();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'toggle_addons' ), 1 );
	}

	public function better_sharing_menu_init() {
		add_menu_page(
			'Better Sharing',
			'Better Sharing',
			'manage_options',
			'better-sharing-wp',
			array( $this, 'better_sharing_menu' ),
			'dashicons-megaphone'
		);

		// Init General Settings Page
		$this->generalSettings->init();

		// Init AddOns Page
		$this->addOnsPage->init();

		remove_submenu_page( 'better-sharing-wp', 'better-sharing-wp' );

	}

	public function better_sharing_menu() {
		echo '<h2>Better Sharing</h2>';

	}

	public function admin_scripts() {
		wp_enqueue_script(
			'bswp-admin-assets',
			BETTER_SHARING_URI . 'dist/admin/admin.bundle.js',
			array( 'jquery' ),
			filemtime( BETTER_SHARING_PATH . 'dist/admin/admin.bundle.js' ),
			false
		);
	}

	public function toggle_addons() {

		// $option_data = new OptionData();
		// $delete = $option_data->deleteAll(true );
		//
		// var_dump( $delete );
		// wp_die();
		if ( ! isset( $_GET['_bswp_addons_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_bswp_addons_nonce'] ) ), 'bswp_addons_nonce' ) ) {
			return;
		} else {
			if ( ! isset( $_GET, $_GET['toggleAddOn'], $_GET['addOn'] ) || 'true' !== $_GET['toggleAddOn'] ) {
				return false;
			}
	
			$addOns   = AddOnsCore::getAddOns();
			$toToggle = sanitize_text_field( wp_unslash( $_GET['addOn'] ) );
	
			foreach ( $addOns as $addOn ) {
				if ( $toToggle === $addOn->slug ) {
					$addOn->toggle_addon();
				}
			}

			wp_safe_redirect( admin_url( 'admin.php?page=better-sharing-addons' ) );
		}
	}
}
