<?php
/*
Plugin Name: Auto download and set thumbnail for youtube content
Plugin URI: http://iworks.pl/
Description: Plugin force to use movie post format for posts with YT movie and try to download YT thumbnailn.
Version: 1.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Copyright 2017 Marcin Pietrzak (marcin@iworks.pl)

this program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


if ( ! class_exists( 'iworks_auto_download_yt_thumbnail' ) ) {
	class iworks_auto_download_yt_thumbnail {
		private $meta_key_name = 'yt_thumbnail_url';
		public function __construct() {
			add_action( 'save_post', array( $this, 'save_post' ), PHP_INT_MAX, 3 );
		}
		public function save_post( $post_id, $post, $update ) {
			$post_type = get_post_type( $post_id );
			$support   = post_type_supports( $post_type, 'thumbnail' );
			if ( ! $support ) {
				return;
			}
			/**
			 * have thumbnail, do nothing!
			 */
			if ( has_post_thumbnail( $post ) ) {
				return;
			}
			/**
			 * parse short youtube share url
			 */
			$movie_id = false;
			if ( preg_match( '#https?://youtu.be/([0-9a-z\-]+)#i', $post->post_content, $matches ) ) {
				$movie_id = $matches[1];
			} elseif ( preg_match( '#https?://((m|www)\.)?youtube\.com/watch(\?v=|/)([0-9a-z\-]+)#i', $post->post_content, $matches ) ) {
				$movie_id = $matches[4];
			}
			if ( $movie_id ) {
				$url = sprintf( 'http://i%d.ytimg.com/vi/%s/maxresdefault.jpg', rand( 1, 4 ), $movie_id );
				set_post_format( $post, 'video' );
				$thumbnail_id = $this->save_external_file( $post, $url, $movie_id );
				if ( ! empty( $thumbnail_id ) ) {
					set_post_thumbnail( $post, $thumbnail_id );
				}
			}
		}
		private function get_existing_attachment_id( $url ) {
			$args      = array(
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'meta_key'       => $this->meta_key_name,
				'meta_value'     => $url,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				return $this->posts[0];
			}
			return false;
		}
		/**
		 * Check Headers
		 */
		public function check_headers( $link ) {
			$curl = curl_init();
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_HEADER         => true,
					CURLOPT_NOBODY         => true,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
					CURLOPT_URL            => $link,
				)
			);
			$file_headers = explode( "\n", curl_exec( $curl ) );
			$size         = curl_getinfo( $curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD );
			$mime         = curl_getinfo( $curl, CURLINFO_CONTENT_TYPE );
			curl_close( $curl );
			$file_headers['size'] = absint( $size );
			$file_headers['mime'] = trim( $mime );
			return $file_headers;
		}
		/**
		 * Check valid link
		 */
		public function checkValidLink( $link ) {
			$file_headers = $this->check_headers( $link );
			$headerStatus = trim( preg_replace( '/\s\s+/', ' ', $file_headers[0] ) );
			$allow_files  = array( 'HTTP/1.1 200 OK', 'HTTP/1.0 200 OK' );
			if ( in_array( $headerStatus, $allow_files ) && ! empty( $file_headers ) && $file_headers['size'] > 0 ) {
				return $file_headers;
			}
			return false;
		}
		/**
		 * Download external files and upload to our server.
		 */
		public function save_external_file( $post, $url, $name ) {
			if ( isset( $check ) and ( $check === true ) ) {
				$existing = $this->get_existing_attachment_id( $url );
			}
			if ( isset( $existing ) and is_numeric( $existing ) ) {
				return $existing;
			}
			$headers = $this->checkValidLink( $url );
			if ( $headers === false ) {
				return false;
			}
			//make sure the function exists
			if ( ! function_exists( 'media_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin' . '/includes/image.php' );
				require_once( ABSPATH . 'wp-admin' . '/includes/file.php' );
				require_once( ABSPATH . 'wp-admin' . '/includes/media.php' );
			}
			$tmp = download_url( $url );
			if ( is_wp_error( $tmp ) ) {
				return false;
			}
			$file_array = array(
				'tmp_name' => $tmp,
				'name'     => $name . '.jpg',
			);
			// do the validation and storage stuff
			$thumbnail_id = media_handle_sideload( $file_array, $post->ID, $post->post_title );
			if ( is_wp_error( $thumbnail_id ) ) {
				return false;
			}
			// create the thumbnails
			$attach_data = wp_generate_attachment_metadata( $thumbnail_id, get_attached_file( $thumbnail_id ) );
			wp_update_attachment_metadata( $thumbnail_id, $attach_data );
			//save the original url as post meta
			add_post_meta( $thumbnail_id, $this->meta_key_name, $url, true );
			return $thumbnail_id;
		}
	}
}

new iworks_auto_download_yt_thumbnail();

