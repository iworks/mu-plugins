<?php
/*
Plugin Name: iWorks prevent some options to change
Plugin URI: https://github.com/iworks/mu-plugins
Description: Prevent some WordPress options to change.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

class iworks_prevent_options_to_change {
	public function __construct() {

		add_filter( 'option_users_can_register', '__return_false' );
		add_filter( 'option_default_role', array( $this, 'option_default_role' ), 10, 2 );
	}

	public function option_default_role( $value, $option ) {
		return 'subscriber';
	}
}

new iworks_prevent_options_to_change();
