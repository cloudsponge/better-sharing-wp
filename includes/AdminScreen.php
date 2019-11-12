<?php
/**
 * Created by PhpStorm.
 * User: roysivan
 * Date: 11/10/19
 * Time: 11:20 PM
 */

namespace BetterSharingWP;


class AdminScreen {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'better_sharing_menu_init' ], 10 );
	}

	public function better_sharing_menu_init() {
		add_menu_page(
			'Better Sharing WP',
			'Better Sharing',
			'manage_options',
			'better-sharing-wp',
			[ $this, 'better_sharing_menu' ],
			'dashicons-megaphone'
		);
	}

	public function better_sharing_menu() {
		echo '<h2>Better Sharing WP</h2>';

		include_once( BETTER_SHARING_PATH . '/admin-templates/proxy-url.php' );

	}

}