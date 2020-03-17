<?php
/**
 * Custom Post Types
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}


/*---------------------------------------------*\
	Disable Posts post type
\*---------------------------------------------*/

/*// Disable side menu
function hs_remove_default_post_type() {
    remove_menu_page( 'edit.php' );
}
add_action( 'admin_menu', 'hs_remove_default_post_type' );

// Disable +New Post in Admin Bar
function hs_remove_default_post_type_menu_bar( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'new-post' );
}
add_action( 'admin_bar_menu', 'hs_remove_default_post_type_menu_bar', 999 );

// Disable Quick Draft Dashboard Widget
function hs_remove_draft_widget(){
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'hs_remove_draft_widget', 999 );*/


/*---------------------------------------------*\
	Example custom post type
\*---------------------------------------------*/

/*function hs_create_example_post_type() {
    $args = array(
				'public' => true,
				'label' => 'Example',
				'menu_icon' => 'dashicons-portfolio',
				'has_archive' => true,
				'supports' => array( 'title' ),
			);
	register_post_type( 'example', $args );
}
add_action( 'init', 'hs_create_example_post_type' );*/
