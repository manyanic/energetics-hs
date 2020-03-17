<?php
/**
 * Custom Dashboard
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}


/******************************************************************
************************** CUSTOM MENUS ***************************
******************************************************************/

/*---------------------------------------------*\
	Disable Dashboard
\*---------------------------------------------*/

function dus_dashboard_redirect() {
   return 'wp-admin/edit.php?post_type=page'; //your redirect URL
}
add_filter('login_redirect', 'dus_dashboard_redirect');

function dus_remove_dashbaord(){
	remove_menu_page( 'index.php' );
}
add_action( 'admin_menu', 'dus_remove_dashbaord', 99 );


/*---------------------------------------------*\
	Remove themes & widgets menu
\*---------------------------------------------*/

function dus_hide_submenus() {

    // Hide theme selection page
    remove_submenu_page( 'themes.php', 'themes.php' );

    // Hide widgets page
    remove_submenu_page( 'themes.php', 'widgets.php' );

}
add_action('admin_head', 'dus_hide_submenus');


/*---------------------------------------------*\
	Remove tools page for non admins
\*---------------------------------------------*/

function dus_hide_menus() {

    if ( !current_user_can( 'administrator') ) {
        remove_menu_page( 'tools.php' );
    }
}
add_action('admin_head', 'dus_hide_menus');


/******************************************************************
************************** PERMISSIONS ****************************
******************************************************************/

/*---------------------------------------------*\
	Allow editors to see Appearance menu
\*---------------------------------------------*/

$role_object = get_role('editor');
if ($role_object !== null) {
    $role_object->add_cap('edit_theme_options');
}


