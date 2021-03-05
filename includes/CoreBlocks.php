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

		$this->shortcode_attributes = array(
			'emailSubject'        => 'Sharing',
			'emailMessage'        => 'What a great way to save! Click {{link}}',
			'twitter'             => 'true',
			'facebook'            => 'true',
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
				'render_callback' => array( $this, 'better_sharing_output' ),
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
	 * Register shortcode.
	 *
	 * @return void
	 */
	public function add_better_sharing_shortcode() {
		add_shortcode( 'better-sharing', array( $this, 'better_sharing_output' ) );
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
	 * Render output.
	 *
	 * @param array  $atts block attributes.
	 * @param string $content post content.
	 * @param string $tag shortcode tag.
	 *
	 * @return string
	 */
	public function better_sharing_output( $atts, $content = null, $tag = null ) {
		// normalize attributes between gute block and shortcode.
		$block_attributes = array_change_key_case( $atts, CASE_LOWER );

		// add user attributes / default attributes if shortcode is used.
		if ( 'better-sharing' === $tag ) {
			$block_attributes = shortcode_atts(
				array_change_key_case( $this->shortcode_attributes, CASE_LOWER ),
				$block_attributes
			);

			// shortcode options to remove social network sharing.
			if ( 'false' === $block_attributes['twitter'] ) {
				unset( $block_attributes['socialnetworks']['twitter'] );
			}

			if ( 'false' === $block_attributes['facebook'] ) {
				unset( $block_attributes['socialnetworks']['facebook'] );
			}
		}

		$social_networks = $block_attributes['socialnetworks'];
		$referral_link   = array_key_exists( 'referrallink', $block_attributes ) ? esc_url( $block_attributes['referrallink'] ) : get_permalink();
		$email_control   = sanitize_text_field( $block_attributes['emailformcontrol'] );

		// replace '{{link}}' with actual link.
		$email_content = sanitize_text_field( $block_attributes['emailmessage'] );
		$email_content = str_replace( '{{link}}', $referral_link, $email_content );

		// used in bswp-form.
		$addon                = 'core';
		$ajax                 = false;
		$link_control         = sanitize_text_field( $block_attributes['referrallinkcontrol'] );
		$email_subject        = sanitize_text_field( $block_attributes['emailsubject'] );
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
