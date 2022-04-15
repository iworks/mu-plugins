<?php
/*
Plugin Name: Allow load SVG
Plugin URI: http://iworks.pl/
Description: Add svg files to allowed.
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
 */


add_filter( 'upload_mimes', 'iworks_custom_upload_mimes_allow_svg', 10, 2 );

function iworks_custom_upload_mimes_allow_svg( $t, $user ) {
	if ( ! isset( $t['svg'] ) ) {
		$t['svg'] = 'image/svg';
	}
	return $t;
}

