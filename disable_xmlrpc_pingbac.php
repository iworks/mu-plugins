<?php
/*
Plugin Name: Disable XML RPC pingback
Plugin URI: http://iworks.pl/
Description: Disable XML-RPC ingback method
Version: 0.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class iWorks_Disable_Pingback
{
    public function __construct()
    {
        add_filter( ‘xmlrpc_methods’, array( &$this, 'xmlrpc_methods' ) );
    }

    static function init()
    {
        new iWorks_Disable_Pingback();
    }

    public function xmlrpc_methods($methods)
    {
        if ( array_key_exists( 'pingback.ping', $methods ) ) {
            unset( $methods['pingback.ping'] );
        }
        return $methods;
    }

}

iWorks_Disable_Pingback::init();

