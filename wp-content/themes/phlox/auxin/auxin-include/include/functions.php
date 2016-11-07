<?php
/**
 * General functions here
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


//// verfiy current page id ////////////////////////////////////////////////////////////

if( ! function_exists( "is_currentpage_id" ) ){

    function is_currentpage_id( $id ){
        if( ! function_exists( "get_current_screen" ) )    return true;

        $screen = get_current_screen();
        return is_object( $screen ) && $screen->id == $id  ? TRUE : FALSE;
    }

}

//// is absolute url ///////////////////////////////////////////////////////////////////

/**
 * Whether it's absolute url or not
 *
 * @param  string $url  The URL
 * @return bool   TRUE if the URL is absolute
 */

if( ! function_exists( "auxin_is_absolute_url" ) ){

    function auxin_is_absolute_url( $url ){
        return preg_match( "~^(?:f|ht)tps?://~i", $url );
    }

}

//// finds out if the url contains upload directory path (true, if it's absolute url to internal file)

/**
 * Whether the URL contains upload directory path or not
 *
 * @param  string $url  The URL
 * @return bool   TRUE if the URL is absolute
 */
if( ! function_exists( "auxin_contains_upload_dir" ) ){

    function auxin_contains_upload_dir( $url ){
        $uploads_dir = wp_get_upload_dir();
        return strpos( $url, $uploads_dir['baseurl'] ) !== false;
    }

}

//// create absolute url if the url is relative ////////////////////////////////////////

/**
 * Print absolute URL for media file event if the URL is relative
 *
 * @param  string $url  The link to media file
 * @return void
 */
function auxin_the_absolute_image_url( $url ){
    echo auxin_get_the_absolute_image_url( $url );
}

    /**
     * Get absolute URL for media file event if the URL is relative
     *
     * @param  string $url  The link to media file
     * @return string   The absolute URL to media file
     */
    if( ! function_exists( 'auxin_get_the_absolute_image_url' ) ){

        function auxin_get_the_absolute_image_url( $url ){
            if( ! isset( $url ) || empty( $url ) )    return '';

            if( auxin_is_absolute_url( $url ) || auxin_contains_upload_dir( $url ) ) return $url;

            $uploads = wp_get_upload_dir();
            return trailingslashit( $uploads['baseurl'] ) . $url;
        }

    }

//// create relative url if it's url for internal uploaded file ////////////////////////

/**
 * Print relative URL for media file event if the URL is absolute
 *
 * @param  string $url  The link to media file
 * @return void
 */
function auxin_the_relative_file_url( $url ){
    echo auxin_get_the_relative_file_url( $url );
}

    /**
     * Get relative URL for media file event if the URL is absolute
     *
     * @param  string $url  The link to media file
     * @return string   The absolute URL to media file
     */
    if( ! function_exists( 'auxin_get_the_relative_file_url' ) ){

        function auxin_get_the_relative_file_url( $url ){
            if( ! isset( $url ) || empty( $url ) )     return '';

            // if it's not internal absolute url
            if( ! auxin_contains_upload_dir( $url ) ) return $url;

            $uploads_dir = wp_get_upload_dir();
            return str_replace( trailingslashit( $uploads_dir['baseurl'] ), '', $url );
        }

    }


//// get all registerd siderbar ids ///////////////////////////////////////////////////

if( ! function_exists( 'auxin_get_all_sidebar_ids' ) ){

    function auxin_get_all_sidebar_ids(){
        $sidebars = get_option( THEME_ID.'_sidebars');
        $output   = array();

        if( isset( $auxin_sidebars ) && ! empty( $auxin_sidebars ) ){
            foreach( $sidebars as $key => $value ) {
                $output[] = THEME_ID .'-'. strtolower( str_replace(' ', '-', $value) );
            }
        }
        return $output;
    }

}

//// remove all p tags from string ////////////////////////////////////////////////////

if( ! function_exists( 'auxin_cleanup_content' ) ){

    function auxin_cleanup_content( $content ) {

        /* Remove any instances of '<p>' '</p>'. */
        $content = str_replace( array( '<p>'  ), '', $content );
        $content = str_replace( array( '</p>' ), '', $content );

        return $content;
    }

}

//// remove all auto generated p tags from shortcode content //////////////////////////

if( ! function_exists( "auxin_do_cleanup_shortcode" ) ){

    function auxin_do_cleanup_shortcode( $content ) {

        /* Parse nested shortcodes and add formatting. */
        $content = trim( wpautop( do_shortcode( $content ) ) );

        /* Remove any instances of '<p>' '</p>'. */
        $content = auxin_cleanup_content( $content );

        return $content;
    }

}


function auxin_get_post_meta( $post_id, $meta_id, $default_value = '' ){
    $post = get_post( $post_id );
    if( ! isset( $post ) ) return '';

    $meta_value = get_post_meta( $post->ID, $meta_id, true );
    return '' === $meta_value ? $default_value : $meta_value;
}

/*-----------------------------------------------------------------------------------*/
/*  Get trimmed string
/*-----------------------------------------------------------------------------------*/

function auxin_the_trimmed_string( $string, $max_length = 1000, $more = " ..." ){
    echo auxin_get_trimmed_string( $string, $max_length, $more );
}

    if( ! function_exists( 'auxin_get_trimmed_string' ) ){
        function auxin_get_trimmed_string( $string, $max_length = 1000, $more = " ..." ){
            if( mb_strwidth( $string ) > $max_length ){
                return function_exists( 'mb_strimwidth' ) ? mb_strimwidth( $string, 0, $max_length, '' ) . $more : substr( $string, 0, $max_length ) . $more;
            }
            return $string;
        }
    }

/*-----------------------------------------------------------------------------------*/
/*  Shortcode enabled excerpts trimmed by character length
/*-----------------------------------------------------------------------------------*/

function auxin_the_trim_excerpt( $post_id = null, $char_length = null, $exclude_strip_shortcode_tags = null, $skip_more_tag = false, $wrap_excerpt_with = '' ){
    echo auxin_get_the_trim_excerpt( $post_id, $char_length, $exclude_strip_shortcode_tags, $skip_more_tag, $wrap_excerpt_with );
}

    if( ! function_exists( 'auxin_get_the_trim_excerpt' ) ){

        // make shortcodes executable in excerpt
        function auxin_get_the_trim_excerpt( $post_id = null, $char_length = null, $exclude_strip_shortcode_tags = null, $skip_more_tag = false, $wrap_excerpt_with = '' ) {
            $post = get_post( $post_id );
            if( ! isset( $post ) ) return '';

            // If post password required and it doesn't match the cookie.
            if ( post_password_required( $post ) )
                return get_the_password_form( $post );

            $content      = $post->post_content;
            $content      = apply_filters( 'the_content', $content );

            $excerpt_more = apply_filters( 'excerpt_more', ' ...' );
            $excerpt_more = apply_filters( 'auxin_trim_excerpt_more', $excerpt_more );

            // check for <!--more--> tag
            if ( ! $skip_more_tag && preg_match( '/<!--more(.*?)?-->/', $content, $matches ) ) {
                $content = explode( $matches[0], $content, 2 );

                if ( ! empty( $matches[1] ) ){
                    $more_link_text = strip_tags( wp_kses_no_null( trim( $matches[1] ) ) );
                    $excerpt_more   = ! empty( $more_link_text ) ? $more_link_text : $excerpt_more;
                }

                $more_link = apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-{$post->ID}\" class=\"more-link\">$excerpt_more</a>", $excerpt_more, 'auxin_trim_excerpt' );

                return $content[0] . $more_link;
            }

            // If char length is defined use it, otherwise use default char length
            $char_length  = empty( $char_length ) ? apply_filters( 'auxin_excerpt_char_length', 250 ) : $char_length;

            // Clean post content
            $excerpt = strip_tags( auxin_strip_shortcodes( $content, $exclude_strip_shortcode_tags ) );
            $excerpt = auxin_get_trimmed_string( $excerpt, $char_length, $excerpt_more );

            return  $excerpt && $wrap_excerpt_with ? "<{$wrap_excerpt_with}>" . $excerpt . "</{$wrap_excerpt_with}>" : $excerpt;
        }

    }



/*-----------------------------------------------------------------------------------*/
/*  Excerpt outside of loop - trimmed by word length - The shortcodes content remains after striping
/*-----------------------------------------------------------------------------------*/

function auxin_the_excerpt( $post_id = null, $excerpt_length = null, $exclude_strip_shortcode_tags = null, $skip_more_tag = false ){
    echo auxin_get_the_excerpt( $post_id, $excerpt_length, $exclude_strip_shortcode_tags );
}

    // Generates an excerpt from the content outside of loop, if needed.
    function auxin_get_the_excerpt( $post_id = null, $excerpt_length = null, $exclude_strip_shortcode_tags = null, $skip_more_tag = false ) {
        $post = get_post( $post_id );
        if( ! isset( $post ) ) return '';

        // If post password required and it doesn't match the cookie.
        if ( post_password_required( $post ) )
            return get_the_password_form( $post );

        if ( $post->post_excerpt ) {
            $result = apply_filters( 'get_the_excerpt', $post->post_excerpt );

        } else {
            $content = $post->post_content;
            $content = apply_filters( 'the_content', $content );

            // If excerpt length is defined use it, otherwise use default excerpt length
            $excerpt_length = empty( $excerpt_length ) ? apply_filters('excerpt_length', 55) : $excerpt_length;
            $excerpt_more   = apply_filters( 'excerpt_more', ' ...' );

            // check for <!--more--> tag
            if ( ! $skip_more_tag && preg_match( '/<!--more(.*?)?-->/', $content, $matches ) ) {
                $content = explode( $matches[0], $content, 2 );

                if ( ! empty( $matches[1] ) ){
                    $more_link_text = strip_tags( wp_kses_no_null( trim( $matches[1] ) ) );
                    $excerpt_more   = ! empty( $more_link_text ) ? $more_link_text : $excerpt_more;
                }
                //$more_link = apply_filters( 'the_content_more_link', ' <a href="' . get_permalink() . "#more-{$post->ID}\" class=\"more-link\">$excerpt_more</a>", $excerpt_more, 'auxin_excerpt' );

                return $content[0] . $excerpt_more;
            }

            // Clean post content
            $excerpt = strip_tags( auxin_strip_shortcodes( $content, $exclude_strip_shortcode_tags ) );
            $result = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
        }

        return apply_filters( 'auxin_get_the_excerpt', $result );
    }

/*-----------------------------------------------------------------------------------*/
/*  Remove just shortcode tags from the given content but remain content of shortcodes
/*-----------------------------------------------------------------------------------*/

