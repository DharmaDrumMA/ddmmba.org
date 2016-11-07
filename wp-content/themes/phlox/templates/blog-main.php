<?php
/**
 * The main template for blog 'page templates'
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

get_header();?>

    <main id="main" <?php auxin_content_main_class(); ?> >
        <div class="aux-wrapper">
            <div class="container aux-fold clearfix">

                <div id="primary" class="aux-primary" >
                    <div class="content" role="main" data-target="archive"  >

                    <?php
                    echo auxin_get_the_archive_slider( 'post', 'content' );

                    if( ! empty( $content ) ){
                    ?>
                        <article <?php post_class(); ?> >
                            <div class="entry-main">
                                <div class="entry-content">
                                    <?php
                                        echo $content;
                                        // clear the floated elements at the end of content
                                        echo '<div class="clear"></div>';
                                    ?>
                                </div>
                            </div>
                        </article>
                    <?php
                    }

                    // page number
                    $paged  = max( 1, get_query_var('paged'), get_query_var('page') );
                    // get template slug
                    $page_template = get_page_template_slug( get_queried_object_id() );
                    // posts perpage
                    $per_page      = get_option( 'posts_per_page' );


                    // if template type is timeline
                    if( strpos( $page_template, 'blog-type-7' ) ){

                        $args = array(
                            'title'              => '',
                            'num'                => 200,
                            'posts_per_page'     => $per_page,
                            'paged'              => $paged,
                            'order_by'           => 'menu_order date',
                            'order'              => 'desc',
                            'show_media'         => true,
                            'show_excerpt'       => true,
                            'excerpt_len'        => '160',
                            'show_title'         => true,
                            'show_info'          => true,
                            'show_readmore'      => true,
                            'show_author_footer' => false,
                            'timeline_alignment' => 'center',
                            'tag'                => '',
                            'reset_query'        => false
                        );

                        if( function_exists( 'auxin_widget_recent_posts_timeline_callback' ) ){
                            // get the shortcode base blog page
                            $result = auxin_widget_recent_posts_timeline_callback( $args );
                        } else {
                            $result = __('To enable this feature, please install "Auxin Elements" plugin.');
                        }

                    }

                    // if template type is grid
                    else if( strpos( $page_template, 'blog-type-5' ) ){

                        $desktop_cnum = 4;
                        $tablet_cnum  = 3;
                        $phone_cnum   = 1;

                        $args = array(
                            'title'              => '',
                            'num'                => 200,
                            'order_by'           => 'menu_order date',
                            'order'              => 'desc',
                            'posts_per_page'     => $per_page,
                            'paged'              => $paged,
                            'show_media'         => true,
                            'display_like'       => auxin_get_option( 'show_blog_archive_like_button', 1 ) ,
                            'show_excerpt'       => true,
                            'excerpt_len'        => '160',
                            'show_title'         => true,
                            'show_info'          => true,
                            'show_readmore'      => true,
                            'show_author_footer' => false,
                            'desktop_cnum'       => $desktop_cnum,
                            'tablet_cnum'        => $tablet_cnum,
                            'phone_cnum'         => $phone_cnum,
                            'preview_mode'       => 'grid',
                            'tag'                => '',
                            'reset_query'        => false,
                            'extra_classes'      => ''
                        );

                        if( function_exists( 'auxin_widget_recent_posts_callback' ) ){
                            // get the shortcode base blog page
                            $result = auxin_widget_recent_posts_callback( $args );
                        } else {
                            $result = __('To enable this feature, please install "Auxin Elements" plugin.');
                        }

                    } else {

                        $q_args = array(
                            'post_type'     => 'post',
                            'order_by'      => 'date',
                            'order'         => 'DESC',
                            'post_status'   => 'publish',
                            'posts_per_page'=> get_option('posts_per_page'),
                            'paged'         => max( 1, get_query_var('paged'), get_query_var('page') ) // 'paged' for archive pages and 'page' for single pages
                        );

                        global $wp_query;
                        $wp_query = new WP_Query( $q_args );

                        $result = have_posts();
                    }

                    // if it is not a shortcode base blog page
                    if( true === $result ){

                        while ( have_posts() ) : the_post();
                            include locate_template( 'templates/theme-parts/entry/post.php' );
                        endwhile; // end of the loop.

                    // if it is a shortcode base blog page
                    } else if( ! empty( $result ) ){
                        echo $result;

                    // if result not found
                    } else {
                        include locate_template( 'templates/theme-parts/content-none.php' );
                    }

                    // generate the archive pagination
                    auxin_the_paginate_nav(
                        array( 'css_class' => auxin_get_option('archive_pagination_skin') )
                    );

                    // reset the main global wp_query instance
                    wp_reset_query(); ?>

                    </div><!-- end content -->
                </div><!-- end primary -->

                <?php get_sidebar(); ?>

            </div><!-- end container -->
        </div><!-- end wrapper -->
    </main><!-- end main -->

<?php get_sidebar('footer'); ?>
<?php get_footer(); ?>
