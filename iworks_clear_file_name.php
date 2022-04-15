<?php
/*
Plugin Name: Sanitize Attachment File Name
Plugin URI: http://iworks.pl/
Description: Sanitize attachment file name, removing non latin1 letters
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
*/

add_action( 'sanitize_file_name', 'iworks_sanitize_file_name' );
if ( ! function_exists( 'iworks_sanitize_file_name' ) ) {
	function iworks_sanitize_file_name( $filename ) {
		$de_from  = array( 'ä', 'ö', 'ü', 'ß', 'Ä', 'Ö', 'Ü' );
		$de_to    = array( 'ae', 'oe', 'ue', 'ss', 'Ae', 'Oe', 'Ue' );
		$filename = str_replace( $de_from, $de_to, $filename );
		$pl_from  = array( 'ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż' );
		$pl_to    = array( 'a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z' );
		$filename = str_replace( $pl_from, $pl_to, $filename );
		$filename = preg_replace( '/[^A-Za-z0-9\._]/', '-', $filename );
		$filename = preg_replace( '/[_ ]+/', '-', $filename );
		$filename = preg_replace( '/%20/', '-', $filename );
		return $filename;
	}
}