function auxin_strip_shortcodes( $content, $exclude_strip_shortcode_tags = null ) {
    if( ! $content ) return $content;

    if( ! $exclude_strip_shortcode_tags )
        $exclude_strip_shortcode_tags = auxin_exclude_strip_shortcode_tags();

    if( empty( $exclude_strip_shortcode_tags ) || ! is_array( $exclude_strip_shortcode_tags ) )
        return preg_replace('/\[[^\]]*\]/', '', $content);

    $exclude_codes = join('|', $exclude_strip_shortcode_tags);
    return preg_replace( "~(?:\[/?)(?!(?:$exclude_codes))[^/\]]+/?\]~s", '', $content );
}

/*-----------------------------------------------------------------------------------*/
/*  The list of shortcode tags that should not be removed in auxin_strip_shortcodes
/*-----------------------------------------------------------------------------------*/

function auxin_exclude_strip_shortcode_tags(){
    return apply_filters( "auxin_exclude_strip_shortcode_tags", array() );
}

/*-----------------------------------------------------------------------------------*/
/*  Extract only raw text - remove all special charecters, html tags, js and css codes
/*-----------------------------------------------------------------------------------*/

function auxin_extract_text( $content = null ) {
    // decode encoded html tags
    $content = htmlspecialchars_decode($content);
    // remove script tag and inline js content
    $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
    // remove style tag and inline css content
    $content = preg_replace('#<style(.*?)>(.*?)</style>#is'  , '', $content);
    // remove iframe content
    $content = preg_replace('#<if'.'rame(.*?)>(.*?)</iframe>#is', '', $content);
    // remove extra white spaces
    $content = preg_replace('/[\s]+/', ' ', $content );
    // strip html tags and escape special charecters
    $content = esc_attr(strip_tags($content));
    // remove double space
    $content = preg_replace('/\s{3,}/',' ', $content );
    return $content;
}

/*-----------------------------------------------------------------------------------*/
/*  Specifies whether the current page is theme admin page or not
/*-----------------------------------------------------------------------------------*/

/**
 * Whether is the current admin page in list or not
 *
 * @param  array   $admin_pages list of admin page ids
 * @return boolean              True if the the current admin page is theme admin page
 */
function auxin_is_theme_admin_page( $admin_pages = array() ){
    global $pagenow;

    $admin_pages = empty( $admin_pages ) ? auxin_theme_admin_pages() : $admin_pages;

    foreach ( $admin_pages as $page ){
        if( is_currentpage_id( $page ) )  return true;
    }

    if( isset( $_GET['page'] ) && 'admin.php' == $pagenow && 'auxin-options' == $_GET['page'] ){
        return true;
    }

    if( is_currentpage_id( 'nav-menus' ) ){
        return true;
    }

    return false;
}

/*-----------------------------------------------------------------------------------*/
/*  List of theme admin pages
/*-----------------------------------------------------------------------------------*/

function auxin_theme_admin_pages(){
    return apply_filters( 'auxin_theme_admin_pages',
        array_merge(
            array('toplevel_page_auxin', 'appearance_page_auxin', 'toplevel_page_auxin-welcome', 'appearance_page_auxin-welcome', 'page', 'post', 'widgets'),
            auxin_registered_post_types(true)
        )
    );
}

/*-----------------------------------------------------------------------------------*/
/*  Removes and return values from an array
/*-----------------------------------------------------------------------------------*/

function auxin_array_remove_val( $array, $values = array() ) {
    if( empty( $values )     ) return  $values;
    if( is_string( $values ) ) $values = array( $values );
    if(!is_array ( $values ) ) return  $values;

    return array_diff( $array, $values );
}

/*-----------------------------------------------------------------------------------*/
/*  Excerpt configuration
/*-----------------------------------------------------------------------------------*/

//// Returns range list of numbers ///////////////////////////////////////////////////

if( ! function_exists( 'auxin_get_range' ) ){

    function auxin_get_range( $start_num, $end_num, $default_values = array() ){
        return array_merge( $default_values, range( $start_num, $end_num ) );
    }

}


function auxin_change_trim_excerpt_more_link( $more_link, $more_text, $from = '' ){

    // dont render readmore link if the blog template has auto readmore link
    if( in_array( auxin_get_option('post_index_template_type'), array('default', '3', '4') ) ){
        return '';
    }

    // if filter called from auxin_trim_excerpt function
    if( 'auxin_trim_excerpt' == $from ){
        return ' <a href="' . get_permalink() . '" class="more-link aux-read-more aux-outline aux-large">'. __( ' Continue Reading', 'phlox' ) .'</a>';
    }
    return $more_link;
}

function auxin_change_content_more_link( $more_link, $more_text, $from = '' ){
    if( empty( $from ) ){
        return ' <a href="' . get_permalink() . '" class="more-link aux-read-more aux-outline aux-large"> '. $more_text .'</a>';
    }
    return $more_link;
}

/*-----------------------------------------------------------------------------------*/
/*  Prints Widget Title
/*-----------------------------------------------------------------------------------*/

if( ! function_exists( 'get_widget_title' ) ){

    function get_widget_title( $title = '', $nav = '', $filters = NULL ){

        ob_start();
    ?>                             <header class="widget-title-bar">
    <?php if( ! empty( $title ) )  { ?>
                                        <h3 class="widget-title"><?php echo $title; ?></h3>
    <?php } ?>
    <?php if( $nav == 'pagination' ){ ?>
                                       <div class="widget-nav pagination">
                                           <a href="" class="w_next"><?php _e( 'next', 'phlox'); ?></a>
                                           <a href="" class="w_prev"><?php _e( 'previous', 'phlox'); ?></a>
                                       </div><!-- widget-nav -->

    <?php } elseif ( $nav == 'filterable' && is_array( $filters ) ) { ?>

                                       <div class="widget-nav filterable">
                                           <a href="" class="active" data-filter="all" ><?php _e( 'All', 'phlox');?></a><span>/</span>
                                           <?php // loop throuth an array contains filters
                                           $len = count($filters);
                                           $counter = 0; // create a counter to add diffrent style for last filter
                                           foreach ( $filters as $key => $value ) {
                                               ++$counter;
                                           echo '<a href="" data-filter="'.$key.'" >'.$value.'</a>';
                                           echo ( $counter < $len ) ? '<span>/</span>' : '';
                                           } ?>
                                       </div><!-- widget-nav -->

    <?php } ?>                      </header><!-- widget-title-bar -->
    <?php
        return ob_get_clean();
    }

}

//// Get page layouts //////////////////////////////////////////////////////////////////////////////

// prints the page layout
function auxin_the_page_layout( $id ){
    echo auxin_get_page_layout( $id );
}
    // outsputs the page layout [right-layout, left-layout, no-layout]
    if( ! function_exists( 'auxin_get_page_layout' ) ){

        function auxin_get_page_layout($id){
            return str_replace( 'sidebar', 'layout', auxin_get_page_sidebar_pos( $id ) );
        }

    }


//// specifies whether the page is full or has sidebar  /////////////////////////////////////////////

/**
 * Specifies whether th page has sidebar or not
 */
if( ! function_exists( 'auxin_has_sidebar' ) ){

    function auxin_has_sidebar( $page_id ) {
        $post = get_post( $page_id );
        $sidebar_pos = auxin_get_page_sidebar_pos( $post );

        if( 'no-sidebar' == $sidebar_pos ){
            return 0;
        } else if( in_array( $sidebar_pos, array( 'right2-sidebar', 'left2-sidebar', 'left-right-sidebar','right-left-sidebar' ) ) ) {
            return 2;
        } else if( in_array( $sidebar_pos, array( 'right-sidebar', 'left-sidebar' ) ) ) {
            return 1;
        }
        return false;
    }

}


/**
 * Retrieve the status of page sidebar [right-sidebar, left-sidebar, no-sidebar]
 *
 * @param  int|Object $page_id The page id or $post object
 * @return string              The the status of page sidebar [right-sidebar, left-sidebar, no-sidebar]
 */
function auxin_get_page_sidebar_pos( $page_id ){

    $layout = 'right-sidebar';
    $post   = get_post( $page_id );

    // check if woocommerce is activaited
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
       if(is_product()) {
            $layout = auxin_get_option( 'product_single_sidebar_position', 'right-sidebar');
        }
    }
    if( is_404() ){
        $layout = 'no-sidebar';

    } elseif( is_home() || is_post_type_archive('post') ){
        $layout = auxin_get_option( 'post_index_sidebar_position', 'right-sidebar');

    } elseif( is_tax() ){

        // If the post type in list of "post_types_with_no_sidebar" set sidebar_pos to no-sidebar
        if( in_array( $post_type, auxin_post_types_with_no_sidebar_on_taxonomy() ) ){
            $layout = 'no-sidebar';
        } else {
            $layout = auxin_get_option( $post->post_type.'_taxonomy_archive_sidebar_position', 'right-sidebar');
            // check if woocommerce is activaited
            if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                 if( is_product_category() || is_product_tag() ){
                    $layout = auxin_get_option( 'product_category_sidebar_position', 'right-sidebar');
                }
            }
        }

    } elseif( is_category() || is_tag() ) {
        $layout = auxin_get_option( 'post_taxonomy_archive_sidebar_position', 'right-sidebar');

    } elseif( is_archive() ){

        if ( empty( $post_type) ) {
            $layout = 'right-sidebar';
        } else {
            // If the post type in list of "post_types_with_no_sidebar" set sidebar_pos to no-sidebar
            if( in_array( $post_type, auxin_post_types_with_no_sidebar_on_archive() ) ){
                $layout = 'no-sidebar';
            } else {
                $layout = auxin_get_option( $post->post_type.'_index_sidebar_position', 'right-sidebar');
            }
        }
        if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            if(is_shop()){
                $layout = auxin_get_option( 'product_index_sidebar_position', 'right-sidebar');
            }
        }

    } elseif( is_single() ){

        if( 'default' == $layout = auxin_get_post_meta( $post->ID, 'page_layout', 'default' ) ){
            $layout = auxin_get_option( $post->post_type.'_single_sidebar_position', 'right-sidebar');
        }

    } elseif( is_page() ){

        if( 'default' == $layout = auxin_get_post_meta( $post->ID, 'page_layout', 'default' ) ){
            $layout = auxin_get_option( $post->post_type.'_single_sidebar_position', 'no-sidebar');
        }

    } elseif( is_search() ){
        $layout = 'right-sidebar';

    } elseif( $post && ! $layout = get_post_meta( $post->ID, 'page_layout', true ) ){
        $layout = 'no-sidebar';
    }

    return apply_filters( 'auxin_get_page_sidebar_pos', $layout, $post );
}


