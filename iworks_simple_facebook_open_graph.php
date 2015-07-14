<?php

/*
Plugin Name: iWorks Simple facebook Open Graph
Plugin URI: http://iworks.pl/
Description: Add featured image as facebook image 
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
*/

if ( !class_exists( 'iWorks_Simple_Facebook_Open_Graph' ) ) {
    class iWorks_Simple_Facebook_Open_Graph
    {
        private static $meta = 'iworks_yt_thumbnails';

        function __construct()
        {
            add_action( 'wp_head', array( &$this, 'wp_head' ), 9 );
            add_action( 'save_post', array( &$this, 'add_youtube_thumbnails' ), 10, 2 );
        }

        public function add_youtube_thumbnails( $post_ID, $post )
        {
            if ( preg_match( '/^(revision|nav_menu_item)$/', $post->post_type )) {
                return;
            }
            delete_post_meta($post_ID, self::$meta);
            if ( isset( $_POST['post_content']) && 'publish' == $post->post_status ) {
                $iworks_yt_thumbnails = array();
                if ( preg_match_all( '#https?://youtu.be/([0-9a-z\-]+)#i', $_POST['post_content'], $matches ) ) {
                    foreach( $matches[1] as $youtube_id ) {
                        $iworks_yt_thumbnails[] = sprintf( 'http://img.youtube.com/vi/%s/maxresdefault.jpg', $youtube_id );
                    }
                }
                if ( preg_match_all( '#https?://(www\.)?youtube\.com/watch\?v=([0-9a-z\-]+)#i', $_POST['post_content'], $matches ) ) {
                    foreach( $matches[2] as $youtube_id ) {
                        $iworks_yt_thumbnails[] = sprintf( 'http://img.youtube.com/vi/%s/maxresdefault.jpg', $youtube_id );
                    }
                }
                if ( count( $iworks_yt_thumbnails ) ) {
                    update_post_meta( $post_ID, self::$meta, array_unique($iworks_yt_thumbnails) );
                }
            }
        }

        public function wp_head()
        {
            // plugin: Facebook Page Publish
            remove_action( 'wp_head', 'fpp_head_action' );
            /**
             * produce
             */
            $description = '';
            $type        = 'website';
            $image       = false;
            echo PHP_EOL;
            if ( is_single() ) {
                global $post;
                $iworks_yt_thumbnails = get_post_meta( $post->ID, self::$meta, true );
                if ( is_array( $iworks_yt_thumbnails ) && count( $iworks_yt_thumbnails ) ) {
                    foreach( $iworks_yt_thumbnails as $image ) {
                        printf( '<meta property="og:image" content="%s"/>%s', $image, PHP_EOL );
                    }
                    $image = false;
                }
                /**
                 * attachment image page
                 */
                if ( is_attachment() && wp_attachment_is_image($post->ID)) {
                    printf( '<meta property="og:image" content="%s"/>%s', wp_get_attachment_url($post->ID), PHP_EOL );
                }

                /**
                 * get post thumbnail
                 */

                if ( function_exists( 'has_post_thumbnail' ) ) {
                    if( has_post_thumbnail( $post->ID ) ) {
                        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
                        $src = esc_attr( $thumbnail_src[0] );
                        printf( '<link rel="image_src" href="%s" />%s', $src, PHP_EOL );
                        printf( '<meta itemprop="image" content="%s" />%s', $src, PHP_EOL );
                        echo PHP_EOL;
                        $image = $src;
                    }
                }

                $title = esc_attr(get_the_title());
                $type  = 'article';
                $url   = get_permalink();
                if ( has_excerpt( $post->ID ) ) {
                    $description = strip_tags( get_the_excerpt() );
                } else {
                    $description = strip_tags( strip_shortcodes( $post->post_content ) );
                }
                /**
                 * add tags
                 */
                $tags = get_the_tags();
                if (is_array($tags) && count($tags) > 0) {
                    foreach ($tags as $tag) {
                        printf( '<meta property="article:tag" content="%s" />%s', esc_attr( $tag->name ), PHP_EOL );
                    }
                }
                printf( '<meta property="article:published_time" content="%s" />%s', get_the_date( 'c' ), PHP_EOL );
                printf( '<meta property="article:modified_time"  content="%s" />%s', get_the_modified_date( 'c' ), PHP_EOL );
            } else {
                $description = esc_attr( get_bloginfo( 'description' ) );
                $title       = esc_attr( get_bloginfo( 'title' ) );
                $url         = home_url();
            }
            if ( mb_strlen( $description ) > 300 ) {
                $description = mb_substr( $description, 0, 400 );
                $description = preg_replace( '/[\n\t\r]/', ' ', $description );
                $description = preg_replace( '/ {2,}/', ' ', $description );
                $description = preg_replace( '/ [^ ]+$/', '', $description );
                $description .= '...';
            }
            printf( '<meta property="og:description" content="%s" />%s', esc_attr($description), PHP_EOL);
            if ( $image ) {
                printf( '<meta property="og:image"       content="%s"/>%s', esc_attr($image), PHP_EOL );
            }
            printf( '<meta property="og:locale"      content="%s" />%s', esc_attr( strtolower(preg_replace( '/-/', '_', get_bloginfo( 'language' ) ) )), PHP_EOL );
            printf( '<meta property="og:title"       content="%s" />%s', esc_attr($title), PHP_EOL );
            printf( '<meta property="og:type"        content="%s" />%s', esc_attr($type), PHP_EOL );
            printf( '<meta property="og:url"         content="%s" />%s', esc_attr($url), PHP_EOL );
            echo PHP_EOL;
        }
    }
    new iWorks_Simple_Facebook_Open_Graph();
}
/**

    = ChangeLog =

    == 2015-07-14 ==

    * exclude "nav_menu_item" from yt thumbnail preparing
    * add check to post_content

*/

