<?php

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
			add_filter( $filter, array( $this, 'reletive' ) );
		}
	}

	/**
	 * Replace!
	 */
	public function reletive( $input ) {
		preg_match( '|https?://([^/]+)(/.*)|i', $input, $matches );
		if ( isset( $matches[1] ) && isset( $matches[2] ) && $matches[1] === $_SERVER['SERVER_NAME'] ) {
			return wp_make_link_relative( $input );
		}
		return $input;
	}

}

new iworks_relative();