/**
 * Retrieve the ID of page sidebar
 *
 * @param  int|Object $page_id The page id or $post object
 * @return string              The the ID of page sidebar, empty string on failure
 */
function auxin_get_page_sidebar_id( $page_id ){
    $post = get_post( $page_id );

    $sidebar_id = isset( $post->ID ) ? get_post_meta( $post->ID, 'auxin_page_sidebar_id', true ) : '';

    return apply_filters( 'auxin_get_page_sidebar_id', $sidebar_id, $post );
}

/**
 * Merge new css classes in current list
 *
 * @param  array        $classes   List of current classes
 * @param  string|array $class     One or more classes to add to the class list.
 *
 * @return                         Array of classes
 */
function auxin_merge_css_classes( $classes = '', $class = '' ){

    if( empty( $classes ) && empty( $class ) )
        return array();

    if ( ! empty( $class ) ) {
        if ( !is_array( $class ) )
            $class = preg_split( '#\s+#', $class );

        $classes = array_merge( $classes, $class );
    }
    // $classes = array_map( 'esc_attr', $classes );

    return $classes;
}

/**
 * Creates and returns an HTML class attribute
 *
 * @param  array        $classes   List of current classes
 * @param  string|array $class     One or more classes to add to the class list.
 *
 * @return string                  HTML class attribute
 */
function auxin_make_html_class_attribute( $classes = '', $class = '' ){

    if( ! $merged_classes = auxin_merge_css_classes( $classes, $class ) ){
        return '';
    }

    return 'class="' . join( ' ', array_unique( $merged_classes ) ) . '"';
}


//// get grid class names by percent size ////////////////////////////////////////////////////////////

function auxin_get_column_num_name( $num ){

    $column_names = array( 1 => 'one-column' , 2 => 'two-column', 3 => 'three-column', 4 => 'four-column', 5 => 'five-column' );

    return key_exists( $num, $column_names ) ? $column_names[ $num ] : 'three-column';
}



function auxin_get_grid_column_name( $size ){

    $column_sizes = array( 100   => 'one-column' , 50    => 'two-column', 33    => 'three-column', 25    => 'four-column', 20    => 'five-column',
                           '1/1' => 'one-column' , '1/2' => 'two-column', '1/3' => 'three-column', '1/4' => 'four-column', '1/5' => 'five-column'
                          );
    // get grid column name, if nothing found, return 'three-column'
    return key_exists( $size, $column_sizes ) ? $column_sizes[ $size ] : 'three-column';
}



function auxin_get_grid_column_number( $name ){

    $column_nums = array( 'one-column' => 100 , 'two-column' => 50, 'three-column' => 33, 'four-column' => 25, 'five-column' => 20 );
    // get grid column number, if nothing found, return 33
    return key_exists( $name, $column_nums ) ? $column_nums[ $name ] : 33;
}



function auxin_get_grid_name( $size ){

    $column_sizes = array(  20   => 'one_fifth',  25   => 'one_fourth',  33   => 'one_third',  50   => 'one_half',  66   => 'two_third',  75   => 'three_fourth', 100   => 'one_one',
                           '1/5' => 'one_fifth', '1/4' => 'one_fourth', '1/3' => 'one_third', '1/2' => 'one_half', '2/3' => 'two_third', '3/4' => 'three_fourth', '1/1' => 'one_one'
                          );

    return key_exists( $size, $column_sizes ) ? $column_sizes[ $size ] : '';
}


function auxin_get_image_size_by_col_percent( $size ){

    switch ( $size ) {
        case 20:
            return auxin_get_image_size('i5');
            break;
        case 25:
            return auxin_get_image_size('i4');
            break;
        case 33:
            return auxin_get_image_size('i3');
            break;
        case 50:
            return auxin_get_image_size('i2');
            break;
        case 100:
            return auxin_get_image_size('i1');
            break;
        default:
            return auxin_get_image_size('i1');
            break;
    }
}

/*-----------------------------------------------------------------------------------*/
/*  get featured image url (original images)
/*-----------------------------------------------------------------------------------*/

function auxin_featured_img_url( $featured_img_size ) {
     $image_id  = get_post_thumbnail_id();
     $image_url = wp_get_attachment_image_src( $image_id, $featured_img_size );
     $image_url = $image_url[0];

     return empty( $image_url )? '' : '<img alt="featured image" height="50px" src="' . $image_url . '" />';
}

// get featured image url by attachment id
function auxin_get_attachment_url( $attach_id, $featured_img_size = "medium" ) {
    if( is_numeric( $attach_id ) ){
        $image_url = wp_get_attachment_image_src( $attach_id, $featured_img_size );
        return $image_url[0];
    }
    return '';
}

// get featured image url by post id
function auxin_get_the_attachment_url( $post_id, $img_size = "medium" ) {
    $post = get_post( $post_id );
    if( $post ){
        $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $img_size );
        return $image_url[0];
    }
    return null;
}

/*-----------------------------------------------------------------------------------*/
/*  Custom functions for resizing images
/*-----------------------------------------------------------------------------------*/

/**
 * Outputs resized image by image URL
 *
 * @param  string   $img_url  The original image URL
 * @param  integer  $width    New image Width
 * @param  integer  $height   New image height
 * @param  bool     $crop     Whether to crop image to specified height and width or resize. Default false (soft crop).
 * @param  integer  $quality  New image quality - a number between 0 and 100
 * @return string   new image src
 */
function auxin_the_resized_image( $img_url = "", $width = null , $height = null, $crop = null , $quality = 100, $attr = '', $upscale = false ) {
    echo auxin_get_the_resized_image( $img_url , $width , $height , $crop , $quality, $attr, $upscale );
}

        function auxin_get_the_resized_image( $img_url = "", $width = null , $height = null, $crop = null , $quality = 100, $attr = '', $upscale = false ) {

            $src = aq_resize( $img_url, $width, $height, $crop, $quality, true, $upscale );
            if( empty( $src ) ) return '';

            $html = '';

            $string_size = $width . 'x' . $height;
            $hwstring = image_hwstring( $width, $height );

            // default image attributes
            $default_attr = array(
                'src'   => $src,
                'class' => "auxin-resized-image auxin-image-$string_size"
            );

            $attr = wp_parse_args( $attr, $default_attr );

            /**
             * Filter the list of attachment image attributes.
             *
             * @param mixed $attr          Attributes for the image markup.
             * @param int   $attachment_id Image attachment ID.
             */
            $attr = apply_filters( 'auxin_get_resized_image_attributes', $attr, $img_url, $width, $height );
            $attr = array_map( 'esc_attr', $attr );
            $html = rtrim("<img $hwstring");
            foreach ( $attr as $name => $value ) {
                $html .= " $name=" . '"' . $value . '"';
            }
            $html .= ' />';

            return $html;
        }

        /**
         * Get resized image by image URL
         *
         * @param  string   $img_url  The original image URL
         * @param  integer  $width    New image Width
         * @param  integer  $height   New image height
         * @param  bool     $crop     Whether to crop image to specified height and width or resize. Default false (soft crop).
         * @param  integer  $quality  New image quality - a number between 0 and 100
         * @return string   new image src
         */
        if( ! function_exists( 'auxin_get_the_resized_image_src') ){

            function auxin_get_the_resized_image_src( $img_url = '', $width = null , $height = null, $crop = null , $quality = 100, $upscale = false ) {
                $resized_img_url = aq_resize( $img_url, $width, $height, $crop, $quality, true, $upscale );
                if( empty( $resized_img_url ) )
                    $resized_img_url = $img_url;
                return apply_filters('auxin_get_the_resized_image_src', $resized_img_url, $img_url);
            }

        }


// get resized image by attachment id ////////////////////////////////////////////////

/**
 * Outputs resized image by attachment id
 *
 * @param  string   $attach_id  The attachment id
 * @param  integer  $width    New image Width
 * @param  integer  $height   New image height
 * @param  bool     $crop     Whether to crop image to specified height and width or resize. Default false (soft crop).
 * @param  integer  $quality  New image quality - a number between 0 and 100
 * @return string   new image src
 */
