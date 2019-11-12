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

include_once 'vendor/autoload.php';

use BetterSharingWP\AdminScreen;

class BetterSharingWP {

	private $adminScreen;

	public function __construct() {
		$adminScreen = new AdminScreen();
	}


}

global $BetterSharingWP;

$BetterSharingWP = new BetterSharingWP();