<?php

/*
 * Plugin Name:       Turn Off Comments
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 */

add_action( 'pre_comment_on_post', '__return_false', PHP_INT_MAX );
add_filter( 'comment_excerpt', '__return_empty_string', PHP_INT_MAX, 2 );
add_filter( 'comments_array', '__return_empty_array', PHP_INT_MAX, 2 );
add_filter( 'comment_save_pre', '__return_empty_string', PHP_INT_MAX );
add_filter( 'comments_number', '__return_empty_string', PHP_INT_MAX, 2 );
add_filter( 'comments_open', '__return_false', PHP_INT_MAX, 2 );
add_filter( 'comment_text', '__return_empty_string', PHP_INT_MAX, 2 );
add_filter( 'comment_text_rss', '__return_empty_string', PHP_INT_MAX, 2 );
add_filter( 'get_comment_excerpt', '__return_empty_string', PHP_INT_MAX, 3 );
add_filter( 'get_comment_ID', '__return_empty_string', PHP_INT_MAX, 2 );
add_filter( 'get_comments_number', '__return_zero', PHP_INT_MAX, 2 );
add_filter( 'get_comment_text', '__return_empty_string', PHP_INT_MAX, 3 );
add_filter( 'pings_open', '__return_false', PHP_INT_MAX, 2 );
add_filter( 'pre_comment_content', '__return_empty_string', PHP_INT_MAX );
add_filter( 'preprocess_comment', '__return_empty_array', PHP_INT_MAX, 2 );
