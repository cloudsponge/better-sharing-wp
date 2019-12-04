<?php
	$toggleVal = $this->optionData->get('shareLinkToggle');
?>

<div class="bswp-form-group">
	<h4>Display Share Link</h4>
	<div class="bswp-checkbox">
		<p class="description">
			Show "Share Your Link" to allow users to copy your unique referral URL.
		</p>
		<label for="share_link_toggle_true">
			Yes
			<input type="radio" id="share_link_toggle_true" name="share_link_toggle" value="true" <?php checked( $toggleVal ); ?>>
		</label>

		<label for="share_link_toggle_false">
			No
			<input type="radio" id="share_link_toggle_false" name="share_link_toggle" value="false" <?php checked( ! $toggleVal ); ?>>
		</label>
	</div>
</div>