function auxin_the_resized_attachment( $attach_id = null, $width = null , $height = null, $crop = null , $quality = 100, $attr = '', $upscale = false ) {
    echo auxin_get_the_resized_attachment( $attach_id, $width , $height, $crop, $quality, $attr, $upscale );
}

    // return resized image tag
    function auxin_get_the_resized_attachment( $attach_id = null, $width = null , $height = null, $crop = null , $quality = 100, $attr = '', $upscale = false ) {

        $html       = '';
        $size       = $width;
        $attachment = get_post( $attach_id );

        $image_meta = get_post_meta( $attach_id, '_wp_attachment_metadata', true );

        // if size is valid and defined
        if( ! is_numeric( $size ) ){
            $size_array = _wp_get_image_size_from_meta( $size, $image_meta );
            if( $size_array ){
                $width  = $size_array[0];
                $height = $size_array[1];
            }
        } else {
            $size = '';
            $size_array = array(
                absint( $width  ),
                absint( $height )
            );
        }

        $src = auxin_get_the_resized_attachment_src( $attach_id, $width , $height, $crop, $quality, $upscale );
        if( empty( $src ) ) return '';

        $srcset = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attach_id );

        $string_size = $width . 'x' . $height;
        $hwstring    = image_hwstring( $width, $height );

        // default image attributes
        $default_attr = array(
            'src'              => $src,
            'class'            => "auxin-attachment attachment-$string_size",
            'alt'              => trim(strip_tags( get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) )), // Use Alt field first
            'width_attr_name'  => '',
            'height_attr_name' => '',
            'srcset'           => $srcset,
            'sizes'            => '33vw'
        );

        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_title   ) ); // Finally, use the title

        $attr = wp_parse_args( $attr, $default_attr );


        if( ! empty( $attr['width_attr_name'] ) || ! empty( $attr['height_attr_name'] ) )
            $metadata = wp_get_attachment_metadata( $attach_id );

        if( ! empty( $attr['width_attr_name'] ) )
            $attr[ $attr['width_attr_name'] ] = $metadata['width'];

        if( ! empty( $attr['height_attr_name'] ) )
            $attr[ $attr['height_attr_name'] ] = $metadata['height'];


        unset( $attr['width_attr_name' ] );
        unset( $attr['height_attr_name'] );

        if ( empty( $attr['srcset'] ) ) {
            unset( $attr['srcset'] );
            unset( $attr['sizes'] );
        }

        /**
         * Filter the list of attachment image attributes.
         *
         * @param mixed $attr          Attributes for the image markup.
         * @param int   $attach_id     Image attachment ID.
         */
        $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
        $attr = array_map( 'esc_attr', $attr );
        $html = rtrim("<img $hwstring");
        foreach ( $attr as $name => $value ) {
            $html .= " $name=" . '"' . $value . '"';
        }
        $html .= ' />';

        return $html;
    }

        /**
         * Get resized image src by attachment id
         *
         * @param  string   $attach_id  The attachment id
         * @param  integer  $width    New image Width
         * @param  integer  $height   New image height
         * @param  bool     $crop     Whether to crop image to specified height and width or resize. Default false (soft crop).
         * @param  integer  $quality  New image quality - a number between 0 and 100
         * @return string|array       A single or list of cropped image srcs
         */
        if( ! function_exists( 'auxin_get_the_resized_attachment_src' ) ){

            function auxin_get_the_resized_attachment_src( $attach_id = null, $width = null , $height = null, $crop = null , $quality = 100, $upscale = false ) {
                if( is_null( $attach_id ) ) return '';


                if( is_array( $attach_id ) ){
                    $srcs = array();

                    foreach ( $attach_id as $id ) {

                        $mime_type = get_post_mime_type( $id );

                        if( 0 === strpos( $mime_type, 'image/' ) ){
                            $img_url     = wp_get_attachment_url( $id ,'full' ); //get img URL
                            $srcs[ $id ] = $img_url ? aq_resize( $img_url, $width, $height, $crop, $quality, true, $upscale ) : '';
                        } elseif ( 0 === strpos( $mime_type, 'audio/' ) ){
                            $srcs[ $id ] = includes_url() . 'images/media/audio.png';
                        } elseif ( 0 === strpos( $mime_type, 'video/' ) ){
                            $srcs[ $id ] = includes_url() . 'images/media/video.png';
                        } elseif ( 0 === strpos( $mime_type, 'text/' ) ){
                            $srcs[ $id ] = includes_url() . 'images/media/file.png';
                        }
                    }

                    return $srcs;
                }

                $mime_type = get_post_mime_type( $attach_id );

                if( 0 === strpos( $mime_type, 'image/' ) ){
                    $img_url     = wp_get_attachment_url( $attach_id ,'full' ); //get img URL
                    return $img_url ? aq_resize( $img_url, $width, $height, $crop, $quality, true, $upscale ) : false;
                } elseif ( 0 === strpos( $mime_type, 'audio/' ) ){
                    return includes_url() . 'images/media/audio.png';
                } elseif ( 0 === strpos( $mime_type, 'video/' ) ){
                    return includes_url() . 'images/media/video.png';
                } elseif ( 0 === strpos( $mime_type, 'text/' ) ){
                    return includes_url() . 'images/media/file.png';
                }

                return false;
            }


        }


// get resized image featured by post id //////////////////////////////////////////////


function auxin_generate_image_sizes( $image_sizes ){
    $attr_sizes = '';
    foreach ( $image_sizes as $element_size ) {
        $attr_sizes .= ! empty( $element_size['min']  ) ? '(min-width:'. $element_size['min'] .') ' : '';
        $attr_sizes .= ! empty( $element_size['min']  ) && ! empty( $element_size['max']  ) ? 'and ' : '';
        $attr_sizes .= ! empty( $element_size['max']  ) ? '(max-width:'. $element_size['max'] .') ' : '';
        $attr_sizes .= ! empty( $element_size['width'] ) ? $element_size['width'] . ',' : ',';
    }
    return rtrim( $attr_sizes, ',' );
}


// echo resized image tag
function auxin_the_post_thumbnail( $post_id = null, $width = null , $height = null, $crop = null , $quality = 100, $upscale = false ) {
    echo auxin_get_the_post_thumbnail( $post_id, $width , $height, $crop, $quality, $upscale );
}


    // return resized responsive image tag with cuatom srcset and sizes
    function auxin_get_the_post_responsive_thumbnail( $post_id = null, $args = array() ) {

        $defaults = array(
            'quality'       => 100,
            'attr'          => '',
            'upscale'       => false,
            'size'          => 'large',
            'crop'          => null,
            'add_hw'        => true,
            'image_sizes'   => array(),
            'srcset_sizes'  => array()
        );

        /* A sample for element sizes

        'image_sizes' => array(
            array( 'min' => '', 'max' => '767px', 'width' => '80vw' ),
            array( 'min' => '', 'max' => '992px', 'width' => '50vw' ),
            array( 'min' => '', 'max' => ''     , 'width' => '18vw' )
        ),
         */

        /* A sample for src set dimentions

        'srcset_sizes'  => array(
            array( 'width' => '300' , 'height' => '200' ),
            array( 'width' => '450' , 'height' => '300' ),
            array( 'width' => '768' , 'height' => '512' ),
            array( 'width' => '1350', 'height' => '900' )
        )
         */


        $args = wp_parse_args( $args, $defaults );
        extract( $args );



        if( ! empty( $size ) && ! is_array( $size ) ){
            $size = auxin_wp_get_image_size( $size );
            $size = array( 'width' => $size['width'], 'height' => $size['height'] );
        }

        // calculate the image sizes
        $attr_sizes  = auxin_generate_image_sizes( $image_sizes );

        // calculate the srcsets
        $attr_srcset = '';
        foreach ( $srcset_sizes as $srcset_size ) {
            $attr_srcset .= auxin_get_the_post_thumbnail_src( $post_id, $srcset_size['width'] , $srcset_size['height'], $crop, $quality, $upscale );
            $attr_srcset .= ' '. $srcset_size['width'].'w,';
        }
        $attr_srcset =  rtrim( $attr_srcset, ',' );

        // crop the main image
        $src = auxin_get_the_post_thumbnail_src( $post_id, $size['width'] , $size['height'], $crop, $quality, $upscale );
        if( empty( $src ) ) return '';

        $html = '';
        $attachment_id = get_post_thumbnail_id( $post_id );
        $attachment    = get_post( $attachment_id );

        $string_size   = $size['width'] . 'x' . $size['height'];
        $hwstring      = $add_hw ? image_hwstring( $size['width'], $size['height'] ) : '';

        // default image attributes
        $default_attr  = array(
            'src'              => $src,
            'class'            => "auxin-attachment auxin-featured-image attachment-$string_size",
            'alt'              => trim(strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) )), // Use Alt field first
            'width_attr_name'  => '',
            'height_attr_name' => ''
        );

        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_title   ) ); // Finally, use the title

        // parse the attr
        $attr = wp_parse_args( $attr, $default_attr );

        // Generate 'srcset' and 'sizes' if not already present.
        if ( empty( $attr['srcset'] ) ) {
            if ( $attr_srcset ) {
                $attr['srcset'] = $attr_srcset;
            }
            if ( empty( $attr['sizes'] ) ) {
                $attr['sizes'] = $attr_sizes;
            }
        }

        if( ! empty( $attr['width_attr_name'] ) || ! empty( $attr['height_attr_name'] ) )
            $metadata = wp_get_attachment_metadata( $attachment_id );

        if( ! empty( $attr['width_attr_name'] ) )
            $attr[ $attr['width_attr_name'] ] = $metadata['width'];

        if( ! empty( $attr['height_attr_name'] ) )
            $attr[ $attr['height_attr_name'] ] = $metadata['height'];


        unset( $attr['width_attr_name' ] );
        unset( $attr['height_attr_name'] );


        /**
         * Filter the list of attachment image attributes.
         *
         * @param mixed $attr          Attributes for the image markup.
         * @param int   $attachment_id Image attachment ID.
         */
        $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
        $attr = array_map( 'esc_attr', $attr );
        $html = rtrim("<img $hwstring");
        foreach ( $attr as $name => $value ) {
            $html .= " $name=" . '"' . $value . '"';
        }
        $html .= ' />';

        return $html;
    }


    // return resized image tag
    function auxin_get_the_post_thumbnail( $post_id = null, $width = null , $height = null, $crop = null , $quality = 100, $attr = '', $upscale = false ) {
        $src = auxin_get_the_post_thumbnail_src( $post_id, $width , $height, $crop, $quality, $upscale );
        if( empty( $src ) ) return '';

        $html = '';
        $attachment_id = get_post_thumbnail_id( $post_id );
        $attachment    = get_post( $attachment_id );

        $string_size   = $width . 'x' . $height;
        $hwstring      = image_hwstring( $width, $height );

        // default image attributes
        $default_attr = array(
            'src'              => $src,
            'class'            => "auxin-attachment auxin-featured-image attachment-$string_size",
            'alt'              => trim(strip_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) )), // Use Alt field first
            'width_attr_name'  => '',
            'height_attr_name' => ''
        );

        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_title   ) ); // Finally, use the title


        $attr = wp_parse_args( $attr, $default_attr );


        if( ! empty( $attr['width_attr_name'] ) || ! empty( $attr['height_attr_name'] ) )
            $metadata = wp_get_attachment_metadata( $attachment_id );

        if( ! empty( $attr['width_attr_name'] ) )
            $attr[ $attr['width_attr_name'] ] = $metadata['width'];

        if( ! empty( $attr['height_attr_name'] ) )
            $attr[ $attr['height_attr_name'] ] = $metadata['height'];


        unset( $attr['width_attr_name' ] );
        unset( $attr['height_attr_name'] );


        /**
         * Filter the list of attachment image attributes.
         *
         * @param mixed $attr          Attributes for the image markup.
         * @param int   $attachment_id Image attachment ID.
         */
        $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
        $attr = array_map( 'esc_attr', $attr );
        $html = rtrim("<img $hwstring");
        foreach ( $attr as $name => $value ) {
            $html .= " $name=" . '"' . $value . '"';
        }
        $html .= ' />';

        return $html;
    }

        /**
         * Get resized image by post id
         *
         * @param  string   $post_id  The post id
         * @param  integer  $width    New image Width
         * @param  integer  $height   New image height
         * @param  bool     $crop     Whether to crop image to specified height and width or resize. Default false (soft crop).
         * @param  integer  $quality  New image quality - a number between 0 and 100
         * @return string   new image src
         */
        if( ! function_exists("auxin_get_the_post_thumbnail_src") ){

            function auxin_get_the_post_thumbnail_src( $post_id = null, $width = null , $height = null, $crop = null , $quality = 100, $upscale = false ) {
                $post_id = is_null( $post_id ) ? get_the_ID() : $post_id;
                $post_thumbnail_id = get_post_thumbnail_id( $post_id );

                $img_url = wp_get_attachment_url( $post_thumbnail_id ,'full' ); //get img URL

                $resized_img = $post_thumbnail_id ? aq_resize( $img_url, (int)$width, (int)$height, $crop, $quality, true, $upscale ) : false;
                return apply_filters( 'auxin_get_the_post_thumbnail_src', $resized_img, $img_url, $width, $height, $crop, $quality );
            }

        }

        /**
         * Get full URI of an featured image for a post id
         *
         * @param  integer $post_id  The post id to get featured image of
         * @return string  Returns a full URI for featured image or false on failure.
         */
        if( ! function_exists( 'auxin_get_the_post_thumbnail_full_src' ) ){

            function auxin_get_the_post_thumbnail_full_src( $post_id = null ) {
                $post_id = is_null( $post_id ) ? get_the_ID() : $post_id;
                $post_thumbnail_id = get_post_thumbnail_id( $post_id );

                return wp_get_attachment_url( $post_thumbnail_id, 'full' );
            }

        }


    /**
     * Returns a cropped post image (featured image or first image in content) from a post id
     *
     * @param  integer $post_id      The post id to get post image of
     * @param  string  $image_from   where to look for post image. possible values are : auto, featured, first. Default to 'auto'
     * @param  integer  $width       New image Width
     * @param  integer  $height      New image height
     * @param  bool     $crop        Whether to crop image to specified height and width or resize. Default false (soft crop).
     * @param  integer  $quality     New image quality - a number between 0 and 100
     *
     * @return string  Returns a  image tag or empty string on failure.
     */
    if( ! function_exists( 'auxin_get_auto_post_thumbnail' ) ){

        function auxin_get_auto_post_thumbnail( $post_id = null, $image_from = 'auto', $width = null , $height = null, $crop = null , $quality = 100, $upscale = false ) {

            $post = get_post( $post_id );
            $image_src = auxin_get_auto_post_thumbnail_src( $post->ID, $image_from );
            $image_src  = $image_src ? aq_resize( $image_src, $width, $height, $crop, $quality, true, $upscale ) : false;

            return $image_src ? '<img src="'.$image_src.'" alt="'.$post->post_title.'" />' : '';
        }

    }

        /**
         * Get full URI of a post image (featured image or first image in content) for a post id
         *
         * @param  integer $post_id  The post id to get post image of
         * @param  string  $image_from   where to look for post image. possible values are : auto, featured, first. Default to 'auto'
         *
         * @return string  Returns a full URI for post image or empty string on failure.
         */
        if( ! function_exists( 'auxin_get_auto_post_thumbnail_src' ) ){

            function auxin_get_auto_post_thumbnail_src( $post_id = null, $image_from = 'auto' ) {

                $post = get_post( $post_id );
                $img_src = '';

                if( empty( $post ) ) return '';

                if ( 'auto' == $image_from ) {
                    $img_src = has_post_thumbnail( $post->ID ) ? auxin_get_the_post_thumbnail_full_src( $post->ID ) : '';

                    if( empty( $img_src ) ) {
                        $img_src = auxin_get_first_image_src_from_string( $post->post_content );
                    }

                } elseif( 'featured' == $image_from ) {
                    $img_src = has_post_thumbnail( $post->ID ) ? auxin_get_the_post_thumbnail_full_src( $post->ID ) : '';

                } elseif ( 'first' == $image_from ) {
                    $img_src = auxin_get_first_image_src_from_string( $post->post_content );
                }

                return $img_src;
            }

        }

