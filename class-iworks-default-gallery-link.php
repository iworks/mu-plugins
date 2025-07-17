<?php
/*
Plugin Name: iWorks Default Gallery Link
Plugin URI: https://github.com/iworks/mu-plugins
Description: Set "file" as default gallery link.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

class iWorks_Default_Gallery_Link {

	public function __construct() {
		add_filter( 'the_content', array( $this, 'the_content' ), 1 );
	}

	public function the_content( $content ) {
		return preg_replace( '/(\[gallery[^\]]*)\]/', '$1 link="file"]', $content );
	}
}
new iWorks_Default_Gallery_Link();
