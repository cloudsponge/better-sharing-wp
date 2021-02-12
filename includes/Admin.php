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

	/**
	 * General Settings
	 *
	 * @var GeneralSettings
	 */
	private $general_settings;

	/**
	 * Addons Page
	 *
	 * @var AddOns
	 */
	private $addons_page;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'better_sharing_menu_init' ), 10 );
		$this->general_settings = new GeneralSettings();
		$this->addons_page      = new AddOns();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'toggle_addons' ), 1 );
	}

	/**
	 * Menu Init
	 *
	 * @return void
	 */
	public function better_sharing_menu_init() {
		add_menu_page(
			'Better Sharing',
			'Better Sharing',
			'manage_options',
			'better-sharing-wp',
			array( $this, 'better_sharing_menu' ),
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMThweCIgaGVpZ2h0PSIxOHB4IiB2aWV3Qm94PSIwIDAgMTggMTgiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8dGl0bGU+R3JvdXBfNjkzPC90aXRsZT4KICAgIDxnIGlkPSJQYWdlLTEiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPgogICAgICAgIDxnIGlkPSIyMCIgZmlsbD0iIzIzMUYyMCIgZmlsbC1ydWxlPSJub256ZXJvIj4KICAgICAgICAgICAgPGcgaWQ9Ikdyb3VwXzY5MyI+CiAgICAgICAgICAgICAgICA8ZyBpZD0iR3JvdXBfNjkyIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLjAwMDAwMCwgMi4wMDAwMDApIj4KICAgICAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTMsMTYgTDMsMTYgQzEuMzQzMTQ1NzUsMTYgLTIuMjIwNDQ2MDVlLTE2LDE0LjY1Njg1NDIgLTIuMjIwNDQ2MDVlLTE2LDEzIEwtMi4yMjA0NDYwNWUtMTYsMyBDLTIuMjIwNDQ2MDVlLTE2LDEuMzQzMTQ1NzUgMS4zNDMxNDU3NSwwIDMsMCBMNywwIEw3LDIgTDMsMiBDMi40NDc3MTUyNSwyIDIsMi40NDc3MTUyNSAyLDMgTDIsMTMgQzIsMTMuNTUyMjg0NyAyLjQ0NzcxNTI1LDE0IDMsMTQgTDEzLDE0IEMxMy41NTIyODQ3LDE0IDE0LDEzLjU1MjI4NDcgMTQsMTMgTDE0LDkgTDE2LDkgTDE2LDEzIEMxNiwxNC42NTY4NTQyIDE0LjY1Njg1NDIsMTYgMTMsMTYgWiIgaWQ9IlBhdGhfMTAxNSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTE1LC0yLjU3Mjk2NTAzZS0wNiBDMTQuMjA0MTc0NCwtMC4wMDEwNDM0NDc3NSAxMy40NDA3OTAzLDAuMzE1MzIzNjUzIDEyLjg3OSwwLjg3OSBMMTEuNzU3LDEuOTk5OTk3NDMgTDksMS45OTk5OTc0MyBMMTAuMzc5LDMuMzc5IEw5LjM3OSw0LjM3OSBMMTMuNjIxLDguNjIxIEwxNC42MjEsNy42MjEgTDE2LDguOTk5OTk3NDMgTDE2LDYuMjQzIEwxNy4xMjEsNS4xMjIgQzE3LjY4NDczNDcsNC41NTk4Mzk1NSAxOC4wMDEwODgzLDMuNzk2MTI4MSAxOCwyLjk5OTk5NzQzIEwxOCwtMi41NzI5NjUwM2UtMDYgTDE1LC0yLjU3Mjk2NTAzZS0wNiBaIE0xNSw0IEMxNC40NDc3MTUzLDQgMTQsMy41NTIyODQ3NSAxNCwzIEMxNCwyLjQ0NzcxNTI1IDE0LjQ0NzcxNTMsMiAxNSwyIEMxNS41NTIyODQ3LDIgMTYsMi40NDc3MTUyNSAxNiwzIEMxNiwzLjI2NTIxNjQ5IDE1Ljg5NDY0MzIsMy41MTk1NzA0IDE1LjcwNzEwNjgsMy43MDcxMDY3OCBDMTUuNTE5NTcwNCwzLjg5NDY0MzE2IDE1LjI2NTIxNjUsNCAxNSw0IFoiIGlkPSJQYXRoXzEwMTYiPjwvcGF0aD4KICAgICAgICAgICAgICAgIDxwb2x5Z29uIGlkPSJQYXRoXzEwMTciIHBvaW50cz0iMTEgOSA3IDExIDkgNyA4IDYgNCAxNCAxMiAxMCI+PC9wb2x5Z29uPgogICAgICAgICAgICAgICAgPHBvbHlnb24gaWQ9IlBhdGhfMTAxOCIgcG9pbnRzPSIxMSA4IDkgOSAxMCA3Ij48L3BvbHlnb24+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg=='
		);

		// Init General Settings Page.
		$this->general_settings->init();

		// Init AddOns Page.
		$this->addons_page->init();

		remove_submenu_page( 'better-sharing-wp', 'better-sharing-wp' );

	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function better_sharing_menu() {
		echo '<h2>Better Sharing</h2>';
	}

	/**
	 * Admin Scripts
	 *
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script(
			'bswp-admin-assets',
			BETTER_SHARING_URI . 'dist/admin/admin.bundle.js',
			array( 'jquery' ),
			BETTER_SHARING_VERSION,
			false
		);
	}

	/**
	 * Toggle AddOn Callback
	 *
	 * @return boolean
	 */
	public function toggle_addons() {
		if ( ! isset( $_GET, $_GET['toggleAddOn'], $_GET['addOn'], $_GET['n'] ) || 'true' !== $_GET['toggleAddOn'] ) {
			return false;
		}

		// check nonce.
		$verify = wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['n'] ) ), 'bswp_addons_nonce' );
		if ( ! $verify ) {
			echo 'EROR';
			return false;
		}

		$add_ons   = AddOnsCore::get_add_ons();
		$to_toggle = sanitize_text_field( wp_unslash( $_GET['addOn'] ) );

		foreach ( $add_ons as $add_on ) {
			if ( $to_toggle === $add_on->slug ) {
				$add_on->toggle_addon();
			}
		}

		wp_safe_redirect( admin_url( 'admin.php?page=better-sharing-addons' ) );

	}
}
