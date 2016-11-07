<?php
/* The template for displaying the footer.
 * Contains the closing of the body div and all contents
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

do_action( 'auxin_before_the_footer' );

if( auxin_get_option( 'show_site_footer', 1 ) ){
?>

    <footer id="sitefooter" class="aux-site-footer" >
        <?php do_action( 'auxin_in_the_footer' ); ?>
        <div class="aux-wrapper aux-float-layout">
                <?php echo auxin_get_footer_components_markup(); ?>
                <!-- end navigation -->
        </div><!-- end wrapper -->
    </footer><!-- end sitefooter -->

</div><!--! end of #inner-body -->
<?php } ?>

<?php do_action( "auxin_before_body_close", $post ); ?>

<!-- outputs by wp_footer -->
<?php wp_footer(); ?>
<!-- end wp_footer -->
</body>
</html>
