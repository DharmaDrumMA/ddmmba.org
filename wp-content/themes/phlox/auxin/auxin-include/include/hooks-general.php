<?php
/**
 * General hooks
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


/*-----------------------------------------------------------------------------------*/
/*  Setup Header
/*-----------------------------------------------------------------------------------*/

function auxin_after_setup_theme(){
    $auxin_support = auxin_theme_support();

    if ( function_exists( 'add_theme_support' ) ) {

        foreach ( $auxin_support as $the_theme_support ) {
            if( $the_theme_support ){

                /*  Add Post Thumbnails
                /*----------------------------*/
                if( 'post-thumbnails' == $the_theme_support ){
                    $post_types = apply_filters( "auxin_post_thumbnails" , auxin_get_possible_post_types(true) );
                    $post_types = array_merge( $post_types, array('post', 'page') );
                    add_theme_support( 'post-thumbnails' , $post_types );
                    continue;
                }

                /*  Add Post Formats
                /*----------------------------*/
                if( 'post-formats' == $the_theme_support ){
                    add_theme_support( 'post-formats',
                        apply_filters( 'auxin_post_formats',
                            array( 'aside', 'gallery', 'image', 'link', 'quote', 'video', 'audio' )
                        )
                    );
                    continue;
                }

                add_theme_support( $the_theme_support );
            }
        }

        /*  Adds RSS feed links to <head> for posts and comments.
        /*-------------------------------------------------------------------------*/
        add_theme_support( 'automatic-feed-links' );

        add_theme_support('title-tag');

    }
    set_post_thumbnail_size( 280, 180, true ); // Featured image display size


    /*  Config excerpts length
    /*------------------------------------------------------------------------------*/
    add_filter( 'the_content_more_link'  , 'auxin_change_content_more_link'     , 15, 3 );
    add_filter( 'the_content_more_link'  , 'auxin_change_trim_excerpt_more_link', 20, 3 );

    // gererate shortcodes in widget text
    add_filter('widget_text', 'do_shortcode');

    // @Deprecate WP Version 5.0
    global $wp_version;
    if ( version_compare( $wp_version, '4.6', '<' ) ) {

        /*  Make theme available for translation
        /*------------------------------------------------------------------------------*/
        /* Translations can be added to the /languages/ directory. */
        if( ! load_theme_textdomain( 'phlox', get_stylesheet_directory() . '/languages' ) )
              load_theme_textdomain( 'phlox', get_template_directory()   . '/languages' );
    }

}

