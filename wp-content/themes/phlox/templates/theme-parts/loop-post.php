<?php /* Loops through all posts, taxes, .. and display posts */

global $query_string;

// print the post slider
echo auxin_get_the_archive_slider( 'post', 'content' );

// the page number
$paged            = max( 1, get_query_var('paged'), get_query_var('page') );
// get template type id
$template_type_id = auxin_get_option( 'post_index_template_type', 'default' );
// posts perpage
$per_page         = get_option( 'posts_per_page' );


// if template type is timeline
if( 7 == $template_type_id ){

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
        'timeline_alignment' => auxin_get_option( 'post_index_timeline_alignment', 'center' ),
        'tag'                => '',
        'reset_query'        => false,
        'use_wp_query'       => true
    );

    if( function_exists( 'auxin_widget_recent_posts_timeline_callback' ) ){
        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_timeline_callback( $args );
    } else {
        $result = __('To enable this feature, please install "Auxin Elements" plugin.');
    }

// if template type is grid
} else if( 5 == $template_type_id ){

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
        'content_layout'     => auxin_get_option( 'post_index_column_content_layout', 'full' ) ,
        'show_excerpt'       => true,
        'excerpt_len'        => '140',
        'show_title'         => true,
        'show_info'          => false,
        'show_readmore'      => true,
        'show_author_footer' => false,
        'desktop_cnum'       => $desktop_cnum,
        'tablet_cnum'        => $tablet_cnum,
        'phone_cnum'         => $phone_cnum,
        'preview_mode'       => 'grid',
        'tag'                => '',
        'reset_query'        => false,
        'use_wp_query'       => true,
        'extra_classes'      => ''
    );

    if( function_exists( 'auxin_widget_recent_posts_callback' ) ){
        // get the shortcode base blog page
        $result = auxin_widget_recent_posts_callback( $args );
    } else {
        $result = __('To enable this feature, please install "Auxin Elements" plugin.');
    }

// if it is normal blog loop
} else {
    $q_args = '&paged='. $paged. '&posts_per_page='. get_option( 'posts_per_page' );

    // query the posts
    query_posts( $query_string . $q_args );
    // does this query has result?
    $result = have_posts();
}


// if it is not a shortcode base blog page
if( true === $result ){

    while ( have_posts() ) : the_post();
        include 'entry/post.php';
    endwhile; // end of the loop.

// if it is a shortcode base blog page
} else if( ! empty( $result ) ){
    echo $result;

// if result not found
} else {
    include 'content-none.php';
}


auxin_the_paginate_nav(
    array( 'css_class' => auxin_get_option('archive_pagination_skin') )
);
