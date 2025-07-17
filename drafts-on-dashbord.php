<?php
/*
Plugin Name: drafts-on-dashbord
Plugin URI: https://github.com/iworks/mu-plugins
Description: Drafts on dashbord functionality.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


class iWorks_Drafts_On_Dashboard {

	private $domain = 'iworks_drafts';

	public function __construct() {
		add_action( 'wp_dashboard_setup', array( &$this, 'wp_dashboard_setup' ) );
	}

	public static function start() {
		new iWorks_Drafts_On_Dashboard();
	}

	public function wp_dashboard_setup() {

		wp_add_dashboard_widget(
			__CLASS__,
			__( 'Drafts', $this->domain ),
			array( &$this, 'content' )
		);
	}

	public function content() {
		$args      = array(
			'post_status' => 'draft',
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			echo '<ul>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				edit_post_link( get_the_title(), '<li>', '</li>' );
			}
			echo '</ul>';
		} else {
			_e( 'No drafts.', $this->domain );
		}
		wp_reset_postdata();
	}
}

iWorks_Drafts_On_Dashboard::start();
