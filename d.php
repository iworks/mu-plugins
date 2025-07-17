<?php
/*
Plugin Name: d
Plugin URI: https://github.com/iworks/mu-plugins
Description: D functionality.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/



add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );

if ( ! function_exists( 'l' ) ) {
	function l( $a, $b = '' ) {
		if ( false !== $b ) {
			error_log( sprintf( '--------------- %s ---------------', $b ) );
		}
		if ( is_bool( $a ) ) {
			$a = $a ? 'true' : 'false';
		}
		if ( empty( $a ) ) {
			$a = '[empty]';
		}
		if ( is_array( $a ) || is_object( $a ) ) {
			error_log( print_r( $a, true ) );
		} else {
			error_log( $a );
		}
		error_log( '------------------------------' );
	}
}



if ( ! function_exists( 'd' ) ) {
	function d( $a, $b = '' ) {
		$do_not_use_html_tags = defined( 'DOING_AJAX' ) || PHP_SAPI === 'cli';
		if ( is_bool( $a ) ) {
			$a = $a ? 'true' : 'false';
		} elseif ( ! is_object( $a ) && ! is_array( $a ) ) {
			$a = (array) $a;
		}
		if ( $b ) {
			if ( $do_not_use_html_tags ) {
				printf( "\n--------- %s ---------\n", $b );
			} else {
				printf( '<h3>%s</h3>', $b );
			}
		}
		if ( ! $do_not_use_html_tags ) {
			print '<pre style="font-family:monospace">';
		}
		print_r( $a );
		if ( $do_not_use_html_tags ) {
			echo PHP_EOL;
			echo '-----------------------------------';
			echo PHP_EOL;
			echo PHP_EOL;
		} else {
			print '</pre>';
		}
	}
}

if ( ! function_exists( 'write_log' ) ) {
	function write_log( $a ) {
		l( $a );
	}
}

add_action( 'admin_head', 'iworks_d_debug_script' );
add_action( 'wp_head', 'iworks_d_debug_script' );
function iworks_d_debug_script() {
	?>
	<script>
	function l( a ) {
		window.console.log( a );
}
</script>
	<?php
}
