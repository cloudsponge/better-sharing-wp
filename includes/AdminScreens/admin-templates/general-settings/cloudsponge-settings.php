<?php

namespace BetterSharingWP;

?>

<div class="bswp__container">
  <div class="card bswp__settings">
	<h2 class="title bswp__title">CloudSponge Configuration</h2>
	<form action="<?php echo esc_url( admin_url( 'admin.php?page=better-sharing-general' ) ); ?>" method="post">
	  <div class="bswp__form-group">
		<?php wp_nonce_field( 'bswp_settings_nonce', '_bswp_settings_nonce' ); ?>
		<label for="__bswp_api_key" class="bswp__form-group__label">API Key:</label>
		<div class="bswp__input-group">
		  <input name="__bswp_api_key" type="text" id="__bswp_api_key" value="<?php echo esc_attr( $this->option_data->get( 'apiKey' ) ); ?>" placeholder="123456789">
		  <input type="submit" value="Save API Key" name="__bswp_api_key__save" class="button button-primary">
		</div>
	  </div>
	  <?php if ( $this->option_data->get( 'apiKey' ) ) : ?>
	  <div class="bswp__form-group">
		<label for="bswp-proxy-url" class="bswp__form-group__label">Your Proxy URL:</label>
		<div class="bswp__input-group">
		  <div class="bswp__copy-input">
			<input type="text" id="bswp-proxy-url" readonly value="<?php echo esc_url( BETTER_SHARING_URI . 'public/proxy.html' ); ?>" />
			<a href="#" class="copyText" data-text="bswp-proxy-url">Copy</a>
		  </div>
		</div>
	  </div>
	  <?php endif; ?>

	  <p class="help">
		You can find your key in the <a target="_blank" rel="noopener noreferrer" href="https://app.cloudsponge.com/app/keys">CloudSponge Dashboard</a>
	  </p>
	</form>
  </div>
</div>
