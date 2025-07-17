<?php
/*
Plugin Name: iWorks Random Post Slug
Plugin URI: https://github.com/iworks/mu-plugins
Description: Create random post slug.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


class iWorks_Random_Post_Slug {

	private $length    = 10;
	private $semaphore = '_iworks_random_slug';

	public function __construct() {
		add_action( 'publish_post', array( $this, 'action_publish_post_add_random_slug' ), 10, 3 );
	}

	public function action_publish_post_add_random_slug( $post_id, $post, $old_status ) {
		if ( get_post_meta( $post_id, $this->semaphore, true ) ) {
			return;
		}
		add_post_meta( $post_id, $this->semaphore, 'set', true );
		$postarr = array(
			'ID'        => $post_id,
			'post_name' => wp_generate_password( $this->length, false, false ),
		);
		wp_update_post( $postarr );
	}
}

new iWorks_Random_Post_Slug();
