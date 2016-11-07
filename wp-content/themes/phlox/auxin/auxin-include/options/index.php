<?php
/**
 * Generates option panel
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;

// Init option panel
Auxin_Option::api();

// admin hooks for option panel
require( 'auxin-panel-hooks.php' );


/* ---------------------------------------------------------------------------------------------------
   Field types
------------------------------------------------------------------------------------------------------

- switch/checkbox
- color
- color-alpha
- dropdown-pages
- editor
- multicheck (@TODO in option panel)
- number
    'choices' => array(
        'min'  => 1,
        'max'  => 100,
        'step' => 1
    ),
- url
- palette
    'choices' => array(
        'red' => array(
            '#ef9a9a',
            '#f44336',
            '#ff1744',
        ),
        'pink' => array(
            '#fce4ec',
            '#f06292',
            '#e91e63',
            '#ad1457',
            '#f50057',
        ),
        'cyan' => array(
            '#e0f7fa',
            '#80deea',
            '#26c6da',
            '#0097a7',
            '#00e5ff',
        )
    ),
- radio-image
    'choices' => array(
        'option_id_1'  => array(
            'label'     => __('Option ID 1','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img1-2x.png'
        ),
        'option_id_2'  => array(
            'label'     => __('Option ID 2','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img2-2x.png'
        ),
        'option_id_3'  => array(
            'label'     => __('Option ID 3','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img3-2x.png'
        )
    )
- radio (@TODO in option panel)
- select
    'choices' => array(
        'option_id_1'  => array(
            'label'     => __('Option ID 1','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img1-2x.png'
        ),
        'option_id_2'  => array(
            'label'     => __('Option ID 2','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img2-2x.png'
        ),
        'option_id_3'  => array(
            'label'     => __('Option ID 3','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img3-2x.png'
        )
    )
- select2
    'choices' => array(
        'option_id_1'  => array(
            'label'     => __('Option ID 1','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img1-2x.png'
        ),
        'option_id_2'  => array(
            'label'     => __('Option ID 2','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img2-2x.png'
        ),
        'option_id_3'  => array(
            'label'     => __('Option ID 3','phlox'),
            'css_class' => 'the_css_class',
            'image'     => admin_url() . '/images/img3-2x.png'
        )
    )
- select2-multiple
- text
- textarea
- toggle (@TODO in option panel)

// 'Background_Image' extends 'Image' extends 'Upload' extends 'Media' ---------
//                 'Site_Icon' extends 'Cropped_image' extends 'Media' ---------

- background_image (@TODO in option panel)
- image
    - button_labels => array(
        'select'       => __( 'Select Image', 'phlox' ),
        'change'       => __( 'Change Image', 'phlox' ),
        'remove'       => __( 'Remove', 'phlox' ),
        'default'      => __( 'Default', 'phlox' ),
        'placeholder'  => __( 'No image selected', 'phlox' ),
        'frame_title'  => __( 'Select Image', 'phlox' ),
        'frame_button' => __( 'Choose Image', 'phlox' ),
    )
- upload
- media
    - mime_type => array( 'image', 'audio', 'video', 'pdf' ),
    - button_labels => array(
        'select'       => __( 'Select File', 'phlox' ),
        'change'       => __( 'Change File', 'phlox' ),
        'default'      => __( 'Default', 'phlox' ),
        'remove'       => __( 'Remove', 'phlox' ),
        'placeholder'  => __( 'No file selected', 'phlox' ),
        'frame_title'  => __( 'Select File', 'phlox' ),
        'frame_button' => __( 'Choose File', 'phlox' ),
    ),
    - canUpload
- cropped_image (@TODO in option panel)
    - 'flex_width'  => true, // Allow any width, making the specified value recommended. False by default.
    - 'flex_height' => false, // Require the resulting image to be exactly as tall as the height attribute (default).
    - 'width'       => 1920,
    - 'height'      => 1080
- background
    'default' => array(
        'color'    => 'rgba(255,255,255,1)',
        'image'    => '',
        'repeat'   => 'no-repeat',
        'size'     => 'cover',
        'attach'   => 'fixed',
        'pattern'  => '',
        'position' => 'left-top',
    ),
    'choices' => array(
        'color'    => array(
            'title'       => '',
            'description' => '',
            'dependency'  => ''
        ),
        'image'    => array(
            'title'       => '',
            'description' => '',
            'dependency'  => ''
        ),
        'repeat'   => array(
            'title'       => '',
            'description' => '',
            'dependency'  => ''
        ),
        'size'     => array(
            'title'       => '',
            'description' => '',
            'dependency'  => ''
        ),
        'attach'   => array(
            'title'       => '',
            'description' => '',
            'dependency'  => ''
        ),
        'position' => array(
            'title'       => '',
            'description' => '',
            'dependency'  => ''
        )
    ),
- area_customize (@TODO in option panel)
- site_icon (@TODO in option panel)
- slider (@TODO in option panel)
    'choices' => array(
        'min'  => 1,
        'max'  => 100,
        'step' => 1
    ),



------------------------------------------------------------------------------------------------------
   Arguments
------------------------------------------------------------------------------------------------------

array(
    'type'        => 'select',
    'setting'     => 'slider_demo_2',
    'title'       => __( 'Font-Family', 'phlox' ),
    'description' => __( 'Sample description.', 'phlox' ),
    'help'        => __( 'Extra description.', 'phlox' ),
    'section'     => 'the_generals',
    'default'     => 'Roboto',
    'priority'    => 10,
    'choices'     => array(
        'option-1' => __( 'Option 1', 'phlox' ),
        'option-2' => __( 'Option 2', 'phlox' ),
        'option-3' => __( 'Option 3', 'phlox' ),
        'option-4' => __( 'Option 4', 'phlox' ),
        'option-5' => __( 'Option 5', 'phlox' ),
    ),

    'transport'   => 'postMessage',
    'post_js'     => '$("body").toggleClass( "aux-resp", 1 == to );', // 'to' is the placeholder for the real value
    'partial'     => array(
        'selector'              => '.the-css-selector-for-partial',
        'container_inclusive'   => false,
        'render_callback'       => function(){ echo "something"; }
    ),
    'style_callback' => function( $css = null ){

        // The css selectors and properties ( |to| will be replaced with real option value )
        $style = ".site-header-section .aux-header-elements { height:|to|";

        // If the callback was called from customizer for live preview
        if( null === $css ){
            return $style;

        // If the callback was called to return the real styles for saving in custom css file
        } else {
            $to = trim( auxin_get_option( 'site_header_container_height', 85 ), 'px' );
            // Consider an ID for this css code and replace the placeholder with real option value
            $css['header-height'] = str_replace( '|to|', $to, $style );
            return $css; // Make sure to return the CSS list at the end
        }
    },

    'dependency'  => array(
        array(
            'setting'  => 'my_checkbox',
            'operator' => '==',
            'value'    => 1,
        ),
        array(
            'setting'  => 'my_radio',
            'operator' => '!=',
            'value'    => 'option-1',
        )
    ),
    'add_to' => 'all' // 'option_panel', 'customizer'
)

------------------------------------------------------------------------------------------------------
   Sample Section
------------------------------------------------------------------------------------------------------

array(
    'id'         => 'the-setting-section',
    'parent'     => '', // section parent's id
    'title'      => __( 'Section title', 'phlox'),
    'priority'   => 160, // optional. Default 160
    'desc'       => __( 'Section description', 'phlox'),
    'icon'       => 'axicon-tools',
    'capability' => 'edit_theme_options', // optional. Default 'edit_theme_options'
    'add_to' => 'all' // 'option_panel', 'customizer'
);

------------------------------------------------------------------------------------------------------
   Sample Options
------------------------------------------------------------------------------------------------------


//////// dependency array (common for all fields) //////////////////////////

'dependency'=> array(
    'relation' => 'AND', // (optional) AND or OR, default value is AND
    array(
         'id'      => '', // the observer id (the field which we are watching its value)
         'value'   => array(''), // [array/string] these are values that we are watching for
         'callback'=> '' // (optional)
    ),
    array(
         'id'      => '', // the observer id (the field which we are watching its value)
         'value'   => array(''), // [array/string] these are values that we are watching for
         'callback'=> '' // (optional)
    )
),


////////// Visual Select /////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'choices' => array(
        'val' => array(
            'label' => __('val1', 'phlox'),
            'image' => AUX_INC_URL.'options/assets/images/layouts/2-11.jpg',
            'css_class' => '' // optional - useful for adapting font icons
        ),
        'val2' => array(
            'label' => __('val2', 'phlox'),
            'image' => AUX_INC_URL.'options/assets/images/layouts/2-11.jpg',
            'css_class' => '' // optional - useful for adapting font icons
        ),
        'val3' => array(
            'label' => __('val3' , 'phlox'),
            'image' => AUX_INC_URL.'options/assets/images/layouts/2-11.jpg',
            'css_class' => '' // optional - useful for adapting font icons
        )
    ),
    'default'   => 'val2',
    'type'      => 'radio-image'
),


////////// Dropdown /////////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'choices'   => array(   'option_value1'  => 'option_label1',
                            'option_value2'  => 'option_label2' ),
    'default'   => 'full',
    'type'      => 'select'
),


////////// Icon selector ////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'default'   => '',
    'type'      => 'icon'
),

////////// Switch /////////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'default'   => '',
    'type'      => 'switch'
),


////////// Color ///////////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'default'   => '#eeeeee',
    'type'      => 'color'
),


////////// Upload //////////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'default'   => '',
    'type'      => 'upload'
),


////////// Typography //////////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array('checked'),
             'callback'=> ''
        )
    ),
    'default'   => array('font' => '_gof_Open Sans:300', 'color'=>'3D3D3D'),
    'type'      => 'typography'
),


////////// Information block ////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array('checked'),
             'callback'=> ''
        )
    ),
    'default'   => '',
    'type'      => 'sep'
),


////////// WP Editor ///////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'type'      => 'editor',
    'default'   => ''
),


////////// Code Editor //////////////////////////////////////////////////////

array(
    'title'     => __('Title', 'phlox'),
    'desc'      => __('Description', 'phlox'),
    'id'        => 'unique_id',
    'section'   => 'section_unique_id',
    'dependency'=> array(
        array(
             'id'      => '',
             'value'   => array(''),
             'callback'=> ''
        )
    ),
    'default'   => 'css', // css, javascript, php
    'type'      => 'code'
),


------------------------------------------------------------------------------------------------------
   Group of options
------------------------------------------------------------------------------------------------------

////////// Background group ////////////////////////////////////////////////

$options[] = array( 'title'     => __('Enable Background?', 'phlox'),
                    'desc'      => __('Do You want to display custom background for this page?', 'phlox'),
                    'id'        => '{bg_prefix_id}_show',
                    'section'   => 'tools-setting-section-login',
                    'type'      => 'checkbox',
                    'wrapper_class' => 'collaspe-head',
                    'default'   => 'off');

$options[] = array( 'title'     => __('Background color', 'phlox'),
                    'desc'      => __('Do You want to display custom background for login page?', 'phlox'),
                    'id'        => '{bg_prefix_id}_color',
                    'section'   => 'tools-setting-section-login',
                    'dependency'=> array(
                        array(
                             'id'      => '{bg_prefix_id}_show',
                             'value'   => array('checked'),
                             'callback'=> ''
                        )
                    ),
                    'type'      => 'color',
                    'default'   => '' );

$options[] = array( 'title'     => __('Background repeat', 'phlox'),
                    'desc'      => __('Specifies how background image repeats', 'phlox'),
                    'id'        => '{bg_prefix_id}_repeat',
                    'section'   => 'tools-setting-section-login',
                    'type'      => 'radio-image',
                    'dependency'=> array(
                        array(
                             'id'      => '{bg_prefix_id}_show',
                             'value'   => array('checked'),
                             'callback'=> ''
                        )
                    ),
                    'choices' => array(
                        'no-repeat' => array(
                            'label' => __('No repeat', 'phlox'),
                            'css_class' => 'axiAdminIcon-none'
                        ),
                        'repeat' => array(
                            'label' => __('Repeat horizontally and vertically', 'phlox'),
                            'css_class' => 'axiAdminIcon-repeat-xy'
                        ),
                        'repeat-x' => array(
                            'label' => __('Repeat horizontally', 'phlox'),
                            'css_class' => 'axiAdminIcon-repeat-x'
                        ),
                        'repeat-y' => array(
                            'label' => __('Repeat vertically', 'phlox'),
                            'css_class' => 'axiAdminIcon-repeat-y'
                        )
                    ),
                    'std'   => 'repeat');


$options[] = array( 'title'     => __('Background attachment', 'phlox'),
                    'desc'      => __('Specifies the background is fixed or scrollable as user scrolls the document', 'phlox'),
                    'id'        => '{bg_prefix_id}_attachment',
                    'section'   => 'tools-setting-section-login',
                    'type'      => 'select',
                    'dependency'=> array(
                        array(
                             'id'      => '{bg_prefix_id}_show',
                             'value'   => array('checked'),
                             'callback'=> ''
                        )
                    ),
                    'choices'   => array('fixed' , 'scroll')
                );


$options[] = array( 'title'     => __('Background position', 'phlox'),
                    'desc'      => __('specifies background image alignment', 'phlox'),
                    'id'        => '{bg_prefix_id}_position',
                    'section'   => 'tools-setting-section-login',
                    'type'      => 'radio-image',
                    'dependency'=> array(
                        array(
                             'id'      => '{bg_prefix_id}_show',
                             'value'   => array('checked'),
                             'callback'=> ''
                        )
                    ),
                    'choices' => array(
                        'left top' => array(
                            'label'     => __('Left top', 'phlox'),
                            'css_class' => 'axiAdminIcon-top-left'
                        ),
                        'center top' => array(
                            'label'     => __('Center top', 'phlox'),
                            'css_class' => 'axiAdminIcon-top-center'
                        ),
                        'right top' => array(
                            'label'     => __('Right top', 'phlox'),
                            'css_class' => 'axiAdminIcon-top-right'
                        ),

                        'left center' => array(
                            'label'     => __('Left center', 'phlox'),
                            'css_class' => 'axiAdminIcon-center-left'
                        ),
                        'center center' => array(
                            'label'     => __('Center center', 'phlox'),
                            'css_class' => 'axiAdminIcon-center-center'
                        ),
                        'right center' => array(
                            'label'     => __('Right center', 'phlox'),
                            'css_class' => 'axiAdminIcon-center-right'
                        ),

                        'left bottom' => array(
                            'label'     => __('Left bottom', 'phlox'),
                            'css_class' => 'axiAdminIcon-bottom-left'
                        ),
                        'center bottom' => array(
                            'label'     => __('Center bottom', 'phlox'),
                            'css_class' => 'axiAdminIcon-bottom-center'
                        ),
                        'right bottom' => array(
                            'label'     => __('Right bottom', 'phlox'),
                            'css_class' => 'axiAdminIcon-bottom-right'
                        )

                    ),
                    'default'   => 'left top' );

$options[] = array( 'title'     => __('Background image', 'phlox'),
                    'desc'      => _x('custome background image','custom background image for each page', 'phlox'),
                    'id'        => '{bg_prefix_id}_image',
                    'section'   => 'tools-setting-section-login',
                    'dependency'=> array(
                        array(
                             'id'      => '{bg_prefix_id}_show',
                             'value'   => array('checked'),
                             'callback'=> ''
                        )
                    ),
                    'type'      => 'upload',
                    'class'     => 'media_upload_field',
                    'upload_std'=> __('Browse', 'phlox'),
                    'remove_std'=> __('Remove', 'phlox'),
                    'default'   => '' );

-----------------------------------------------------------------------------------------------------*/
