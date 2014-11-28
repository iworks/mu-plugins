<?php
/*
Plugin Name: Show post publication time
Plugin URI: http://iworks.pl/
Description: Show post time for futured publication.
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
*/

add_filter( 'post_date_column_time', 'iworks_show_post_time' );

function iworks_show_post_time( $time, $post='', $column_name='', $mode='' )
{
    if ( get_post_time( 'G', true, $post) > time() ) {
        return get_the_time(__('Y/m/d g:i:s A'));
    }
    return $time;
}

