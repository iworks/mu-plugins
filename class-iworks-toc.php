<?php
/*
Plugin Name: iWorks Table Of Contents
Plugin URI: https://github.com/iworks/mu-plugins
Description: Added TOC on singular post type.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


class iWorks_Table_Of_Contents {

	public function __construct() {
		add_filter( 'the_content', array( $this, 'filter_the_content_add_toc' ), PHP_INT_MAX );
	}

	public function filter_the_content_add_toc( $content ) {
		if ( ! is_main_query() ) {
			return;
		}
		if ( is_admin() ) {
			return $content;
		}
		if ( ! is_singular( 'post' ) ) {
			return $content;
		}
			$toc = '';
		if ( preg_match_all( '/<h2.*>(.+)<\/h2>/', $content, $matches ) ) {
			$toc .= '<aside class="toc">';
			$toc .= '<span>Spis treści</span>';
			$toc .= '<ul>';
			for ( $i = 0; $i < count( $matches[0] );$i++ ) {
				$toc    .= sprintf(
					'<li><a href="#toc-%d">%s</a></li>',
					$i,
					$matches[1][ $i ]
				);
				$re      = sprintf( '@%s@', $matches[0][ $i ] );
				$replace = sprintf( '<a name="toc-%d"></a>$0', $i );
				$content = preg_replace( $re, $replace, $content );
			}
			$toc .= '</ol>';
			$toc .= '</aside>';
		}
		return $toc . $content;
	}
}

new iWorks_Table_Of_Contents();
