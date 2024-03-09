<?php
/*
Plugin Name: iWorks Aggresive Lazy Load
Plugin URI: http://iworks.pl/szybki-wordpress-obrazki-leniwe-ladowanie
Description: Added ability to agresive lazy load to improve page UX and speed.
Version: 1.0.4
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

class iworks_aggresive_lazy_load {

	/**
	 * Post meta name for tiny thumbnail
	 *
	 * @since 1.0.0
	 */
	private $meta_name_tiny_thumbnail = '_iworks_tiny';

	/**
	 * Post meta name for dominant color
	 *
	 * @since 1.0.0
	 */
	private $meta_name_dominant_color = '_iworks_color';

	/**
	 * Debug
	 *
	 * value for debug 'debug'
	 *
	 * @since 1.0.0
	 */
	private $replace_status = false;

	public function __construct() {
		/**
		 * Turn off in admin.
		 *
		 * Props for Patryk Siuta
		 *
		 * @since 1.0.3
		 */
		if ( is_admin() ) {
			return;
		}
		/**
		 * settings
		 */
		add_action( 'init', array( $this, 'settings' ) );
		/**
		 * replace
		 */
		add_action( 'add_attachment', array( $this, 'add_dominant_color' ) );
		add_action( 'edit_attachment', array( $this, 'add_dominant_color' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'filter_post_thumbnail_html' ), PHP_INT_MAX, 5 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'filter_attachment_image_attributes' ), 10, 3 );
		/**
		 * own
		 */
		add_filter( 'iworks_aggresive_lazy_load_filter_value', array( $this, 'filter_content' ) );
		add_filter( 'iworks_aggresive_lazy_load_get_dominant_color', array( $this, 'get_dominant_color' ), 10, 2 );
		add_filter( 'iworks_aggresive_lazy_load_get_tiny_thumbnail', array( $this, 'get_tiny_thumbnail' ), 10, 2 );
		/**
		 * WooCommerce
		 *
		 * @since 1.0.4
		 */
		add_action( 'woocommerce_email_header', array( $this, 'remove_replacements' ) );
	}

	/**
	 * remove replacements hooks
	 *
	 * @since 1.0.4
	 */
	function remove_replacements() {
		remove_filter( 'post_thumbnail_html', array( $this, 'filter_post_thumbnail_html' ), PHP_INT_MAX, 5 );
		remove_filter( 'wp_get_attachment_image_attributes', array( $this, 'filter_attachment_image_attributes' ), 10, 3 );
	}

	/**
	 * Settings function, allow to set 'debug' for 'replace_status'.
	 *
	 * @since 1.0.1
	 */
	public function settings() {
		$this->replace_status = apply_filters( 'iworks_aggresive_lazy_load_replace_status', $this->replace_status );
	}

	/**
	 * Filter content
	 *
	 * Filter content to modify img tags with lazyload data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Post content.
	 *
	 * @return string
	 */
	public function filter_content( $content ) {
		/**
		 * turn off replacement
		 *
		 * @since 1.0.3
		 */
		if ( apply_filters( 'iworks_aggresive_lazy_load_filter_content', false ) ) {
			return $content;
		}
		preg_match_all( '/<img[^>]+>/', $content, $matches );
		if ( empty( $matches ) ) {
			return $content;
		}
		if ( empty( $matches[0] ) ) {
			return $content;
		}
		foreach ( $matches[0] as $image ) {
			if ( ! preg_match( '/wp-image-(\d+)/', $image, $m ) ) {
				continue;
			}
			$new = $this->filter_post_thumbnail_html( $image, 0, $m[1], 'full', '' );
			if ( $image === $new ) {
				continue;
			}
			$content = str_replace( $image, $new, $content );
		}
		return $content;
	}

	/**
	 * Filter attachemnt attributes.
	 *
	 * Filter attachemnt attributes to add lazy load element and replace src attribute.
	 *
	 * @since 1.0.0
	 */
	public function filter_attachment_image_attributes( $attr, $attachment, $size ) {
		$dominant_color = $this->get_dominant_color( null, $attachment->ID );
		if ( ! empty( $dominant_color ) ) {
			if ( isset( $attr['style'] ) ) {
				$attr['style'] .= ';';
			} else {
				$attr['style'] = '';
			}
			if (
				is_a( $attachment, 'WP_Post' )
				&& ! preg_match( '/image\/svg/', $attachment->post_mime_type )
				&& 'transparent' !== $dominant_color
			) {
				$attr['style'] .= sprintf(
					'background-color:#%s;',
					$dominant_color
				);
			}
		}
		$tiny = $this->get_tiny_thumbnail( null, $attachment->ID );
		if ( ! empty( $tiny ) ) {
			$attr['data-src'] = $attr['src'];
			$attr['src']      = $tiny;
		}
		if ( 'debug' === $this->replace_status && isset( $attr['srcset'] ) ) {
			unset( $attr['srcset'] );
		}
		return $attr;
	}

	/**
	 * Get tiny thumbnail.
	 *
	 * Get tiny 6x0 pixels thumbnail to show it from html as encoded image.
	 *
	 * @since 1.0.0
	 */
	public function get_tiny_thumbnail( $thumb, $post_thumbnail_id ) {
		if ( empty( $post_thumbnail_id ) ) {
			return $thumb;
		}
		return $this->get_data( $post_thumbnail_id );
	}

	/**
	 * Get dominant color.
	 *
	 * Get and save image dominant color to set background color.
	 *
	 * @since 1.0.0
	 */
	public function get_dominant_color( $color, $post_thumbnail_id ) {
		if ( empty( $post_thumbnail_id ) ) {
			return $color;
		}
		$value = get_post_meta( $post_thumbnail_id, $this->meta_name_dominant_color, true );
		if ( empty( $value ) ) {
			$value = $this->add_dominant_color( $post_thumbnail_id );
			if ( ! is_wp_error( $value ) ) {
				/**
				 * allow to change dominant color
				 *
				 * @since 1.0.3
				 */
				return apply_filters( 'iworks_aggresive_lazy_load_dominant_color', $value );
			}
			return $color;
		}
		/**
		 * allow to change dominant color
		 *
		 * @since 1.0.3
		 */
		return apply_filters( 'iworks_aggresive_lazy_load_dominant_color', $value );
	}

	/**
	 * Filters the post thumbnail HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param string       $html              The post thumbnail HTML.
	 * @param int          $post_id           The post ID.
	 * @param int          $post_thumbnail_id The post thumbnail ID.
	 * @param string|int[] $size              Requested image size. Can be any registered image size name, or
	 *                                        an array of width and height values in pixels (in that order).
	 * @param string       $attr              Query string of attributes.
	 */
	public function filter_post_thumbnail_html( $html, $post_ID, $post_thumbnail_id, $size, $attr ) {
		if ( preg_match( '/ data-src=/', $html ) ) {
			return $html;
		}
		/**
		 * no defined width or height - no lazy load!
		 */
		if (
			! preg_match( '/ width=/', $html )
			|| ! preg_match( '/ height=/', $html )
		) {
			return $html;
		}
		/**
		 * Browser-level image lazy-loading for the web
		 */
		if ( ! preg_match( '/ loading=/', $html ) ) {
			$html = preg_replace( '/<img /', '<img loading="lazy" /', $html );
		}
		$tiny = $this->get_data( $post_thumbnail_id );
		if ( ! empty( $tiny ) ) {
			$html       = preg_replace(
				'/ src="([^"]+)"/',
				sprintf( ' src="%s" data-src="%s"', $tiny, '$1' ),
				$html
			);
			$background = get_post_meta( $post_thumbnail_id, $this->meta_name_dominant_color, true );
			if ( ! empty( $background ) ) {
				if ( preg_match( ' /style=/', $html ) ) {
					$html = preg_replace(
						'/ style="/',
						sprintf(
							' style="background-color:#%s;',
							$background
						),
						$html
					);
				} else {
					$html = preg_replace(
						'/<img /',
						sprintf(
							'<img style="background-color:#%s" ',
							$background
						),
						$html
					);
				}
			}
		}
		if ( 'debug' === $this->replace_status ) {
			$html = preg_replace( '/(data\-src|srcset)="[^"]+"/', '', $html );
		}
		return $html;
	}

	/**
	 * Calculates the dominant color of an attachment and saves it as post meta.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $post_id
	 * @return string|WP_Error
	 */
	public function add_dominant_color( $post_id ) {
		$post_type = get_post_mime_type( $post_id );
		if ( ! preg_match( '/image\/(gif|jpeg|png)/', $post_type ) ) {
			return;
		}
		if ( ! class_exists( 'Imagick', false ) ) {
			return;
		}
		$path = get_attached_file( $post_id );
		try {
			$tiny   = $this->calculate( $path );
			$result = update_post_meta( $post_id, $this->meta_name_tiny_thumbnail, $tiny );
			if ( false === $result ) {
				add_post_meta( $post_id, $this->meta_name_tiny_thumbnail, $tiny, true );
			}
		} catch ( Exception $e ) {
			return new WP_Error( 'invalid_image', $e->getMessage(), $path );
		}
		try {
			$dominant_color = $this->calculate_dominant_color( $path );
			$result         = update_post_meta( $post_id, $this->meta_name_dominant_color, $dominant_color );
			if ( false === $result ) {
				add_post_meta( $post_id, $this->meta_name_dominant_color, $dominant_color, true );
			}
			return $dominant_color;
		} catch ( Exception $e ) {
			return new WP_Error( 'invalid_image', $e->getMessage(), $path );
		}
	}

	/**
	 * Calculates the dominant color of an image.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	private function calculate_dominant_color( $path ) {
		$image            = new Imagick( $path );
		$has_transparency = $image->getImageAlphaChannel();
		if ( $has_transparency ) {
			return 'transparent';
		}
		$image->resizeImage( 256, 256, Imagick::FILTER_QUADRATIC, 1 );
		$image->quantizeImage( 1, Imagick::COLORSPACE_RGB, 0, false, false );
		$image->setFormat( 'RGB' );
		return substr( bin2hex( $image ), 0, 6 );
	}

	/**
	 * Calculates tiny thumbnails of an image in three different sizes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	private function calculate( $path ) {
		$image = new Imagick( $path );
		$image->stripImage();
		$image->resizeImage( 6, 0, Imagick::FILTER_QUADRATIC, 1 );
		$image->setFormat( 'GIF' );
		return base64_encode( $image );
	}

	/**
	 * Get very small thumbnail
	 *
	 * @since 1.0.0
	 */
	private function get_data( $post_thumbnail_id, $format = 'encode' ) {
		$thumb = get_post_meta( $post_thumbnail_id, $this->meta_name_tiny_thumbnail, true );
		if ( ! empty( $thumb ) ) {
			if ( 'encode' === $format ) {
				return sprintf(
					'data:image/gif;base64,%s',
					$thumb
				);
			}
			return $thumb;
		}
		$this->add_dominant_color( $post_thumbnail_id );
		$thumb = get_post_meta( $post_thumbnail_id, $this->meta_name_tiny_thumbnail, true );
		if ( empty( $thumb ) ) {
			return $thumb;
		}
		if ( 'encode' === $format ) {
			return sprintf(
				'data:image/gif;base64,%s',
				$thumb
			);
		}
		return $thumb;
	}

	/**
	 * Add JavaScript to footer.
	 *
	 * Action to add JavaScript with lazy load replacements to footer.
	 *
	 * @since 1.0.0
	 */
	public function wp_footer() {
		if ( 'debug' === $this->replace_status ) {
			return;
		}
		?>
<script>
function iwork_image_replacement( event ) {
	var wh = window.innerHeight * 1.1 + window.scrollY;
	var wm = window.scrollY * .9;
	document.querySelectorAll('[data-src]').forEach( function( el ) {
		if (
			! el
			|| ! el.offsetParent
			|| 'undefined' === typeof el.offsetParent
			|| 'undefined' === typeof el.offsetParent.offsetTop
		) {
			return;
		}
		if ( wh < el.offsetParent.offsetTop ) {
			return true;
		}
		if ( wm > el.offsetParent.offsetTop + el.offsetHeight ) {
			return true;
		}
		if ( 'img' === el.tagName.toLowerCase() ) {
			el.setAttribute( 'src', el.dataset.src );
		} else {
			el.style.backgroundImage = 'url(' + el.dataset.src + ')';
		}
		el.removeAttribute('data-src');
	});
	document.querySelectorAll('[data-srcset]').forEach( function( el ) {
		if ( wh < el.offsetParent.offsetTop ) {
			return true;
		}
		if ( wm > el.offsetParent.offsetTop + el.offsetHeight ) {
			return true;
		}
		el.setAttribute( 'srcset', el.dataset.srcset );
		el.removeAttribute('data-srcset');
	})
}
window.addEventListener('load', (event) => { iwork_image_replacement(); });
window.addEventListener('scroll', (event) => { iwork_image_replacement(); } );
window.addEventListener('resize', (event) => { iwork_image_replacement(); } );
</script>
		<?php
	}
}

new iworks_aggresive_lazy_load;

/**
 * changelog
 *
 * 1.0.4 (2024-03-09)
 * - tuen off replacements when woo starts to email
 */
