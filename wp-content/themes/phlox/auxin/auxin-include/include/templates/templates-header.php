<?php
/**
 * Header Template Functions.
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

function auxin_main_title_preset_args( $defaults, $post = null ){
    if( empty( $post ) ){
        return $defaults;
    }


    $titlebar_args = array();

    if ( is_archive() || is_search() ) {
        // title bar is required to appears in archive pages.
        $titlebar_args['show_titlebar']  = true;
    } elseif( ! empty( $post->post_type ) && 'post' == $post->post_type ){
        // whether to display title bar or not - default false
        $titlebar_args['show_titlebar']  = auxin_get_post_meta( $post->ID, 'aux_title_bar_show', false );
    } else {
        // whether to display title bar or not - default true
        $titlebar_args['show_titlebar']  = auxin_get_post_meta( $post->ID, 'aux_title_bar_show', true );
    }

    if( ! is_singular() ){

        $titlebar_args['heading_bordered'] = 0;
        $titlebar_args['bread_bordered']   = 0;
        $titlebar_args['bread_enabled']    = 1;
        $titlebar_args['meta_enabled']     = 0;
        $titlebar_args['bread_sep_style']  = 'slash';
        $titlebar_args['text_align']       = '';

        return wp_parse_args( $titlebar_args, $defaults );
    }

    // content_width_type
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_content_width_type', 'boxed' ) ){
        $titlebar_args['content_width_type'] = $meta_val;
    }

    // section_height
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_content_section_height', '' ) ){
        $titlebar_args['section_height'] = $meta_val;
    }

    // heading_bordered
    $titlebar_args['heading_bordered'] = auxin_get_post_meta( $post->ID, 'aux_title_bar_heading_bordered', 0 );

    // heading_boxed
    $titlebar_args['heading_boxed']  = auxin_get_post_meta( $post->ID, 'aux_title_bar_heading_boxed', 0 );

    // heading bg color
    $titlebar_args['heading_bg_color']  = auxin_get_post_meta( $post->ID, 'aux_title_bar_heading_bg_color', '' );

    // post meta enabled
    $titlebar_args['meta_enabled']   = auxin_get_post_meta( $post->ID, 'aux_title_bar_meta_enabled', 0 );

    // bread_enabled
    $titlebar_args['bread_enabled']  = auxin_get_post_meta( $post->ID, 'aux_title_bar_bread_enabled', 1 );

    // bread_bordered
    $titlebar_args['bread_bordered'] = auxin_get_post_meta( $post->ID, 'aux_title_bar_bread_bordered', 0 );

    // bread_sep_style
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bread_sep_style', 'arrow' ) ){
        $titlebar_args['bread_sep_style'] = $meta_val;
    }

    // text_align
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_text_align', 'left' ) ){
        $titlebar_args['text_align'] = $meta_val;
    }

    // vertical_align
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_vertical_align', 'top' ) ){
        $titlebar_args['vertical_align'] = $meta_val;
    }

    // scroll_arrow
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_scroll_arrow', 'none' ) ){
        $titlebar_args['scroll_arrow'] = $meta_val;
    }

    // color_style
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_color_style', 'dark' ) ){
        $titlebar_args['color_style'] = $meta_val;
    }

    // overlay_color
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_overlay_color', '' ) ){
        $titlebar_args['overlay_color'] = $meta_val;
    }

    // overlay_pattern
    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_overlay_pattern', '' ) ){
        $titlebar_args['overlay_pattern'] = $meta_val;
    }

    if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_show', false ) ){

        // bg image
        if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_image', '' ) ){
            $titlebar_args['bg_image'] = $meta_val;
        }

        // bg size
        if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_size', 'cover' ) ){
            $titlebar_args['bg_size'] = $meta_val;
        }

        // bg color
        if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_color', '' ) ){
            $titlebar_args['bg_color'] = $meta_val;
        }

        // bg video mp4
        if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_video_mp4', '' ) ){
            $titlebar_args['video_mp4'] = wp_get_attachment_url( $meta_val );
        }

        // bg video webm
        if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_video_webm', '' ) ){
            $titlebar_args['video_webm'] = wp_get_attachment_url( $meta_val );
        }

        // bg video ogg
        if( $meta_val = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_video_ogg', '' ) ){
            $titlebar_args['video_ogg'] = wp_get_attachment_url( $meta_val );
        }

        // bg parallax
        $titlebar_args['bg_parallax'] = auxin_get_post_meta( $post->ID, 'aux_title_bar_bg_parallax', false );
    }


    if ( is_singular() ) { // in this case one of is_single, is_page, is_page returns true

        if( is_single() && ( 'testimonial' == get_post_type() ) ) {
            $titlebar_args['subtitle'] = ! empty( $post ) ? auxin_get_post_meta( $post->ID, 'customer_job' , '' ) : '';
        } else{
            $titlebar_args['subtitle'] = ! empty( $post ) ? auxin_get_post_meta( $post->ID, 'page_subtitle', '' ) : '';
        }

    }

    return wp_parse_args( $titlebar_args, $defaults );
}
add_filter( 'auxin_main_title_args', 'auxin_main_title_preset_args', 19, 2 );



/**
 * Display page title section
 *
 * @since 2.0.0
 */
