<?php
/**
 * Register widgetized areas
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;




function auxin_theme_widgets_init() {

//---- Default sidebar widget areas --------------------------------------

    register_sidebar( array(
        'name'          => __( 'Global Primary Widget Area', 'phlox'),
        'id'            => 'auxin-global-primary-sidebar-widget-area',
        'description'   => __( 'The primary sidebar which is accessible on all pages.' , 'phlox'),
        'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

    register_sidebar( array(
        'name'          => __( 'Global Secondary Widget Area' , 'phlox'),
        'id'            => 'auxin-global-secondary-sidebar-widget-area',
        'description'   => __( 'The secondary sidebar which is accessible on all pages.' , 'phlox'),
        'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

    register_sidebar( array(
        'name'          => __( 'Blog Primary Widget Area' , 'phlox'),
        'id'            => 'auxin-blog-primary-sidebar-widget-area',
        'description'   => __( 'The primary sidebar which is accessible only on blog pages.' , 'phlox'),
        'before_widget' => '<section id="%1$s" class="widget-container %2$s ">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

    register_sidebar( array(
        'name'          => __( 'Blog Secondary Widget Area' , 'phlox'),
        'id'            => 'auxin-blog-secondary-sidebar-widget-area',
        'description'   => __( 'The secondary sidebar which is accessible only on blog pages.' , 'phlox'),
        'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

    register_sidebar( array(
        'name'          => __( 'Search Result Widget Area' , 'phlox'),
        'id'            => 'auxin-search-sidebar-widget-area',
        'description'   => __( 'The which is accessible only on search result page.' , 'phlox'),
        'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));


    //---- Footer sidebar (subfooter top bar) widget areas --------------------------------------
    register_sidebar( array(
        'name'          => __( 'Subfooter Bar Widget Area' , 'phlox'),
        'id'            => 'auxin-subfooter-bar-widget-area',
        'description'   => __( 'The subfooter bar widget area.' , 'phlox'),
        'before_widget' => '<section id="%1$s" class="widget-container %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>'
    ));

    //---- Footer sidebar widget areas --------------------------------------

    // get number of active subfooters
    // user can change this number via option panel
    $layout    = auxin_get_option( 'subfooter_layout' );
    $grid_cols = explode( '_', $layout);
    $col_nums  = count( $grid_cols );

    $footer_names = array( "First", "Second", "Third", "Fourth", "Fifth" );

    for ( $i=1; $i <= $col_nums; $i++ ) {

        register_sidebar( array(
            'name'          => sprintf(__( 'Subfooter %s Widget Area', 'phlox'), $footer_names[ $i - 1 ]),
            'id'            => 'auxin-footer'.$i.'-sidebar-widget-area',
            'description'   => sprintf(__( 'The %s Column in Footer.' , 'phlox'), $footer_names[ $i - 1 ]),
            'before_widget' => '<section id="%1$s" class="widget-container %2$s _ph_">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ) );
    }

    unset( $layout, $grid_cols, $col_nums ,$footer_names );


    //---- Sidebar generator -------------------------------------------------------------

    // get and register all user define sidebars
    $auxin_sidebars = get_option( THEME_ID.'_sidebars' );

    if( isset( $auxin_sidebars )  && ! empty( $auxin_sidebars ) ) {
        foreach( $auxin_sidebars as $key => $value ) {
            $sidebar_id = THEME_ID .'-'. strtolower( str_replace( ' ', '-', $value ) );

            register_sidebar( array(
                'name'          => $value,
                'id'            => $sidebar_id,
                'description'   => '',
                'before_widget' => '<section id="%1$s" class="widget-container %2$s _ph_">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>'
            ) );

        }
    }
}

add_action( 'widgets_init', 'auxin_theme_widgets_init' );