add_action( 'after_setup_theme', 'auxin_after_setup_theme' );


 add_filter( 'wp_link_pages_link', function( $link, $pos ){
    global $page, $numpages;

    if( $pos == $page ){
        $link = '<span class="aux-page-current">' . $link . '</span>';
    }

    return $link;
 }, 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Declare nav menu
/*-----------------------------------------------------------------------------------*/

function auxin_declare_nav_menu(){
    /*  Register Theme menus
    /*------------------------------------------------------------------------------*/

    // Adds Header menu
    register_nav_menu( 'header-primary'     , __( 'Header Primary Navigation'  , 'phlox') );
    register_nav_menu( 'header-secondary'   , __( 'Header Secondary Navigation', 'phlox') );

    // adds Footer menu
    register_nav_menu( 'footer'  , __( 'Footer Navigation', 'phlox') );
}

add_action( 'after_setup_theme', 'auxin_declare_nav_menu' );

/*-----------------------------------------------------------------------------------*/
/*  Get data about current main/parent theme
/*-----------------------------------------------------------------------------------*/

function auxin_get_main_theme(){
    $theme = wp_get_theme();
    // always use parent theme data - averta
    if( is_child_theme() )
        $theme = wp_get_theme( $theme->template );
    return $theme;
}


/*----------------------------------------------------------------------------*/
/*  Set content width
/*----------------------------------------------------------------------------*/

function auxin_set_content_width(){
    global $post;

    // the theme wrapper width
    $theme_width_name = auxin_get_option( 'site_max_width_layout', 'hd' );
    $theme_width_list = auxin_theme_width_list();

    $theme_width = isset( $theme_width_list[ $theme_width_name ] ) ? $theme_width_list[ $theme_width_name ] : 1200;

    // calculate the content width if there is sibebar on page
    $sidebar_num = (int) auxin_has_sidebar( $post );
    if( $sidebar_num ){
        if( 1 === $sidebar_num ){
            $theme_width -= 300;
        } elseif( 2 === $sidebar_num ){
            $theme_width -= 560;
        }
    }

    global $aux_content_width, $content_width;

    $aux_content_width = $theme_width;
    $content_width     = empty( $content_width ) ? $theme_width : $content_width;
}

add_action( 'wp', 'auxin_set_content_width' );


/*-----------------------------------------------------------------------------------*/
/*  Add some user contact fields
/*-----------------------------------------------------------------------------------*/

function auxin_user_contactmethods($user_contactmethods){
    $user_contactmethods['twitter']    = __('Twitter'    , 'phlox');
    $user_contactmethods['facebook']   = __('Facebook'   , 'phlox');
    $user_contactmethods['googleplus'] = __('Google Plus', 'phlox');
    $user_contactmethods['flickr']     = __('Flickr'     , 'phlox');
    $user_contactmethods['delicious']  = __('Delicious'  , 'phlox');
    $user_contactmethods['pinterest']  = __('Pinterest'  , 'phlox');
    $user_contactmethods['github']     = __('GitHub'     , 'phlox');
    $user_contactmethods['skills']     = __('Skills'     , 'phlox');

    return apply_filters("auxin_user_contactmethods", $user_contactmethods);
}

add_filter('user_contactmethods', 'auxin_user_contactmethods');


/*-----------------------------------------------------------------------------------*/
/*  Front end query modifications
/*-----------------------------------------------------------------------------------*/

function auxin_front_end_update_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;


    if (is_post_type_archive('portfolio')){
        $perpage = auxin_get_option("portfolio_index_page_items_perpage");
        $perpage = (int)$perpage < 1?-1:$perpage;

        $query->set( 'posts_per_page', $perpage );
        return;
    }

    if ( $query->is_tax('portfolio-cat') || $query->is_tax('portfolio-tag') ) {
        $perpage = auxin_get_option("portfolio_category_page_items_perpage");
        $perpage = (int)$perpage < 1?-1:$perpage;

        $query->set( 'posts_per_page', $perpage );
        return;
    }

    if ( is_post_type_archive('axi_product') ) {
        $perpage = auxin_get_option("product_index_page_items_perpage");
        $perpage = (int)$perpage < 1?-1:$perpage;

        $query->set( 'posts_per_page', $perpage );
        return;
    }

    if ( $query->is_tax('product-category') || $query->is_tax('product-tag') ) {
        $perpage = auxin_get_option("product_category_page_items_perpage");
        $perpage = (int)$perpage < 1?-1:$perpage;

        $query->set( 'posts_per_page', $perpage );
        return;
    }

    if ( is_post_type_archive('news') ) {
        $query->set( 'orderby', 'menu_order date' );
        return;
    }

    if ( $query->is_tax('news-category') || $query->is_tax('news-tag') ) {
        $query->set( 'orderby', 'menu_order date' );
        return;
    }

    if ( is_post_type_archive('service') ) {
        $query->set( 'posts_per_page', 12 );
        return;
    }

    if ( $query->is_tax('service-category') ) {
        $query->set( 'posts_per_page', 12 );
        return;
    }

    if ( is_post_type_archive('faq') ) {
        $query->set( 'posts_per_page', 30 );
        return;
    }

    if ( $query->is_tax('faq-category') ) {
        $query->set( 'posts_per_page', 20 );
        return;
    }

    if ( is_post_type_archive('staff') ) {
        $query->set( 'posts_per_page', 12 );
        return;
    }

    if ( $query->is_tax('department') ) {
        $query->set( 'posts_per_page', 12 );
        return;
    }

    if ( is_post_type_archive('testimonial') ) {
        $query->set( 'posts_per_page', 20 );
        return;
    }

    // Filter Search and only display results from main post types
     if ( $query->is_search ) {
        $query->set(
            'post_type', array( 'page', 'post', 'portfolio', 'axi_product', 'staff', 'service', 'testimonial', 'news', 'faq' )
        );
        return;
    }

}
add_action( 'pre_get_posts', 'auxin_front_end_update_query', 1 );


/*-----------------------------------------------------------------------------------*/


/**
 * Retrieve the custom background styles for the page
 *
 * @since 2.0.0
 *
 * @return string   The custom background styles for the page
 */
function auxin_get_page_background_style( $css, $post ){
    global $post;

    if( empty( $post ) || is_404() || ! is_singular() )
        return '';

    $output = "";

    if( auxin_is_true( auxin_get_post_meta( $post->ID, 'aux_custom_bg_show', true ) == 1 ) ){
        $styles = auxin_generate_styles_for_backgroud_fields(
            'aux_custom_bg',
            'post_meta'
        );
        $output = "\thtml body {\t" . $styles . "} \n";
    }

    if( ! empty( $output ) ){
        $output = stripslashes( $output );
    }

    return $output;
}

