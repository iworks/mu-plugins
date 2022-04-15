<?php
/*
Plugin Name: Block all robots robots.txt
Plugin URI: http://iworks.pl/
Description: Try to block all requests in <a href="/robots.txt">robots.txt</a>
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
 */

add_filter( 'robots_txt', 'iworks_filter_block_robots_txt' );
if ( ! function_exists( 'iworks_filter_block_robots_txt' ) ) {
	function iworks_filter_block_robots_txt( $robots ) {
		$robots  = '';
		$entries = array(
			'*',
			/**
			 * Google
			 */
			'AdsBot-Google',
			'AdsBot-Google-Mobile',
			'AdsBot-Google-Mobile-Apps',
			'APIs-Google',
			'DuplexWeb-Google',
			'FeedFetcher-Google',
			'Google Favicon',
			'Googlebot',
			'Googlebot-Image',
			'Googlebot-News',
			'Googlebot-Video',
			'Google-Read-Aloud',
			'Mediapartners-Google',
			/**
			 * Bing
			 */
			'Bingbot',
			/**
			 * Yandex
			 */
			'Yandex',

		);
		foreach ( $entries as $one ) {
			$robots .= sprintf( 'User-agent: %s%s', $one, PHP_EOL );
		}
		$robots .= 'Disallow: /';
		return $robots;
	}
}

