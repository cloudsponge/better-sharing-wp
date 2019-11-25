<?php

namespace BetterSharingWP;

use BetterSharingWP\AdminScreens\GeneralSettings;


class Admin {

	private $generalSettings;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'better_sharing_menu_init' ], 10 );
		$this->generalSettings = new GeneralSettings();

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
	}

	public function better_sharing_menu_init() {
		add_menu_page(
			'Better Sharing WP',
			'Better Sharing WP',
			'manage_options',
			'better-sharing-wp',
			[ $this, 'better_sharing_menu' ],
			'dashicons-megaphone'
		);

		// Init General Settings Page
		$this->generalSettings->init();

		remove_submenu_page( 'better-sharing-wp', 'better-sharing-wp' );

	}

	public function better_sharing_menu() {
		echo '<h2>Better Sharing WP</h2>';

	}

	public function admin_scripts() {
		wp_enqueue_script(
			'bswp-admin-assets',
			BETTER_SHARING_URI . 'dist/admin/admin.bundle.js',
			['jquery'],
			filemtime( BETTER_SHARING_PATH . 'dist/admin/admin.bundle.js' ),
			false
		);
	}

}