add_filter( 'auxin_header_inline_styles', 'auxin_get_page_background_style', 10, 2 );


/*-----------------------------------------------------------------------------------*/
/*  Remove all auto generated p tags & line breaks beside shortcode content
/*-----------------------------------------------------------------------------------*/

function auxin_cleanup_beside_shortcodes($content){
    $array = array (
        '<p>['    => '[',
        ']</p>'   => ']',
        ']<br />' => ']'
    );
    $content = strtr( $content, $array );
    return $content;
}

add_filter('the_content', 'auxin_cleanup_beside_shortcodes');


/*-----------------------------------------------------------------------------------*/
/*  Allow shortcode functioning as value of srcset over img tags
/*-----------------------------------------------------------------------------------*/
function auxin_allow_img_srcset_shortcode( $allowedposttags, $context ) {
    if ( 'post' == $context ) {
        $allowedposttags['img']['srcset'] = 1;
    }
    return $allowedposttags;
}

add_filter( 'wp_kses_allowed_html', 'auxin_allow_img_srcset_shortcode', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/*  Filtering wp_title to improve seo and letting seo plugins to filter the output too
/*-----------------------------------------------------------------------------------*/

if( ! defined( 'WPSEO_VERSION') ){

    function auxin_wp_title($title, $sep, $seplocation) {
        global $page, $paged, $post;

        // Don't affect feeds
        if ( is_feed() )  return $title;

        // Add the blog name
        if ( 'right' == $seplocation )
            $title  .= get_bloginfo( 'name' );
        else
            $title   = get_bloginfo( 'name' ) . $title;

        // Add the blog description for the home/front page
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title .= " $sep $site_description";

        // Add a page number if necessary
        if ( $paged >= 2 || $page >= 2 )
            $title .= " $sep " . sprintf( __( 'Page %s', 'phlox'), max( $paged, $page ) );

        return $title;
    }

    add_filter( 'wp_title', 'auxin_wp_title', 10, 3 );
}


/*-----------------------------------------------------------------------------------*/
/*  add excerpts to pages
/*-----------------------------------------------------------------------------------*/

function auxin_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'auxin_add_excerpts_to_pages' );

/*-----------------------------------------------------------------------------------*/
/*  Add layout and other required classes to body tag
/*-----------------------------------------------------------------------------------*/

function auxin_body_class( $classes ) {
    if( is_string( $classes ) ){
        $classes = array( $classes );
    }

    $classes[] = 'boxed' == auxin_get_option( 'site_wrapper_size') ? 'aux-boxed' : '';
    $classes[] = auxin_get_option( 'enable_site_reponsiveness', true ) ? 'aux-resp' : '';
    $classes['max_width_layout'] = 'aux-' . auxin_get_option( 'site_max_width_layout', 'hd' );

    if( auxin_get_option( 'site_header_top_sticky', true ) ){
        $classes['top_header_sticky'] = 'aux-top-sticky';
    }


    return $classes;
}

add_filter( 'body_class', 'auxin_body_class', 12 );


/*-----------------------------------------------------------------------------------*/
/*  Apply shortcodes to widgets
/*-----------------------------------------------------------------------------------*/

add_filter( 'widget_text', 'do_shortcode');
add_filter( 'the_excerpt', 'do_shortcode');


/*-----------------------------------------------------------------------------------*/
/*  the function runs when auxin framework loaded
/*-----------------------------------------------------------------------------------*/

function auxin_on_auxin_fw_admin_loaded(){

    // assign theme custom capabilities to roles on first run
    if( ! get_option( THEME_ID.'_are_caps_assigned', 0 ) ){
        add_action( 'admin_init'  , 'auxin_assign_default_caps_for_post_types' );
        update_option( THEME_ID.'_are_caps_assigned', 1 );
    }
}

add_action( 'auxin_admin_loaded', 'auxin_on_auxin_fw_admin_loaded' );


    /*-------------------------------------------------------------------------------*/
    /*  assigns theme custom post types capabilities to main roles
    /*-------------------------------------------------------------------------------*/

    function auxin_assign_default_caps_for_post_types() {
        $auxin_registered_post_types = auxin_registered_post_types(true);

        // the roles to add capabilities of custom post types to
        $roles = array('administrator', 'editor');

        foreach ( $roles as $role_name ) {

            $role = get_role( $role_name );

            // loop through custom post types and add custom capabilities to defined rules
            foreach ( $auxin_registered_post_types as $post_type ) {

                $post_type_object = get_post_type_object( $post_type );
                // add post type capabilities to role
                foreach ( $post_type_object->cap as $cap_key => $cap ) {
                    if( ! in_array( $cap_key, array( 'edit_post', 'delete_post', 'read_post' ) ) )
                        $role->add_cap( $cap );
                }
            }

            $role_caps = apply_filters( 'auxin_capabilities', array('manage_auxin'), $role_name );

            foreach ( $role_caps as $role_cap_id => $role_cap ) {
                $role->add_cap( $role_cap );
            }

        }
    }
