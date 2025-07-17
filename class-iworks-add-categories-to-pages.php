<?php
/*
Plugin Name: Categories for Pages
Plugin URI: https://github.com/iworks/mu-plugins
Description: Simply adds the stock Categories to your Pages.
Version: 1.0.0
Author: iworks
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

class iWorks_Categories_To_Pages {

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		register_taxonomy_for_object_type( 'category', 'page' );
	}
}

new iWorks_Categories_To_Pages();
