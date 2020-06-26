<?php

namespace BetterSharingWP\AdminScreens;

use BetterSharingWP\OptionData;

class GeneralSettings {

  private $optionData;
  private $errorMsg;

  public function init() {
    add_submenu_page(
      'better-sharing-wp',
      'General Settings',
      'General Settings',
      'manage_options',
      'better-sharing-general',
      [ $this, 'template' ]
    );

    add_action( 'admin_init', [ $this, 'load_init' ] );
  }

  /**
   * Template for page
   */
  public function template() {
    echo '<div class="wrap bswp" id="bswp">';
      echo '<h1>Better Sharing Settings</h1>';
      include_once( BETTER_SHARING_ADMIN_TEMPLATE_PATH . 'general-settings/cloudsponge-settings.php' );
    echo '</div>';
  }


  /**
   * Page load init
   */
  public function load_init() {

    // Load OptionData
    $optionData = new OptionData( 'core' );

    if ( ! is_wp_error( $optionData ) ) {
      $this->optionData = $optionData;
    }

    // Save Data
    $post = $_POST;
    if ( !isset ( $_POST['__bswp_api_key__save'] ) ) {
      return;
    }

    if ( isset( $_POST['__bswp_api_key'] ) ) {
      $apiKeySaved = $this->save_api_key( sanitize_text_field( $_POST['__bswp_api_key'] ) );

      if ( is_wp_error( $apiKeySaved ) ) {
        $this->errorMsg = $apiKeySaved->get_error_message();

        add_action( 'admin_notices', function() {
          $class = 'notice notice-error';
          $message = $this->errorMsg;
          printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
        });
      }
    }
  }


  private function save_api_key( $keyValue ) {
    $key = 'apiKey';

    if ( '' === $keyValue ) {
      return $this->optionData->delete( $key );
    } else {
      return $this->optionData->save( $key, $keyValue );
    }
  }
}