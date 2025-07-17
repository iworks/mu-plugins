<?php
/*
Plugin Name: iWorks Login Screen
Plugin URI: https://github.com/iworks/mu-plugins
Description: Instantly update your WordPress login screen with a new, professionally designed look—no customization required.
Version: 1.0.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


class iWorks_Login_Screen {

	private $version = '1.0.1';

	/**
	 * Thumbnail is BIG name
	 *
	 * @since 1.0.0
	 */
	private $thumbnail_is_big_name = '_iworks_is_big';

	public function __construct() {
		add_action( 'edit_attachment', array( $this, 'set_is_big' ) );
		add_action( 'login_footer', array( $this, 'add_login_footer' ) );
		add_action( 'login_head', array( $this, 'output' ), 99 );
		add_action( 'login_header', array( $this, 'add_login_header' ) );
		add_action( 'signup_header', array( $this, 'output' ), 99 );
		add_filter( 'login_headerurl', array( $this, 'filter_login_headerurl' ) );
	}

	/**
	 * Save meta for thumbnail is BIG
	 *
	 * @since 1.0.0
	 */
	public function set_is_big( $post_id ) {
		$post_type = get_post_mime_type( $post_id );
		if ( ! preg_match( '/image/', $post_type ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		delete_post_meta( $post_id, $this->thumbnail_is_big_name );
		$image = wp_get_attachment_image_src( $post_id, 'full' );
		if (
			is_array( $image )
			&& 1199 < intval( $image[1] )
			&& 799 < intval( $image[2] )
		) {
			add_post_meta( $post_id, $this->thumbnail_is_big_name, 'yes', true );
		}
	}

	/**
	 * Get image for login screen.
	 *
	 * @since 1.0.0
	 */
	private function get_image() {
		$args     = array(
			'post_type'       => 'attachment',
			'meta_key'        => $this->thumbnail_is_big_name,
			'meta_value'      => 'yes',
			'orderby'         => 'rand',
			'posts_per_page'  => 1,
			'fields'          => 'ids',
			'post_status'     => 'inherit',
			'suppres_filters' => true,
		);
		$wp_query = new WP_Query( $args );
		if ( empty( $wp_query->posts ) ) {
			return WPMU_PLUGIN_URL . '/Gemini_Generated_Image_dsrjchdsrjchdsrj.jpg';
			return false;
		}
		return wp_get_attachment_image_url( $wp_query->posts[0], 'full' );
	}

	/**
	 * CSS for login screen
	 *
	 * @since 1.0.0
	 */
	public function output() {
		$background = apply_filters( 'iworks_login_screen_background', $this->get_image() );
		$logo       = apply_filters( 'iworks_login_screen_logo', get_site_icon_url() );
		?>
<style id="iworks-login-screen-css" type="text/css" data-version="<?php echo esc_attr( $this->version ); ?>">
.privacy-policy-page-link {
	display: none;
}
#login h1, #login h1 a {display: block;}
#login h1 a {
	background: transparent url(<?php echo $logo; ?>) no-repeat 50% 5%;
	background-size: contain;
	margin: 0px auto 25px auto;
	overflow: hidden;
	text-indent: -9999px;
	height: 84px;
	width: 84px;
}
.iworks-login {
	margin: 0;
	width: 100%;
	max-width: 500px;
	position: absolute;
	top: 0;
	bottom: 0;
	right: 0;
	background-color: rgba( 255, 255, 255, .8 );
}
@media screen and ( max-width: 399px ) {
	.iworks-login,
	#login {
		width: 100%;
	}
}
html {
	background: #fff url(<?php echo $background; ?>) no-repeat 50%;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
}
body {
	background: transparent none;
	margin: 0;
}
#loginform {
	border: 0;
	box-shadow: none;
	background-color: transparent;
}
</style>
		<?php
	}
	/**
	 * Before Login form
	 *
	 * @since 1.0.0
	 */
	public function add_login_header() {
		echo '<div class="iworks-login">';
	}

	/**
	 * After Login form
	 *
	 * @since 1.0.0
	 */
	public function add_login_footer() {
		echo '</div>';
	}

	/**
	 * Change url to home
	 *
	 * @since 1.0.1
	 */
	public function filter_login_headerurl( $url ) {
		return home_url();
	}
}

new iWorks_Login_Screen();
