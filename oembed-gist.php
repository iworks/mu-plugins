<?php
/*
Plugin Name: oembed gist
Description: Add gist url to omebeds.
Author: Marcin Pietrzak
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
class iworks_oembed_gist {

	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {
		wp_embed_register_handler(
			'gist',
			'/https?:\/\/gist.github.com\/(.*)/',
			array( $this, 'handle' )
		);
	}

	public function handle( $matches, $attr, $url, $rawattr ) {
		return sprintf(
			'<script src="https://gist.github.com/%s.js"></script>',
			esc_attr( $matches[1] )
		);
	}
}
new iworks_oembed_gist();
