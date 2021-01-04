<?php
/**
 * AddOns - Admin Page.
 *
 * @package AddOns
 */

namespace BetterSharingWP\AdminScreens;

use BetterSharingWP\OptionData;

/**
 * AddOns - Addons Page in Admin.
 */
class AddOns {

	/**
	 * Option Data
	 *
	 * @var OptionData
	 */
	private $option_data;

	/**
	 * Error Message
	 *
	 * @var string
	 */
	private $error_msg;

	/**
	 * Init AddOns Page
	 *
	 * @return void
	 */
	public function init() {
		add_submenu_page(
			'better-sharing-wp',
			'AddOns',
			'AddOns',
			'manage_options',
			'better-sharing-addons',
			array( $this, 'template' )
		);

		add_action( 'admin_init', array( $this, 'load_init' ) );
	}

	/**
	 * Template for page
	 */
	public function template() {
		echo '<div class="wrap bswp" id="bswp">';
		echo '<h1>Better Sharing AddOns</h1>';
		include_once BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'addons-page.php';
		echo '</div>';
	}


	/**
	 * Page load init
	 */
	public function load_init() {

		// Load OptionData.
		$option_data = new OptionData( 'core' );

		if ( ! is_wp_error( $option_data ) ) {
			$this->option_data = $option_data;
		}

		// Save Data.
		$post = array();

		if ( ! empty( $_POST ) && is_array( $_POST ) ) {
				foreach ( $_POST as $key => $value ) {
						$post[$key] = sanitize_text_field( $value );
				}
		}

		if ( ! isset( $post['__bswp_api_key__save'] ) ) {
			return;
		}
		if ( isset( $post['__bswp_api_key'] ) ) {
			$api_key_saved = $this->save_api_key( $post['__bswp_api_key'] );
			if ( is_wp_error( $api_key_saved ) ) {
				$this->error_msg = $api_key_saved->get_error_message();
				add_action(
					'admin_notices',
					function () {
						$class   = 'notice notice-error';
						$message = $this->error_msg;
						printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
					}
				);
			}
		}
	}


	/**
	 * Save API Key
	 *
	 * @param string $key_value api value.
	 * @return boolean
	 */
	private function save_api_key( $key_value ) {
		$key = 'apiKey';
		if ( '' === $key_value ) {
			// delete if empty.
			return $this->option_data->delete( $key );
		} else {
			return $this->option_data->save( $key, $key_value );
		}
	}
}
