<?php
/*
Plugin Name: Filtruj liczbę wyświetlanych zdjęć
Description: Wtyczka zmniejsza liczbę pobierancych załączników w postach z galeriami.
Version: 11.12.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl
*/


add_action( 'pre_get_posts', 'iworks_limit_gallery_pre_get_posts_action', 1, 1 );

function iworks_limit_gallery_pre_get_posts_action( $data )
{
    if (
        isset( $data->query['post_status'] )    && 'inherit' == $data->query['post_status'] &&
        isset( $data->query['post_mime_type'] ) && 'image'   == $data->query['post_mime_type'] &&
        $data->query['posts_per_page'] == -1
    ) {
//        $data->query_vars['posts_per_page'] = 20;
    }
}

