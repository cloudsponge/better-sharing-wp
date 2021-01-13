<?php
/**
 * Better Sharing
 *
 * @wordpress-plugin
 * Plugin Name:       Better Sharing
 * Description:       Add essential viral sharing functionality to any WordPress site.
 * Version:           1.2.2
 * Author:            CloudSponge
 * Author URI:        https://www.cloudsponge.com
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       better-sharing-wp
 *
 * @package BetterSharingWP
 */

namespace BetterSharingWP;

define( 'BETTER_SHARING_PATH', plugin_dir_path( __FILE__ ) );
define( 'BETTER_SHARING_URI', plugin_dir_url( __FILE__ ) );
define( 'BETTER_SHARING_VERSION', '1.2.2' );

define( 'BETTER_SHARING_ADMIN_TEMPLATE_PATH', BETTER_SHARING_PATH . 'includes/AdminScreens/admin-templates/' );

require_once 'vendor/autoload.php';

// API
use BetterSharingWP\API\Email;

// Core Blocks.
use BetterSharingWP\CoreBlocks;

// AddOns.
use BetterSharingWP\Admin;
use BetterSharingWP\AddOns\BetterSharingAddOn;
use BetterSharingWP\AddOns\AutomateWoo\AutomateWoo;
use BetterSharingWP\AddOns\CouponReferralProgram\CouponReferralProgram;
use BetterSharingWP\AddOns\WooWishlists\WooWishlists;

/**
 * BetterSharingWP - Main Plugin Class
 */
class BetterSharingWP {

	/**
	 * BWP Admin Screens
	 *
	 * @var Admin admin screens.
	 */
	private $admin_screen;

	/**
	 * Errors
	 *
	 * @var array errors for plugins
	 */
	private $errors;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->admin_screen = new Admin();
		$this->errors       = array();

		// Email API.
		$api = new Email();
		add_action( 'rest_api_init', array( $api, 'rest_init' ) );

		// Core Blocks.
		$core_blocks = new CoreBlocks();
		add_action( 'init', array( $core_blocks, 'register_block' ) );
		add_action( 'wp_enqueue_scripts', array( $core_blocks, 'core_block_public_scripts' ) );

		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}

	/**
	 * Initialize Addon
	 *
	 * @param BetterSharingAddOn $add_on initialize AddOn.
	 * @return void
	 */
	public function init_add_on( BetterSharingAddOn $add_on ) {
		do_action( 'bswp_before_init_addon', $add_on );
		$new_add_on = $add_on->init();
		if ( is_wp_error( $new_add_on ) ) {
			$this->errors[] = $new_add_on;
		}
		do_action( 'bswp_after_init_addon', $add_on, $new_add_on );
	}

	/**
	 * Get Errors
	 *
	 * @return array errors.
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Deactivate Plugin
	 *
	 * @return void
	 */
	public function deactivate() {
		$option_data = new OptionData();
		$delete      = $option_data->deleteAll( true );
	}

}

global $better_sharing_wp;

$better_sharing_wp = new BetterSharingWP();

/**
 * Initialize Core Add Ons
 */
add_action(
	'init',
	function() {
		global $better_sharing_wp;

		$automate_woo_addon = new AutomateWoo();
		$better_sharing_wp->init_add_on( $automate_woo_addon );

		$coupon_referral_addon = new CouponReferralProgram();
		$better_sharing_wp->init_add_on( $coupon_referral_addon );

		$woo_wishlist_addon = new WooWishlists();
		$better_sharing_wp->init_add_on( $woo_wishlist_addon );

		$errors = $better_sharing_wp->get_errors();
		if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) && ! empty( $errors ) ) {
			foreach ( $errors as $error ) {
				error_log(
					print_r(
						array(
							$error->get_error_message(),
							$error->get_error_data(),
						)
					),
					true
				);
			}
		}
	}
);

