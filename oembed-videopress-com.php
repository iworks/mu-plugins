<?php
/*
Plugin Name: oembed videopress
Description: Add videopress url to omebeds.
Author: Marcin Pietrzak
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
class iworks_oembed_videopress {

	private $add_script = true;

	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {
		wp_embed_register_handler(
			'videopress',
			'/https?:\/\/videopress.com\/embed\/(.*)/',
			array( $this, 'handle' )
		);
	}

	public function handle( $matches, $attr, $url, $rawattr ) {
		if ( $this->add_script ) {
			echo '<script src="https://videopress.com/videopress-iframe.js"></script>';
			$this->add_script = false;
		}
		return sprintf(
			'<iframe width="%d" height="%d" src="%s" frameborder="0" allowfullscreen></iframe>',
			$attr['width'],
			intval( $attr['width'] * 315 / 560 ),
			esc_url( $url )
		);
	}
}
new iworks_oembed_videopress();
