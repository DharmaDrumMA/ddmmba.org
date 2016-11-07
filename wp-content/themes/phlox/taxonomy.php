<?php
/**
 * The template for displaying taxonomies
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/
get_header(); ?>

    <main id="main" <?php auxin_content_main_class(); ?> >
        <div class="aux-wrapper">
            <div class="container aux-fold clearfix">

                <div id="primary" class="aux-primary" >
                    <div class="content" role="main" data-target="archive"  >

                <?php if( have_posts() ) : ?>

                        <?php get_template_part('templates/theme-parts/tax', get_query_var('taxonomy') ); ?>

                <?php else : ?>

                        <?php get_template_part('templates/theme-parts/content', 'none' );?>

                <?php endif; ?>

                    </div><!-- end content -->
                </div><!-- end primary -->


                <?php get_sidebar(); ?>

            </div><!-- end container -->
        </div><!-- end wrapper -->
    </main><!-- end main -->

<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>