// get list of resized images by attachment list id //////////////////////////////////////////////

// return resized image tag
function auxin_get_the_resized_attachments_list( $post_id, $attachment_meta_key = null, $width = null , $height = null, $crop = null , $quality = 100, $attr = '' ) {
    $img_list = array();

    if( ! $post_id ){
        return $list;
    }

    $attach_ids_str = get_post_meta( $post_id, $attachment_meta_key, true );
    $attach_ids     = explode( ',', $attach_ids_str );

    foreach ( $attach_ids as $attach_id ) {

        $src = auxin_get_the_resized_attachment_src( $attach_id, $width , $height, $crop, $quality );
        if( empty( $src ) ) return '';

        $html = '';
        $attachment = get_post( $attach_id );

        $string_size = $width . 'x' . $height;
        $hwstring = image_hwstring( $width, $height );

        // default image attributes
        $default_attr = array(
            'src'   => $src,
            'class' => "auxin-attachment attachment-$string_size",
            'alt'   => trim(strip_tags( get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) )), // Use Alt field first
        );

        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
        if ( empty( $default_attr['alt'] ) )
            $default_attr['alt'] = trim( strip_tags( $attachment->post_title   ) ); // Finally, use the title

        $attr = wp_parse_args( $attr, $default_attr );

        /**
         * Filter the list of attachment image attributes.
         *
         * @param mixed $attr          Attributes for the image markup.
         * @param int   $attach_id     Image attachment ID.
         */
        $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment );
        $attr = array_map( 'esc_attr', $attr );
        $html = rtrim("<img $hwstring");
        foreach ( $attr as $name => $value ) {
            $html .= " $name=" . '"' . $value . '"';
        }
        $html .= ' />';

        $img_list[] = $html;
    }

    return $img_list;
}

    /**
     * Get resized image src by attachment id
     *
     * @param  string   $attach_id  The attachment id
     * @param  integer  $width    New image Width
     * @param  integer  $height   New image height
     * @param  bool     $crop     Whether to crop image to specified height and width or resize. Default false (soft crop).
     * @param  integer  $quality  New image quality - a number between 0 and 100
     * @return string   new image src
     */
    if( ! function_exists( 'auxin_get_the_resized_attachments_src_list' ) ){

        function auxin_get_the_resized_attachments_src_list( $post_id, $attachment_meta_key = null, $width = null , $height = null, $crop = null , $quality = 100, $upscale = false ) {

            $img_list = array();

            if( ! $post_id ){
                return $list;
            }

            $attach_ids_str = get_post_meta( $post_id, $attachment_meta_key, true );
            $attach_ids     = explode( ',', $attach_ids_str );

            foreach ( $attach_ids as $attach_id ) {

                $img_url    = wp_get_attachment_url( $attach_id ,'full' ); //get img URL
                $img_list[] = $img_url ? aq_resize( $img_url, $width, $height, $crop, $quality, true, $upscale ) : false;
            }
        }

    }

//// returns the number of active columns in subfooter  ////////////////////////////////

function auxin_get_active_footer_columns (){
    return 4;
}

/**
 * Retrieves the width of each column when inserted fully iin page
 *
 * @param  int     $col_num The number of column in one row
 * @param  int     $gutter  The spaces between the columns
 *
 * @return int              Width of each column in pixel
 */
function auxin_get_content_column_width( $col_num, $gutter = 15 ){
    global $aux_content_width;
    return  ( $aux_content_width -  ( $col_num - 2 ) * 2 * $gutter ) / $col_num ;
}


//// returns the width of nth column in subfooter  //////////////////////////////////

if( ! function_exists( 'auxin_get_nth_footer_column_width' ) ){

    function auxin_get_nth_footer_column_width( $layout, $num ){
        $size   = substr( $layout, (6 + 3 * $num ), 2 );
        return auxin_get_grid_name( $size );
    }

}

//// modify posts_per_page for current query  //////////////////////////////////

if( ! function_exists( 'auxin_update_query_var' ) ){

    function auxin_update_query_var( $query_var = "posts_per_page", $per_page = 6 ){
        global $wp_query;
        $q_vars = $wp_query->query_vars;

        $paged = max( 1, get_query_var('paged'), get_query_var('page') );
        $q_vars["paged"] = $paged;
        $q_vars[$query_var] = $per_page;

        query_posts( $q_vars );
    }

}

//// retrieves the provider from an embed code link  ///////////////////////////

function auxin_extract_embed_provider_name( $src ){
    require_once( ABSPATH . WPINC . '/class-oembed.php' );
    $oembed   = _wp_oembed_get_object();
    if( ! $provider = $oembed->get_provider( $src ) ){
        return '';
    }

    $provider_info = parse_url( $provider );
    if( $provider_info['host'] ){
        $host_parts = explode( '.', $provider_info['host'] );
        $host_parts_num = count( $host_parts );
        if( $host_parts_num > 1 ){
            return $host_parts[ $host_parts_num -2 ];
        }
    }

    return '';
}

//// validate the variable as boolean  /////////////////////////////////////////

function auxin_is_true( $var ) {
    if ( is_bool( $var ) ) {
        return $var;
    }

    if ( is_string( $var ) ){
        $var = strtolower( $var );
        if( in_array( $var, array( 'yes', 'on', 'true', 'checked' ) ) ){
            return true;
        }
    }

    if ( is_numeric( $var ) ) {
        return (bool) $var;
    }

    return false;
}


///// extract image from content ///////////////////////////////////////////////

/**
 * Get first image tag from string
 *
 * @param  string $content  The content to extract image from
 * @return string           First image tag on success and empty string if nothing found
 */
function auxin_get_first_image_from_string( $content ){
    $images = auxin_extract_string_images( $content );
    return ( $images && count( $images[0]) ) ? $images[0][0] : '';
}

/**
 * Get first image src from content
 *
 * @param  string $content  The content to extract image from
 * @return string           First image URL on success and empty string if nothing found
 */
function auxin_get_first_image_src_from_string( $content ){
    $images = auxin_extract_string_images( $content );
    return ( $images && count( $images[1]) ) ? $images[1][0] : '';
}

    /**
     * Extract all images from content
     *
     * @param  string $content   The content to extract images from
     * @return array             List of images in array
     */
    if( ! function_exists( 'auxin_extract_string_images' ) ){

        function auxin_extract_string_images( $content ){
            preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', $content, $matches );
            return isset( $matches ) && count( $matches[0] ) ? $matches : false;
        }

    }


/**
 * Removes the method form a hook by class name
 * @return void
 */
if( ! function_exists( 'remove_filter_from_class' ) ){

    function remove_filter_from_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 0 ) {
        global $wp_filter;

        // Take only filters on right hook name and priority
        if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
            return false;

        // Loop on filters registered
        foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
            // Test if filter is an array ! (always for class/method)
            if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
                // Test if object is a class, class and method is equal to param !
                if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
                    unset($wp_filter[$hook_name][$priority][$unique_id]);
                }
            }

        }

        return false;
    }
}

//// Check whether script has been registered  //////////////////////////////////////

function axi_is_script_registered( $handle ){
    return wp_script_is( $handle, "registered" );
}


//// Store content in file  ////////////////////////////////////////////////////////

/**
 * Creates and stores content in a file (#admin)
 *
 * @param  string $content    The content for writing in the file
 * @param  string $file_location  The address that we plan to create the file in.
 *
 * @return boolean            Returns true if the file is created and updated successfully, false on failure
 */
