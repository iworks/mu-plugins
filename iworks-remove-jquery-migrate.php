<?php
/*
Plugin Name: Remove jquery-migrate.js
Plugin URI: https://github.com/iworks/mu-plugins
Description: Remove script jquery-migrate.js on frontend.
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
*/

add_filter( 'script_loader_src', 'iworks_script_loader_src', 10, 2 );

function iworks_script_loader_src( $src, $handle ) {
	if ( is_admin() ) {
		return $src;
	}
	if ( 'jquery-migrate' == $handle ) {
		return;
	}
	return $src;
}
