<?php

/*
Plugin Name: iWorks Add ID to "A"
Plugin URI: https://github.com/iworks/mu-plugins
Description: Added ability to add ID attribute to menu item.
Version: 1.0.0
Author: Marcin Pietrzak
Author URI: http://iworks.pl/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


class iworks_add_wp_nav_menu_item_a_tag {

	private $meta_option_name = '_menu_item_a_id';

	public function __construct() {
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'form' ), 10, 5 );
		add_action( 'wp_update_nav_menu', array( $this, 'save' ), 10, 2 );
		add_filter( 'nav_menu_link_attributes', array( $this, 'add_id' ), 10, 4 );
	}

	public function add_id( $atts, $menu_item, $args, $depth ) {
		if ( isset( $atts['id'] ) ) {
			return $atts;
		}
		$value = get_post_meta( $menu_item->ID, $this->meta_option_name, true );
		if ( empty( $value ) ) {
			return $atts;
		}
		$atts['id'] = esc_attr( $value );
		return $atts;
	}

	public function save( $menu_id, $menu_data = array() ) {
		if ( ! isset( $_POST['menu-item-a-id'] ) ) {
			return;
		}
		if ( ! is_array( $_POST['menu-item-a-id'] ) ) {
			return;
		}
		foreach ( $_POST['menu-item-a-id'] as $post_id => $value ) {
			$value = esc_html( $value );
			if ( empty( $value ) ) {
				delete_post_meta( $post_id, $this->meta_option_name );
				continue;
			}
			if ( update_post_meta( $post_id, $this->meta_option_name, $value ) ) {
				continue;
			}
			add_post_meta( $post_id, $this->meta_option_name, $value, true );
		}
	}

	public function form( $item_id, $menu_item, $depth, $args, $current_object_id ) {
		$value = get_post_meta( $item_id, $this->meta_option_name, true );
		?>
<p class="field-a-id description description-wide">
	<label for="edit-menu-item-a-id-<?php echo $item_id; ?>">
		<?php _e( '"A" tag ID' ); ?><br />
		<input type="text" id="edit-menu-item-a-id-<?php echo $item_id; ?>" class="widefat code edit-menu-item-a-id" name="menu-item-a-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $value ); ?>" />
	</label>
</p>
		<?php
	}
}

new iworks_add_wp_nav_menu_item_a_tag();

