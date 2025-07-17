<?php
/*
Plugin Name: iWorks wp-login.php renamer
Plugin URI: https://github.com/iworks/mu-plugins
Description: raname wp-login.php o other name
Version: 1.0.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


/**
 * IMPORTANT!
 *
 * 1. Make a symlink wp-login.php to ${login_prefix}wp-login.php!!!
 * 2. Set 403 on wp-login.php file.
 */


class iWorks_WP_Login_Redirect {

	private $login_prefix = 'ephuiC0aoW-';

	public function __construct() {
		add_filter( 'site_url', array( $this, 'site_url' ), 10, 2 );
		add_filter( 'wp_redirect', array( $this, 'wp_redirect' ), 10, 2 );
		add_filter( 'lostpassword_url', array( $this, 'lostpassword_url' ), 10, 2 );
		#        add_filter( 'network_site_url', array( $this, 'network_site_url' ), 10, 3 );
	}

	/**
	 * Replace wp-login.php URL
	 *
	 * @since 1.0.0
	 */
	public function site_url( $url, $file ) {
		if ( preg_match( '/wp-login.php/', $file ) ) {
			return preg_replace( '/wp-login/', $this->login_prefix . 'wp-login', $url );
		}
		return $url;
	}

	/**
	 * Change redirect to proper
	 *
	 * @since 1.0.1
	 */
	public function wp_redirect( $location, $status ) {
		if ( preg_match( '/^wp-login.php/', $location ) ) {
			return preg_replace( '/wp-login/', $this->login_prefix . 'wp-login', $location );
		}
		if ( preg_match( '@/wp-login.php@', $location ) ) {
			return preg_replace( '@/wp-login@', '/' . $this->login_prefix . 'wp-login', $location );
		}
		return $location;
	}

	/**
	 * Change Lost Password URL
	 *
	 * @since 1.0.0
	 */
	public function lostpassword_url( $lostpassword_url, $redirect ) {
		if ( preg_match( '@/wp-login.php@', $lostpassword_url ) ) {
			return preg_replace( '/\/wp-login/', '/' . $this->login_prefix . 'wp-login', $lostpassword_url );
		}
		return $lostpassword_url;
	}

	public function network_site_url( $url, $path, $scheme ) {
		if ( preg_match( '@/wp-login.php@', $url ) ) {
			return preg_replace( '/\/wp-login/', '/' . $this->login_prefix . 'wp-login', $url );
		}
		return $url;
	}
}

new iWorks_WP_Login_Redirect();
