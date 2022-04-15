<?php
/*
Plugin Name: iWorks add shortcodes
Plugin URI: http://iworks.pl/
Description: add all existing shortcodes to editor
Version: 0.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class iWorks_add_all_shortcodes {

	public function __construct() {
		add_action( 'media_buttons', array( $this, 'media_buttons' ), 11 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
	}
	public static function init() {
		new iWorks_add_all_shortcodes();
	}

	public function media_buttons() {
		global $shortcode_tags;
		/* ------------------------------------- */
		/* enter names of shortcode to exclude bellow */
		/* ------------------------------------- */
		$exclude = array( 'wp_caption', 'embed' );
		echo '&nbsp;<select id="' . __CLASS__ . '"><option>Shortcode</option>';
		foreach ( $shortcode_tags as $key => $val ) {
			if ( ! in_array( $key, $exclude ) ) {
				$shortcodes_list .= '<option value="[' . $key . '][/' . $key . ']">' . $key . '</option>';
			}
		}
		echo $shortcodes_list;
		echo '</select>';
	}

	public function admin_head() {
		echo '<script type="text/javascript">';
		echo 'jQuery(document).ready(function(){';
		echo 'jQuery("#' . __CLASS__ . '").change(function() {';
		echo 'send_to_editor(jQuery("#' . __CLASS__ . ' :selected").val());';
		echo 'return false;';
		echo '});';
		echo '});';
		echo '</script>';
	}

}

iWorks_add_all_shortcodes::init();

