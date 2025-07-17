<?php
/*
Plugin Name: Add meta robots
Plugin URI: https://github.com/iworks/mu-plugins
Description: Add meta robots: noindex for selected pages
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
 */

add_action( 'wp_head', 'iworks_action_wp_head' );
if ( ! function_exists( 'iworks_action_wp_head' ) ) {
	function iworks_action_wp_head() {
		$index = $follow = true;
		if (
			0
			or is_404()
			or is_archive()
			or is_author()
			or is_category()
			or is_comments_popup()
			or is_preview()
			or is_search()
			or is_tag()
			or is_tax()
			or is_paged()
		) {
			$index = false;
		}
		if (
			0
			or is_comments_popup()
			or is_preview()
		) {
			$follow = false;
		}
		printf(
			'<meta name="robots" content="%s, %s" />',
			$follow ? 'follow' : 'nofollow',
			$index ? 'index' : 'noindex'
		);
	}
}
