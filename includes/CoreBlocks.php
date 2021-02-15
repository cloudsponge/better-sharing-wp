<?php
/**
 * Core Blocks
 *
 * @package CoreBlocks
 */

namespace BetterSharingWP;

/**
 * CoreBlocks
 */
class CoreBlocks {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Public Scripts and Styles
	 *
	 * @return void
	 */
	public function core_block_public_scripts() {
		if ( has_block( 'cgb/block-ea-better-sharing' ) ) {
			wp_enqueue_script(
				'better-sharing-blocks-public',
				BETTER_SHARING_URI . 'dist/blocks/public.bundle.js',
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
				BETTER_SHARING_VERSION,
				false
			);
		}
	}

	/**
	 * Register Block
	 *
	 * @return mixed
	 */
	public function register_block() {
		wp_enqueue_script(
			'better-sharing-blocks',
			BETTER_SHARING_URI . 'dist/blocks/blocks.bundle.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
			BETTER_SHARING_VERSION,
			false
		);

		register_block_type(
			'cgb/block-ea-better-sharing',
			array(
				'editor_script'   => 'better-sharing-blocks',
				'render_callback' => array( $this, 'render_block' ),
				// default attributes if not contained within $block_attributes.
				'attributes'      => array(
					'emailFormControl'    => array(
						'type'    => 'string',
						'default' => 'readonly',
					),
					'referralLinkControl' => array(
						'type'    => 'string',
						'default' => 'default',
					),
				),
			)
		);
	}

	/**
	 * Render Block
	 *
	 * @param array  $block_attributes block attributes.
	 * @param string $content post content.
	 *
	 * @return string
	 */
	public function render_block( $block_attributes, $content ) {
		$social_networks = $block_attributes['socialNetworks'];
		$referral_link   = esc_url_raw( $block_attributes['referralLink'] );
		$email_control   = sanitize_text_field( $block_attributes['emailFormControl'] );

			// replace '{{link}}' with actual link.
		$email_content = sanitize_text_field( $block_attributes['emailMessage'] );
		$email_content = str_replace( '{{link}}', $referral_link, $email_content );

		// used in bswp-form.
		$addon                = 'core';
		$ajax                 = false;
		$link_control         = sanitize_text_field( $block_attributes['referralLinkControl'] );
		$email_subject        = sanitize_text_field( $block_attributes['emailSubject'] );
		$preview_email_toggle = false;

		// whether or not email form is rendered.
		if ( 'default' === $email_control || 'readonly' === $email_control ) {
			$preview_email_toggle = true;
		}

		ob_start();
		echo '<div class="wp-block-cgb-block-ea-better-sharing">';

		// social network sharing buttons.
		echo '<div class="social-links">';
		foreach ( $social_networks as $network ) {
			if ( true === $network['visible'] ) {
				$key        = strtolower( $network['name'] );
				$name       = $network['name'];
				$icon       = $network['icon'];
				$intent_url = $network['intentUrl'];

				echo '<a key="' . esc_attr( $key ) . '" href="' . esc_url_raw( $intent_url ) . '" target="_blank" ref="noopener noreferrer">';
				echo '<button class="components-button is-secondary is-small has-text has-icon">';
				echo '<span class="dashicons dashicons-' . esc_attr( $icon ) . '"></span>&nbsp;';
				echo esc_html( $name );
				echo '</button>';
				echo '</a>';
			}
		}
		echo '</div>'; // <!-- end .social-links -->

		echo '<hr>';

		// referral link and copy button.
		?>
		<div class='referral-link'>
			<label for='referral-link'>Your referral link</label>
			<input type='text' id='referral-link' value='<?php echo esc_attr( $referral_link ); ?>' readOnly>
			<button class="components-button is-secondary is-small has-text has-icon" icon='admin-page' id='referral-btn-copy'>
				Copy Link
			</button>
			<p id="copy-success">Copied!</p>
		</div> <!-- end .referral-link -->

		<hr>
		<?php

		include BETTER_SHARING_PATH . 'includes/templates/bswp-form.php';

		echo '</div>'; // <!-- end .wp-block-cgb-block-ea-better-sharing -->

		return ob_get_clean();
	}
}