if( ! function_exists( 'auxin_the_main_title' ) ){

    function auxin_the_main_title( $args = array() ){
        global $post;

        //  dont display title bar on 404 page
        if( is_404() ){
            return;
        }

        // default title section args
        $defaults = array(
            'show_titlebar'     => 1, // whether display title bar or not
            'title'             => '',
            'subtitle'          => '',
            'content_width_type'=> 'boxed', // boxed, semi-full, full, default
            'section_height'    => '', // full, 300px, auto
            'heading_bordered'  => 1,
            'heading_boxed'     => 0,
            'heading_bg_color'  => '',
            'bread_bordered'    => 1,
            'bread_enabled'     => 1,
            'meta_enabled'      => 1,
            'bread_sep_style'   => 'slash', //slash, arrow, gt
            'text_align'        => 'center', // center, left, right
            'vertical_align'    => 'top', // top, middle, bottom, bottom-overlap
            'color_style'       => 'dark', // light, dark,
            'scroll_arrow'      => '', // round, classic
            'overlay_color'     => '', // light, dark
            'overlay_pattern'   => '', // hash, ..
            'bg_image'          => '', // section background image
            'bg_size'           => '',  // section background image size
            'bg_color'          => '',  // section background color
            'bg_parallax'       => 0,
            'video_mp4'         => '', // http://wp.dev/en/wp-content/uploads/2015/11/echo-hereweare.mp4
            'video_ogg'         => '',
            'video_webm'        => ''
        );


        if( is_home() ){

            $posts_page_id = get_option( 'page_for_posts' );
            $defaults['title'] = get_the_title( $posts_page_id );
            // lets disable titlebar on home page temporary
            return '';

        } elseif ( is_search() ){

            $defaults['title'] = __( 'Results for: ', 'phlox') . get_search_query();

        /* If this is a category archive */
        } elseif ( is_archive() ) {

            if( is_category() ){
                $defaults['title'] = __('Posts in category', 'phlox'). ': '. single_cat_title( '', false);
                $defaults['subtitle'] = category_description();
            /* If this is a tag archive */
            } elseif( is_tag() ){
                $defaults['title'] = __('Posts tagged', 'phlox') . ': '. single_tag_title('', false);
            /* If this is a daily archive */
            } elseif ( is_day() ){
                $defaults['title'] = __("Daily Archives", 'phlox') .': '. get_the_date();
            /* If this is a monthly archive */
            } elseif ( is_month() ){
                $defaults['title'] = __("Monthly Archives", 'phlox') .': '. get_the_date('F Y');
            /* If this is a yearly archive */
            } elseif ( is_year() ){
                $defaults['title'] = __("Yearly Archives", 'phlox') .': '. get_the_date('Y');
            /* If this is an author archive */
            } elseif ( is_author() ){
                $defaults['title'] = _x("All posts by : ", "Title of all posts by author", 'phlox') . get_the_author();
            /* If this is an author archive */
            } elseif ( is_tax() ) {
                $term  = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                $defaults['title'] = $term->name;
            /* If this is a custom post type archive(index) page */
            } elseif( is_post_type_archive() ){
                $defaults['title'] = auxin_get_the_post_type_user_defined_name();
            }

        } elseif ( is_singular() ) { // in this case one of is_single, is_page, is_page returns true
            $defaults['title'] = get_the_title();
        }

        // if it was 'no-result' page
        if( ! $post ){
            $defaults['meta_enabled']       = false;
            $defaults['heading_bordered']   = false;
            $defaults['text_align']         = 'left';
            $defaults['content_width_type'] = 'boxed';
        }


        $args = wp_parse_args( $args, $defaults );
        $args = apply_filters( 'auxin_main_title_args', $args, $post );

        if( null === $args ){ return; }

        if( ! $args['show_titlebar'] ){
            return;
        }

        // Default classes for title bar
        $header_classes = array('page-header', 'aux-wrapper');

        if( ! empty( $args['subtitle'] ) ){
            $header_classes[] = 'has-subtitle';
        }

        /*
        // Is title section full or boxed?
        if( ! empty( $post ) && get_post_meta( $post->ID, 'axi_show_title_section_background', true ) == "yes" ){
            $header_classes[] = 'aux-full-wrapper';
        }

        // Is title section width - possible value: boxed, full, semi-full
        if( ! empty( $post ) ){
            $title_section_width = get_post_meta( $post->ID, 'axi_title_section_width', true );
            switch ( $title_section_width ) {
                case 'full':
                    $header_classes[] = 'aux-full-container';
                    break;

                case 'semi-full':
                    $header_classes[] = 'aux-semi-full-container';
                    break;

                default:
                    $header_classes[] = 'aux-boxed-container';
                    break;
            }
        }


        Possible classes:

        aux-full-height
        aux-heading-bordered
        aux-heading-boxed
        aux-bread-bordered
        aux-bread-sep-arrow|aux-bread-sep-slash|aux-bread-sep-gt
        aux-left|aux-center|aux-right
        aux-top|aux-middle|aux-bottom
        aux-light|aux-dark
        aux-scroll-arrow-round|aux-scroll-arrow-classic,
        aux-overlay-dark|aux-overlay-light
        aux-overlay-bg-hash
        aux-bg-parallax

        Sample markup:

        <header id="page-title" class="page-title-section">

            <div class="page-header aux-wrapper aux-boxed-container aux-fullscreen-section
            aux-heading-bordered aux-heading-boxed aux-bread-bordered aux-bread-sep-arrow
            aux-center aux-light aux-scroll-arrow-round aux-overlay-darken">

                <div class="aux-overlay"></div>

                <div class="container aux-fold">
                    <div class="aux-page-title-entry">
                            <section class="page-title-group">
                                <h1 class="page-title" itemprop="headline">Standard Post title</h1>
                                <h3 class="page-subtitle" >Standard Post subtitle</h3>
                            </section>

                            <div class="page-title-meta aux-single-inline-meta">
                                <span>Posted on</span>
                                <time datetime="2012-11-11">November 11, 2012</time>
                                <span class="meta-sep">by</span>
                                <span class="author vcard">
                                    <a href="#" rel="author" title="View all posts by admin">admin</a>
                                </span>
                                <i> | </i>
                                <a class="post-edit-link" href="#">Edit</a>
                            </div>
                    </div>
                </div>

            </div><!-- end page header -->

        </header>*/

        if( 'full' == $args['section_height'] ){
            $header_classes[] = 'aux-full-height';
        }
        if( ! empty( $args['content_width_type'] ) && 'default' != $args['content_width_type'] ){
            $header_classes[] = 'aux-'. $args['content_width_type'] .'-container';
        }
        if( $args['heading_bordered'] ){
            $header_classes[] = 'aux-heading-bordered';
        }
        if( $args['heading_boxed'] ){
            $header_classes[] = 'aux-heading-boxed';
        }
        if( $args['bread_bordered'] ){
            $header_classes[] = 'aux-bread-bordered';
        }
        if( ! empty( $args['bread_sep_style'] ) ){
            $header_classes[] = 'aux-bread-sep-'. $args['bread_sep_style'];
        }
        if( ! empty( $args['text_align'] ) ){
            $header_classes[] = 'aux-'. $args['text_align'];
        }
        if( ! empty( $args['vertical_align'] ) ){
            $header_classes[] = 'aux-'. $args['vertical_align'];
        }
        if( ! empty( $args['color_style'] ) ){
            $header_classes[] = 'aux-'. $args['color_style'];
        }
        if( ! empty( $args['scroll_arrow'] ) ){
            $header_classes[] = 'aux-arrow-'. $args['scroll_arrow'];
        }
        if( ! empty( $args['overlay_pattern'] ) ){
            $header_classes[] = 'aux-overlay-bg-'. $args['overlay_pattern'];
        }
        if( $args['bg_parallax'] ){
            $header_classes[] = 'aux-parallax-box';
        }
        // if( $args['video_mp4'] || $args['video_webm'] || $args['video_ogg'] ){
        //     $header_classes[] = 'aux-video-box';
        // }


        $page_header_style = "display:block; ";


        $bg_img_url = '';

        if( ! empty( $args['bg_image'] ) ){

            $attach_ids = explode( ',', $args['bg_image'] );

            $bg_img_url    = wp_get_attachment_url( $attach_ids[0] ,'full' ); //get img URL
            // $page_header_style .= 'background-image: url( ' . $bg_img_url . ' ) !important; ';
        }
        if( ! empty( $args['bg_color'] ) ){
            $page_header_style .= 'background-color: ' . $args['bg_color'] . ' !important; ';
        }
        if( ! empty( $args['bg_size'] ) && 'cover' != $args['bg_size'] ){
            $page_header_style .= 'background-size: ' . $args['bg_size'] . ' !important; ';
        }


        $inline_style = '';
        if( ! empty( $page_header_style ) ){
            $inline_style = ' style="' . $page_header_style . '" ';
        }

        // get the bg color of heading box
        $title_group_inline_style =  $args['heading_boxed'] && ! empty( $args['heading_bg_color'] ) ? 'style="background-color: ' . $args['heading_bg_color'] . ' !important;"' : '';

        ?>
        <header id="site-title" class="page-title-section">

            <div <?php echo auxin_make_html_class_attribute( $header_classes ); echo $inline_style; ?>  >

                <?php // Print video background
                    echo auxin_get_media_background(
                        array(
                            'color' => $args['bg_color'],
                            'image' => $bg_img_url,
                            'ogg'   => $args['video_ogg' ],
                            'webm'  => $args['video_webm'],
                            'mp4'   => $args['video_mp4' ],
                            'parallax' => 0.5
                        )
                    );
                ?>

                <div class="container" >

                    <?php // ------------- breadcrumb ---------------
                        if( $args['bread_enabled'] ){
                            // combination of parallax and sticky header doesn't look good
                            //if ( $args['bg_parallax'] ) { echo '<div class="aux-parallax" data-parallax-depth="1">'; }
                            auxin_the_breadcrumbs();
                            //if ( $args['bg_parallax'] ) { echo '</div>'; }
                        }
                    ?>

                    <div class="aux-page-title-entry">
                    <?php if ( $args['bg_parallax'] ) {?>
                        <div class="aux-page-title-box aux-parallax" data-parallax-depth="0.5">
                    <?php } else { ?>
                        <div class="aux-page-title-box">
                    <?php } ?>
                            <section class="page-title-group" <?php echo $title_group_inline_style; ?>>
                                <?php if( ! empty( $args['title'] ) ) { ?>
                                <h1 class="page-title"><?php echo $args['title']; ?></h1>
                                <?php } if( ! empty( $args['subtitle'] ) ) { ?>
                                <h3 class="page-subtitle" ><?php echo $args['subtitle']; ?></h3>
                                <?php } ?>
                            </section>

                            <?php if( $args['meta_enabled'] ){ // Post meta ?>
                            <div class="page-title-meta aux-single-inline-meta">
                                <span><?php _e("Posted on", 'phlox'); ?></span>
                                <time datetime="<?php the_time('Y-m-d')?>" ><?php the_date(); ?></time>
                                <span class="meta-sep"><?php _e("by", 'phlox'); ?></span>
                                <span class="author vcard">
                                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'phlox'), get_the_author() ); ?>" >
                                        <?php the_author(); ?>
                                    </a>
                                </span>
                                <?php edit_post_link( __('Edit', 'phlox'), '<i> | </i>', '' ); ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div><!-- end title entry -->
                    <?php
                        $arrow_color_style =  'light' == $args['color_style'] ? 'aux-white' : '';
                        $arrow_hover_color_style =  'light' == $args['color_style'] ? '' : 'aux-white';
                    ?>

                    <?php if ( $args['bg_parallax'] ) {?>
                    <div class="aux-title-scroll-down aux-parallax" data-parallax-depth="1">
                    <?php } else { ?>
                    <div class="aux-title-scroll-down">
                    <?php } ?>
                         <div class="aux-down-arrow aux-arrow-nav aux-round aux-outline <?php echo $arrow_color_style; ?>">
                                <span class="aux-overlay"></span>
                                <span class="aux-svg-arrow aux-h-medium-down prim-arrow <?php echo $arrow_color_style; ?>"></span>
                                <span class="aux-hover-arrow aux-svg-arrow aux-h-medium-down prim-arrow <?php echo $arrow_hover_color_style; ?>"></span>
                        </div>
                    </div>
                </div>

                <?php
                    if( ! empty( $args['overlay_color'] ) ){
                        echo '<div class="aux-header-overlay" style="background-color: ' . $args['overlay_color'] . '"></div>';
                    }
                ?>

            </div><!-- end page header -->
        </header> <!-- end page header -->
        <?php
    }

}



