<?php
/*
Plugin Name: Tags for Pages
Plugin URI: http://iworks.pl/wtyczki/mu
Description: Simply adds the stock Tags to your Pages.
Version: 1.0.0
Author: iworks
Author URI: http://iworks.pl/
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

class iWorks_Tags_To_Pages {

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );

	}

	public function init() {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}

}

new iWorks_Tags_To_Pages;

