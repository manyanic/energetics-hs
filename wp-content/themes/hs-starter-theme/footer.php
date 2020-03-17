<?php
/**
 * Footer
 *
 * @package      HS Wordpress Starter
 * @author       herraizsoto&co.
 * @since        1.0.0
**/
?>

<?php 
global $sitepress;
$copyright_text = "";
if ( function_exists( 'icl_object_id' ) ) {
    if ( ICL_LANGUAGE_CODE && ICL_LANGUAGE_CODE !== $sitepress->get_default_language() ) {
        $copyright_text = get_option( 'options_' . ICL_LANGUAGE_CODE . '_copyright_text' );
    } else {
        $copyright_text = get_option( 'options_copyright_text' );
    }
}
?>

        </main>
    </div>

    <?php get_template_part( 'template-parts/popups/cookies' ); ?>

    <footer class="footer">
        <p><?php echo $copyright_text; ?></p>
    </footer>

    <?php wp_footer(); ?>

</body>
</html>