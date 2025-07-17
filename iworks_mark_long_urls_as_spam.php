<?php
/*
Plugin Name: iworks_mark_long_urls_as_spam
Plugin URI: https://github.com/iworks/mu-plugins
Description: Iworks mark long urls as spam functionality.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/



function iworks_long_url_spamcheck( $approved, $commentdata ) {
	return ( strlen( $commentdata['comment_author_url'] ) > 50 ) ? 'spam' : $approved;
}

add_filter( 'pre_comment_approved', 'iworks_long_url_spamcheck', 99, 2 );
