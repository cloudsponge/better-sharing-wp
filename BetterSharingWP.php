<?php
/*
 * @wordpress-plugin
 * Plugin Name:       Better Sharing WP
 * Description:       Better Sharing WordPress plugin for use for CloudSponge
 * Version:           1.1.0
 * Author:            CloudSponge
 * Author URI:        https://www.cloudsponge.com
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Textdomain:        better-sharing-wp
 */

namespace BetterSharingWP;

define( 'BETTER_SHARING_PATH', plugin_dir_path( __FILE__ ) );
define( 'BETTER_SHARING_URI', plugin_dir_url( __FILE__ ) );
define( 'BETTER_SHARING_VERSION', '1.1.0' );

define( 'BETTER_SHARING_ADMIN_TEMPLATE_PATH', BETTER_SHARING_PATH . 'includes/AdminScreens/admin-templates/' );

include_once 'vendor/autoload.php';

// AddOns
use BetterSharingWP\AddOns\BetterSharingAddOn;
use BetterSharingWP\AddOns\AutomateWoo\AutomateWoo;
use BetterSharingWP\Addons\CouponReferralProgram\CouponReferralProgram;
use BetterSharingWP\AddOns\WooWishlists\WooWishlists;

class BetterSharingWP {

	private $adminScreen;
	private $errors;

	public function __construct() {
		$this->adminScreen = new Admin();
		$this->errors = [];

		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
	}

	/**
	 * @param $addOn
	 */
	public function initAddOn( BetterSharingAddOn $addOn ) {
		do_action( 'bswp_before_initAddOn', $addOn );
		$newAddOn = $addOn->init();
		if ( is_wp_error( $newAddOn ) ) {
			$this->errors[] = $newAddOn;
		}
		do_action( 'bswp_after_initAddOn', $addOn, $newAddOn );
	}

	public function deactivate() {
		$OptionData = new OptionData();
		$delete = $OptionData->deleteAll(true );
	}


}

global $BetterSharingWP;

$BetterSharingWP = new BetterSharingWP();

add_action( 'init', function() {
	global $BetterSharingWP;

	// Core AddONs
	$BWPAddOns_AutomateWoo = new AutomateWoo();
	$BetterSharingWP->initAddOn( $BWPAddOns_AutomateWoo );

	$BWPAddOns_CouponReferral = new CouponReferralProgram();
	$BetterSharingWP->initAddOn( $BWPAddOns_CouponReferral );

	$BWPAddons_WooWishLists = new WooWishlists();
	$BetterSharingWP->initAddOn( $BWPAddons_WooWishLists );
});