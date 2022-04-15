<?php
/*
Plugin Name: iWorks forecast.io shortcode
Plugin URI: http://iworks.pl/
Description: add shortcode to show forecast
Version: 0.1
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class iWorks_forecast_shortcode {

	public function __construct() {
		add_shortcode( 'iworks-forecast', array( $this, 'shortcode' ) );
	}

	public function shortcode( $atts ) {
		$data = shortcode_atts(
			array(
				'lat'    => '54.0484',
				'lon'    => '21.7678',
				'width'  => '100%',
				'height' => '245',
				'title'  => 'Giżycko',
			),
			$atts
		);

		return printf(
			'<iframe id="forecast_embed" type="text/html" frameborder="0" height="%d" width="%s" src="http://forecast.io/embed/#lat=%s&lon=%s&name=%s&lang=pl"></iframe>',
			$data['height'],
			$data['width'],
			$data['lat'],
			$data['lon'],
			$data['title']
		);

	}

}

new iWorks_forecast_shortcode();

