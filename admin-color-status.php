<?php
/*
Plugin Name: Change admin post/page color by status
Plugin URI: https://github.com/iworks/mu-plugins
Description: Adding this snippet to the functions.php of your WordPress theme will change the background colors of the post / page within the admin based on the current status. Draft, Pending, Published, Future, Private.
Version: trunk
Author: Kevin Chard
Author URI: http://wpsnipp.com/index.php/author/kevin/
License: GPLv3 or later
*/


class KevinChangeAdminColors {

	public function __construct() {
		add_action( 'admin_footer', array( &$this, 'posts_status_color' ) );
	}

	public function posts_status_color() {
		?>
<style>
.status-draft{background: #FCE3F2 !important;}
.status-pending{background: #87C5D6 !important;}
.status-publish{/* no background keep wp alternating colors */}
.status-future{background: #C6EBF5 !important;}
.status-private{background:#F2D46F;}
</style>
		<?php
	}
}

new KevinChangeAdminColors();