function auxin_put_contents( $content, $file_location = '', $chmode = 0644 ){

    if( empty( $file_location ) ){
        return false;
    }

    /**
     * Initialize the WP_Filesystem
     */
    global $wp_filesystem;
    if ( empty( $wp_filesystem ) ) {
        require_once ( ABSPATH.'/wp-admin/includes/file.php' );
        WP_Filesystem();
    }

    // Write the content, if possible
    if ( wp_mkdir_p( dirname( $file_location ) ) && ! $wp_filesystem->put_contents( $file_location, $content, $chmode ) ) {
        // If writing the content in the file was not successful
        return false;
    } else {
        return true;
    }

}


/**
 * Creates and stores content in a file (#admin)
 *
 * @param  string $content    The content for writing in the file
 * @param  string $file_name  The name of the file that we plan to store the content in. Default value is 'customfile'
 * @param  string $file_dir   The directory that we plan to store the file in. Default dir is wp-contents/uploads/{THEME_ID}
 *
 * @return boolean            Returns true if the file is created and updated successfully, false on failure
 */
function auxin_put_contents_dir( $content, $file_name = '', $file_dir = null, $chmode = 0644 ){

    if( is_null( $file_dir ) ){
        $file_dir  = THEME_CUSTOM_DIR;
    }
    $file_dir = trailingslashit( $file_dir );


    if( empty( $file_name ) ){
        $file_name = 'customfile';
    }

    $file_location = $file_dir . $file_name;

    return auxin_put_contents( $content, $file_location, $chmode );
}

//// Stores content in custom js file   /////////////////////////////////////////////

/**
 * Adds and stores javascript content in custom js file (#admin)
 *
 * @param  string $content    New content for writing in the file - If you pass nothing, the file will be rewritten again
 * @param  string $ref_name   A reference name for referring a content in future - In fact, this name is a key in $js_array array
 *
 * @return boolean            Returns true if the file is created and updated successfully, false on failure
 */
function auxin_save_custom_js( $content = '', $ref_name = '' ){

    // retrieve the js array list
    $js_array = get_option( THEME_ID.'_custom_js_array', array() );

    if( ! empty( $content ) ){
        if( ! empty( $ref_name ) ){
            $js_array[ $ref_name ] = $content;
        } else {
            $js_array[] = $content;
        }
    }

    // You can modify custom JavaScript content by using this filter (For instance, you can inject extra content in)
    // While passing custom javascript content, you are expected to inject array node in main array
    $js_array  = apply_filters( 'auxin_custom_js_file_content', $js_array );
    $js_string = '';

    $js_array = array_filter( $js_array );

    // Convert the contents in array to string
    if( is_array( $js_array ) ){
        foreach ( $js_array as $node_ref => $node_content ) {
            if( ! is_numeric( $node_ref) ){
                $js_string .= "/* $node_ref \n=========================*/\n";
            }
            $js_string .= "$node_content\n";
        }
    }

    // Remove <script> if user used them in the script content
    $js_string = str_replace( array( "<script>", "</script>" ), array('', ''), $js_string );

    // store javascript content in database for third party purposes
    update_option( THEME_ID.'_custom_js_string' , $js_string );
    update_option( THEME_ID.'_custom_js_array'  , $js_array  );

    if ( auxin_put_contents_dir( $js_string, 'custom.js' ) ) {

        $GLOBALS[ THEME_ID.'_custom_js_ver'] = get_option( THEME_ID.'_custom_js_ver' , 1.0 );
        $GLOBALS[ THEME_ID.'_custom_js_ver'] = (float)$GLOBALS[ THEME_ID.'_custom_js_ver'] + 0.1;

        update_option( THEME_ID.'_custom_js_ver', $GLOBALS[ THEME_ID.'_custom_js_ver'] ); // disable inline css output
        update_option( THEME_ID.'_inline_custom_js' , '' ); // disable inline css output

        return true;
    } else {
        // if the directory is not writable, try inline css fallback
        update_option( THEME_ID.'_inline_custom_js' , $js_string ); // save css rules as option to print as inline css

        return false;
    }
}


/**
 * Removes an specific content from custom js file (#admin)
 *
 * @param  string $ref_name   The reference name for referring a content in $js_array array
 *
 * @return boolean            Returns true if the content was removed successfully, false on failure
 */
function auxin_remove_custom_js( $ref_name = '' ){

    // retrieve the js array list
    $js_array = get_option( THEME_ID.'_custom_js_array', array() );

    if( isset( $js_array[ $ref_name ] ) ){
        unset( $js_array[ $ref_name ] );

        update_option( THEME_ID.'_custom_js_array'  , $js_array  );
        // update the file content too
        auxin_save_custom_js();
    }
}


/**
 * Retrieves the list of custom scripts generated with themes options
 *
 * @param  string $exclude_ref_names   The reference names that are expected to be excluded from result
 *
 * @return boolean    The list of custom scripts generated with themes options
 */
function auxin_get_custom_js_array( $exclude_ref_names = array() ){
    // retrieve the css array list
    $js_array = get_option( THEME_ID.'_custom_js_array', array() );

    return array_diff_key( $js_array, array_flip( (array) $exclude_ref_names ) );
}


//// Stores content in custom css file  /////////////////////////////////////////////


/**
 * Adds and stores css content in custom css file (#admin)
 *
 * @param  string $content    New content for writing in the file - If you pass nothing, the file will be rewritten again
 * @param  string $ref_name   A reference name for referring a content in future - In fact, this name is a key in $css_array array
 *
 * @return boolean            Returns true if the file is created and updated successfully, false on failure
 */
function auxin_save_custom_css( $content = '', $ref_name = '' ){

    // retrieve the css array list
    $css_array = get_option( THEME_ID.'_custom_css_array', array() );

    if( ! empty( $content ) ){
        if( ! empty( $ref_name ) ){
            $css_array[ $ref_name ] = $content;
        } else {
            $css_array[] = $content;
        }
    }


    // You can modify custom css content by using this filter (For instance, you can inject extra content in)
    // While passing custom css content, you are expected to inject array node in main array
    $css_array  = apply_filters( 'auxin_custom_css_file_content', $css_array );
    $css_string = '';

    $sep_comment = apply_filters( 'auxin_custom_css_sep_comment', "/* %s \n=========================*/\n" );

    // remove empty nodes
    $css_array  = array_filter( $css_array );
    $css_string = '';

    // Convert the contents in array to string
    if( is_array( $css_array ) ){
        foreach ( $css_array as $node_ref => $node_content ) {
            if( ! is_numeric( $node_ref) ){
                $css_string .= sprintf( $sep_comment, str_replace( '_', '-', $node_ref ) );
            }
            $css_string .= "$node_content\n";
        }
    }

    // Remove <style> if user used them in the style content
    $css_string = str_replace( array( "<style>", "</style>" ), array('', ''), $css_string );

    // store css content in database for third party purposes
    update_option( THEME_ID.'_custom_css_string' , $css_string );
    update_option( THEME_ID.'_custom_css_array'  , $css_array  );

    if ( auxin_put_contents_dir( $css_string, 'custom.css' ) ) {

        $GLOBALS[ THEME_ID.'_custom_css_ver'] = get_option( THEME_ID.'_custom_css_ver' , 1.0 );
        $GLOBALS[ THEME_ID.'_custom_css_ver'] = (float)$GLOBALS[ THEME_ID.'_custom_css_ver'] + 0.1;

        update_option( THEME_ID.'_custom_css_ver', $GLOBALS[ THEME_ID.'_custom_css_ver'] );
        update_option( THEME_ID.'_inline_custom_css' , '' ); // disable inline css output

        return true;
    // if the directory is not writable, try inline css fallback
    } else {
        update_option( THEME_ID.'_inline_custom_css' , $css_string ); // save css rules as option to print as inline css

        return false;
    }
}


/**
 * Removes an specific content from custom css file (#admin)
 *
 * @param  string $ref_name   The reference name for referring a content in $css_array array
 *
 * @return boolean            Returns true if the content was removed successfully, false on failure
 */
function auxin_remove_custom_css( $ref_name = '' ){

    // retrieve the css array list
    $css_array = get_option( THEME_ID.'_custom_css_array', array() );

    if( isset( $css_array[ $ref_name ] ) ){
        unset( $css_array[ $ref_name ] );

        update_option( THEME_ID.'_custom_css_array'  , $css_array  );
        // update the file content too
        auxin_save_custom_css();
    }
}


/**
 * Retrieves the list of custom styles generated with themes options
 *
 * @param  string $exclude_ref_names   The reference names that are expected to be excluded from result
 *
 * @return boolean    The list of custom styles generated with themes options
 */
function auxin_get_custom_css_array( $exclude_ref_names = array() ){
    // retrieve the css array list
    $css_array = get_option( THEME_ID.'_custom_css_array', array() );

    return array_diff_key( $css_array, array_flip( (array) $exclude_ref_names ) );
}


/**
 * Retrieves the custom styles generated with themes options
 *
 * @param  string $exclude_ref_names   The css reference names that are expected to be excluded from result
 *
 * @return boolean    The custom styles generated with themes options
 */
function auxin_get_custom_css_string( $exclude_ref_names = array() ){
    // retrieve the css array list
    $css_array  = auxin_get_custom_css_array( (array) $exclude_ref_names );
    $css_string = '';

    $sep_comment = apply_filters( 'auxin_custom_css_sep_comment', "/* %s \n=========================*/\n" );

    // Convert the contents in array to string
    if( is_array( $css_array ) ){
        foreach ( $css_array as $node_ref => $node_content ) {
            if( ! is_numeric( $node_ref) ){
                $css_string .= sprintf( $sep_comment, str_replace( '_', '-', $node_ref ) );
            }
            $css_string .= "$node_content\n";
        }
    }

    // Remove <style> if user used them in the style content
    return str_replace( array( "<style>", "</style>" ), array('', ''), $css_string );
}


//// Color Manipulation  /////////////////////////////////////////////////////////////

if( ! function_exists( 'ColorShader' ) ){

    function ColorShader( $color, $dif=20 ){

      $color = str_replace('#', '', $color);
      if (strlen($color) != 6){ return '000000'; }
      $rgb = '';

      for ($x=0;$x<3;$x++){
        $c = hexdec(substr($color,(2*$x),2)) - $dif;
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
      }

      return '#'.$rgb;
    }

}

/**
 * Extract numbers from string
 *
 * @param  string $str     The string that contains numbers
 * @param  int    $default The number which should be returned if no number found in the string
 * @return int             The extracted numbers
 */
function auxin_get_numerics( $str, $default = null ) {
    if( empty( $str ) ){
        return is_numeric( $default ) ? $default: '';
    }
    preg_match('/\d+/', $str, $matches);
    return $matches[0];
}


