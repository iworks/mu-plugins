<?php
/*
Plugin Name: Preseve self-pingback
Plugin URI: https://github.com/iworks/mu-plugins
Description: Stop sending self pinbacks
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
*/

add_action( 'pre_ping', 'iworks_action_pre_ping_remove_selfpings' );

if ( ! function_exists( 'iworks_action_pre_ping_remove_selfpings' ) ) {
	function iworks_action_pre_ping_remove_selfpings( &$links ) {
		$home = home_url();
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, $home ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}
