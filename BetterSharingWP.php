<?php
/*
 * @wordpress-plugin
 * Plugin Name:       Better Sharing WP
 * Description:       Better Sharing WordPress plugin for use for CloudSponge
 * Version:           1.0.0
 * Author:            CloudSponge
 * Author URI:        https://www.cloudsponge.com
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Textdomain:        better-sharing-wp
 */

namespace BetterSharingWP;

define( 'BETTER_SHARING_PATH', plugin_dir_path( __FILE__ ) );
define( 'BETTER_SHARING_URI', plugin_dir_url( __FILE__ ) );
define( 'BETTER_SHARING_VERSION', '1.0.0' );

define( 'BETTER_SHARING_ADMIN_TEMPLATE_PATH', BETTER_SHARING_PATH . 'includes/AdminScreens/admin-templates/' );

include_once 'vendor/autoload.php';

// AddOns
use BetterSharingWP\AddOns\AutomateWoo\AutomateWoo;

class BetterSharingWP {

	private $adminScreen;
	private $errors;

	public function __construct() {
		$this->adminScreen = new Admin();
	}

	/**
	 * @param BetterSharingAddOn $addOn
	 */
	public function initAddOn( $addOn ) {
		do_action( 'bswp_before_initAddOn', $addOn );
		$newAddOn = $addOn->init();
		if ( is_wp_error( $newAddOn ) ) {
			var_dump( $newAddOn->get_error_message() );
		}
		do_action( 'bswp_after_initAddOn', $addOn, $newAddOn );
	}


}

global $BetterSharingWP;

$BetterSharingWP = new BetterSharingWP();

add_action( 'init', function() {
	global $BetterSharingWP;

	// Core AddONs
	$BWPAddOns_AutomateWoo = new AutomateWoo();
	$BetterSharingWP->initAddOn( $BWPAddOns_AutomateWoo );
});