/*-----------------------------------------------------------------------------------*/
/*  breadcrumb functions
/*-----------------------------------------------------------------------------------*/

if( ! function_exists( 'auxin_the_breadcrumbs' ) ){

    function auxin_the_breadcrumbs() {
        $breadcrumb = new Auxin_Breadcrumb();
        $breadcrumb->render();
    }

}

/*-----------------------------------------------------------------------------------*/
/*  Returns post type menu name
/*-----------------------------------------------------------------------------------*/

if( ! function_exists( 'auxin_get_the_post_type_name' ) ){

    // returns post type menu name
    function auxin_get_the_post_type_name( $post_type = '' ){
        $post_type     = empty( $post_type ) ? get_post_type() : $post_type;
        $post_type_obj = get_post_type_object( $post_type );

        return apply_filters( 'auxin_get_the_post_type_name', $post_type_obj->labels->menu_name, $post_type );
    }

}

/*-----------------------------------------------------------------------------------*/
/*  Returns alternative for post type names if user defined custom name in option panel
/*-----------------------------------------------------------------------------------*/

if( ! function_exists( 'auxin_get_the_post_type_user_defined_name' ) ){

    function auxin_get_the_post_type_user_defined_name( $post_type = '' ){
        $post_type      = empty( $post_type ) ? get_post_type() : $post_type;
        $post_type_obj  = get_post_type_object( $post_type );

        $post_type_name = $post_type_obj->labels->menu_name;
        $alter_name     = auxin_get_option( THEME_ID.'_'.$post_type.'_custom_name' );

        return empty( $alter_name ) ? $post_type_name : $alter_name;
    }

}

/*-----------------------------------------------------------------------------------*/
/*  Generate and returns proper styles for background option fields
/*-----------------------------------------------------------------------------------*/

/**
 * Generate and returns proper styles for background option fields
 *
 * @param  string $field_prefix The prefix name for background id of the fields.
 * @param  string $field_type   Specifies the type of fields. 'option' if background fields are options, 'metafield' if they are post meta fields.
 * @param  array  $field_names  The id of different corresponding background options
 * @return string               The generated style for background option fields
 */
function auxin_generate_styles_for_backgroud_fields( $field_prefix, $field_type = 'option', $field_names = array(), $field_suffixs = array() ){

    global $post;


    if( 'option' !== $field_type ){
        $post = get_post( $post );
        if( empty( $post ) ){
            return '';
        }
    }

    $styles = '';

    if( empty( $field_names ) ){
        $field_names = array(
            'color'     => $field_prefix . '_color',
            'pattern'   => $field_prefix . '_pattern',
            'image'     => $field_prefix . '_image',
            'repeat'    => $field_prefix . '_repeat',
            'size'      => $field_prefix . '_size',
            'position'  => $field_prefix . '_position',
            'attachment'=> $field_prefix . '_attachment',
            'clip'      => $field_prefix . '_clip'
        );
    }

    if( empty( $field_suffixs ) ){
        $field_suffixs = array(
            'color'     => '',
            'pattern'   => '',
            'image'     => '',
            'repeat'    => '',
            'size'      => '',
            'position'  => '',
            'attachment'=> '',
            'clip'      => ''
        );
    }

    $background_images = '';

    foreach ( $field_names as $field_key => $field_id ) {
        if( 'option' == $field_type ){
            $field_value = auxin_get_option( $field_id );
        } else {
            $field_value = get_post_meta( $post->ID, $field_id, true );
        }

        if( empty( $field_value ) ){
            continue;
        }

        switch ( $field_key ) {
            case 'color':
                $styles .= "background-color:". $field_value .' '. ( ! empty( $field_suffixs[ $field_key ] ) ? $field_suffixs[ $field_key ] : '' ) . "; ";
                break;

            case 'image':
                $field_value = is_numeric( $field_value ) ? wp_get_attachment_url( $field_value ) : $field_value;
                if( ! empty( $background_images ) )
                    $background_images .= ', ';

                $background_images .= "url(". $field_value .') ';
                break;

            case 'pattern':
                $field_value = is_numeric( $field_value ) ? wp_get_attachment_url( $field_value ) : $field_value;
                if( ! empty( $background_images ) )
                    $background_images .= ', ';

                $background_images .= "url(". $field_value .') ';
                break;

            case 'repeat':
                $styles .= "background-repeat:". $field_value .' '. ( ! empty( $field_suffixs[ $field_key ] ) ? $field_suffixs[ $field_key ] : '' ) . "; ";
                break;

            case 'position':
                $styles .= "background-position:". $field_value .' '. ( ! empty( $field_suffixs[ $field_key ] ) ? $field_suffixs[ $field_key ] : '' ) . "; ";
                break;

            case 'attachment':
                $styles .= "background-attachment:". $field_value .' '. ( ! empty( $field_suffixs[ $field_key ] ) ? $field_suffixs[ $field_key ] : '' ) . "; ";
                break;

            case 'size':
                $styles .= "background-size:". $field_value .' '. ( ! empty( $field_suffixs[ $field_key ] ) ? $field_suffixs[ $field_key ] : '' ) . "; ";
                break;

            case 'clip':
                $styles .= "background-clip:". $field_value .' '. ( ! empty( $field_suffixs[ $field_key ] ) ? $field_suffixs[ $field_key ] : '' ) . "; ";
                break;
        }
    }

    if( ! empty( $background_images ) ){
        $styles .= "background-image:" . $background_images . ( ! empty( $field_suffixs[ 'image' ] ) ? $field_suffixs[ 'image' ] : '' ) . "; ";
    }

    return $styles;
}

/*-----------------------------------------------------------------------------------*/
/*  Ensures a string is a valid SQL order clause.
/*-----------------------------------------------------------------------------------*/

if( ! function_exists( 'sanitize_sql_order' ) ){

    function sanitize_sql_order( $order ){
        return 'asc' === strtolower( $order ) ? 'ASC' : 'DESC';
    }

}

/*-----------------------------------------------------------------------------------*/
/*  Triggers a user error if WP_DEBUG is true.
/*-----------------------------------------------------------------------------------*/

if( ! function_exists( 'auxin_error' ) ){

    function auxin_error( $error ){
        if ( WP_DEBUG && apply_filters( 'auxin_trigger_error_message', true ) ) {
            trigger_error( $error );
        }
    }

}

/*-----------------------------------------------------------------------------------*/
/*  A function to print array or objects nicely!
/*-----------------------------------------------------------------------------------*/
/**
 * Retrieves the list of socials for site or an specific part of website
 *
 * @param  string  $type           the name of social list. Pass 'site', if you want
 *                                 to get the main socials of the website
 * @param  boolean $include_values True, to include the address of the socials in
 *                                 result; false, to return the name of socials
 *
 * @return array                   The list socials for an specific type
 */
function auxin_get_social_list( $type = 'site', $include_values = false ) {

    $socials_dictionary = array();

    $socials_dictionary['site'] = array(
        'facebook'    => '', 'twitter'     => '',
        'googleplus'  => '', 'dribbble'    => '',
        'youtube'     => '', 'vimeo'       => '',
        'flickr'      => '', 'digg'        => '',
        'stumbleupon' => '', 'lastfm'      => '',
        'delicious'   => '', 'skype'       => '',
        'linkedin'    => '', 'tumblr'      => '',
        'pinterest'   => '', 'instagram'   => '',
        'rss'         => ''
    );

    if( ! isset( $socials_dictionary[ $type ] ) ){
        $socials_dictionary[ $type ] = array();
    }

    // whether to fill the values with social address or not
    if( $include_values ){
        foreach ( $socials_dictionary[ $type ] as $name => $value ) {
            $socials_dictionary[ $type ][ $name ] = auxin_get_option( $name );
        }
    }

    return apply_filters( 'auxin_get_social_list', $socials_dictionary[ $type ], $type, $include_values );
}

/*-----------------------------------------------------------------------------------*/
/*  A function to generate header and footer for all widgets
/*-----------------------------------------------------------------------------------*/

function auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content = '' ){

    $result = array(
        'parsed_atts'   => '',
        'widget_info'   => '',
        'widget_header' => '',
        'widget_title'  => '',
        'widget_footer' => ''
    );

    // ----
    if( ! isset( $default_atts['extra_classes'] ) ){
        $default_atts['extra_classes'] = '';
    }
    if( ! isset( $default_atts['custom_el_id'] ) ){
        $default_atts['custom_el_id'] = '';
    }
    if( ! isset( $default_atts['content'] ) ){
        $default_atts['content'] = '';
    }

    // Widget general info
    $before_widget = $after_widget  = '';
    $before_title  = $after_title   = '';

    // If widget info is passed, extract them in above variables
    if( isset( $atts['widget_info'] ) ){
        $result['widget_info'] = $atts['widget_info'];
        extract( $atts['widget_info'] );
    }
    // CSS class names for section -------------

    // The default CSS classes for widget container
    // Note that 'widget-container' should be in all element
    $_css_classes = array( 'widget-container' );

    // Parse shortcode attributes
    $parsed_atts = shortcode_atts( $default_atts, $atts, __FUNCTION__ );

    if( empty( $parsed_atts['content'] ) ){
        $parsed_atts['content'] = $shortcode_content;
    }

    $result['parsed_atts'] = $parsed_atts;


    // Defining extra class names --------------
    // Add extra class names to class list here - widget-{element_name}
    $_css_classes[] = $parsed_atts['base_class'];

    // Covering classes in list to class attribute for main section
    $section_class_attr = auxin_make_html_class_attribute( $_css_classes, $parsed_atts['extra_classes'] );


    if( $before_widget ){
        $result['widget_header'] .= str_replace( array('class="', '<div'), array( 'class="' . $parsed_atts['base_class'] . ' widget-container ', '<section' ), $before_widget );
    } elseif ( !empty($parsed_atts['custom_el_id']) ){
        $result['widget_header'] .= sprintf('<section id="%s" %s>', $parsed_atts['custom_el_id'], $section_class_attr );
    } else {
        $result['widget_header'] .= sprintf('<section %s>', $section_class_attr );
    }

    if( isset( $parsed_atts['title'] ) && ! empty( $parsed_atts['title'] ) ){
        if( $before_title ){
            $result['widget_title'] .= $before_title . $parsed_atts['title'] . $after_title;
        } elseif( ! empty( $title ) ){
            $result['widget_title'] .= get_widget_title( $parsed_atts['title'] );
        }
    }

    if( $after_widget ){
        // fix for the difference in end tag in siteorigin page builder
        $result['widget_footer'] .= str_replace( '</div', '</section', $after_widget );
    } else {
        $result['widget_footer'] .= '</section><!-- widget-container -->';
    }

    return $result;
}