/*-----------------------------------------------------------------------------------*/
/*  Adds comment-replay feature
/*-----------------------------------------------------------------------------------*/

function auxin_comment_features(){
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

add_action( 'wp_head', 'auxin_comment_features' );

/*-----------------------------------------------------------------------------------*/
/*  Adds the custom CSS class of the page to body element
/*-----------------------------------------------------------------------------------*/

function auxin_page_custom_css_class( $classes ){
    global $post;

    if( empty( $post ) || is_404() ){
        return $classes;
    }

    if( $body_class = get_post_meta( $post->ID, 'aux_custom_body_class', true ) ){

        $classes[] = trim( str_replace( '.', ' ', $body_class ) );
    }

    return $classes;
}

add_filter( 'body_class', 'auxin_page_custom_css_class' );

/*-----------------------------------------------------------------------------------*/
/*  Adds title bar and slider to header.php
/*-----------------------------------------------------------------------------------*/

add_action( 'auxin_before_the_content', 'auxin_the_main_title'   , 11 );
add_action( 'auxin_before_the_content', 'auxin_the_header_slider', 12 );
add_action( 'auxin_before_the_content', 'auxin_the_header_archive_slider_block', 13 );

/*-----------------------------------------------------------------------------------*/
/*  Prints the post slider on archive and corresponding pages
/*-----------------------------------------------------------------------------------*/

function auxin_the_header_archive_slider_block(){
    echo auxin_get_the_archive_slider( 'post', 'block' );
}

/*-----------------------------------------------------------------------------------*/
/*  Add home page menu arg to menu item list
/*-----------------------------------------------------------------------------------*/

function auxin_add_home_page_to_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
//add_filter( 'wp_page_menu_args', 'auxin_add_home_page_to_menu_args' );

/*-----------------------------------------------------------------------------------*/
/*  Adds title bar and slider to header.php
/*-----------------------------------------------------------------------------------*/

add_action( 'auxin_before_body_close', 'auxin_add_hidden_blocks' );

/*-----------------------------------------------------------------------------------*/
/*  Adds goto top button to footer.php
/*-----------------------------------------------------------------------------------*/


function auxin_add_goto_top_btn(){
    if ( !auxin_get_option( 'show_goto_top_btn' ) ) {
        return '';
    }

    $goto_top = '<div class="aux-goto-top-btn aux-align-btn-'. auxin_get_option( 'goto_top_alignment' ) .'" data-animate-scroll="'. auxin_get_option('goto_top_animate') .'">'.
        '<div class="aux-hover-slide aux-arrow-nav aux-round aux-outline">'.
        '    <span class="aux-overlay"></span>'.
        '    <span class="aux-svg-arrow aux-h-small-up"></span>'.
        '    <span class="aux-hover-arrow aux-svg-arrow aux-h-small-up aux-white"></span>'.
        '</div>'.
    '</div>';

    echo $goto_top;
}

add_action( 'auxin_before_body_close', 'auxin_add_goto_top_btn' );

/*-----------------------------------------------------------------------------------*/
/*  Init customizer on demand
/*-----------------------------------------------------------------------------------*/

function auxin_init_customizer(){
    if( is_customize_preview() ){
        Auxin_Customizer::get_instance()->maybe_render();
    }
}
add_action( 'init', 'auxin_init_customizer' );


/*-----------------------------------------------------------------------------------*/
/*  Change related posts plugin thumbnail size
/*-----------------------------------------------------------------------------------*/
function rp4wp_custom_thumbnail_size( $thumb_size ){
    return 'medium';
}
add_filter( 'rp4wp_thumbnail_size', 'rp4wp_custom_thumbnail_size' );


/*-----------------------------------------------------------------------------------*/
/*  Remove + symbol from  counter of wp ulike button plugin
/*-----------------------------------------------------------------------------------*/
function wp_ulike_number_format( $value, $num, $plus ){
    if ($num >= 1000 && get_option('wp_ulike_format_number') == '1'):
        $value = round($num/1000, 2) . 'K';
    else:
        $value = $num;
    endif;

    return $value;
}
add_filter('wp_ulike_format_number','wp_ulike_number_format',10,3);