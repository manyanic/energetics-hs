<?php
/**
 * Custom ACF config
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
	ACF JSON location
\*---------------------------------------------*/

function ca_json_save_point( $path ) {
    
    // update path
    $path = WP_CONTENT_DIR . '/acf-json';
       
    // return
    return $path;
    
}
add_filter('acf/settings/save_json', 'ca_json_save_point');

function ca_json_load_point( $paths ) {
    
    // remove original path (optional)
    unset($paths[0]);
    
    
    // append path
    $paths[] = WP_CONTENT_DIR . '/acf-json';
    
    
    // return
    return $paths;
    
}
add_filter('acf/settings/load_json', 'ca_json_load_point');


/*---------------------------------------------*\
	ACF add general option page
\*---------------------------------------------*/

function ca_add_general_options_page() {
    if( function_exists('acf_add_options_page') ) {
        $option_page = acf_add_options_page( __( "General", "hs-dashboard" ) );
    }
}
add_action('acf/init', 'ca_add_general_options_page');
