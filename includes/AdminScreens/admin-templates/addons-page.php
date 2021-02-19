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

add_thickbox();

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
			// AddOn is active status - label
			$active_label = ( $add_on_active ) ? 'Better Sharing is enabled' : 'Better Sharing is disabled';
			?>
			<div class="card bswp__addon <?php echo esc_attr( $plugin_available_class ); ?>">

				<div class="bswp__addon__header">
					<h2 class="title bswp__addon__title"><?php echo esc_html( $add_on->name ); ?></h2>
				</div>
				
				<?php if ( $add_on->description ) : ?>
				<!-- Description -->
					<div class="bswp__addon__description">
						<?php echo wp_kses( wpautop( $add_on->description ), array( 'p' ) ); ?>

						
						<?php if ( $add_on->support_url ) : ?>
						<!-- Doc / Support URL -->
							<a href="<?php echo esc_url( $add_on->support_url ); ?>" target="_blank" rel="noopener noreferrer">
								Learn More
							</a>
						<!-- / Doc / Support URL -->
						<?php endif; ?>
						
					</div>
				<!-- /Description -->
				<?php endif; ?>
				
				
				<div class="bswp__addon__toggle">
					
					<?php if ( ! $add_on->is_plugin_active() ) : ?>
						
						<div class="disclaimer">
							<?php echo wp_kses( '<p>Plugin is not installed or activated.', array( 'p' ) ); ?>
						</div>
					
					<?php else : ?>
					

						<?php if ( $add_on->is_plugin_active() ) : ?>
							<!-- Toggle -->
							<div class="bswp__addon__status">
								<!-- toggle slider -->
								<div 
									class="bswp__addon__status-indicator <?php echo esc_attr( $active_class ); ?>" 
									data-addon="<?php echo esc_attr( $add_on->slug ); ?>" 
									data-status="<?php echo esc_attr( $add_on->status ); ?>" 
									data-plugin="<?php echo esc_attr( $plugin_available_class ); ?>"
									data-nonce="<?php echo esc_html( $nonce ); ?>"></div>

								<span class="bswp__addon__status-label"><?php echo esc_html( $active_label ); ?></span>
							</div>
							<!-- /Toggle -->
						<?php endif; ?>

						
						<!-- Settings -->
						<div class="bswp__addon__config">
							<?php if ( $add_on->has_settings && $add_on_active ) : ?>

								<a class="button button-primary thickbox" href="#TB_inline?width=600&height=550&inlineId=modal-<?php echo esc_attr( $add_on->slug ); ?>">Settings</a>

								<!-- Settings Modal -->
								<div id="modal-<?php echo esc_attr( $add_on->slug ); ?>" class="bswp__addon__settings">
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
							
						</div>
						<!-- / Settings -->

					<?php endif; ?>

				</div>

			</div>
		<?php endforeach; ?>

	</div>
</div>
