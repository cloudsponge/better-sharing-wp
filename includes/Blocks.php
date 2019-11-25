<?php

namespace BetterSharingWP;


class Blocks {

	public function __construct() {

		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );

	}

	public function enqueue_editor_assets() {
		wp_enqueue_script(
			'bswp-block',
			BETTER_SHARING_URI . 'dist/blocks/blocks.bundle.js',
			[ 'wp-blocks', 'wp-element', 'wp-components', 'wp-i18n' ],
			filemtime( BETTER_SHARING_PATH . 'dist/blocks/blocks.bundle.js' )
		);
	}

}