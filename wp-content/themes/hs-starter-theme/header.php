<?php
/**
 * Header
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/
global $sitepress;
$meta_description = get_post_meta( get_the_ID(), 'meta_description', true );
$meta_keywords = get_post_meta( get_the_ID(), 'meta_keywords', true );
$meta_image = get_post_meta( get_the_ID(), 'meta_image', true );
$meta_title = get_post_meta( get_the_ID(), 'meta_title', true );
$canonical_url = explode("?", $_SERVER['REQUEST_URI']);
$menu_args = array(
    'theme_location'  => 'header-menu',
    'items_wrap' => '<nav class="header__menu" role="navigation"><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
    'container_class'   => 'header__menu-container',
    'menu_class'        => 'header__menu-container__list'

);
$google_analytics_code = "";
$client_name = "client";
$slug = get_queried_object()->post_name;

if ( function_exists( 'icl_object_id' ) ) {
    $lang_code = hs_wpml_get_code( ICL_LANGUAGE_CODE );
    if ( ICL_LANGUAGE_CODE && ICL_LANGUAGE_CODE !== $sitepress->get_default_language() ) {
        $google_analytics_code = get_option( 'options_' . ICL_LANGUAGE_CODE . '_google_analytics_id' );
    } else {
        $google_analytics_code = get_option( 'options_google_analytics_id' );
    }
} else {
    $lang_code = "es_ES";
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>   <html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <!-- DNS prefetch -->
    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <!-- End DNS prefetch -->

    <!-- Canonical url -->
    <link rel="canonical" href="<?php echo get_site_url() . $canonical_url[0]; ?>">

    <!-- Meta -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
    <meta name="description" content="<?php echo $meta_description; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/images/icons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta property="og:locale" content="<?php echo $lang_code; ?>">
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo $meta_title; ?>">
    <meta property="og:description" content="<?php echo $meta_description; ?>">
    <meta property="og:url" content="<?php echo $meta_image; ?>">
    <meta property="og:image" content="<?php echo $meta_image; ?>">
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $meta_title; ?>">
    <meta name="twitter:description" content="<?php echo $meta_description; ?>">
    <meta name="twitter:image" content="<?php echo $meta_image; ?>">
    <!-- End meta -->

    <!-- Authoring -->
    <link type="text/plain" rel="author" href="<?php echo get_site_url(); ?>/humans.txt" />
    <meta name="author" content="herraizsoto&co.">
    <meta name="copyright" content="<?php echo $client_name; ?>" />
    <!-- End authoring -->

    <title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

    <!-- Icons -->
    <link href="<?php echo get_template_directory_uri(); ?>/images/icons/favicon.png" rel="shortcut icon">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/icons/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/images/icons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_template_directory_uri(); ?>/images/icons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/images/icons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/images/icons/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/icons/manifest.json">
    <!-- End icons -->

	<!-- Add Font -->

	<link rel="stylesheet" href="https://rsms.me/inter/inter.css">

	<!-- End Add Font -->

    <!-- Google Analytics -->
    <script>
    window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
    ga('create', '<?php echo $google_analytics_code; ?>', 'auto');
    ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->

    <!-- Scripts & style -->
    <?php wp_head(); ?>
    <!-- End scripts & style -->

</head>
<body <?php body_class(); ?> data-barba="wrapper">
    <header class="header">
        <div class="header__logo">
            <picture>
                <!--[if IE 9]><video style="display: none;"><![endif]-->
                <source srcset="<?php echo get_template_directory_uri(); ?>/images/logo.svg" type="image/svg+xml">
                <!--[if IE 9]></video><![endif]-->
                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php _e( 'Logo', '' ); ?>">
            </picture>
        </div>
        <label class="header__menu-toogle" for="toggle">
            &#9776; <span>Menu</span>
        </label>
        <input class="header__menu-toogle" id="toggle" type="checkbox">

        <?php if ( has_nav_menu( 'header-menu' ) ) : ?>
            <?php wp_nav_menu( $menu_args ); ?>
        <?php endif; ?>

        <div class="header__languages">

            <?php hs_language_selector(); ?>

        </div>
    </header>
    <div class="wrapper">
        <main class="barba-container" data-barba="container" role="main">
            <input type="hidden" id="body-classes" name="body-classes" value="<?php echo implode( ",", get_body_class( $slug ) ); ?>">

            <?php hs_translation_links(); ?>
