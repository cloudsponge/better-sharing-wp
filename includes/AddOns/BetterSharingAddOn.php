<?php

namespace BetterSharingWP\AddOns;

use BetterSharingWP\AddOnsCore;
use BetterSharingWP\OptionData;


abstract class BetterSharingAddOn {

	public $name;
	public $slug;
	public $description;
	public $status;
	public $apiKey;

	private $optionData;

	/**
	 * Initialize AddOn
	 *
	 * @param $name
	 * @param $description
	 *
	 * @return int|\WP_Error
	 */
	public function initAddOn( $name, $description ) {
		$this->name = sanitize_text_field( $name );
		$this->slug = sanitize_title( $name );
		$this->description = sanitize_text_field( $description );
		$this->apiKey = get_site_option( '_bswp_option_core_apiKey', false );

		if ( ! $this->apiKey ) {
			return new \WP_Error( '400', __( 'No API Key Set' ) );
		}

		$this->optionData = new OptionData( $this->slug );
		if ( ! $this->optionData ) {
			return new \WP_Error( '400', __( 'Error Creating OptionData Object' ) );
		}

		// Set Active State if not set.
		if ( ! $this->optionData->get( 'status' ) ) {
			$this->optionData->save( 'status', 'inactive' );
		}

		$this->status = $this->optionData->get( 'status' );

		// Add to list of addOns
		return AddOnsCore::add( $this );
	}

	/**
	 * Is AddOn Active
	 *
	 * @return bool
	 */
	public function isActive() {
		return 'active' === $this->status;
	}

	/**
	 * Init actions
	 *
	 * @return int|\WP_Error
	 */
	public function init() {}

	/**
	 * Activate AddOn
	 *
	 * @return string
	 */
	public function activate() {
		$this->optionData->save( 'status', 'active' );
		$this->status = $this->optionData->get( 'status' );
		return $this->status;
	}

	/**
	 * Deactivate AddOn
	 *
	 * @return string
	 */
	public function deactivate() {
		$this->optionData->save( 'status', 'inactive' );
		$this->status = $this->optionData->get( 'status' );
		return $this->status;
	}

}