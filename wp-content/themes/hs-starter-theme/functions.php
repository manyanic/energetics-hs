<?php
/**
 * Functions
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/

/******************************************************************
*********************** SCRIPTS & STYLES **************************
******************************************************************/

/**
 * Load scripts and styles.
 *
 * @since 1.0.0
 * 
 */
function hs_load_scripts_and_styles() {

	// Load styles
	wp_enqueue_style( 'theme-style', get_stylesheet_uri() );

	// Load scripts
	wp_enqueue_script( 'respond-js', get_template_directory_uri() . '/js/vendor/respond.min.js', array(), '3.6.0' );
	wp_script_add_data( 'respond-js', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'html5shiv-printshiv', get_template_directory_uri() . '/js/vendor/html5shiv-printshiv.min.js', array(), '3.7.3' );
	wp_script_add_data( 'html5shiv-printshiv', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/vendor/modernizr-custom.min.js', array(), '3.6.0', true );
	wp_enqueue_script( 'global-scripts', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0', true );
	wp_localize_script( 'global-scripts', 'scripts_ajax_variables', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'wp_enqueue_scripts', 'hs_load_scripts_and_styles' );



/******************************************************************
************************* THEME SUPPORT ***************************
******************************************************************/

// Add theme suppor for some HTML5 elements
add_theme_support( 'html5', array(
	'search-form',
	'comment-form',
	'comment-list',
	'gallery',
	'caption',
) );



/******************************************************************
***************************** MENUS *******************************
******************************************************************/

/**
 * Register the menus.
 *
 * @since 1.0.0
 * 
 */
function hs_register_menus() {
	register_nav_menu('header-menu',__( 'Header Menu', 'hs-starter-theme' ));
}
add_action( 'init', 'hs_register_menus' );



/******************************************************************
***************************** ACF *********************************
******************************************************************/

/**
 * Disable ACF on the front-end.
 *
 * @since 1.0.0
 * 
 */
function hs_disable_frontend_acf( $plugins ) {
	if( is_admin() )
		return $plugins;
	foreach( $plugins as $i => $plugin )
		if( 'advanced-custom-fields-pro/acf.php' == $plugin )
			unset( $plugins[$i] );
	return $plugins;
}
add_filter( 'option_active_plugins', 'hs_disable_frontend_acf' );

/**
 * Get template parts from ACF sections flexible content field
 *
 * @since 1.0.0
 * 
 */
function hs_get_flexible_content() {

	$rows = get_post_meta( get_the_ID(), 'sections', true );

	if ($rows) {
		foreach( (array) $rows as $count => $row ) {
			set_query_var( 'count', $count );
			get_template_part( 'template-parts/sections/' . $row );
		}
	}

}



/******************************************************************
***************************** WPML *********************************
******************************************************************/

// Disable default styles
if ( function_exists( 'icl_get_languages' ) ) {
	define('ICL_DONT_LOAD_LANGUAGES_JS', true);
	define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
	define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
}

/**
 * Get WPML local code.
 *
 * @since 1.0.0
 * 
 * @param string $lang The language.
 */
function hs_wpml_get_code( $lang = "" ) {

    $langs = icl_get_languages( 'skip_missing=0' );

    if ( isset( $langs[$lang]['default_locale'] ) ) {
        return $langs[$lang]['default_locale'];
    }

    return false;
}

/**
 * Print the WPML language selector.
 *
 * @since 1.0.0
 * 
 */
function hs_language_selector() {

	if ( function_exists( 'icl_get_languages' ) ) {

		$languages = icl_get_languages( 'skip_missing=N&orderby=KEY&link_empty_to=str' );

		if ( ! empty( $languages ) ) {
		  echo '<ul>';
		  foreach ( $languages as $lang ) {
			echo '<li><a href=' . $lang['url'] . '>' . $lang['code'] . "</a></li>";
		  }
		  echo '</ul>';
		}
	}
}



/******************************************************************
**************************** MEDIA ********************************
******************************************************************/

// Set the quality for new images
add_filter( 'jpeg_quality', create_function( '', 'return 70;' ) );

/**
 * Add new image sizes.
 *
 * @since 1.0.0
 * 
 */
function hs_custom_image_sizes() {
	add_image_size( 'large-retina', 2048, 2048 );
	add_image_size( 'medium-retina', 600, 600 );
	add_image_size( 'thumbnail-retina', 300, 300 );
}
add_action( 'after_setup_theme', 'hs_custom_image_sizes' );

/**
 * Print adaptative, and optionally lazy loading images.
 *
 * @since 1.0.0
 *
 * @param integer $image_id The id of the image.
 * @param integer $img_width The width of the image (optional).
 * @param integer $img_height The height of the image (optional).
 * @param string $largest_image The biggest size image to show. Default: small
 * @param bool $lazy Select wether the image has to be lazy loaded on scroll or not. Default: false
 */
function hs_adaptative_image( $image_id, $img_width = 0, $img_height = 0, $largest_image = "small", $lazy = false ) {

	if ( $image_id !== null ) {

		$image_small_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
		$image_small_retina_url = wp_get_attachment_image_src( $image_id, 'thumbnail-retina' );
		$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true);
		$height = "";
		$width = "";

		if ( $largest_image === "large" ) {
			$image_medium_url = wp_get_attachment_image_src( $image_id, 'medium' );
			$image_medium_retina_url = wp_get_attachment_image_src( $image_id, 'medium-retina' );
			$image_large_url = wp_get_attachment_image_src( $image_id, 'large' );
			$image_large_retina_url = wp_get_attachment_image_src( $image_id, 'large-retina' );
		} else if ( $largest_image === "medium" ) {
			$image_medium_url = wp_get_attachment_image_src( $image_id, 'medium' );
			$image_medium_retina_url = wp_get_attachment_image_src( $image_id, 'medium-retina' );
			$image_large_url = $image_medium_url;
			$image_large_retina_url = $image_medium_retina_url ;
		} else {
			$image_medium_url = $image_small_url;
			$image_medium_retina_url = $image_small_retina_url;
			$image_large_url = $image_small_url;
			$image_large_retina_url = $image_small_retina_url ;
		}

		if ( $img_width !== 0 ) {
			$width = " width=\"" . $img_width . "\"";
		}
	
		if ( $img_height !== 0 ) {
			$height = " height=\"" . $img_height . "\"";
		}

		echo ( $lazy === true ? "<noscript>" : "" ) .
				"<picture>
					<!--[if IE 9]><video style=\"display: none;\"><![endif]-->
					<source media=\"(min-width: 1200px)\" srcset=\"" . $image_large_retina_url[0] . "  2x, " . $image_large_url[0] . " 1x\" />
					<source media=\"(min-width: 992px)\" srcset=\"" . $image_large_retina_url[0] . "  2x, " . $image_large_url[0] . " 1x\" />
					<source media=\"(min-width: 768px)\" srcset=\"" . $image_medium_retina_url[0] . "  2x, " . $image_medium_url[0] . " 1x\" />
					<source srcset=\"" . $image_small_retina_url[0] . "  2x, " . $image_small_url[0] . " 1x\" />
					<!--[if IE 9]></video><![endif]-->
					<img src=\"" . $image_large_url[0] . "\" alt=\"" . $image_alt . "\"" . $width . $height . " />
				</picture>"
			. ( $lazy === true ? "</noscript>" : "" );
	}
}



/******************************************************************
************************** BARBA JS *******************************
******************************************************************/

/**
 * Fix a Dashboard bar links bug provoked by Barba.
 *
 * @since 1.0.0
 *
 */
function hs_fix_barbajs_wp_admin() {
	ob_clean();

	$data = [
		'success' => false,
		'pageId'  => false,
	];

	if (isset($_POST['location']) && filter_var($_POST['location'], FILTER_VALIDATE_URL) !== false) {
		$postId = url_to_postid($_POST['location']);
		$data['success'] = true;
		$data['pageId'] = $postId;
	}

	echo json_encode($data);

	wp_die();
	exit;
}
add_action('wp_ajax_hs_fix_barbajs_wp_admin', 'hs_fix_barbajs_wp_admin');


/**
 * Localize WPML language links when using Barba
 *
 * @since 1.0.0
 *
 */
function hs_translation_links() {

    $links = array();

    if ( function_exists( 'icl_get_languages' ) ) {

        $languages = icl_get_languages( 'skip_missing=N&orderby=KEY&link_empty_to=str' );

        foreach ( $languages as $lang ) {
            array_push( $links, $lang['url'] );
		}
		
		echo "<input type=\"hidden\" id=\"translation-links\" name=\"translation-links\" value=\"" . implode( ",", $links ) . "\">";
    }
}