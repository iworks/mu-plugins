<?php
/*
Plugin Name: Clean admin bar logo links
Plugin URI: http://iworks.pl/
Description: Remove links from admin bar an add link rto "przełam sieć"
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
*/

add_action( 'wp_before_admin_bar_render', 'iworks_admin_bar' );
if ( ! function_exists( 'iworks_admin_bar' ) ) {
	function iworks_admin_bar() {
		global $wp_admin_bar;
		//$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu( 'about' );
		$wp_admin_bar->remove_menu( 'wporg' );
		$wp_admin_bar->remove_menu( 'documentation' );
		$wp_admin_bar->remove_menu( 'support-forums' );
		$wp_admin_bar->remove_menu( 'feedback' );
		$wp_admin_bar->remove_menu( 'view-site' );
		$wp_admin_bar->remove_menu( 'iworks-pl' );
		$wp_admin_bar->add_menu(
			array(
				'id'     => 'iworks-pl',
				'title'  => 'Przełam Sieć',
				'parent' => 'wp-logo',
				'href'   => 'http://iworks.pl/',
			)
		);
	}
}
