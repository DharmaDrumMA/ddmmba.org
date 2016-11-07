<?php
/**
 * Layout option for pages
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


/*==================================================================================================

    Add Page Option meta box

 *=================================================================================================*/

function auxin_metabox_fields_general_layout(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'layout-options';
    $model->title  = __( 'Layout Options', 'phlox');
    $model->fields = array(

        array(
            'title'         => __('Content Layout', 'phlox'),
            'description'   => __('If you select "Full", Content fills the full width of the page.', 'phlox'),
            'id'            => 'content_layout',
            'type'          => 'radio-image',
            'default'       => 'boxed',
            'dependency'    => '',
            'choices'       => array(
                'boxed' => array(
                    'label'     => __('Boxed Content', 'phlox'),
                    'css_class' => 'axiAdminIcon-content-boxed'
                ),
                'full' => array(
                    'label' => __('Full Content', 'phlox'),
                    'css_class' => 'axiAdminIcon-content-full'
                )
            )
        ),

        array(
            'title'       => __('Sidebar Layout', 'phlox'),
            'description' => __('Specifies the position of sidebar on this page.', 'phlox'),
            'id'          => 'page_layout',
            'type'        => 'radio-image',
            'default'     => 'no-sidebar',
            'choices'     => array(
                'no-sidebar' => array(
                    'label'  => __('No Sidebar', 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-none'
                ),
                'right-sidebar' => array(
                    'label'  => __('Right Sidebar', 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-right'
                ),
                'left-sidebar' => array(
                    'label'  => __('Left Sidebar' , 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-left'
                ),
                'left2-sidebar' => array(
                    'label'  => __('Left Left Sidebar' , 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-left-left'
                ),
                'right2-sidebar' => array(
                    'label'  => __('Right Right Sidebar' , 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-right-right'
                ),
                'left-right-sidebar' => array(
                    'label'  => __('Left Right Sidebar' , 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-left-right'
                ),
                'right-left-sidebar' => array(
                    'label'  => __('Right Left Sidebar' , 'phlox'),
                    'css_class' => 'axiAdminIcon-sidebar-right-right'
                )
            )
        ),
        array(
            'title'       => __('Sidebar Style', 'phlox'),
            'description' => __('Specifies the style of sidebar on this page.', 'phlox'),
            'id'          => 'page_sidebar_style',
            'type'        => 'radio-image',
            'default'     => 'simple',
            'choices'     => array(
                'simple'  => array(
                    'label'  => __( 'Simple' , 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/sidebar-style-1.svg'
                ),
                'border' => array(
                    'label'  => __( 'Bordered Sidebar' , 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/sidebar-style-2.svg'
                ),
                'overlap' => array(
                    'label'  => __( 'Overlap Background' , 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/sidebar-style-3.svg'
                )
            )
        ),

        array(
            'title'         => __('Display Content Top Margin', 'phlox'),
            'description'   => __('whether to display a space between title and content or not. If you need to start your content from very top of the page, disable it.', 'phlox'),
            'id'            => 'show_content_top_margin',
            'type'          => 'switch',
            'default'       => '1'
        ),

        array(
            'title'       => __('Header Layout', 'phlox'),
            'description' => __('Specifies the header layout on this page.', 'phlox'),
            'id'          => 'page_header_top_layout',
            'type'        => 'radio-image',
            'choices'     => array(
                'default' => array(
                    'label' => __('Theme Default', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/default3.svg'
                ),
                'horizontal-menu-right' => array(
                    'label' => __('Logo left, Menu right', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-1.svg'
                ),
                'burger-right' => array(
                    'label' => __('Logo left, Burger menu right', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-2.svg'
                ),
                'horizontal-menu-left' => array(
                    'label'     => __('Logo right, Menu left', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-7.svg'
                ),
                'burger-left' => array(
                    'label' => __('Logo Right, Burger menu left', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-8.svg'
                ),
                'horizontal-menu-center' => array(
                    'label' => __('Logo middle in top, Menu middle in bottom', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-4.svg'
                ),
                /*
                'logo-in-middle-menu' => array(
                    'label'     => __('Logo in middle of the menu', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-9.svg'
                ),
                'logo-left-menu-right-over' => array(
                    'label' => __('Logo and menu over content', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-5.svg'
                ),*/
                'logo-left-menu-bottom-left' => array(
                    'label' => __('Logo left in top, Menu left in bottom', 'phlox'),
                    'image' => AUX_URL . 'images/visual-select/header-layout-3.svg'
                )
            ),
            'default'   => 'default'
        )

    );

    return $model;
}
