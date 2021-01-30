<?php

/**
 * Template File for Admin Addon page
 *
 * @package Admin AddOn Template
 */

namespace BetterSharingWP;

use BetterSharingWP\AddOnsCore;

$add_ons = AddOnsCore::get_add_ons();
$nonce = wp_create_nonce( 'bswp_addons_nonce' );

?>
<div class="bswp__container">
	<div class="bswp__addons">
		<?php foreach ( $add_ons as $add_on ) : ?>
			<?php
			// Is Addon Active.
			$add_on_active = 'active' === $add_on->status;
			// Plugin is installed and activated - class.
			$plugin_available_class = $add_on->is_plugin_active() ? 'plugin-available' : 'plugin-unavailable';
			// AddOn is active status - class.
			$active_class = $add_on_active && $add_on->is_plugin_active() ? 'active' : 'inactive';
			?>
			<div class="card bswp__addon <?php echo esc_attr( $plugin_available_class ); ?>">

				<div class="bswp__addon__header">
					<h2 class="title bswp__addon__title"><?php echo esc_html( $add_on->name ); ?></h2>
				</div>

				<!-- Description -->
				<?php if ( $add_on->description ) : ?>
					<div class="bswp__addon__description">
						<?php echo wp_kses( wpautop( $add_on->description ), array( 'p' ) ); ?>
					</div>
				<?php endif; ?>
				<!-- /Description -->
				
				<div class="bswp__addon__btns">
					<!-- Settings Toggle -->
					<?php if ( $add_on->has_settings ) : ?>
						<a class="button button-primary bswp__addon__settings-toggle" href="#" data-addon="<?php echo esc_attr( $add_on->slug ); ?>">Settings</a>
					<?php endif; ?>
					<!-- / Settings Toggle -->
					<!-- Doc / Support URL -->
					<?php if ( $add_on->support_url ) : ?>
						<a class="button" href="<?php echo esc_url( $add_on->support_url ); ?>" target="_blank" rel="noopener noreferrer">
							Learn More
						</a>
					<?php endif; ?>
					<!-- / Doc / Support URL -->
				</div>
				
				<div class="bswp__addon__toggle">
					<div class="disclaimer">
						<?php
						if ( ! $add_on->is_plugin_active() ) {
							echo wp_kses( '<p>Plugin is not installed or activated', array( 'p' ) );
						} elseif ( $add_on_active ) {
							echo wp_kses( '<p>Plugin is active!</p>', array( 'p' ) );
						}
						?>
					</div>
					<!-- Toggle -->
					<div class="bswp__addon__status">
						<!-- toggle slider -->
						<div 
							class="bswp__addon__status-indicator <?php echo esc_attr( $active_class ); ?>" 
							data-addon="<?php echo esc_attr( $add_on->slug ); ?>" 
							data-status="<?php echo esc_attr( $add_on->status ); ?>" 
							data-plugin="<?php echo esc_attr( $plugin_available_class ); ?>"
							data-nonce="<?php echo esc_html( $nonce ); ?>"></div>
					</div>
					<!-- /Toggle -->
				</div>

				<!-- Settings -->
				<?php if ( $add_on->has_settings ) : ?>
					<div class="bswp__addon__settings <?php echo esc_attr( $add_on->slug ) . '-settings'; ?>">
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
