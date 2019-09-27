<?php
/*
Plugin Name: Relative URLs
Plugin URI: http://iworks.pl/
Description: Replace assets links to relative, removing protocol and host name.
Version: 1.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Copyright 2019 Marcin Pietrzak (marcin@iworks.pl)

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

class iworks_relative {

	private $filters = array(
		'bloginfo_url', // Enlace a portada
		'the_permalink', // Enlaces a entradas
		'wp_list_pages', // Enlaces a páginas
		'wp_list_categories', // Enlaces a categorías
		'the_content_more_link', // Enlaces a "sigue leyendo"
		'the_tags', // Enlaces a etiquetas
		'get_pagenum_link', // Enlaces a entradas paginadas
		'get_comment_link', // Enlaces a comentarios
		'month_link', // Enlaces a archivo por meses
		'day_link', // Enlaces a archivo por días
		'year_link', // Enlaces a archivo por años
		'tag_link', // Enlaces a archivo de tags
		'the_author_posts_link', // Enlaces a archivos de autor
		'script_loader_src',
		'style_loader_src',
	);

	public function __construct() {
		foreach ( $this->filters as $filter ) {
			add_filter( $filter, array( $this, 'remove_hostname' ) );
		}
		add_action( 'wp_default_scripts', array( $this, 'change_urls' ) );
		add_action( 'wp_default_styles', array( $this, 'change_urls' ) );
	}

	/**
	 * Change ulr for load-scripts.php & load-styles.php
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Scripts/WP_Styles $config Config to change.
	 */
	public function change_urls( $config ) {
		$config->base_url    = '';
		$config->content_url = wp_make_link_relative( $config->content_url );
	}

	/**
	 * Remove host name
	 *
	 * @since 1.0.0
	 *
	 * @param string $input Input value to change
	 */
	public function remove_hostname( $input ) {
		preg_match( '|https?://([^/]+)(/.*)|i', $input, $matches );
		if ( isset( $matches[1] ) && isset( $matches[2] ) && $matches[1] === $_SERVER['SERVER_NAME'] ) {
			return wp_make_link_relative( $input );
		}
		return $input;
	}

}

new iworks_relative();

