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
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iMTlweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMTkgMjAiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+CiAgICA8dGl0bGU+SWNvbjwvdGl0bGU+CiAgICA8ZyBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KICAgICAgICA8ZyBpZD0iMjAtMyIgZmlsbD0iIzIzMUYyMCIgZmlsbC1ydWxlPSJub256ZXJvIj4KICAgICAgICAgICAgPGcgaWQ9Ikljb24iIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuODE4MTgyLCAwLjAwMDAwMCkiPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTkuNTQ1NDU0NTUsMTUuMDg5MjM3OSBDMTAuNzQ3NjM5OSwxNC43MTY0MTI4IDE1LjQ1NTAwNDUsMTIuOTAyNzM0OSAxNS40NTQ1NDU1LDcuOTAyNzM0OTMgTDExLjA5MDkwOTEsNy45MDI3MzQ5MyBDMTAuNjQzMDc0NywxMS42Mzk2OTU2IDkuMDMzMzg2MDYsMTMuMzM4NzExNSAzLjYzNjM2MzY0LDE1LjQ1NDU0NTUgQzQuOTA5NTA0OTgsMTUuNDU0NTQ1NSA4LjI0MTYxNTMsMTUuNTA0MTA2MiA5LjU0NTQ1NDU1LDE1LjA4OTIzNzkgWiIgaWQ9IlBhdGhfMTAxOSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLjI3MjcyNzMsMC41IEwxMy44NTQ1NDU1LDEuMjc3Nzc3NzggQzE0LjgwMjE4NzIsMS41MzI2ODkyMiAxNS40NTczNzQsMi4zNzIzNTQwNSAxNS40NTQ1NDU1LDMuMzI4MjgyODMgTDE1LjQ1NDU0NTUsMy4zMjgyODI4MyBMMTUuNDU0NTQ1NSwzLjQ2OTY5Njk3IEwxNi45MDkwOTA5LDUuNDQ5NDk0OTUgTDE1LjQ1NDM2MzYsNS40NDkgTDE1LjQ1NDU0NTUsNi44NjM2MzYzNiBMMTEuMDkwOTA5MSw2Ljg2MzYzNjM2IEwxMS4wOTAzNjM2LDUuNDQ5IEw5LjYzNjM2MzY0LDUuNDQ5NDk0OTUgTDExLjA5MDkwOTEsMy40Njk2OTY5NyBMMTEuMDkwOTA5MSwzLjMyODI4MjgzIEMxMS4wODgwODA2LDIuMzcyMzU0MDUgMTEuNzQzMjY3MywxLjUzMjY4OTIyIDEyLjY5MDkwOTEsMS4yNzc3Nzc3OCBMMTIuNjkwOTA5MSwxLjI3Nzc3Nzc4IEwxMy4yNzI3MjczLDAuNSBaIE0xMy4yNzI3MjczLDIuNjIxMjEyMTIgQzEzLjA3NjQ2MywyLjYwOTc0MjE2IDEyLjg4NDU0NCwyLjY4MDU0NTc3IDEyLjc0NTUxMzcsMi44MTU3MTQxMiBDMTIuNjA2NDgzNCwyLjk1MDg4MjQ3IDEyLjUzMzY1NjksMy4xMzc0NzAzNCAxMi41NDU0NTQ1LDMuMzI4MjgyODMgQzEyLjU0NTQ1NDUsMy43MTg3ODcyIDEyLjg3MTA2NTYsNC4wMzUzNTM1NCAxMy4yNzI3MjczLDQuMDM1MzUzNTQgQzEzLjY3NDM4ODksNC4wMzUzNTM1NCAxNCwzLjcxODc4NzIgMTQsMy4zMjgyODI4MyBDMTQuMDExNzk3NywzLjEzNzQ3MDM0IDEzLjkzODk3MTEsMi45NTA4ODI0NyAxMy43OTk5NDA4LDIuODE1NzE0MTIgQzEzLjY2MDkxMDUsMi42ODA1NDU3NyAxMy40Njg5OTE1LDIuNjA5NzQyMTYgMTMuMjcyNzI3MywyLjYyMTIxMjEyIFoiIGlkPSJDb21iaW5lZC1TaGFwZSI+PC9wYXRoPgogICAgICAgICAgICAgICAgPHBhdGggZD0iTTEzLjYzNjM2MzYsMTYuMzYzNjM2NCBDMTMuNjUxMTEwNywxNi42MDg5NjY3IDEzLjU2MDA3NzUsMTYuODQ4ODY1NCAxMy4zODYyODk2LDE3LjAyMjY1MzMgQzEzLjIxMjUwMTgsMTcuMTk2NDQxMiAxMi45NzI2MDMxLDE3LjI4NzQ3NDQgMTIuNzI3MjcyNywxNy4yNzI3MjczIEwyLjcyNzI3MjczLDE3LjI3MjcyNzMgQzIuNDgxOTQyMzksMTcuMjg3NDc0NCAyLjI0MjA0MzY5LDE3LjE5NjQ0MTIgMi4wNjgyNTU4MSwxNy4wMjI2NTMzIEMxLjg5NDQ2Nzk0LDE2Ljg0ODg2NTQgMS44MDM0MzQ3MywxNi42MDg5NjY3IDEuODE4MTgxODIsMTYuMzYzNjM2NCBMMS44MTgxODE4Miw2LjM2MzYzNjM2IEMxLjgwMzQzNDczLDYuMTE4MzA2MDMgMS44OTQ0Njc5NCw1Ljg3ODQwNzMzIDIuMDY4MjU1ODEsNS43MDQ2MTk0NSBDMi4yNDIwNDM2OSw1LjUzMDgzMTU4IDIuNDgxOTQyMzksNS40Mzk3OTgzNiAyLjcyNzI3MjczLDUuNDU0NTQ1NDUgTDguMTgxODE4MTgsNS40NDk0OTQ5NSBMOS41MDkwOTA5MSwzLjYzNjM2MzY0IEwyLjcyNzI3MjczLDMuNjM2MzYzNjQgQzEuOTk5OTEyNDIsMy42MjI5MDQzNyAxLjI5ODM3MjM1LDMuOTA1OTE3MDggMC43ODM5NjI4OTYsNC40MjAzMjY1MyBDMC4yNjk1NTM0NDQsNC45MzQ3MzU5OCAtMC4wMTM0NTkyNjg3LDUuNjM2Mjc2MDYgLTIuMDExMzU3NjZlLTE1LDYuMzYzNjM2MzYgTC0yLjAxMTM1NzY2ZS0xNSwxNi4zNjM2MzY0IEMtMC4wMTM0NTkyNjg3LDE3LjA5MDk5NjcgMC4yNjk1NTM0NDQsMTcuNzkyNTM2NyAwLjc4Mzk2Mjg5NiwxOC4zMDY5NDYyIEMxLjI5ODM3MjM1LDE4LjgyMTM1NTYgMS45OTk5MTI0MiwxOS4xMDQzNjg0IDIuNzI3MjcyNzMsMTkuMDkwOTA5MSBMMTIuNzI3MjcyNywxOS4wOTA5MDkxIEMxMy40NTQ2MzMsMTkuMTA0MzY4NCAxNC4xNTYxNzMxLDE4LjgyMTM1NTYgMTQuNjcwNTgyNiwxOC4zMDY5NDYyIEMxNS4xODQ5OTIsMTcuNzkyNTM2NyAxNS40NjgwMDQ3LDE3LjA5MDk5NjcgMTUuNDU0NTQ1NSwxNi4zNjM2MzY0IEwxNS40NTQ1NDU1LDEyLjM5NzMyMTQgQzE1LjA1ODQ0NDYsMTMuMjQ5Mjg4IDE0LjMwMzk4NzcsMTMuOTczMjAzMSAxMy42MzYzNjM2LDE0LjM2MzYzNjQgTDEzLjYzNjM2MzYsMTYuMzYzNjM2NCBaIiBpZD0iUGF0aF8xMDIxIj48L3BhdGg+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPg=='
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
