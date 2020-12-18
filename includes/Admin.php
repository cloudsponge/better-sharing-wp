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
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCI+DQogIDxkZWZzPg0KICAgIDxjbGlwUGF0aCBpZD0iY2xpcC1fMjAiPg0KICAgICAgPHJlY3Qgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIi8+DQogICAgPC9jbGlwUGF0aD4NCiAgPC9kZWZzPg0KICA8ZyBpZD0iXzIwIiBkYXRhLW5hbWU9IjIwIiBjbGlwLXBhdGg9InVybCgjY2xpcC1fMjApIj4NCiAgICA8cmVjdCB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMCkiLz4NCiAgICA8ZyBpZD0iQ29tcG9uZW50XzJfNCIgZGF0YS1uYW1lPSJDb21wb25lbnQgMiDigJMgNCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMSkiPg0KICAgICAgPHBhdGggaWQ9IlBhdGhfNDgiIGRhdGEtbmFtZT0iUGF0aCA0OCIgZD0iTTYxMi42OSw1NS4xNzNWNTQuNzZhMi4wNjgsMi4wNjgsMCwwLDAtMS40LTEuOTU2bC0uNjY4LS44LS42NjguOGEyLjA2OCwyLjA2OCwwLDAsMC0xLjQsMS45NTZ2LjQxNGwtMS4zNzksMS42NTVoMS4zNzl2MS4zNzloNC4xMzhWNTYuODI5aDEuMzc5Wm0tMi4wNjkuMjc2YS42OS42OSwwLDEsMSwuNjktLjY5aDBBLjY5LjY5LDAsMCwxLDYxMC42MjEsNTUuNDQ5WiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTU5Ni4xMzggLTUyLjAwMSkiLz4NCiAgICAgIDxwYXRoIGlkPSJQYXRoXzQ5IiBkYXRhLW5hbWU9IlBhdGggNDkiIGQ9Ik02MjguOTEyLDM2OS4yMzlhMTIuMjc4LDEyLjI3OCwwLDAsMS0yLjMxNiw2LjY1Myw4LjQ2Nyw4LjQ2NywwLDAsMCwzLjY5LTYuNjUzWiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTYxNS4xMzkgLTM2Mi4zNDMpIi8+DQogICAgICA8cGF0aCBpZD0iUGF0aF81MCIgZGF0YS1uYW1lPSJQYXRoIDUwIiBkPSJNMzAxLjQ2LDM2OS4yMzljLS4yNjYsNC4wNi0yLjg1Niw3LjM0Ni02LjE5NSw3Ljk4OGExMS4yMTYsMTEuMjE2LDAsMCwwLDMuNzg2LTcuOTg4aC0uODY2Yy0uMyw1LjAxLTMuODgxLDguOTY2LTguMjUzLDguOTY2YTE2LjY2OSwxNi42NjksMCwwLDAsNS4xNDUsMGMzLjk4LS42NjksNi45NDItMy45NTUsNy4yNDYtOC45NjZaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjg1Ljc5NCAtMzYyLjM0MykiLz4NCiAgICAgIDxwYXRoIGlkPSJQYXRoXzUxIiBkYXRhLW5hbWU9IlBhdGggNTEiIGQ9Ik0xMTQuNzU5LDIyMC42NjhWMjI1LjFhLjY5LjY5LDAsMCwxLS42ODkuNjlIMTAxLjY1NmEuNjkuNjksMCwwLDEtLjY5LS42OVYyMTIuNjg4YS42OS42OSwwLDAsMSwuNjktLjY5aDcuNjA4bDEuMTQ5LTEuMzc5aC05LjQ0N0ExLjM3OSwxLjM3OSwwLDAsMCw5OS41ODcsMjEydjEzLjc5M2ExLjM3OSwxLjM3OSwwLDAsMCwxLjM3OSwxLjM3OWgxMy43OTNhMS4zNzksMS4zNzksMCwwLDAsMS4zNzktMS4zNzloMHYtNy40NDJBMTEuMDg5LDExLjA4OSwwLDAsMSwxMTQuNzU5LDIyMC42NjhaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtOTkuNTg3IC0yMDcuMTcxKSIvPg0KICAgIDwvZz4NCiAgPC9nPg0KPC9zdmc+DQo='
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

		if ( ! isset( $_GET, $_GET['toggleAddOn'], $_GET['addOn'] )
		) {
			if ( isset( $_GET['toggleAddOn'] ) && 'true' !== $_GET['toggleAddOn'] ) {
				return false;
			}
			return false;
		}

		$addOns   = AddOnsCore::getAddOns();
		$toToggle = sanitize_text_field( $_GET['addOn'] );

		foreach ( $addOns as $addOn ) {
			if ( $toToggle === $addOn->slug ) {
				$addOn->toggle_addon();
			}
		}

		wp_safe_redirect( admin_url( 'admin.php?page=better-sharing-addons' ) );

	}

}
