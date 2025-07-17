<?php
/*
Plugin Name: Boost robots.txt
Plugin URI: https://github.com/iworks/mu-plugins
Description: Add some disallows to file <a href="/robots.txt">robots.txt</a>
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
 */

add_filter( 'robots_txt', 'iworks_filter_robots_txt' );
if ( ! function_exists( 'iworks_filter_robots_txt' ) ) {
	function iworks_filter_robots_txt( $robots ) {
		$entries = array(
			'/.htaccess',
			'/license.txt',
			'/readme.html',
			'*/trackback/',
			'/wp-admin/',
			'/wp-content/languages/',
			'/wp-content/mu-plugins/',
			'/wp-content/plugins/',
			'/wp-content/themes/',
			'/wp-includes/',
			'/wp-*.php',
			'/xmlrpc.php',
			'/yoast-ga/outbound-article/',
			'/20*/feed',
			'*preview=true*',
			'*cf_action=*',
			'*attachment_id=*',
			'*replytocom=*',
			'*doing_wp_cron*',
			'/irclog.php',
		);
		foreach ( array( 'tag', 'category' ) as $taxonomy_name ) {
			$url_base = get_option( $taxonomy_name . '_base', '' );
			if ( ! $url_base ) {
				$url_base = $taxonomy_name;
			}
			$entries[] = sprintf( '/%s/*/feed', $url_base );
		}
		$robots .= "\n";
		foreach ( $entries as $one ) {
			$robots .= sprintf( 'Disallow: %s%s', $one, "\n" );
		}
		return $robots;
	}
}
