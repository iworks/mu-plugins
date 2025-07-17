<?php
/*
Plugin Name: contact-form-load-only-on-single
Plugin URI: https://github.com/iworks/mu-plugins
Description: Contact form load only on single functionality.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/



class iWorks_Remove_CF7_assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'remove' ), 0 );
	}

	public function remove() {
		$remove = true;
		if ( is_singular() ) {
			global $post;
			$remove = ! preg_match( '/contact-form-7/', $post->post_content );
		}
		if ( ! $remove ) {
			return;
		}
			remove_action( 'wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 20, 0 );
		remove_action( 'wp_enqueue_scripts', 'wpcf7_html5_fallback', 20, 0 );
		add_action( 'wpcf7_enqueue_scripts', array( $this, 'remove_cf' ) );
		add_action( 'wpcf7_enqueue_styles', array( $this, 'remove_cf' ) );
	}

	public function remove_cf() {
		wp_dequeue_script( 'contact-form-7' );
		wp_dequeue_style( 'contact-form-7' );
	}
}

new iWorks_Remove_CF7_assets();
