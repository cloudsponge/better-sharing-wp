<?php

namespace BetterSharingWP;

/**
 * AddONsCore
 */
class AddOnsCore {
	/**
	 * Add_ONs
	 *
	 * @var mixed AddOns
	 */
	public static $add_ons;

	/**
	 * Add add_on
	 *
	 * @param mixed $add_on AddOn.
	 *
	 * @return int|\WP_Error
	 */
	public static function add( $add_on ) {
		if ( ! is_array( self::$add_ons ) ) {
			self::$add_ons = array();
		}

		// Make sure proper parent class used.
		if ( ! get_parent_class( $add_on ) || 'BetterSharingWP\AddOns\BetterSharingAddOn' !== get_parent_class( $add_on ) ) {
			return new \WP_Error( '401', __( 'Wrong parent class used for add_on' ), get_parent_class( $add_on ) );
		}

		return array_push( self::$add_ons, $add_on );
	}

	/**
	 * Get all add_ons
	 *
	 * @return array
	 */
	public static function get_add_ons() {
		if ( ! is_array( self::$add_ons ) ) {
			self::$add_ons = array();
		}
		return self::$add_ons;
	}

}
