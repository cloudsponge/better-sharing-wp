<?php

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$add_ons = AddOnsCore::get_add_ons();

?>
<div class="bswp__container">
	<div class="bswp__addons">
	<?php foreach ( $add_ons as $add_on ) : ?>
		<?php
		// Is Addon Active.
		$addonActive = 'active' === $add_on->status;
		// Plugin is installed and activated - class.
		$pluginAvailableClass = $add_on->is_plugin_active() ? 'plugin-available' : 'plugin-unavailable';
		// AddOn is active status - class.
		$activeClass = $addonActive && $add_on->is_plugin_active() ? 'active' : 'inactive';
		?>
	<div class="card bswp__addon <?php echo esc_attr( $pluginAvailableClass ); ?>">

		<div class="bswp__addon__header">
		<h2 class="title bswp__addon__title"><?php echo esc_html( $addOn->name ); ?></h2>

		<!-- Toggle -->
		<div class="bswp__addon__status">
			<span class="bswp__addon__status-label">
			<?php echo esc_html( $activeClass ); ?>
			</span>
			<!-- toggle slider -->
			<div class="bswp__addon__status-indicator <?php echo esc_attr( $activeClass ); ?>" data-addon="<?php echo esc_attr( $addOn->slug ); ?>" data-status="<?php echo esc_attr( $addOn->status ); ?>" data-plugin="<?php echo esc_attr( $pluginAvailableClass ); ?>"></div>
		</div>
		<!-- /Toggle -->
		</div>

		<?php
		if ( ! $add_on->is_plugin_active() ) {
			echo '<p>Plugin is not installed & activated. Go to the Plugins page to activate the appropriate plugin</p>';
		}
		?>


		<!-- Description -->
		<?php if ( $add_on->description ) : ?>
		<div class="bswp__addon__description">
			<?php echo esc_html( wpautop( $addOn->description ) ); ?>
		</div>
		<?php endif; ?>
		<!-- /Description -->

		<!-- Doc / Support URL -->
		<?php if ( $add_on->support_url ) : ?>
		<div class="bswp__addon__link">
		<strong>AddOn Link:</strong>
		<a href="<?php echo esc_url( $addOn->support_url ); ?>" target="_blank" rel="noopener noreferrer">
			<?php echo esc_html( $addOn->support_url ); ?>
			<span class="dashicons dashicons-external"></span>
		</a>
		</div>
		<?php endif; ?>
		<!-- / Doc / Support URL -->

		<!-- Settings Toggle -->
		<?php if ( $add_on->has_settings && $addonActive ) : ?>
		<hr class="bswp__spacer">
		<a class="btn button bswp__addon__settings-toggle" href="#" data-addon="<?php echo esc_attr( $addOn->slug ); ?>">Settings</a>
		<?php endif; ?>
		<!-- / Settings Toggle -->



		<!-- Settings -->
		<?php if ( $addOn->has_settings ) : ?>
		<div class="bswp__addon__settings <?php echo esc_attr( $addOn->slug ) . '-settings'; ?>">
		<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=better-sharing-addons' ) ); ?>">
			<?php wp_nonce_field( 'bswp_addons_nonce', '_bswp_addons_nonce' ); ?>
			<input type="hidden" name="save_addon" value="yes" />
			<div class="bswp__addon__settings-group">
			<?php $add_on->display_settings(); ?>
			</div>
			<input class="button button-primary" type="submit" value="Save Settings" />
		</form>
		</div>
		<?php endif; ?>
		<!-- /Settings -->

	</div>
	<?php endforeach; ?>

	</div>
</div>
