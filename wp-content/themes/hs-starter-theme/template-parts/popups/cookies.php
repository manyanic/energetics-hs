<?php
/**
 * Cookies popup
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/
?>

<?php 
global $sitepress;
$text = "";
if ( function_exists( 'icl_object_id' ) ) {
    if ( ICL_LANGUAGE_CODE && ICL_LANGUAGE_CODE !== $sitepress->get_default_language() ) {
        $text = get_option( 'options_' . ICL_LANGUAGE_CODE . '_cookies_banner_text' );
    } else {
        $text = get_option( 'options_cookies_banner_text' );
    }
}
?>

<div class="popup popup--cookies">
    <div id="cookies-advice" class="cookies-advice">
        <p><?php echo $text; ?></p>
        <button class="cookies-advice__close" onclick="window.cookies.acceptCookies()">
            <svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 64 64" role="img" aria-labelledby="closeCookiesButtonTitle closeCookiesButtonDesc">
                <title id="closeCookiesButtonTitle"><?php _e( "Close cookies", "hs-starter-theme" ); ?></title>
                <desc id="closeCookiesButtonDesc"><?php _e( "Press this button to close the cookies banner", "hs-starter-theme" ); ?></desc>
                <path fill="#FFF" d="M28.941 31.786L.613 60.114a2.014 2.014 0 1 0 2.848 2.849l28.541-28.541 28.541 28.541c.394.394.909.59 1.424.59a2.014 2.014 0 0 0 1.424-3.439L35.064 31.786 63.41 3.438A2.014 2.014 0 1 0 60.562.589L32.003 29.15 3.441.59A2.015 2.015 0 0 0 .593 3.439l28.348 28.347z"/>
            </svg>
        </button>
    </div>
</div>