/**
 * Display the slider for the page
 *
 * @since 2.0.0
 *
 * @return string   The custom background styles for the page
 */
if( ! function_exists( 'auxin_the_header_slider' ) ){

    function auxin_the_header_slider(){
        global $post;

        if( ! is_object( $post ) )
            return '';

        // get slider ID
        $slider_slug = get_post_meta( $post->ID, 'top_slider_id', true );

        // get slider layout
        $slider_width   = get_post_meta( $post->ID, 'top_slider_width'  , true ) ;
        $slider_divider = get_post_meta( $post->ID, 'top_slider_divider', true ) ;

        $container_class   = array('aux-top-slider', 'aux-sep-' . $slider_divider );

        $container_class = apply_filters( 'auxin_header_slider_class', $container_class, $post, $slider_slug, $slider_width );
        if( is_array( $container_class ) ){
            $container_class = join( ' ', $container_class );
        }

        $fold_class = $slider_width == 'boxed' ? 'aux-fold' : '';

        $wrapper_start_tag = '<div id="site_topslider" class="'.$container_class.'" ><div class="aux-wrapper"><div class="container '. $fold_class .'">';
        $wrapper_end_tag   = '</div></div></div>';

        do_action( 'auxin_the_header_slider', $post, $slider_slug );


        if( empty( $slider_slug ) || $slider_slug == 'none' )
            return '';

        // the slider is flex or nivo slider
        if( is_numeric( $slider_slug ) ){

            // echo slider wrapper
            echo $wrapper_start_tag;

            $slider_options = get_post_meta( $slider_slug, 'slider-data', true );

            if( $slider_options["type"] == 'flex' )
                echo do_shortcode('[the_flexslider id="'.$slider_slug.'" ]');
            elseif($slider_options["type"] == 'nivo' )
                echo do_shortcode('[the_nivoslider id="'.$slider_slug.'" ]');

            // action to display more slider types here
            do_action("auxin_the_header_builtin_slider", $slider_options["type"], $slider_slug);

            echo $wrapper_end_tag;

        // if the slider is cute slider
        } elseif( substr( $slider_slug, 0, 3 ) == "ms_" ){
            echo $wrapper_start_tag;

            $ms_id = substr($slider_slug, 3);
            echo do_shortcode( '[masterslider id="'.$ms_id.'" ]' );

            echo $wrapper_end_tag;

        // if the slider is layer slider
        } elseif( substr( $slider_slug, 0, 3 ) == "ls_" ){
            echo $wrapper_start_tag;

            $ls_id = substr( $slider_slug, 3 );
            echo do_shortcode( '[layerslider id="'.$ls_id.'" ]' );

            echo $wrapper_end_tag;

        // if the slider is cute slider
        } elseif( substr( $slider_slug, 0, 3 ) == "cs_" ){
            echo $wrapper_start_tag;

            $cs_id = substr($slider_slug, 3);
            echo do_shortcode('[cuteslider id="'.$cs_id.'" ]');

            echo $wrapper_end_tag;

        // the slider is revolution slider
        } elseif ( class_exists( 'RevSlider' ) ){
            echo '<div id="site_topslider" class="aux-top-slider" >';

            putRevSlider( $slider_slug );

            echo $wrapper_end_tag;
        }

    }

}

