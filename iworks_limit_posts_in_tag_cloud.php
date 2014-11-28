<?php
/*
Plugin Name: Cloud Tag Limiter
Plugin URI: http://iworks.pl/
Description: Limit tag number in default tag cloud widge
Version: trunk
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GNU GPL
 */

class iworks_cloud_tag_limiter
{

    private $option_name = __CLASS__;

    public function __construct()
    {
        add_filter('widget_tag_cloud_args', array($this, 'args'));
        add_action('admin_init', array($this, 'admin_init'));
    }

    public function admin_init()
    {
        register_setting(
            'reading',
            $this->option_name,
            'intval'
        );

        add_settings_field(
            $this->option_name,
            __( 'Number of tags', __CLASS__ ),
            array( $this, 'setting_input'),
            'reading',
            'default'
        );
    }

    public function setting_input()
    {
        printf(
            '<input name="%s" type="number" value="%d" stat="1" step="1" class="small-text" /> %s',
            $this->option_name,
            $this->get_value(),
            __( 'Tags', __CLASS__)
        );
    }

    public function args($args)
    {
        $args['number'] = $this->get_value();
        return $args;
    }

    public static function init()
    {
        new iworks_cloud_tag_limiter();
    }

    private function get_value()
    {
        return get_option( $this->option_name, 45 );
    }

}

iworks_cloud_tag_limiter::init();