/*----------------------------------------------------------------------------*/
/*  A function to print array or objects nicely!
/*----------------------------------------------------------------------------*/

/**
 * Retrieves a URL using the HTTP POST method
 *
 * @return mixed|boolean    The body content
 */
function auxin_remote_post( $url, $args ) {
    $request = wp_remote_post( $url, $args );

    if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
        return $request['body'];
    }
    return false;
}

/**
 * Retrieves a URL using the HTTP GET method
 *
 * @return mixed|boolean    The body content
 */
function auxin_remote_get( $url, $args ) {
    $request = wp_remote_get( $url, $args );

    if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
        return $request['body'];
    }
    return false;
}


if( ! function_exists( 'remove_filter_from_class' ) ){

    /**
     * Removes a class method from a specified filter hook.
     *
     * @param  string  $hook_name   The filter hook to which the function to be removed is hooked
     * @param  string  $class_name  The name of class which its method should be removed.
     * @param  string  $method_name The name of the method which should be removed.
     * @param  integer $priority    Optional. The priority of the function. Default 10.
     * @return bool                 Whether the function existed before it was removed.
     */
    function remove_filter_from_class( $hook_name = '', $class_name ='', $method_name = '', $priority = 10 ) {
        global $wp_filter;

        // Take only filters on right hook name and priority
        if ( !isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority]) )
            return false;

        // Loop on filters registered
        foreach( (array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array ) {
            // Test if filter is an array ! (always for class/method)
            if ( isset($filter_array['function']) && is_array($filter_array['function']) ) {
                // Test if object is a class, class and method is equal to param !
                if ( is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && get_class($filter_array['function'][0]) == $class_name && $filter_array['function'][1] == $method_name ) {
                    unset($wp_filter[$hook_name][$priority][$unique_id]);
                }
            }

        }

        return false;
    }

}




/**
 * Whether a plugin is active or not
 *
 * @param  string $plugin_basename  plugin directory name and mail file address
 * @return bool                     True if plugin is active and FALSE otherwise
 */
if( ! function_exists( 'auxin_is_plugin_active' ) ){
    function auxin_is_plugin_active( $plugin_basename ){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active( $plugin_basename );
    }
}

/*-----------------------------------------------------------------------------------*/
/*  A function to insert latest post slider
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'auxin_get_latest_posts_slider' ) ) {

    function auxin_get_latest_posts_slider ( $options = array() ) {

        if( ! defined( 'AUXELS_VERSION' ) ){
            return;
        }

        $defaults = array (
            'slides_num'            => auxin_get_option('post_archive_slider_slides_num', '10'),
            'exclude'               => auxin_get_option('post_archive_slider_exclude'),
            'include'               => auxin_get_option('post_archive_slider_include'),
            'offset'                => auxin_get_option('post_archive_slider_offset'),
            'order_by'              => auxin_get_option('post_archive_slider_order_by', 'date'),
            'order_dir'             => auxin_get_option('post_archive_slider_order_dir', 'DESC'),
            'skin'                  => auxin_get_option('post_archive_slider_skin', 'aux-light-skin'),
            'add_title'             => auxin_get_option('post_archive_slider_add_title', '1'),
            'add_meta'              => auxin_get_option('post_archive_slider_add_meta', '1'),
            'image_from'            => auxin_get_option('post_archive_slider_image_from', 'auto'),
            'custom_image'          => auxin_get_option('post_archive_slider_custom_image'),
            'exclude_without_image' => auxin_get_option('post_archive_slider_exclude_without_images', '1'),
            'width'                 => auxin_get_option('post_archive_slider_width', '960' ),
            'height'                => auxin_get_option('post_archive_slider_height', '560'),
            'arrows'                => auxin_get_option('post_archive_slider_arrows', '0'),
            'space'                 => auxin_get_option('post_archive_slider_space', '5'),
            'loop'                  => auxin_get_option('post_archive_slider_loop', '1'),
            'slideshow'             => auxin_get_option('post_archive_slider_slideshow', '0'),
            'slideshow_delay'       => auxin_get_option('post_archive_slider_slideshow_delay', '2')
        );

        $options = wp_parse_args( $options, $defaults );

        // generate the shortcode
        $post_slider_shortcode = '[aux_latest_posts_slider ';

        foreach ( $options as $key => $value ) {
            $post_slider_shortcode .= $key . '="' . $value . '" ';
        }

        $post_slider_shortcode .= ']';

        return do_shortcode( $post_slider_shortcode );
    }
}

/* ------------------------------------------------------------------------------ */

/*-----------------------------------------------------------------------------------*/
/*  A function to get the custom style of Google maps
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'auxin_get_gmap_style' ) ) {

    function auxin_get_gmap_style () {

        $styler = '[{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#e9e5dc"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"poi.medical","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"poi.sports_complex","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54},{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"water","elementType":"all","stylers":[{"saturation":43},{"lightness":-11},{"color":"#89cada"}]}]';

        return apply_filters( 'auxin_gmap_style', $styler );
    }
}


function auxin_get_background_patterns( $insert_array = array(), $where_to_insert = 'after' ){

    $patterns = array(
        THEME_URL . 'css/images/pattern/p1.png'=>
            array(
                'label' =>__('pattern 1', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/pattern1.png'
            ),
        THEME_URL . 'css/images/pattern/p2.png'=>
            array(
               'label' =>__('pattern 2', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern2.png'
            ),
        THEME_URL . 'css/images/pattern/p3.png'=>
            array(
               'label' =>__('pattern 3', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern3.png'
            ),
        THEME_URL . 'css/images/pattern/p4.png'=>
            array(
               'label' =>__('pattern 4', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern4.png'
            ),
        THEME_URL . 'css/images/pattern/p5.png'=>
            array(
               'label' =>__('pattern 5', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern5.png'
            ),
        THEME_URL . 'css/images/pattern/p6.png'=>
            array(
               'label' =>__('pattern 6', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern6.png'
            ),
        THEME_URL . 'css/images/pattern/p7.png'=>
            array(
               'label' =>__('pattern 7', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern7.png'
            ),
        THEME_URL . 'css/images/pattern/p8.png'=>
            array(
               'label' =>__('pattern 8', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern8.png'
            ),
        THEME_URL . 'css/images/pattern/p9.png'=>
            array(
               'label' =>__('pattern 9', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern9.png'
            ),
        THEME_URL . 'css/images/pattern/p10.png'=>
            array(
               'label' =>__('pattern 10', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern10.png'
            ),
        THEME_URL . 'css/images/pattern/p11.png'=>
            array(
               'label' =>__('pattern 11', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern11.png'
             ),
        THEME_URL . 'css/images/pattern/p12.png'=>
            array(
               'label' =>__('pattern 12', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern12.png'
            ),
        THEME_URL . 'css/images/pattern/p14.png'=>
            array(
               'label' =>__('pattern 14', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern14.png'
            ),
        THEME_URL . 'css/images/pattern/p15.png'=>
            array(
               'label' =>__('pattern 15', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern15.png'
            ),
        THEME_URL . 'css/images/pattern/p16.png'=>
            array(
               'label' =>__('pattern 16', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern16.png'
            ),
        THEME_URL . 'css/images/pattern/p17.png'=>
            array(
               'label' =>__('pattern 17', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern17.png'
            ),
        THEME_URL . 'css/images/pattern/p18.png'=>
            array(
               'label' =>__('pattern 18', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern18.png'
            ),
        THEME_URL . 'css/images/pattern/p19.png'=>
            array(
               'label' =>__('pattern 19', 'phlox'),
               'image' => AUX_URL . 'images/visual-select/pattern19.png'
            )


    );

    if( ! empty( $insert_array ) ){
        if( 'after' == $where_to_insert ){
            $patterns = array_merge( $patterns, (array)$insert_array );
        } else {
            $patterns = array_merge( (array)$insert_array, $patterns );
        }
    }

    return apply_filters( 'auxin_get_background_patterns', $patterns );
}


/**
 * Retrieves the type of top header layout for current page
 */
if( ! function_exists( 'auxin_get_top_header_layout' ) ) {
     function auxin_get_top_header_layout( $post_id = '' ){
        $post   = get_post( $post_id );
        $layout = '';

        if( ! empty( $post->ID ) ){
            $layout = get_post_meta( $post->ID, 'page_header_top_layout', true );
        }
        if( empty( $layout ) || 'default' == $layout ){
            $layout = auxin_get_option( 'site_header_top_layout' );
        }
        return $layout;
    }
}


/**
 * Retrieves the content between two characters
 */
if( ! function_exists( 'auxin_get_string_between' ) ) {
     function auxin_get_string_between( $string, $start, $end ){
        $string  = ' ' . $string;
        $ini     = strpos($string, $start);
        if ($ini == 0) return '';
        $ini     += strlen($start);
        $len     = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }
}

/*-----------------------------------------------------------------------------------*/
/*  A function to print array or objects nicely!
/*-----------------------------------------------------------------------------------*/

function auxin_wp_get_image_size( $size_name ){
    global $_wp_additional_image_sizes;

    if( ! empty( $_wp_additional_image_sizes[ $size_name ] ) ){
        return $_wp_additional_image_sizes[ $size_name ];
    } elseif ( get_option( $size_name . '_size_w' ) || get_option( $size_name . '_size_w' ) ){
        return array(
            'width'  => get_option( $size_name . '_size_w' ),
            'height' => get_option( $size_name . '_size_h' ),
            'crop'   => get_option( $size_name . '_crop'   )
        );
    } else {
        return false;
    }
}

/*-----------------------------------------------------------------------------------*/
/*  A function to print array or objects nicely!
/*-----------------------------------------------------------------------------------*/

/**
 * Prints Pretty human-readable information about a variable (developer debug tool)
 *
 * @param  mixed             The expression to be printed.
 * @param  boolean $dump     Whether to dump information about a variable or not
 * @param  boolean $return   When this parameter is set to TRUE, it will return the information rather than print it.
 * @return bool              When the return parameter is TRUE, this function will return a string. Otherwise, the return value is TRUE.
 */
if ( ! function_exists( 'axpp' ) ) {

    function axpp ( $expression, $dump = false, $return = false ) {
        if ( $return ) {
            return '<pre>' . print_r( $expression , true ) . '</pre>';
        } elseif ( $dump ) {
            echo '<pre>'; var_dump( $expression ); echo '</pre>';
        } else {
            echo '<pre style="margin-left:170px;">'; print_r ( $expression ); echo '</pre>';
        }
        return true;
    }

}
