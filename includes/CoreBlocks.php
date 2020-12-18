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
	 * Register Block
	 *
	 * @return void
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
		ob_start();
		$ajax          = false;
		$email_subject = $block_attributes['emailSubject'];
		$email_content = $block_attributes['emailMessage'];
		$content       = apply_filters( 'the_content', $content );
		echo wp_kses(
			$content,
			array(
				'p'   => array(),
				'div' => array( 'class' => array() ),
				'a'   => array( 'href' => array() ),
				'button' => array(),
			)
		);

		include BETTER_SHARING_PATH . 'includes/templates/bswp-form.php';
		return ob_get_clean();
	}
}
