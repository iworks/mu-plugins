<?php
/*
Plugin Name: iWorks add more allowed html tags
Plugin URI: https://github.com/iworks/mu-plugins
Description: Add some html tags to $global $allowedposttags
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
*/


if ( ! function_exists( 'iworks_add_allowed_tags' ) ) {
	add_action( 'init', 'iworks_add_allowed_tags' );

	function iworks_add_allowed_tags() {
		global $allowedposttags;
		$allowedposttags['footnote']       = array();
		$allowedposttags['small']          = array();
		$allowedposttags['pre']['lang']    = array();
		$allowedposttags['pre']['escaped'] = array();
	}
}
