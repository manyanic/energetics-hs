<?php
/**
 * Disable unnecessary stuff
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
	Disable comments
\*---------------------------------------------*/

// Disable support for comments and trackbacks in post types
function dus_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'dus_disable_comments_post_types_support');

// Close comments on the front-end
function dus_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'dus_disable_comments_status', 20, 2);
add_filter('pings_open', 'dus_disable_comments_status', 20, 2);

// Hide existing comments
function dus_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'dus_disable_comments_hide_existing_comments', 10, 2);

// Remove comments page in menu
function dus_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'dus_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function dus_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'dus_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function dus_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'dus_disable_comments_dashboard');

// Remove comments links from admin bar
function dus_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'dus_disable_comments_admin_bar');

// Remove comment-reply.min.js
function dus_clean_header() {
	wp_deregister_script( 'comment-reply' );
}
add_action('init','dus_clean_header');


/*---------------------------------------------*\
	Remove all embeds
\*---------------------------------------------*/

function dus_remove_wp_embed() {
    if (!is_admin()) {
        wp_deregister_script('wp-embed');
    }
}
add_action( 'wp_enqueue_scripts', 'dus_remove_wp_embed' ); // Add Custom Scripts to wp_head

function dus_disable_embeds_code_init() {

	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'dus_disable_embeds_tiny_mce_plugin' );

	// Remove all embeds rewrite rules.
	add_filter( 'rewrite_rules_array', 'dus_disable_embeds_rewrites' );

	// Remove filter of the oEmbed result before any HTTP requests are made.
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}
add_action( 'init', 'dus_disable_embeds_code_init', 9999 );

function dus_disable_embeds_tiny_mce_plugin($plugins) {
    return array_diff($plugins, array('wpembed'));
}

function dus_disable_embeds_rewrites($rules) {
    foreach($rules as $rule => $rewrite) {
        if(false !== strpos($rewrite, 'embed=true')) {
            unset($rules[$rule]);
        }
    }
    return $rules;
}


/*---------------------------------------------*\
	Remove Wordpress emojis
\*---------------------------------------------*/

function dus_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'dus_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'dus_disable_emojis_remove_dns_prefetch', 10, 2 );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'dus_disable_emojis' );


// Filter function used to remove the tinymce emoji plugin.
function dus_disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// Remove emoji CDN hostname from DNS prefetching hints.
function dus_disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {

	if ( 'dns-prefetch' == $relation_type ) {
		/** This filter is documented in wp-includes/formatting.php */
		$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
		$urls = array_diff( $urls, array( $emoji_svg_url ) );
	}

	return $urls;
}


/*---------------------------------------------*\
	Disable XML-RPC
\*---------------------------------------------*/

add_filter('xmlrpc_enabled', '__return_false');
remove_action ('wp_head', 'rsd_link');

function dus_remove_xmlrpc_pingback_ping( $methods ) {
   unset( $methods['pingback.ping'] );
   return $methods;
}
add_filter( 'xmlrpc_methods', 'dus_remove_xmlrpc_pingback_ping' );

function dus_remove_unneeded_xmlrpc( $methods ) {
    unset( $methods['wp.getUsersBlogs'] );
    return $methods;
}
add_filter( 'xmlrpc_methods', 'dus_remove_unneeded_xmlrpc' );


/*---------------------------------------------*\
	Remove Windows Live Writer Manifest Link
\*---------------------------------------------*/

remove_action( 'wp_head', 'wlwmanifest_link');


/*---------------------------------------------*\
	Disable X-Pingback to header
\*---------------------------------------------*/

function dus_disable_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'dus_disable_x_pingback' );


/*---------------------------------------------*\
	Remove the WordPress version number
\*---------------------------------------------*/

remove_action( 'wp_head', 'wp_generator' );


/*---------------------------------------------*\
	Remove WordPress Page/Post Shortlinks
\*---------------------------------------------*/

remove_action( 'wp_head', 'wp_shortlink_wp_head');


/*---------------------------------------------*\
	Remove Post Relational Links
\*---------------------------------------------*/

function dus_remove_post_relational_links() {
	remove_action( 'wp_head', 'start_post_rel_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'parent_post_rel_link' );
	remove_action( 'wp_head', 'rel_canonical' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
}
add_action( 'init', 'dus_remove_post_relational_links' );


/*---------------------------------------------*\
	Remove Feed Links
\*---------------------------------------------*/

remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );


/*---------------------------------------------*\
	Remove Query Strings From Static Resources
\*---------------------------------------------*/

function dus_remove_script_version( $src ){
    $parts = explode( '?', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', 'dus_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'dus_remove_script_version', 15, 1 );


/*---------------------------------------------*\
	Disable Guttenberg
\*---------------------------------------------*/

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);