/**
 * Retrieves the markup of archive post slider section
 *
 * @param  string $post_type The post type for post slider. Default 'post'
 * @param  string $location  The requested location of post slider, possible values: 'content', 'block'
 * @return string            The markup of section
 */
function auxin_get_the_archive_slider( $post_type = 'post', $request_for_location = 'block' ){
    // @TODO - slider for other post types

    // skip if auxin elements plugin is not active
    if ( ! defined('AUXELS_VERSION') || !auxin_get_option( 'post_archive_slider_show' ) ) {
        return '';
    }
    // get and compare the allowed location for archive post slider
    if( $request_for_location !== ( $allowed_location = auxin_get_option( 'post_archive_slider_location' ) ) ){
        return '';
    }

    // get the slug of page template
    $page_template_slug  = is_page_template() ? get_page_template_slug( get_queried_object_id() ) : '';
    // whether the current page is a blog page template or not
    $is_blog_template    = ! empty( $page_template_slug ) && false !== strpos( $page_template_slug, 'blog-type' );


    if( ( $is_blog_template || ( is_home() && !is_paged() ) || ( !is_category() && !is_paged() && !is_tag() && !is_author() && is_archive() && !is_date() ) ) ) {

        if( 'content' == $request_for_location ){
            // insert post slider at top of blog archive page
            return '<div class="aux-archive-post-slider aux-wrapper-post-slider">' . auxin_get_latest_posts_slider() . '</div>';

        } else if( 'block' == $request_for_location ){

            if ( auxin_is_plugin_active( 'woocommerce/woocommerce.php') && ( is_shop() || is_product_category() || is_product_tag() ) ) {
                return '';
            } else{
                $result  = '<div class="aux-top-slider aux-top-post-slider aux-wrapper-post-slider" ><div class="aux-wrapper"><div class="container aux-fold">';
                $result .= auxin_get_latest_posts_slider();
                $result .= '</div></div></div>';
                return $result;
            }

        }

    }

    return null;
}


