<?php
/*
Plugin Name: Replce jQuery by googleapis.
Version: 1.12.4
 */
function googleapis_modify_jquery() {
    if (is_admin()) {
        return;
    }
    $version = '1.12.4';
        // comment out the next two lines to load the local copy of jQuery
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js', false, $version);
        wp_enqueue_script('jquery');
}
add_action('init', 'googleapis_modify_jquery');
