<?php
/*
Plugin Name: iWorks default link file
Plugin URI: http://iworks.pl/
Description: Setup global image_default_link_type to file.
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
*/

add_action( 'admin_init', 'iworks_image_default_link_type_checker' );
function iworks_image_default_link_type_checker()
{
    if ( get_option( 'image_default_link_type', '' ) != 'file' ) {
        update_option( 'image_default_link_type', 'file' );
    }
}