/**
 * Retrieves the header layout
 *
 * @param  string $layout The layout name
 * @return string         Markup for header section
 */
function auxin_get_header_layout( $layout = '' ){

    if( empty( $layout ) ){
        $layout = auxin_get_top_header_layout();
    }

    $header_position = auxin_get_option( 'site_header_position', 'top' );  // header position on desktop size (top, right, left)

    $mobile_menu_position = auxin_get_option( 'site_header_mobile_menu_position', 'toggle-bar' );  // the menu position on mobile and tablet size (toggle-bar, offcanvas, overlay, none)
    // the max width of logo image
    $logo_max_width = auxin_get_option( 'header_logo_width', '' );


    $logo_to_scale = auxin_get_option( 'site_header_logo_can_scale', true ) ? 'aux-scale' : '';
    $add_search    = auxin_get_option( 'site_header_search_button' );
    $logo_markup   = auxin_get_logo_block( array(
        'css_class' => 'aux-logo-header-inner ' . $logo_to_scale,
        'max_width' => $logo_max_width,
        'middle'    => true,
        'logo_type' => 'primary' // get header logo
    ));

    // whether locate submenu below the menu items.
    $submenu_below = auxin_get_option( 'site_header_navigation_sub_location', 'below-menu-item' ) == 'below-menu-item';

    ob_start();

    switch ( $layout ) {

        case 'horizontal-menu-left':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-end aux-fill aux-tablet-center aux-phone-center">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-end aux-phone-on aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>">
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>

                    <!-- menu -->
                    <div class="aux-menu-box aux-phone-off aux-auto-locate aux-start <?php echo $submenu_below ? 'aux-middle aux-tabel-center-middle' : 'aux-fill aux-tablet-center'; ?>" data-tablet=".aux-header .secondary-bar-2">
                    <?php
                        /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
                        wp_nav_menu( array(
                            'container_id'   => 'master-menu-main-header',
                            'theme_location' => 'header-primary'
                        ));
                    ?>
                    </div>
                    <?php if ( $add_search ) { ?>
                     <!-- search -->
                    <div class="aux-search-box aux-desktop-on aux-start aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>

                </div>
                <!-- secondary bar: this element will be filled in tablet size -->
                <div class="bottom-bar secondary-bar secondary-bar-2  aux-tablet-on aux-float-wrapper"></div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>
            </div>
            <?php

            break;

        case 'burger-right':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-start aux-fill aux-tablet-center aux-phone-center">
                        <?php echo $logo_markup; ?>
                    </div>

                    <?php if ( $add_search ) { ?>
                     <!-- search -->
                    <div class="aux-search-box aux-desktop-on aux-end aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-end aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>" >
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>

                    <div class="aux-menu-box aux-off aux-auto-locate aux-end aux-fill aux-tablet-center" >
                    <?php
                        /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
                        wp_nav_menu( array(
                            'container_id'   => 'master-menu-main-header',
                            'theme_location' => 'header-primary',
                            'mobile_under'   => 7000
                        ));
                    ?>
                    </div>

                </div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>

            </div>
            <?php

            break;

        case 'burger-left':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-end aux-fill aux-tablet-center aux-phone-center">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-start aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>">
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>
                    <?php if ( $add_search ) { ?>
                     <!-- search -->
                    <div class="aux-search-box aux-desktop-on aux-start aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>

                    <div class="aux-menu-box aux-off aux-auto-locate aux-start aux-fill aux-tablet-center" >
                    <?php
                        /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
                        wp_nav_menu( array(
                            'container_id'   => 'master-menu-main-header',
                            'theme_location' => 'header-primary',
                            'mobile_under'   => 7000
                        ));
                    ?>
                    </div>

                </div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>

            </div>
            <?php

            break;

        case 'horizontal-menu-center':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-fill aux-center aux-phone-center">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-start aux-phone-on aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>">
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>
                    <?php if ( $add_search ) { ?>
                     <!-- search -->
                    <div class="aux-search-box aux-desktop-on aux-end aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>

                </div>

                <div class="bottom-bar secondary-bar aux-phone-off aux-float-wrapper">
                    <!-- menu -->
                    <div class="aux-menu-box <?php echo $submenu_below ? 'aux-middle aux-center-middle' : 'aux-fill aux-center'; ?>">
<?php
    /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
    wp_nav_menu( array( 'container_id' => 'master-menu-main-header', 'theme_location' => 'header-primary' ) );
?>
                    </div>
                </div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>
            </div>
            <?php

            break;

        case 'logo-in-middle-menu':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-center aux-fill">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-end aux-phone-on aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>">
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>
                    <!-- menu -->
                    <div class="aux-menu-box aux-phone-off aux-auto-locate aux-center-middle aux-phone-center-middle aux-fill aux-tablet-center" data-tablet=".aux-header .secondary-bar-2">
<?php
    /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
    wp_nav_menu( array( 'container_id' => 'master-menu-main-header', 'theme_location' => 'header-primary' ) );
?>
                    </div>

                </div>
                <!-- secondary bar: this element will be filled in tablet size -->
                <div class="bottom-bar secondary-bar secondary-bar-2  aux-tablet-on aux-float-wrapper"></div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>
            </div>
            <?php

            break;

        case 'logo-left-menu-right-over':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout aux-over-content">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-start aux-fill aux-tablet-center aux-phone-center">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-start aux-phone-on aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>" data-target-menu="overlay" >
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>
                    <?php if ( $add_search ) { ?>
                    <!-- search -->
                    <div class="aux-search-box aux-desktop-on aux-end aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>
                    <!-- menu -->
                    <div class="aux-menu-box aux-phone-off aux-auto-locate aux-end <?php echo $submenu_below ? 'aux-middle aux-tablet-center-middle' : 'aux-fill aux-tablet-center'; ?>" data-tablet=".aux-header .secondary-bar">
                    <?php
                        /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
                        wp_nav_menu( array( 'container_id' => 'master-menu-main-header', 'theme_location' => 'header-primary' ) );
                    ?>
                    </div>

                </div>
                <!-- secondary bar: this element will be filled in tablet size -->
                <div class="bottom-bar secondary-bar aux-tablet-on aux-float-wrapper"></div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>
            </div>
            <?php

            break;


        case 'logo-left-menu-bottom-left':

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-start aux-fill">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-end aux-phone-on aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>" data-target-menu="overlay" >
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>
                    <?php if ( $add_search ) { ?>
                    <!-- search -->
                    <div class="aux-search-box aux-phone-off aux-end aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>
                </div>
                <!-- secondary bar: this element will be filled in tablet size -->
                <div class="bottom-bar secondary-bar aux-float-wrapper aux-sticky-off aux-phone-off">
                    <!-- menu -->
                    <div class="aux-menu-box aux-phone-off aux-auto-locate aux-start <?php echo $submenu_below ? 'aux-middle' : 'aux-fill'; ?>" data-sticky-move="#logo" data-sticky-move-method="after">
                    <?php
                        /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
                        wp_nav_menu( array( 'container_id' => 'master-menu-main-header', 'theme_location' => 'header-primary' ) );
                    ?>
                    </div>
                </div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>
            </div>
            <?php

            break;


        case 'horizontal-menu-right':
        default:

            ?>
            <div class="aux-header aux-header-elements-wrapper aux-float-layout">
                <!-- ribbon bar -->
                <div class="aux-header-elements">

                    <!-- logo -->
                    <div id="logo" class="aux-logo-header aux-start aux-fill aux-tablet-center aux-phone-center">
                        <?php echo $logo_markup; ?>
                    </div>
                    <!-- burger -->
                    <div id="nav-burger" class="aux-burger-box aux-start aux-phone-on aux-middle" data-target-panel="<?php echo $mobile_menu_position; ?>" data-target-menu="overlay" >
                        <div class="aux-burger aux-lite-small"><span class="mid-line"></span></div>
                    </div>
                    <?php if ( $add_search ) { ?>
                    <!-- search -->
                    <div class="aux-search-box aux-desktop-on aux-end aux-middle">
                        <?php echo auxin_get_search_box( array( 'has_form' => false, 'has_toggle_icon' => true, 'toggle_icon_class' => 'aux-overlay-search' ) );?>
                    </div>
                    <?php } ?>
                    <!-- menu -->
                    <div class="aux-menu-box aux-phone-off aux-auto-locate aux-end <?php echo $submenu_below ? 'aux-middle aux-tablet-center-middle' : 'aux-fill aux-tablet-center'; ?>" data-tablet=".aux-header .secondary-bar">
                    <?php
                        /* The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.*/
                        wp_nav_menu( array( 'container_id' => 'master-menu-main-header', 'theme_location' => 'header-primary' ) );
                    ?>
                    </div>

                </div>
                <!-- secondary bar: this element will be filled in tablet size -->
                <div class="bottom-bar secondary-bar aux-tablet-on aux-float-wrapper"></div>

                <!-- toggle menu bar: this element will be filled in tablet and mobile size -->
                <div class="aux-toggle-menu-bar"></div>
            </div>
            <?php

            break;
    }

    return ob_get_clean();
}


/**
 * Retrieves the markup for logo element
 *
 * @param  array  $args The properties fort this element
 *
 * @return string       The markup for logo element
 */
function auxin_get_logo_block( $args = array() ){

    $defaults = array(
        'max_width'      => '',
        'max_height'     => '',
        'css_class'      => '',
        'logo_link'      => '',
        'logo_title'     => '',
        'logo_desc'      => '',
        'logo_type'      => 'primary',
        'show_logo_text' => true,
        'middle'         => true
    );

    $args = wp_parse_args( $args, $defaults );

ob_start();
?>
    <div class="aux-logo <?php echo $args['css_class']; ?>">

        <?php
        $logo_title        = $args['logo_title'] ? $args['logo_title'] : get_bloginfo( 'name', 'display' );
        $logo_link         = $args['logo_link']  ? esc_url( $args['logo_link'] )  : home_url( '/' );

        $logo_main_srcs    = _auxin_get_logo_main_image_src( true, $args['logo_type'] );
        $logo_invert_srcs  = _auxin_get_logo_invert_image_src( true, $args['logo_type'] );

        $logo_image        = $logo_main_srcs[2];
        $logo_image_invert = $logo_invert_srcs[2];


        $inline_anchor_style = '';
        if( ( $logo_image || $logo_image_invert ) && ( $args['max_width'] || $args['max_height'] ) ){
            $inline_anchor_style .= $args['max_width' ] ? 'max-width:' . trim( $args['max_width'] , 'px' ) .'px;' : '';
            $inline_anchor_style .= $args['max_height'] ? 'max-height:'. trim( $args['max_height'], 'px' ) .'px;' : '';

        ?>
        <a class="aux-logo-anchor <?php echo ($args['middle'] ? 'aux-middle' : ''); ?>" href="<?php echo $logo_link; ?>" title="<?php echo esc_attr( $logo_title ); ?>" rel="home" style="<?php echo $inline_anchor_style; ?>">
            <?php echo _auxin_get_logo_image( $args['logo_title'], $args['logo_type'] ); ?>
        </a>
        <?php
        }

        if( $args['show_logo_text'] ){
            $logo_desc           = $args['logo_desc']  ? $args['logo_desc' ] : get_bloginfo( 'description' );
        ?>
        <section class="aux-logo-text <?php echo ($args['middle'] ? 'aux-middle' : ''); ?>">
            <h3 class="site-title">
                <a href="<?php echo $logo_link; ?>" title="<?php echo esc_attr( $logo_title ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
            </h3>
            <?php if( $logo_desc ){ echo '<p class="site-description">' . $logo_desc . '</p>'; } ?>
        </section>
        <?php } ?>

    </div><!-- end logo aux-fold -->

<?php
    return ob_get_clean();
}


/**
 * Retrieves the logo image tag
 *
 * @param  string $title The logo title
 * @param  strint $type  Which logo we intent to get. possible values 'primary'(header), 'secondary'(footer)
 *
 * @return string        The markup for logo image
 */
function _auxin_get_logo_image( $title = '', $type = 'primary' ){

    $title               = $title ? $title : get_bloginfo( 'name', 'display' );

    $logo_main_srcs      = _auxin_get_logo_main_image_src( true, $type );
    $data_logo_2x        = 'data-image2x="'. $logo_main_srcs[1] . '"';

    $logo_invert_srcs    = _auxin_get_logo_invert_image_src( true, $type );
    $data_logo_invert_2x = 'data-image2x="'. $logo_invert_srcs[1] . '"';

    $logo_image = '';

    if( $logo_main_srcs[2]   ){ $logo_image .= sprintf( '<img src="%s" class="aux-logo-image aux-logo-dark"  %s alt="%s" />',  $logo_main_srcs[2] , $data_logo_2x       , esc_attr( $title ) ); }
    if( $logo_invert_srcs[2] ){ $logo_image .= sprintf( '<img src="%s" class="aux-logo-image aux-logo-light" %s alt="%s" />', $logo_invert_srcs[2], $data_logo_invert_2x, esc_attr( $title ) ); }

    return $logo_image;
}


/**
 * Whether to return 1x image size or both 1x and 2x size
 *
 * @param  boolean $single Whether to return just 1x image size or both 1x and 2x size
 * @param  strint  $type   Which logo we intent to get. possible values 'primary', 'secondary'
 *
 * @return string|array          The 1x image src or the source of other sizes
 */
function _auxin_get_logo_main_image_src( $single = true, $type = 'primary' ){

    $logo_image_1x = 'secondary' !== $type ? auxin_get_option('site_logo_image') : auxin_get_option('site_secondary_logo_image');
    $logo_image_1x = auxin_get_attachment_url( $logo_image_1x, 'full' );

    if( ! $single ){
        return $logo_image_1x;

    } else {
        $logo_image_2x = auxin_get_option('site_logo_image_2x');
        $logo_image_2x = auxin_get_attachment_url( $logo_image_2x, 'full' );

        $logo_image    = empty( $logo_image_1x ) ? $logo_image_2x : $logo_image_1x;

        return array( $logo_image_1x, $logo_image_2x, $logo_image );
    }
}


/**
 * Whether to return 1x image size or both 1x and 2x size
 *
 * @param  boolean $single Whether to return just 1x image size or both 1x and 2x size
 * @param  strint  $type   Which logo we intent to get. possible values 'primary', 'secondary'
 *
 * @return string|array          The 1x image src or the source of other sizes
 */
function _auxin_get_logo_invert_image_src( $single = true, $type = 'primary' ){

    $logo_image_invert_1x = 'secondary' !== $type ? auxin_get_option('site_logo_image_invert') : auxin_get_option('site_secondary_logo_image_invert');
    $logo_image_invert_1x = auxin_get_attachment_url( $logo_image_invert_1x, 'full' );

    if( ! $single ){
        return $logo_image_invert_1x;

    } else {
        $logo_image_invert_2x = auxin_get_option('site_logo_image_invert_2x');
        $logo_image_invert_2x = auxin_get_attachment_url( $logo_image_invert_2x, 'full' );

        $logo_image_invert    = empty( $logo_image_invert_1x ) ? $logo_image_invert_2x : $logo_image_invert_1x;

        return array( $logo_image_invert_1x, $logo_image_invert_2x, $logo_image_invert );
    }
}




/**
 * Returns markup for search box
 *
 * @param  array  $args [description]
 * @return [type]       [description]
 */
function auxin_get_search_box( $args = array() ){

    $defaults = array(
        'id'                => '',
        'has_toggle_icon'   => true,
        'has_field'         => true,
        'has_submit'        => true,
        'has_form'          => true,
        'has_submit_icon'   => false, // this option added for changing submit type
        'css_class'         => '',
        'toggle_icon_class' => ''
    );

    $args = wp_parse_args( $args, $defaults );
    $id_attr = !empty( $args['id']) ? 'id="' . $args['id'] . '"' : '';

ob_start();
?>
    <div <?php echo $id_attr; ?> class="aux-search-section <?php echo esc_attr( $args[ 'css_class' ] ); ?>">
    <?php if( $args['has_toggle_icon'] ){ ?>
        <button class="aux-search-icon auxicon-search-4 <?php echo esc_attr( $args[ 'toggle_icon_class' ] ); ?> "></button>
    <?php } ?>
    <?php if( $args['has_form'] ){ ?>
        <div <?php  ?> class="aux-search-form <?php echo $args['has_submit_icon'] ? 'aux-iconic-search' : '' ?>">
            <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" >
            <?php if( $args['has_field'] ){
                $placeholder = $args['has_submit_icon'] ? __('Search...', 'phlox') : __('Type here ..', 'phlox');
            ?>
                <input type="text" class="aux-search-field"  placeholder="<?php echo esc_attr( $placeholder ); ?>" name="s" />
            <?php } ?>
            <?php if( $args['has_submit_icon'] ){ ?>
                <div class="aux-submit-icon-container">
                    <input type="submit" class="aux-iconic-search-submit" value="<?php esc_attr_e( 'Search', 'phlox' ); ?>" >
                </div>
            <?php } else if( $args['has_submit'] ){ ?>
                <input type="submit" class="aux-black aux-search-submit aux-uppercase" value="<?php esc_attr_e( 'Search', 'phlox' ); ?>" >
            <?php } ?>
            </form>
        </div><!-- end searchform -->
    <?php } ?>
    </div>

<?php
    return ob_get_clean();
}



