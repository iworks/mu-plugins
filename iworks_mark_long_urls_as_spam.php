<?php

function iworks_long_url_spamcheck( $approved, $commentdata ) {
	return ( strlen( $commentdata['comment_author_url'] ) > 50 ) ? 'spam' : $approved;
}

add_filter( 'pre_comment_approved', 'iworks_long_url_spamcheck', 99, 2 );

