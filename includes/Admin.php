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
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMThweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMTggMjAiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8dGl0bGU+Q29tcG9uZW50XzJfNDwvdGl0bGU+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0iMjAiIGZpbGw9IiNGRkZGRkYiIGZpbGwtcnVsZT0ibm9uemVybyI+CiAgICAgICAgICAgIDxnIGlkPSJDb21wb25lbnRfMl80Ij4KICAgICAgICAgICAgICAgIDxwYXRoIGQ9Ik0xNi41NTIsMy4xNzIgTDE2LjU1MiwyLjc1OSBDMTYuNTUxNTEyMiwxLjg3NDc0MDQgMTUuOTg4ODU3MywxLjA4ODYzMTE1IDE1LjE1MiwwLjgwMyBMMTQuNDg0LDAuMDAzIEwxMy44MTYsMC44MDMgQzEyLjk3OTE0MjcsMS4wODg2MzExNSAxMi40MTY0ODc4LDEuODc0NzQwNCAxMi40MTYsMi43NTkgTDEyLjQxNiwzLjE3MyBMMTEuMDM3LDQuODI4IEwxMi40MTYsNC44MjggTDEyLjQxNiw2LjIwNyBMMTYuNTU0LDYuMjA3IEwxNi41NTQsNC44MjggTDE3LjkzMyw0LjgyOCBMMTYuNTUyLDMuMTcyIFogTTE0LjQ4MywzLjQ0OCBDMTQuMTAxOTIzNSwzLjQ0OCAxMy43OTMsMy4xMzkwNzY0OCAxMy43OTMsMi43NTggQzEzLjc5MywyLjM3NjkyMzUyIDE0LjEwMTkyMzUsMi4wNjggMTQuNDgzLDIuMDY4IEMxNC44NjQwNzY1LDIuMDY4IDE1LjE3MywyLjM3NjkyMzUyIDE1LjE3MywyLjc1OCBMMTUuMTczLDIuNzU4IEMxNS4xNzMsMi45NDA5OTkzOCAxNS4xMDAzMDM4LDMuMTE2NTAzNTggMTQuOTcwOTAzNywzLjI0NTkwMzY4IEMxNC44NDE1MDM2LDMuMzc1MzAzNzggMTQuNjY1OTk5NCwzLjQ0OCAxNC40ODMsMy40NDggTDE0LjQ4MywzLjQ0OCBaIiBpZD0iUGF0aF80OCI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLjc3Myw2Ljg5NiBDMTMuNjY3NDMyLDkuMjkyMjgzMiAxMi44NjIyODU4LDExLjYwNTE2NjMgMTEuNDU3LDEzLjU0OSBDMTMuNjcwMTU1MywxMi4wMzk4OTIyIDE1LjAzODY1NDgsOS41NzI1MTM0IDE1LjE0Nyw2Ljg5NiBMMTMuNzczLDYuODk2IFoiIGlkPSJQYXRoXzQ5Ij48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTUuNjY2LDYuODk2IEMxNS40LDEwLjk1NiAxMi44MSwxNC4yNDIgOS40NzEsMTQuODg0IEMxMS43NzMzMDkyLDEyLjg1MTg5NDIgMTMuMTQxNzM2MSw5Ljk2NDY3OTggMTMuMjU3LDYuODk2IEwxMi4zOTEsNi44OTYgQzEyLjA5MSwxMS45MDYgOC41MSwxNS44NjIgNC4xMzgsMTUuODYyIEM1Ljg0MjY2NDkxLDE2LjEyODI2ODIgNy41NzgzMzUwOSwxNi4xMjgyNjgyIDkuMjgzLDE1Ljg2MiBDMTMuMjYzLDE1LjE5MyAxNi4yMjUsMTEuOTA3IDE2LjUyOSw2Ljg5NiBMMTUuNjY2LDYuODk2IFoiIGlkPSJQYXRoXzUwIj48L3BhdGg+CiAgICAgICAgICAgICAgICA8cGF0aCBkPSJNMTUuMTcyLDEzLjQ5NyBMMTUuMTcyLDE3LjkyOSBDMTUuMTcyMDAwNCwxOC4zMDk2ODYzIDE0Ljg2MzY4NTksMTguNjE4NDQ4MyAxNC40ODMsMTguNjE5IEwyLjA2OSwxOC42MTkgQzEuNjg3OTIzNTIsMTguNjE5IDEuMzc5LDE4LjMxMDA3NjUgMS4zNzksMTcuOTI5IEwxLjM3OSw1LjUxNyBDMS4zNzksNS4xMzU5MjM1MiAxLjY4NzkyMzUyLDQuODI3IDIuMDY5LDQuODI3IEw5LjY3Nyw0LjgyNyBMMTAuODI2LDMuNDQ4IEwxLjM3OSwzLjQ0OCBDMS4wMTI5MTk2NywzLjQ0OCAwLjY2MTg2MjE5NSwzLjU5MzU2MTIxIDAuNDAzMTkxOTYsMy44NTI2MDY2IEMwLjE0NDUyMTcyNiw0LjExMTY1MTk5IC0wLjAwMDUzMDkzNTkzMyw0LjQ2MjkyMDA2IC0xLjQ1MjQxNjAyZS0wNiw0LjgyOSBMLTEuNDUyNDE2MDJlLTA2LDE4LjYyMiBDLTEuNDUyNDE2MDJlLTA2LDE5LjM4MzYwMDcgMC42MTczOTkzMywyMC4wMDEgMS4zNzksMjAuMDAxIEwxNS4xNzIsMjAuMDAxIEMxNS45MzM2MDA3LDIwLjAwMSAxNi41NTEsMTkuMzgzNjAwNyAxNi41NTEsMTguNjIyIEwxNi41NTEsMTguNjIyIEwxNi41NTEsMTEuMTggQzE2LjE4NzI5MTUsMTIuMDA1NTUwNyAxNS43MjQyMDI0LDEyLjc4MzYzNDUgMTUuMTcyLDEzLjQ5NyBMMTUuMTcyLDEzLjQ5NyBaIiBpZD0iUGF0aF81MSI+PC9wYXRoPgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4='
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
