<?php

namespace BetterSharingWP;


class AddOnsCore {
	
	public static $addOns;

	/**
	 * Add AddOn
	 *
	 * @param $addOn
	 *
	 * @return int|\WP_Error
	 */
	public static function add( $addOn ) {
		if ( ! is_array( self::$addOns ) ) {
			self::$addOns = [];
		}

		// Make sure proper parent class used
		if( ! get_parent_class( $addOn ) || 'BetterSharingWP\AddOns\BetterSharingAddOn' !== get_parent_class( $addOn ) ) {
			return new \WP_Error( '401', __( 'Wrong parent class used for addon' ) );
		}

		return array_push( self::$addOns, $addOn );
	}

	/**
	 * Get all AddOns
	 *
	 * @return array
	 */
	public static function getAddOns() {
		if ( ! is_array( self::$addOns ) ) {
			self::$addOns = [];
		}
		return self::$addOns;
	}
	
}