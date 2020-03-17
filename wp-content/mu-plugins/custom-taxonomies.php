<?php
/**
 * Custom Taxonomies
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}


/*------------------------------------*\
  Example Taxonomy
\*------------------------------------*/

/*function hs_create_example_taxonomy() {
	register_taxonomy(
		'example',
		array( 'post' ),
		array(
			'label' => __('Example', 'hs-starter-theme'),
			'hierarchical' => true
		)
	);
}
add_action( 'init', 'hs_create_example_taxonomy' );*/