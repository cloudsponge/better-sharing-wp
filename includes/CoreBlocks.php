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
	 * Message to share with social share links.
	 *
	 * @var string $message Message.
	 */
	public $message;

	/**
	 * Default block attributes for shortcode output
	 *
	 * @var array  $block_attributes block attributes.
	 */
	public $block_attributes;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->message = 'Check out this link!';

		$this->block_attributes = array(
			'emailSubject'        => 'Sharing',
			'emailMessage'        => 'What a great way to save! Click {{link}}',
			'socialNetworks'      => array(
				'twitter'  => array(
					'visible'   => true,
					'name'      => 'Twitter',
					'icon'      => 'twitter',
					'message'   => $this->message,
					'intentUrl' => 'https://www.twitter.com/intent/tweet?url={{permalink}}&text=' . $this->message,
				),
				'facebook' => array(
					'visible'   => true,
					'name'      => 'Facebook',
					'icon'      => 'facebook',
					'message'   => 'No custom message available',
					'intentUrl' => 'https://www.facebook.com/sharer/sharer.php?&u={{permalink}}',
				),
			),
			'emailFormControl'    => 'readonly',
			'referralLinkControl' => 'default',
		);

		add_action( 'init', array( $this, 'add_better_sharing_shortcode' ) );
	}

	/**
	 * Register shortcode.
	 *
	 * @return void
	 */
	public function add_better_sharing_shortcode() {
		add_shortcode( 'better-sharing', array( $this, 'render_better_sharing_output' ) );
	}

	/**
	 * Public Scripts and Styles
	 *
	 * @return void
	 */
	public function core_block_public_scripts() {
		global $post;
		if ( has_block( 'cgb/block-ea-better-sharing' ) || has_shortcode( $post->post_content, 'better-sharing' ) ) {
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
				'render_callback' => array( $this, 'render_better_sharing_output' ),
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
	 * Create intent url for shortcode.
	 *
	 * @param string $intent_url Social sharing intent URL w/o permalink.
	 *
	 * @return string
	 */
	public function create_intent_url( $intent_url ) {
		return str_replace( '{{permalink}}', get_permalink(), $intent_url );
	}

	/**
	 * Render Block
	 *
	 * @param array  $block_attributes block attributes.
	 * @param string $content post content.
	 *
	 * @return string
	 */
	public function render_better_sharing_output( $block_attributes, $content ) {
		// use default attributes for shortcode.
		if ( '' === $block_attributes ) {
			$block_attributes = $this->block_attributes;
		}

		$social_networks = $block_attributes['socialNetworks'];
		$referral_link   = array_key_exists( 'referralLink', $block_attributes ) ? esc_url( $block_attributes['referralLink'] ) : false;
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

				echo '<a key="' . esc_attr( $key ) . '" href="' . esc_url( $this->create_intent_url( $intent_url ) ) . '" target="_blank" ref="noopener noreferrer">';
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
			<input type='text' id='referral-link' value='<?php echo esc_attr( $referral_link ? $referral_link : get_permalink() ); ?>' readOnly>
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
