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
		wp_enqueue_script(
			'better-sharing-blocks-public',
			BETTER_SHARING_URI . 'dist/blocks/public.bundle.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ),
			BETTER_SHARING_VERSION,
			false
		);
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
		$addon            = 'core';
		$ajax             = false;
		$referral_link    = $block_attributes['referralLink'];
		$email_subject    = $block_attributes['emailSubject'];
		$email_content    = $block_attributes['emailMessage'];
		$emailFormControl = $block_attributes['emailFormControl'];

		$email_content = str_replace( '{{link}}', $referral_link, $email_content );

		$preview_email_toggle;
		if ( $emailFormControl === 'default' || $emailFormControl === 'readonly' ) {
			$preview_email_toggle = true;
		} else {
			$preview_email_toggle = false;
		}

		$content = apply_filters( 'the_content', $content );

		ob_start();
		echo '<div class="wp-block-cgb-block-ea-better-sharing">';
		echo wp_kses(
			$content,
			array(
				'p'      => array(
					'class' => array(),
					'id'    => array(),
				),
				'div'    => array(
					'class' => array(),
				),
				'a'      => array(
					'href'  => array(),
					'class' => array(),
				),
				'button' => array(
					'class' => array(),
					'id'    => array(),
				),
				'input'  => array(
					'id'       => array(),
					'value'    => array(),
					'readonly' => array(),
					'class'    => array(),
					'type'     => array(),
					'name'     => array(),
				),
				'span'   => array(
					'class' => array(),
				),
				'hr'     => array(
					'class' => array(),
				),
				'form'   => array(
					'action'         => array(),
					'accept-charset' => array(),
					'method'         => array(),
				),
				'label'  => array(
					'for'   => array(),
					'class' => array(),
				),
			)
		);

		include BETTER_SHARING_PATH . 'includes/templates/bswp-form.php';
		echo '</div>';

		return ob_get_clean();
	}
}
