<?php
/*
Plugin Name: iWorks turn off comments for atttachments
Plugin URI: https://github.com/iworks/mu-plugins
Description: turn off ability to comment attachments
Version: 1.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

class iworks_turn_off_comments_for_atttachments {

	public function __construct() {
		add_action( 'comments_open', array( $this, 'comments_open' ), PHP_INT_MAX, 2 );
	}
	public static function init() {
		new iworks_turn_off_comments_for_atttachments();
	}

	public function comments_open( $open, $post_id ) {
		$post = get_post( $post_id );

		if (
			is_object( $post )
			&& isset( $post->post_type )
			&& 'attachment' == $post->post_type
		) {
			$open = false;
		}

		return $open;
	}
}

iworks_turn_off_comments_for_atttachments::init();
