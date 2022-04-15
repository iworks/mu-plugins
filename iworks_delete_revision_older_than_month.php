<?php
/*
Plugin Name: Delete older revisions
Plugin URI: http://iworks.pl/
Description: Delete older than month revisions
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
*/

if ( ! function_exists( 'iworks_delete_older_revisions' ) ) {
	/**
	 * tylko przy 15s w poniedziałek
	 */
	if ( '15' == date( 's' ) && '1' == date( 'W' ) ) {
		add_action( 'admin_init', 'iworks_delete_older_revisions' );
	}

	function iworks_delete_older_revisions() {
		global $wpdb;
		$query = 'delete from ' . $wpdb->posts . ' where post_type = \'revision\' and post_date < now() - interval 1 month';
		$wpdb->query( $query );
		$query = 'delete a from ' . $wpdb->postmeta . ' a left join ' . $wpdb->posts . ' b on b.ID = a.post_id where b.ID is null';
		$wpdb->query( $query );
	}
}

