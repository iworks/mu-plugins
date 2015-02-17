<?php
/*
Plugin Name: iWorks contact shortcode
Plugin URI: http://iworks.pl/
Description: add kontakt data
Version: 0.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class iWorks_contact_shortcode
{
    public function __construct()
    {
        add_shortcode('iworks-contact', array($this, 'shortcode'));
    }

    public function shortcode($atts)
    {
        $data = array(
            'Facebook' => 'https://www.facebook.com/pietrzak.marcin',
            'LinkedIn' => 'http://pl.linkedin.com/in/pietrzakmarcin',
            'E-mail' => antispambot('marcin@iworks.pl'),
        );

        $content = sprintf('<h2>%s</h2>', __('Contact', 'iworks'));
        $content .= '<ul>';
        foreach( $data as $label => $href ) {
            $content .= sprintf('<li><a href="%s">%s</li>', $href, $label);
        }
        $content .= '</ul>';
    }

}

new iWorks_contact_shortcode();

