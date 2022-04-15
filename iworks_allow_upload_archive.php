<?php
/*
Plugin Name: Allow load archive files, csv too
Plugin URI: http://iworks.pl/
Description: Add zip and gzip files to allowed.
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
 */


add_filter( 'upload_mimes', 'iworks_custom_upload_mimes' );

function iworks_custom_upload_mimes( $existing_mimes = array() ) {
	$existing_mimes['gzip'] = 'application/x-gzip';
	$existing_mimes['gz']   = 'application/x-gzip';
	$existing_mimes['zip']  = 'application/zip';
	$existing_mimes['csv']  = 'text/csv';
	return $existing_mimes;
}

