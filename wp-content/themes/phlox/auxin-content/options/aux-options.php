<?php
/**
 * Outputs option panel
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */


/* ---------------------------------------------------------------------------------------------------
    General Section
    Note: $option and $section vars are defined before, don't define or reset them again
--------------------------------------------------------------------------------------------------- */

//  @TODO: Name of this file shoulde change to aux from axi

function auxin_define_options_info( $fields_sections_list ){

    $options  = array();
    $sections = array();

    // General section ==================================================================

    $sections[] = array(
        'id'      => 'general-setting-section',
        'parent'  => '', // section parent's id
        'title'   => __( 'General', 'phlox'),
        'description' => __( 'General Setting', 'phlox'),
        'icon'    => 'axicon-cog'
    );

    // Sub section - General layout -------------------------------

    $sections[] = array(
        'id'      => 'general-setting-section-layout',
        'parent'  => 'general-setting-section', // section parent's id
        'title'   => __( 'General Layout', 'phlox'),
        'description' => __( 'General Layout Setting', 'phlox')
    );


    $options[] = array(
        'title'       => __('Website Layout', 'phlox'),
        'description' => __('If you choose "Boxed", site content will be wrapped in a box.', 'phlox' ),
        'id'          => 'site_wrapper_size',
        'section'     => 'general-setting-section-layout',
        'dependency'  => array(),
        'post_js'     => '$("body").toggleClass( "aux-boxed", "boxed" == to );',
        'choices'     => array(
            'full'    => array(
                'label'     => __('Full', 'phlox'),
                'css_class' => 'axiAdminIcon-content-full',
            ),
            'boxed'   => array(
                'label'     => __('Boxed', 'phlox'),
                'css_class' => 'axiAdminIcon-content-boxed',
            )
        ),
        'default'   => 'full',
        'type'      => 'radio-image'
    );

    /*
    $options[] = array(
        'title'       => __('Responsive Layout', 'phlox'),
        'description' => __('Enable it to adjust the layout of your website depending on the screen size/device.', 'phlox'),
        'id'          => 'enable_site_reponsiveness',
        'section'     => 'general-setting-section-layout',
        'dependency'  => array(),
        'post_js'     => '$("body").toggleClass( "aux-resp", 1 == to );',
        'default'     => '1',
        'type'        => 'switch'
    );
    */

    $options[] = array(
        'title'       => __('Site Max width', 'phlox'),
        'description' => __('Specifies the maximum width of website.', 'phlox'),
        'id'          => 'site_max_width_layout',
        'section'     => 'general-setting-section-layout',
        'type'        => 'select',
        'transport'   => 'postMessage',
        'dependency'  => array(),
        'choices'     => array(
            'nd'      => __('1000 Pixel', 'phlox'),
            'hd'      => __('1200 Pixel', 'phlox'),
            'xhd'     => __('1400 Pixel', 'phlox'),
            's-fhd'   => __('1600 Pixel', 'phlox'),
            'fhd'     => __('1900 Pixel', 'phlox')
        ),
        'post_js'   => 'var $body = $( "body" ); $body.removeClass( "aux-nd" );
                        $body.removeClass( "aux-hd" ); $body.removeClass( "aux-xhd" );
                        $body.removeClass( "aux-s-fhd" ); $body.removeClass( "aux-fhd" );
                        $body.addClass( "aux-" + to ); $(window).trigger("resize");',
        'default'   => 'hd'
    );


    // Sub section - Logo options ------------------------------------

    $sections[] = array(
        'id'          => 'general-setting-section-logo',
        'parent'      => 'general-setting-section', // section parent's id
        'title'       => __( 'Logo', 'phlox'),
        'description' => __( 'Logo Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __('Logo Image', 'phlox'),
        'description' => __('This image appears as site logo on header section.', 'phlox'),
        'id'          => 'site_logo_image',
        'section'     => 'general-setting-section-logo',
        'dependency'  => array(),
        'default'     => '',
        'partial'     => array(
            'selector'              => '.aux-logo-header .aux-logo-anchor',
            'container_inclusive'   => false,
            'render_callback'       => function(){ echo _auxin_get_logo_image(); }
        ),
        'type'        => 'image'
    );

    $options[] = array(
        'title'       => __('Logo Image Retina', 'phlox'),
        'description' => __('This is the logo image that appears on high resolution screens like iPad, iPhone and etc. <br/> you need to attach an image with double size in dimensions. <br /> Leave this field blank if you do not need this feature.', 'phlox'),
        'id'          => 'site_logo_image_2x',
        'section'     => 'general-setting-section-logo',
        'dependency'  => array(),
        'partial'     => array(
            'selector'              => '.aux-logo-header .aux-logo-anchor',
            'container_inclusive'   => false,
            'render_callback'       => function(){ echo _auxin_get_logo_image(); }
        ),
        'default'     => '',
        'type'        => 'image'
    );

    /*
    $options[] = array( 'title'     => __('Logo Image (light version)', 'phlox'),
                        'description' => __('This image appears as site logo on site header section.', 'phlox'),
                        'id'          => 'site_logo_image_invert',
                        'section'     => 'general-setting-section-logo',
                        'dependency'  => array(),
                        'default'     => '',
                        'type'        => 'image' );

    $options[] = array( 'title'       => __('Logo Image Retina (light version)', 'phlox'),
                        'description' => __('This is the logo image that appears on high resolution screens (retina devices like iPad, iPhone, ..). <br/> you need to attach an image with double size in dimensions. <br /> Leave this field blank if you do not need this feature.', 'phlox'),
                        'id'          => 'site_logo_image_invert_2x',
                        'section'     => 'general-setting-section-logo',
                        'dependency'  => array(),
                        'default'     => '',
                        'type'        => 'image' ); */

    $options[] = array(
        'title'       => __('Logo Width', 'phlox'),
        'description' => __('Set the width of site logo image in pixel.', 'phlox'),
        'id'          => 'header_logo_width',
        'section'     => 'general-setting-section-logo',
        'dependency'  => array(),
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-logo-header .aux-logo-anchor").css( "max-width", $.trim(to) + "px" );',
        'default'     => '200',
        'type'        => 'text'
    );

    // --- Video Logo ----

    /*
    $options[] = array( 'title'       => __('Enable Video logo?', 'phlox'),
                        'description' => __('Do you want to display background for this section?', 'phlox'),
                        'id'          => 'header_logo_video_enabled',
                        'section'     => 'general-setting-section-logo',
                        'default'     => '0',
                        'type'        => 'switch' );
     $options[] = array( 'title'       => __('Enable Video logo?', 'phlox'),
                        'description' => __('Do you want to display background for this section?', 'phlox'),
                        'id'          => 'header_logo_video_enabled',
                        'section'     => 'general-setting-section-logo',
                        'default'     => '0',
                        'type'        => 'switch' );

     $options[] = array( 'title'      => __('Loop Video?', 'phlox'),
                        'id'         => 'header_logo_video_loop',
                        'section'    => 'general-setting-section-logo',
                        'dependency' => array(
                            array(
                                'id'      => 'header_logo_video_enabled',
                                'value'   => array('1'),
                                'operator'=> '=='
                            )
                        ),
                        'type'      => 'switch' );

    $options[] = array( 'title'      => __('MP4', 'phlox'),
                        'id'         => 'site_logo_video_mp4',
                        'section'    => 'general-setting-section-logo',
                        'dependency' => array(
                            array(
                                'id'      => 'header_logo_video_enabled',
                                'value'   => array('1'),
                                'operator'=> '=='
                            )
                        ),
                        'type'      => 'media' );

    $options[] = array( 'title'     => __('Ogg', 'phlox'),
                        'id'        => 'site_logo_video_ogg',
                        'section'   => 'general-setting-section-logo',
                        'dependency'=> array(
                            array(
                                'id'      => 'header_logo_video_enabled',
                                'value'   => array('1'),
                                'operator'=> '=='
                            )
                        ),
                        'type'      => 'media' );

     $options[] = array( 'title'    => __('WebM', 'phlox'),
                        'id'        => 'site_logo_video_webm',
                        'section'   => 'general-setting-section-logo',
                        'dependency'=> array(
                            array(
                                'id'      => 'header_logo_video_enabled',
                                'value'   => array('1'),
                                'operator'=> '=='
                            )
                        ),
                        'type'      => 'media' ); */

    // Sub section - Website socials ----------------------------------

    $sections[] = array(
        'id'          => 'general-setting-section-main-socials',
        'parent'      => 'general-setting-section', // section parent's id
        'title'       => __( 'Website Socials', 'phlox'),
        'description' => __( 'Website Socials', 'phlox')
    );

    $options[] = array(
        'title'       => __('Facebook', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'facebook',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Twitter', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'twitter',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Google +', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'googleplus',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Dribbble', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'dribbble',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('YouTube', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'youtube',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Vimeo', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'vimeo',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Flickr', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'flickr',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Digg', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'digg',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Stumbleupon', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'stumbleupon',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('LastFM', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'lastfm',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Delicious', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'delicious',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Skype', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'skype',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('LinkedIn', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'linkedin',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Tumblr', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'tumblr',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Pinterest', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'pinterest',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Instagram', 'phlox'),
        'description' => __('Should start with <code>http://</code>', 'phlox' ),
        'id'          => 'instagram',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('RSS', 'phlox'),
        'description' => __('Enter your RSS Feed page. For example :', 'phlox').' <code>'.home_url().'?feed=rss2</code>',
        'id'          => 'rss',
        'section'     => 'general-setting-section-main-socials',
        'transport'   => 'refresh',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'text'
    );


    // Sub section - Custom Css ----------------------------------

    $sections[] = array(
        'id'          => 'general-setting-section-custom-css',
        'parent'      => 'general-setting-section', // section parent's id
        'title'       => __( 'Custom CSS Code', 'phlox'),
        'description' => __( 'Your Custom CSS', 'phlox')
    );

    $options[] = array(
        'title'       => __('Custom CSS', 'phlox'),
        'description' => sprintf( __('You can add your custom css code here. %s DO NOT use %s tag.', 'phlox'), '<br />', '<code>&lt;style&gt;</code>' ),
        'id'          => 'auxin_user_custom_css',
        'section'     => 'general-setting-section-custom-css',
        'dependency'  => array(),
        'default'     => '',
        'transport'   => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'auxin_user_custom_css' );
            }
            return $value;
        },
        'mode'        => 'css',
        'type'        => 'code'
    );

    // Sub section - Custom PHP ----------------------------------

    // $sections[] = array(
    //     'id'          => 'general-setting-section-custom-php',
    //     'parent'      => 'general-setting-section', // section parent's id
    //     'title'       => __( 'Custom PHP Code', 'phlox'),
    //     'description' => __( 'Your Custom PHP Code', 'phlox')
    // );

/*
    $options[] = array( 'title'     => __('Custom PHP Code', 'phlox'),
                        'description'   => __('You can add your custom PHP code here and make sure to keep &lt;?php on top of the code.<br />The codes will be executed ONLY on front-end.', 'phlox').'<br />'.
                                       sprintf( __('To add global code for both admin and front-end, please use %sWP theme editor%s tool.'),
                                               '<a href="'.admin_url('theme-editor.php?file=functions.php').'" target="_blank">', '</a>'  ),
                        'id'        => 'auxin_user_custom_php',
                        'section'   => 'general-setting-section-custom-php',
                        'dependency'=> array(),
                        'default'   => "<?php \n// your code here ..",
                        'mode'      => 'php',
                        'type'      => 'code' );
*/

    /* ---------------------------------------------------------------------------------------------------
        Colors
    --------------------------------------------------------------------------------------------------- */

    // Color section ==================================================================

    $sections[] = array(
        'id'          => 'appearance-setting-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Appearance', 'phlox'),
        'description' => __( 'Appearance Setting', 'phlox'),
        'icon'        => 'axicon-droplet'
    );

    // Sub section - Website background -------------------------------

    $sections[] = array(
        'id'          => 'appearance-setting-section-background',
        'parent'      => 'appearance-setting-section', // section parent's id
        'title'       => __( 'Website Background', 'phlox'),
        'description' => __( 'Website Background Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __('Enable background', 'phlox'),
        'description' => __('Specifies the background options for the body of website (the canvas behind the boxed website). If you enable this option, the following option overrides default background styles.', 'phlox').'<br />'.
                         __('Please note that these options work only if "Website Layout" options is set to "Boxed layout".', 'phlox'),
        'id'          => 'site_body_background_show',
        'section'     => 'appearance-setting-section-background',
        'default'     => '1',
        'type'        => 'switch'
    );


    $options[] = array(
        'title'       => __( 'Background Color', 'phlox'),
        'id'          => 'site_body_background_color',
        'description' => __( 'Specifies the color of website background.', 'phlox'),
        'section'     => 'appearance-setting-section-background',
        'type'        => 'color',
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_color' );
            }
            return empty( $value ) ? '' : "body { background-color:$value; }";
        },
        'transport' => 'postMessage',
        'default'   => ''
    );

    $options[] = array(
        'title'       =>  __('Background Image', 'phlox'),
        'id'          => 'site_body_background_image',
        'description' =>  __('You can upload custom image for site background.', 'phlox'),
        'section'     => 'appearance-setting-section-background',
        'type'        => 'image',
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_image' );
            }

            $value = auxin_get_attachment_url( $value, 'full' );
            return empty( $value ) ? '' : "body { background-image:url($value); }";
        },
        'transport' => 'postMessage',
        'default'   => ''
    );


    $options[] = array(
        'title'       =>  __('Background Size', 'phlox'),
        'description' =>  __('Specifies the background size.', 'phlox'),
        'id'          => 'site_body_background_size',
        'section'     => 'appearance-setting-section-background',
        'type'        => 'radio-image',
        'choices'     => array(
            'auto' => array(
                'label'     => __('Auto', 'phlox'),
                'css_class' => 'axiAdminIcon-bg-size-1',
            ),
            'contain' => array(
                'label'     => __('Contain', 'phlox'),
                'css_class' => 'axiAdminIcon-bg-size-2'
            ),
            'cover' => array(
                'label'     => __('Cover', 'phlox'),
                'css_class' => 'axiAdminIcon-bg-size-3'
            )
        ),
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_size' );
            }
            return "body { background-size:$value; }";
        },
        'transport' => 'postMessage',
        'default'   => 'auto'
    );

    $options[] = array(
        'title'       => __('Background Pattern', 'phlox'),
        'description' => sprintf(__('You can select one of these patterns as site background image. %s Some of these can be used as a pattern over your background image.', 'phlox'), '<br>'),
        'id'          => 'site_body_background_pattern',
        'section'     => 'appearance-setting-section-background',
        'choices'     => auxin_get_background_patterns( array( 'none' => array( 'label' =>__('None', 'phlox'), 'image' => AUX_URL . 'images/visual-select/none-pattern.svg' ) ), 'before' ),
        'type'        => 'radio-image',
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_pattern' );
            }
            return 'none' != $value ? "body:before { height:100%; background-image:url($value); }" : '';
        },
        'transport' => 'postMessage',
        'default'   => ''
    );


    $options[] = array(
        'title'       =>  __( 'Background Repeat', 'phlox'),
        'description' =>  __( 'Specifies how background image repeats.', 'phlox'),
        'id'          => 'site_body_background_repeat',
        'section'     => 'appearance-setting-section-background',
        'choices'     =>  array(
            'no-repeat' => array(
                'label'     => __('No repeat', 'phlox'),
                'css_class' => 'axiAdminIcon-none',
            ),
            'repeat' => array(
                'label'     => __('Repeat horizontally and vertically', 'phlox'),
                'css_class' => 'axiAdminIcon-repeat-xy',
            ),
            'repeat-x' => array(
                'label'     => __('Repeat horizontally', 'phlox'),
                'css_class' => 'axiAdminIcon-repeat-x',
            ),
            'repeat-y' => array(
                'label'     => __('Repeat vertically', 'phlox'),
                'css_class' => 'axiAdminIcon-repeat-y',
            )
        ),
        'type'        => 'radio-image',
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_repeat' );
            }
            return "body { background-repeat:$value; }";
        },
        'transport' => 'postMessage',
        'default'   => 'no-repeat'
    );



    $options[] = array(
        'title'       =>  __( 'Background Position', 'phlox'),
        'description' =>  __('Specifies background image position.', 'phlox'),
        'id'          => 'site_body_background_position',
        'section'     => 'appearance-setting-section-background',
        'choices'     => array(
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
        'type'        => 'radio-image',
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_position' );
            }
            return "body { background-position:$value; }";
        },
        'transport' => 'postMessage',
        'default'   => 'left top'
    );


    $options[] = array(
        'title'       =>  __('Background Attachment', 'phlox'),
        'description' =>  __('Specifies whether the background is fixed or scrollable as user scrolls the page.', 'phlox'),
        'id'          => 'site_body_background_attach',
        'section'     => 'appearance-setting-section-background',
        'type'        =>  'radio-image',
        'choices'     => array(
            'scroll' => array(
                'label'     => __('Scroll', 'phlox'),
                'css_class' => 'axiAdminIcon-bg-attachment-scroll',
            ),
            'fixed' => array(
                'label'     => __('Fixed', 'phlox'),
                'css_class' => 'axiAdminIcon-bg-attachment-fixed',
            )
        ),
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_body_background_attach' );
            }
            return "body { background-attachment:$value; }";
        },
        'default'     => 'scroll',
        'transport'   => 'postMessage'
    );

    // Sub section - Website background -------------------------------

    $sections[] = array(
        'id'          => 'appearance-setting-section-content-bg',
        'parent'      => 'appearance-setting-section', // section parent's id
        'title'       => __( 'Content Background', 'phlox'),
        'description' => __( 'Content Background Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __( 'Content Background Color', 'phlox'),
        'id'          => 'site_content_background_color',
        'description' => __( 'Specifies the color of content background.', 'phlox'),
        'section'     => 'appearance-setting-section-content-bg',
        'type'        => 'color',
        'dependency'  => array(
            array(
                'id'      => 'site_body_background_show',
                'value'   => array('1'),
                'operator'=> '=='
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_content_background_color' );
            }
            return empty( $value ) ? '' : ".aux-top-header, .site-header-section, #inner-body { background-color:$value; }";
        },
        'transport' => 'postMessage',
        'default'   => ''
    );


    // Sub section - General Colors -------------------------------
    /*
    $sections[] = array(
        'id'          => 'color-setting-section-general',
        'parent'      => 'appearance-setting-section', // section parent's id
        'title'       => __( 'Color options', 'phlox'),
        'description' => __( 'Color options', 'phlox')
    );

    $options[] = array(
        'title'       => __('Enable Custom Colors', 'phlox'),
        'description' => __('The following option overrides default colors if you enable this option.', 'phlox'),
        'id'          => 'enable_custom_general_colors',
        'section'     => 'color-setting-section-general',
        'dependency'  => array(),
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Text Color', 'phlox'),
        'description' => __('Specifies the color of text content.', 'phlox'),
        'id'          => 'site_body_color',
        'section'     => 'color-setting-section-general',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_general_colors',
                 'value'   => array('1'),
                 'operator'=> ''
            ),
        ),
        'default'        => '#ffffff',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_body_color' );

            if( auxin_get_option('enable_custom_general_colors') ){
                return sprintf( "body { color:%s; }", $value );
            }
            return '';
        },
        'transport' => 'postMessage',
        'type'      => 'color'
    );

    $options[] = array(
        'title'       => __('Links Color', 'phlox'),
        'description' => __('Specifies the color of links.', 'phlox'),
        'id'          => 'site_general_link_color',
        'section'     => 'color-setting-section-general',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_general_colors',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'default'        => '#94BDCF',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_general_link_color' );

            if( auxin_get_option('enable_custom_general_colors') ){
                return sprintf( "a { color:%s; }", $value );
            }
            return '';
        },
        'transport' => 'postMessage',
        'type'      => 'color'
    );

    $options[] = array(
        'title'       => __('Links Hover Color', 'phlox'),
        'description' => __('Specifies the color of links when user mouse over it.', 'phlox'),
        'id'          => 'site_general_link_hover_color',
        'section'     => 'color-setting-section-general',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_general_colors',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'default'        => '#6C92A3',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_general_link_hover_color' );

            if( auxin_get_option('enable_custom_general_colors') ){
                return sprintf( "a:hover { color:%s; }", $value );
            }
            return '';
        },
        'transport' => 'postMessage',
        'type'      => 'color'
    );
    */

    // TODO: we will add this in future updates
    /*
    $options[] = array( 'title'         => __('Feature Color', 'phlox'),
                        'description'   => __('Specifies the most dominant theme color', 'phlox'),
                        'id'            => 'feature_color',
                        'section'       => 'color-setting-section-general',
                        'dependency'    => array(
                            array(
                                 'id'      => 'enable_custom_general_colors',
                                 'value'   => array('1'),
                                 'operator'=> ''
                            )
                        ),
                        'default'   => '#78acc2',
                        'type'  => 'color' );

    $options[] = array( 'title' => __('Divider Color', 'phlox'),
                        'description' => __('Specifies the color of dividers', 'phlox'),
                        'id'    => 'divider_color',
                        'section'   => 'color-setting-section-general',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'enable_custom_general_colors',
                                                 'value'   => array('1'),
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => '#b9b9b9',
                        'type'  => 'color' );

    $options[] = array( 'title' => __('Font Icons Color', 'phlox'),
                        'description' => __('Specifies the color of font icons', 'phlox'),
                        'id'    => 'font_icon_color',
                        'section'   => 'color-setting-section-general',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'enable_custom_general_colors',
                                                 'value'   => array('1'),
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => '#78acc2',
                        'type'  => 'color' );

    $options[] = array( 'title' => __('Callout Button Color', 'phlox'),
                        'description' => __('Specifies the color of callout/stunning button', 'phlox'),
                        'id'    => 'callout_btn_bgcolor',
                        'section'   => 'color-setting-section-general',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'enable_custom_general_colors',
                                                 'value'   => array('1'),
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => '#78acc2',
                        'type'  => 'color' ); */


    /* ---------------------------------------------------------------------------------------------------
        Typography Section
    --------------------------------------------------------------------------------------------------- */

    // Sub section - Main Typography -------------------------------

    $sections[] = array(
        'id'          => 'typo-setting-section-main',
        'parent'      => 'appearance-setting-section', // section parent's id
        'title'       => __( 'Typography', 'phlox'),
        'description' => __( 'Typography Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __('Enable Custom Typography', 'phlox'),
        'description' => __('The following options override defaults typography if you enable this option.', 'phlox'),
        'id'          => 'enable_custom_typography',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Main Content', 'phlox'),
        'description' => __('Specifies content typography.', 'phlox'),
        'id'          => 'content_typography',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            // Don't generate custom style if custom typography is disabled
            if( ! auxin_get_option( 'enable_custom_typography' ) ){
                return '';
            }

            // if called from save handler or on customizer start
            if( ! $value ){
                $face   = auxin_get_option( 'content_typography_face' );
                $weight = auxin_get_option( 'content_typography_weight' );

            // if called via ajax handler and control value passed
            } else {
                // parse the font into face and weight
                $components = Auxin_Fonts::get_instance()->extract_font( $value, '' ); // pas second
                $face       = isset( $components['face']   ) ? '"'.$components['face'].'"' : '';
                $weight     = isset( $components['weight'] ) ? $components['weight'] : '';
            }

            $typography  = $face   ? 'font-family: ' . $face . ';': '';
            $typography .= $weight ? 'font-weight: ' . $weight . ';': '';

            return $typography ? "body { $typography }" :'';
        },
        'default'   => '_gof_Raleway:regular',
        'type'      => 'typography'
    );

    /*
    $options[] = array(
        'title'       => __('Text Color', 'phlox'),
        'description' => __('Specifies the color of text content.', 'phlox'),
        'id'          => 'content_typography_color',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'default'   => '3D3D3D',
        'type'      => 'color'
    );
    */

    $options[] = array(
        'title'       => __('Main Titles', 'phlox'),
        'description' => sprintf(__('Main titles contain sections and widget titles. %1$sAll %2$sh1%3$s to %2$sh6%3$s typography can be set here.', 'phlox'), '<br>', '<code>', '</code>'),
        'id'          => 'main_title_typography',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            // Don't generate custom style if custom typography is disabled
            if( ! auxin_get_option( 'enable_custom_typography' ) ){
                return '';
            }

            // if called from save handler or on customizer start
            if( ! $value ){
                $face   = auxin_get_option( 'main_title_typography_face' );
                $weight = auxin_get_option( 'main_title_typography_weight' );

            // if called via ajax handler and control value passed
            } else {
                // parse the font into face and weight
                $components = Auxin_Fonts::get_instance()->extract_font( $value, '' ); // pas second
                $face       = isset( $components['face']   ) ? '"'.$components['face'].'"' : '';
                $weight     = isset( $components['weight'] ) ? $components['weight'] : '';
            }

            $typography  = $face   ? 'font-family: ' . $face . ';': '';
            $typography .= $weight ? 'font-weight: ' . $weight . ';': '';

            return $typography ? "h1, h2, h3, h4, h5, h6, .aux-h1, .aux-h2, .aux-h3, .aux-h4, .aux-h5, .aux-h6 { $typography }" :'';
        },
        'default'   => '_gof_Raleway:300',
        'type'      => 'typography'
    );
    /*
    $options[] = array(
        'title'       => __('Main Titles Color', 'phlox'),
        'description' => __('Specifies the color of text titles.', 'phlox'),
        'id'          => 'main_title_typography_color',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'default'   => '#6D6D6D',
        'type'      => 'color'
    );*/

    $options[] = array(
        'title'       => __('Page Heading', 'phlox'),
        'description' => __('Specifies main title typography of page.', 'phlox'),
        'id'          => 'page_title_typography',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            // Don't generate custom style if custom typography is disabled
            if( ! auxin_get_option( 'enable_custom_typography' ) ){
                return '';
            }

            // if called from save handler or on customizer start
            if( ! $value ){
                $face   = auxin_get_option( 'page_title_typography_face' );
                $weight = auxin_get_option( 'page_title_typography_weight' );

            // if called via ajax handler and control value passed
            } else {
                // parse the font into face and weight
                $components = Auxin_Fonts::get_instance()->extract_font( $value, '' ); // pas second
                $face       = isset( $components['face']   ) ? '"'.$components['face'].'"' : '';
                $weight     = isset( $components['weight'] ) ? $components['weight'] : '';
            }

            $typography  = $face   ? 'font-family: ' . $face . ';': '';
            $typography .= $weight ? 'font-weight: ' . $weight . ';': '';

            return $typography ? ".page-title { $typography }" :'';
        },
        'default'   => '_gof_Raleway:300',
        'type'      => 'typography'
    );
/*
    $options[] = array(
        'title'       => __('Page Heading Color', 'phlox'),
        'description' => __('Specifies the text color of page title.', 'phlox'),
        'id'          => 'page_title_typography_color',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'default'   => '#3D3D3D',
        'type'      => 'color'
    );*/

    $options[] = array(
        'title'       => __('Menu Typography', 'phlox'),
        'description' => __('Specifies menu typography.', 'phlox'),
        'id'          => 'header_menu_typography',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            // Don't generate custom style if custom typography is disabled
            if( ! auxin_get_option( 'enable_custom_typography' ) ){
                return '';
            }

            // if called from save handler or on customizer start
            if( ! $value ){
                $face   = auxin_get_option( 'header_menu_typography_face' );
                $weight = auxin_get_option( 'header_menu_typography_weight' );

            // if called via ajax handler and control value passed
            } else {
                // parse the font into face and weight
                $components = Auxin_Fonts::get_instance()->extract_font( $value, '' ); // pas second
                $face       = isset( $components['face']   ) ? '"'.$components['face'].'"' : '';
                $weight     = isset( $components['weight'] ) ? $components['weight'] : '';
            }

            $typography  = $face   ? 'font-family: ' . $face . ';': '';
            $typography .= $weight ? 'font-weight: ' . $weight . ';': '';

            return $typography ? ".aux-master-menu { $typography }" :'';
        },
        'default'   => '_gof_Raleway:regular',
        'type'      => 'typography'
    );


    $options[] = array(
        'title'       => __('Include Latin Charecters', 'phlox'),
        'description' => __('If there are characters in your language that are not supported in fonts, use following options to load them.', 'phlox'),
        'id'          => 'include_latin_chars',
        'section'     => 'typo-setting-section-main',
        'dependency'  => array(
            array(
                 'id'      => 'enable_custom_typography',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'default'   => '',
        'type'      => 'switch'
    );



        // Sub section - Skin options --------------------------------------

    $sections[] = array(
        'id'          => 'appearance-setting-section-skin',
        'parent'      => 'appearance-setting-section', // section parent's id
        'title'       => __( 'Skin options', 'phlox'),
        'description' => __( 'Skin options', 'phlox')
    );


    $options[] = array(
        'title'       => __('Video Player Skin', 'phlox'),
        'description' => __('Specifies the default skin for self hosted video player.', 'phlox'),
        'id'          => 'global_video_player_skin',
        'section'     => 'appearance-setting-section-skin',
        'type'        => 'radio-image',
        'choices' => array(
            'dark' => array(
                'label' => __('Dark', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'light' => array(
                'label' => __('Light', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-light.svg'
            )
        ),
        'transport' => 'refresh',
        'default'   => 'dark'
    );

    $options[] = array(
        'title'       => __('Audio Player Skin', 'phlox'),
        'description' => __('Specifies the default skin for self hosted audio player.', 'phlox'),
        'id'          => 'global_audio_player_skin',
        'section'     => 'appearance-setting-section-skin',
        'type'        => 'radio-image',
        'choices' => array(
            'dark' => array(
                'label' => __('Dark', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'light' => array(
                'label' => __('Light', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-light.svg'
            )
        ),
        'transport' => 'refresh',
        'default'   => 'dark'
    );

    $options[] = array(
        'title'       => __('Pagination Skin', 'phlox'),
        'description' => __('Specifies the default skin for pagination on archive pages.', 'phlox'),
        'id'          => 'archive_pagination_skin',
        'section'     => 'appearance-setting-section-skin',
        'type'        => 'radio-image',
        'choices'     => array(
            'aux-round aux-page-no-border' => array(
                'label' => __('Round, No Page Border', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'aux-round aux-no-border' => array(
                'label' => __('Round, No Border', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'aux-round' => array(
                'label' => __('Round, With Border', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'aux-square aux-page-no-border' => array(
                'label' => __('Square, No Page Border', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'aux-square aux-no-border' => array(
                'label' => __('Square, No Border', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            ),
            'aux-square' => array(
                'label' => __('Square, With Border', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/audio-player-dark.svg'
            )
        ),
        'post_js'   => '$(".content .aux-pagination").prop("class", "aux-pagination " + to );',
        'transport' => 'postMessage',
        'default'   => 'aux-square'
    );


/* ---------------------------------------------------------------------------------------------------
        Header Section
   --------------------------------------------------------------------------------------------------- */

    // Header section ==================================================================

    $sections[] = array(
        'id'          => 'header-setting-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Header', 'phlox'),
        'description' => __( 'Header Setting', 'phlox'),
        'icon'        => 'axicon-align-justify'
    );




    // Sub section - Header layout -------------------------------
    $sections[] = array(
        'id'          => 'header-setting-section-layout',
        'parent'      => 'header-setting-section', // section parent's id
        'title'       => __( 'Header Section', 'phlox'),
        'description' => __( 'Header Section Setting', 'phlox')
    );

    // @TODO: We should have no header option hear as well
     // $options[] = array( 'title'     => __('Header Postion', 'phlox'),
     //                    'description'   => __('Choose the header position from top, left and right', 'phlox'),
     //                    'id'        => 'site_header_position',
     //                    'section'   => 'header-setting-section-layout',
     //                    'type'      => 'select',
     //                    'dependency'=> array(),
     //                    'choices'   => array(   'top'         => 'Top'
     //                                            //'left'        => 'Left',
     //                                            //'right'        => 'Right',
     //                                            //'full-screen' => 'Full Screen Menu'
     //                                        ),
     //                    'default'   => 'top');

/*      $options[] = array(
            'title'     => __('vertical fixed Menu', 'phlox'),
            'description'   => __('Enables the sticky menu', 'phlox'),
            'id'        => 'site_header_vertical_sticky',
            'section'   => 'header-setting-section-layout',
            'type'      => 'switch',
            'dependency'=> array(
                                array(
                                     'id'      => 'site_header_position',
                                     'value'   => 'right',
                                     'operator'=> ''
                                ),
                                array(
                                     'id'      => 'site_header_position',
                                     'value'   => 'left',
                                     'operator'=> ''
                                ),
                                'relation'=> 'or'
                            ),
            'default'   => '1');

        $options[] = array( 'title'     => __('Show Logo', 'phlox'),
                        'description'   => __('Show logo on full screen menu', 'phlox'),
                        'id'        => 'site_menu_full_screen_show_logo',
                        'section'   => 'header-setting-section-layout',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'full-screen',
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => '1',
                        'type'      => 'switch' );*/


     $options[] = array(
        'title'       => __('Header Layout', 'phlox'),
        'description' => __('Specifies header layout.', 'phlox'),
        'id'          => 'site_header_top_layout',
        'section'     => 'header-setting-section-layout',
        'type'        => 'radio-image',
        // 'dependency'=> array(
        //                     array(
        //                          'id'      => 'site_header_position',
        //                          'value'   => 'top',
        //                          'operator'=> ''
        //                     )
        //                 ),
        'choices' => array(
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
        'transport' => 'refresh',
        'default'   => 'horizontal-menu-right'
    );

    $options[] = array(
        'title'       => __('Search button', 'phlox'),
        'description' => __('Enable it to insert search button in the header.', 'phlox'),
        'id'          => 'site_header_search_button',
        'section'     => 'header-setting-section-layout',
        'type'        => 'switch',
        'transport' => 'refresh',
        'default'     => '0'
    );

     $options[] = array(
        'title'         => __('Header Width', 'phlox'),
        'description'   => sprintf(__('Specifies %1$sboxed%2$s or %1$sfullwidth%2$s header.', 'phlox'), '<code>', '</code>'),
        'id'            => 'site_header_width',
        'section'       => 'header-setting-section-layout',
        'type'          => 'radio-image',
        // 'dependency' => array(
        //                     array(
        //                          'id'      => 'site_header_position',
        //                          'value'   => 'top',
        //                          'operator'=> ''
        //                     )
        //                 ),

        'choices' => array(
            'boxed' => array(
                'label'     => __('Boxed', 'phlox'),
                'css_class' => 'axiAdminIcon-content-boxed',
            ),
            'semi-full' => array(
                'label'     => __('Full Width', 'phlox'),
                'css_class' => 'axiAdminIcon-content-full',
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$(".aux-top-header, .site-header-section").removeClass("aux-boxed-container")
                        .removeClass("aux-semi-full-container").addClass("aux-"+ to +"-container");',
        'default'   => 'boxed'
    );


    $options[] = array(
        'title'          => __('Header height', 'phlox'),
        'description'    => __('Specifies the header height in pixel.', 'phlox'),
        'id'             => 'site_header_container_height',
        'section'        => 'header-setting-section-layout',
        'dependency'     => array(),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_header_container_height' );

            $selector  = ".site-header-section .aux-header-elements, ";
            $selector .= ".site-header-section .aux-fill .aux-menu-depth-0 > .aux-item-content { height:%spx; }";

            return sprintf( $selector , $value );
        },
        'default'   => '85',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Add border', 'phlox'),
        'description' => __('Enable it to add border below the header.', 'phlox'),
        'id'          => 'site_header_border_bottom',
        'section'     => 'header-setting-section-layout',
        'type'        => 'switch',
        'post_js'     => '$("#site-header").toggleClass("aux-add-border", 1 == to );',
        'default'     => '1'
    );

    $options[] = array(
        'title'       => __('Enable Sticky Header', 'phlox'),
        'description' => __('Enable it to display header menu  on top even by scrolling the page.', 'phlox'),
        'id'          => 'site_header_top_sticky',
        'section'     => 'header-setting-section-layout',
        'type'        => 'switch',
        'post_js'     => '$("body").toggleClass("aux-top-sticky", 1 == to );',
        'default'     => '1'
    );

    $options[] = array(
        'title'       => __('Sticky Header Height', 'phlox'),
        'description' => __('Specifies the Sticky header height.', 'phlox'),
        'id'          => 'site_header_container_scaled_height',
        'section'     => 'header-setting-section-layout',
        'dependency'  => array(
            //  array(
            //      'id'      => 'site_header_position',
            //      'value'   => 'top',
            //      'operator'=> '=='
            // ),
             array(
                 'id'      => 'site_header_top_sticky',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            'relation' => 'and'
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = trim( auxin_get_option( 'site_header_container_scaled_height', 85 ), 'px' );

            $selector  = ".aux-top-sticky .site-header-section.aux-sticky .aux-fill .aux-menu-depth-0 > .aux-item-content, ".
                         ".aux-top-sticky .site-header-section.aux-sticky .aux-header-elements { height:%spx; }";

            return sprintf( $selector , $value );
        },
        'default'   => '85',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Scale Logo on Sticky Header', 'phlox'),
        'description' => __('logo would be scaled on sticky header, if you enable this option.', 'phlox'),
        'id'          => 'site_header_logo_can_scale',
        'section'     => 'header-setting-section-layout',
        'type'        => 'switch',
        'dependency'  => array(
            //  array(
            //      'id'      => 'site_header_position',
            //      'value'   => 'top',
            //      'operator'=> '=='
            // ),
             array(
                 'id'      => 'site_header_top_sticky',
                 'value'   => 1,
                 'operator'=> '=='
            ),
            'relation' => 'and'
        ),
        'post_js'   => '$(".aux-logo-header-inner").toggleClass("aux-scale", 1 == to );',
        'default'   => '1'
    );

    // horizontal header dependencies
   /* $options[] = array( 'title'     => __('Custom Header Style?', 'phlox'),
                        'description'   => __('Do you want to modify the colors of header and header navigation? If you check this field, the following options will be applied', 'phlox'),
                        'id'        => 'enable_header_custom_style',
                        'section'   => 'header-setting-section-layout',
                        // 'dependency'=> array(
                        //                      array(
                        //                          'id'      => 'site_header_position',
                        //                          'value'   => 'top',
                        //                          'operator'=> ''
                        //                     )
                        //                 ),
                        'default'   => '0',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Header Background Color', 'phlox'),
                        'description'   => __('Specifies the color of header background', 'phlox'),
                        'id'        => 'site_header_background_color1',
                        'section'   => 'header-setting-section-layout',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'enable_header_custom_style',
                                                 'value'   => array('1'),
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => '#4A9BDC',
                        'type'      => 'color' );


    $options[] = array( 'title'     => __('Header Bottom Border Color', 'phlox'),
                        'description'   => __('Specifies the color of line under header section', 'phlox'),
                        'id'        => 'site_header_border_bottom_color',
                        'section'   => 'header-setting-section-layout',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'enable_header_custom_style',
                                                 'value'   => array('1'),
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => '#358FD8',
                        'type'      => 'color' ); */

    // Vertical left/right Header dependency options -------------------------------
/*
     $options[] = array( 'title'     => __('Pin Vertical Menu?', 'phlox'),
                        'description'   => __('Enables the sticky menu', 'phlox'),
                        'id'        => 'site_header_vertical_sticky',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'switch',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        ),

                        'default'   => '1');

    $options[] = array( 'title'     => __('Always Open', 'phlox'),
                        'description'   => __('Vertical header is always open', 'phlox'),
                        'id'        => 'site_header_vertical_open',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'switch',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        ),
                        'default'   => '1');

     $options[] = array( 'title'     => __('Show Logo', 'phlox'),
                        'description'   => __('Show logo on vertical header', 'phlox'),
                        'id'        => 'site_header_vertical_show_logo',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'switch',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        ),
                        'default'   => '1');

    // @TODO: it is better to have a compelex dependency here it shows if header type is vertical left or right and also the show always open is enabled
    $options[] = array( 'title'     => __('Add background on page height', 'phlox'),
                        'description'   => __('add background on page height pixel', 'phlox'),
                        'id'        => 'site_header_vertical_transparent_pixel',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'text',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        ),
                        'default'   => '0');

    $options[] = array( 'title'     => __('Enable Search Box', 'phlox'),
                        'description'   => __('Wether shows search on vertical menu or not.', 'phlox'),
                        'id'        => 'site_header_vertical_show_search',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'switch',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        ),
                        'default'   => '1');

    $options[] = array( 'title'     => __('Background Color', 'phlox'),
                        'description'   => __('Vertical header is always open', 'phlox'),
                        'id'        => 'site_header_vertical_bg_color',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'color',
                        'default'   => '#4A9BDC',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        )
                );

    $options[] = array( 'title'     => __('Background Image', 'phlox'),
                        'description'   => __('You can upload custom image for vertical menu background', 'phlox'),
                        'id'        => 'site_header_vertical_bg_image',
                        'section'   => 'header-setting-section-layout',
                        'type'      => 'image',
                        'default'   => '',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'right',
                                                 'operator'=> ''
                                            ),
                                            array(
                                                 'id'      => 'site_header_position',
                                                 'value'   => 'left',
                                                 'operator'=> ''
                                            ),
                                            'relation'=> 'or'
                                        )
                );*/

    // Sub section - navigation options -------------------------------

    $sections[] = array(
        'id'          => 'header-setting-section-main-nav',
        'parent'      => 'header-setting-section', // section parent's id
        'title'       => __( 'Header Menu', 'phlox'),
        'description' => __( 'Header Menu Options', 'phlox')
    );

    $options[] = array(
        'title'       => __('Header Submenu Skin', 'phlox'),
        'description' => __('Specifies submenu skin.', 'phlox'),
        'id'          => 'site_header_navigation_sub_skin',
        'section'     => 'header-setting-section-main-nav',
        'dependency'  => array(),
        'default'     => 'classic',
        'type'        => 'radio-image',
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-header .aux-master-menu").alterClass( "aux-skin-*", "aux-skin-" + to );',
        'choices'     => array(
            'classic'       => array(
                'label'     => __('Paradox', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sub-menu-skin-1.svg'
            ),
            'classic-center'=> array(
                'label'     => __('Classic', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sub-menu-skin-2.svg'
            ),
            'dash-divided'  => array(
                'label'     => __('Dark Transparent', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sub-menu-skin-5.svg'
            ),
            'divided'       => array(
                'label'     => __('Divided', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sub-menu-skin-3.svg'
            ),
            'minimal-center'=> array(
                'label'     => __('Center Paradox', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sub-menu-skin-4.svg'
            ),
            'modern'        => array(
                'label'     => __('Modern Paradox', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sub-menu-skin-6.svg'
            )
        )
    );

    $options[] = array(
        'title'       => __('Submenu animation effect', 'phlox'),
        'description' => '',
        'id'          => 'site_header_navigation_sub_effect',
        'section'     => 'header-setting-section-main-nav',
        'transport' => 'postMessage',
        'post_js'     => '$(".aux-header .aux-master-menu").alterClass( "aux-*-nav", "aux-" + to + "-nav" );',
        'choices'   => array (
            ''              => __('None', 'phlox'),
            'fade'          => __('Fade', 'phlox'),
            'slide-up'      => __('Fade and move', 'phlox'),
        ),
        'type'     => 'select',
        'default'  => ''
    );


    $options[] = array(
        'title'       => __('Display Submenu Indicator', 'phlox'),
        'description' => __('Add submenu indicator icon to header menu items.', 'phlox'),
        'id'          => 'site_header_navigation_with_indicator',
        'section'     => 'header-setting-section-main-nav',
        'type'        => 'switch',
        'dependency'  => array(),
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-header .aux-master-menu").toggleClass( "aux-with-indicator", 1 == to );',
        'default'     => '1'
    );

    $options[] = array(
        'title'       => __('Display Menu Splitter', 'phlox'),
        'description' => __('Insert splitter symbol between menu items in header.', 'phlox'),
        'id'          => 'site_header_navigation_with_splitter',
        'section'     => 'header-setting-section-main-nav',
        'type'        => 'switch',
        'dependency'  => array(),
        'transport'   => 'postMessage',
        'post_js'     => '$(".aux-header .aux-master-menu").toggleClass( "aux-with-splitter", 1 == to );',
        'default'     => '1'
    );

     $options[] = array(
        'title'       => __('Submenu location ', 'phlox'),
        'description' => __('Set the location of submenu in header area.', 'phlox'),
        'id'          => 'site_header_navigation_sub_location',
        'section'     => 'header-setting-section-main-nav',
        'dependency'  => array(),
        'default'     => 'below-header',
        'type'        => 'radio-image',
        'transport'   => 'refresh',
        'choices'     => array(
            'below-header'  => array(
                'label'     => __('Below header', 'phlox'),
                'image'     => AUX_URL . 'images/visual-select/sub-menu-padding-1.svg'
            ),
            'below-menu-item' => array(
                'label'     => __('Below header menu items', 'phlox'),
                'image'     => AUX_URL . 'images/visual-select/sub-menu-padding-2.svg'
            )
        )
    );

    $options[] = array(
        'title'       => __('Menu height', 'phlox'),
        'description' => __('Specifies minimum height of main menu items.', 'phlox'),
        'id'          => 'site_header_navigation_item_height',
        'section'     => 'header-setting-section-main-nav',
        'dependency'  => array(
             array(
                 'id'      => 'site_header_navigation_sub_location',
                 'value'   => 'below-menu-item',
                 'operator'=> '=='
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_header_navigation_item_height' );

            $selector = ".site-header-section .aux-middle .aux-menu-depth-0 > .aux-item-content { height:%spx; }";

            return sprintf( $selector , $value );
        },
        'default'   => '60',
        'type'      => 'text'
    );



    // @TODO: It seems the below options should change since now we have skin option to choose or have an option of enable custom skin then shows below options
    // or first select the menu type and then shows the options related to that.

    // fullscreen dependencies


    /* @TODO: in Future updates
    $options[] = array( 'title'     => __('Main Menu Text Color', 'phlox'),
                        'description'   => __('Specifies the text color of top level menu items', 'phlox'),
                        'id'        => 'site_header_nav_main_text_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#FFFFFF',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Main Hover Back Color', 'phlox'),
                        'description'   => __('The background color of top level menu items when mouse is over', 'phlox'),
                        'id'        => 'site_header_nav_main_hover_bg_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#5fa7e0',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Main Menu hover Text Color', 'phlox'),
                        'description'   => __('Specifies the text color of top level menu items when mouse is over', 'phlox'),
                        'id'        => 'site_header_nav_main_hover_text_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#FFFFFF',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Submenu Text Color', 'phlox'),
                        'description'   => __('Specifies the text color of sub menu items', 'phlox'),
                        'id'        => 'site_header_nav_submenu_text_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#FFFFFF',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Submenu Hover Text Color', 'phlox'),
                        'description'   => __('Specifies the text color of sub menu items when mouse is over', 'phlox'),
                        'id'        => 'site_header_nav_submenu_hover_text_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#999999',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Active Menu Back Color', 'phlox'),
                        'description'   => __('The background color of current page menu', 'phlox'),
                        'id'        => 'site_header_nav_current_bg_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#5fa7e0',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Active Menu Text Color', 'phlox'),
                        'description'   => __('The text color of current page menu', 'phlox'),
                        'id'        => 'site_header_nav_current_text_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#FFFFFF',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Active Menu Border Color', 'phlox'),
                        'description'   => __('This is color of the line under current page menu', 'phlox'),
                        'id'        => 'site_header_nav_current_border_color',
                        'section'   => 'header-setting-section-main-nav',
                        'dependency'=> array(),
                        'default'   => '#333333',
                        'type'      => 'color' );
    */

    // Sub section - header mobile options -------------------------------

    $sections[] = array(
        'id'      => 'header-setting-section-mobile-header',
        'parent'  => 'header-setting-section', // section parent's id
        'title'   => __( 'Burger Menu', 'phlox'),
        'description' => __( 'Burger Menu Options', 'phlox')
    );

/*    // the hedear style which can be logo mid toggle left, logo mid toggle right, only toggle and also it can be sticky type here not with a different option
    $options[] = array( 'title'     => __('Mobile Header layout', 'phlox'),
                        'description'   => __('Specifies the position of logo and burger menu in mobile size.', 'phlox'),
                        'id'        => 'site_header_mobile_style',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'radio-image',
                        'choices'   => array(
                            'left'  => array(
                                'label'     => __('Left', 'phlox'),
                                'css_class' => 'axiAdminIcon-content-boxed',
                            ),
                            'center' => array(
                                'label'     => __('Center', 'phlox'),
                                'css_class' => 'axiAdminIcon-content-boxed'
                            ),
                            'right' => array(
                                'label' => __('Right', 'phlox'),
                                'css_class' => 'axiAdminIcon-content-full'
                            )
                        ) );*/

    $options[] = array(
        'title'          => __('Burger Button Color', 'phlox'),
        'description'    => __('Specifies burger button color.', 'phlox'),
        'id'             => 'site_mobile_header_toggle_button_color',
        'section'        => 'header-setting-section-mobile-header',
        'dependency'     => array(),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_mobile_header_toggle_button_color', '#333' );

            $selector  = ".aux-header .aux-burger:before, .aux-header .aux-burger:after, .aux-header .aux-burger .mid-line{ border-color:%s; }";

            return sprintf( $selector , $value );
        },
        'default'   => '#3d3d3d',
        'type'      => 'color',
    );

    // there are 3 types toggle, fulscreen and sidebar and all the other options are related to this choice (toggle-bar, offcanvas, overlay, none)
    $options[] = array(
        'title'       => __('Burger Menu Location', 'phlox'),
        'description' => __('Specifies where menu appears when the burger button is clicked.', 'phlox'),
        'id'          => 'site_header_mobile_menu_position',
        'section'     => 'header-setting-section-mobile-header',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'default'     => 'toggle-bar',
        'type'        => 'radio-image',
        'choices'     => array(
            'toggle-bar' => array(
                'label'     => __('Expandable under top header', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/burger-menu-location-1.svg'
            ),
            'overlay'   => array(
                'label'     => __( 'FullScreen on entire page', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/burger-menu-location-3.svg'
            ),
            'offcanvas' => array(
                'label'     => __('Offcanvas panel', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/burger-menu-location-2.svg'
            )
        )
    );

    // there are 3 types toggle, fulscreen and sidebar and all the other options are related to this choice (toggle-bar, offcanvas, overlay, none)
    $options[] = array(
        'title'       => __('Offcanvas Alignment', 'phlox'),
        'description' => __('Specifies where offcanvas menu appears when the burger button is clicked.', 'phlox'),
        'id'          => 'site_header_mobile_menu_offcanvas_alignment',
        'section'     => 'header-setting-section-mobile-header',
        'dependency'  => array(
            array(
                'id'      => 'site_header_mobile_menu_position',
                'value'   => 'offcanvas',
                'operator'=> '=='
            )
        ),
        'default'     => 'left',
        'type'        => 'radio-image',
        'choices'     => array(
            'left'  => array(
                'label'     => __('Left side', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/off-canvas-position-left.svg'
            ),
            'right' => array(
                'label'     => __( 'Right side', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/off-canvas-position-right.svg'
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$(".aux-offcanvas-menu").alterClass("aux-pin-*", "aux-pin-" + to );'
    );

    // (toggle, accordion, horizontal, vertical, cover)
    // @TODO customizer
    $options[] = array(
        'title'       => __('Burger Menu Toggle Type', 'phlox'),
        'description' => __('Specifies only one submenu shows at a time or multiple.', 'phlox'),
        'id'          => 'site_header_mobile_menu_type',
        'section'     => 'header-setting-section-mobile-header',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'default'     => 'toggle',
        'type'        => 'radio-image',
        'choices'     => array(
            'toggle'    => array(
                'label'     => __('Toggle', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/toggle.svg'
            ),
            'accordion' => array(
                'label'     => __('Accordion', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/accordion.svg'
            )
        )
    );

    $options[] = array(
        'title'       => __('Fullscreen Menu Skin', 'phlox'),
        'description' => __('Specifies the skin of fullscreen menu panel.', 'phlox'),
        'id'          => 'site_menu_full_screen_skin',
        'section'     => 'header-setting-section-mobile-header',
        'dependency'  => array(
            array(
                'id'      => 'site_header_mobile_menu_position',
                'value'   => 'overlay',
                'operator'=> '=='
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$("#fs-menu-search").alterClass("aux-dark", to );',
        'choices'    => array(
            '' => array(
                'label'     => __('Light', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/fullscreen-menu-light.svg'
            ),
            'aux-dark' => array(
                'label'     => __('Dark', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/fullscreen-menu-dark.svg'
            )
        ),
        'default'    => '',
        'type'       => 'radio-image'
    );

    $options[] = array(
        'title'       => __('Fullscreen Menu Background Color', 'phlox'),
        'description' => __('Specifies the background color of fullscreen menu and search panel.', 'phlox'),
        'id'          => 'site_menu_full_screen_background_color',
        'section'     => 'header-setting-section-mobile-header',
        'dependency'  => array(
            array(
                'id'      => 'site_header_mobile_menu_position',
                'value'   => 'overlay',
                'operator'=> '=='
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_menu_full_screen_background_color', 'rgba(255, 255, 255, 0.95)' );

            $selector  = "#fs-menu-search:before { background-color:%s; }";

            return sprintf( $selector , $value );
        },
        'default'   => 'rgba(255, 255, 255, 0.95)',
        'type'      => 'color'
    );

    $options[] = array(
        'title'       => __('Fullscreen Menu Background Image', 'phlox'),
        'description' => __('Specifies the background image of fullscreen menu panel', 'phlox'),
        'id'          => 'site_menu_full_screen_background_image',
        'section'     => 'header-setting-section-mobile-header',
        'dependency'  => array(
            array(
                'id'      => 'site_header_mobile_menu_position',
                'value'   => 'overlay',
                'operator'=> '=='
            )
        ),
        'transport'   => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_menu_full_screen_background_image', 'none' );

            $value = auxin_get_attachment_url( $value, 'full' );
            return empty( $value ) ? '' : sprintf( "#fs-menu-search:after { background-image:url( %s ); }" , $value );
        },
        'default'     => '',
        'type'        => 'image'
    );

   /* @TODO: in Future updates
    $options[] = array( 'title'     => __('Enable Sticky Header', 'phlox'),
                        'description'   => __('Enable sticky header in mobile.', 'phlox'),
                        'id'        => 'site_mobile_header_enable_sticky',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch',
                        );

    $options[] = array( 'title'     => __('Enable Floating Menu button', 'phlox'),
                        'description'   => __('Enable the floating button for menu in mobile.', 'phlox'),
                        'id'        => 'site_mobile_header_enable_floating',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(
                            array(
                                'id'      => 'site_mobile_header_enable_sticky',
                                'value'   => '1',
                                'operator'=> '!='
                            )
                        ),
                        'default'   => '1',
                        'type'      => 'switch',
                        );
    */



    /* @TODO: in Future updates
    $options[] = array( 'title'     => __('Show Search bar', 'phlox'),
                        'description'   => __('Show search bar in menu', 'phlox'),
                        'id'        => 'site_mobile_menu_enable_search',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch',
                        );
    */

    /* @TODO: in Future updates
    // dependencise of toggle mobile menu type
    $options[] = array( 'title'     => __('Menu Text Color', 'phlox'),
                        'description'   => __('Specifies the color of second header background. This background is visible only on tablet screen sizes.', 'phlox'),
                        'id'        => 'site_menu_mobile_toggle_text_color',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(
                                            array(
                                                'id'      => 'site_header_mobile_menu_type',
                                                'value'   => 'toggle',
                                                'operator'=> '=='
                                            )
                                        ),
                        'default'   => '#4A9BDC',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Menu Background Color', 'phlox'),
                        'description'   => __('Specifies the color of second header background. This background is visible only on tablet screen sizes.', 'phlox'),
                        'id'        => 'site_menu_mobile_toggle_background_color',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(
                                            array(
                                                'id'      => 'site_header_mobile_menu_type',
                                                'value'   => 'toggle',
                                                'operator'=> '=='
                                            )
                                        ),
                        'default'   => '#4A9BDC',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Second Level Menu Background Color', 'phlox'),
                        'description'   => __('Specifies the color of second header background. This background is visible only on tablet screen sizes.', 'phlox'),
                        'id'        => 'site_menu_mobile_toggle_background_color_second_level',
                        'section'   => 'header-setting-section-mobile-header',
                        'dependency'=> array(
                                            array(
                                                'id'      => 'site_header_mobile_menu_type',
                                                'value'   => 'toggle',
                                                'operator'=> '=='
                                            )
                                        ),
                        'default'   => '#4A9BDC',
                        'type'      => 'color' );
    */

    // @TODO: we have fullscreen and somewheere overly we need to make all of them fullscreen
    // dependencise of full screen mobile menu type


        // Sub section - Top Header -------------------------------

    $sections[] = array(
        'id'          => 'header-setting-section-topbar',
        'parent'      => 'header-setting-section', // section parent's id
        'title'       => __( 'Top Header bar', 'phlox'),
        'description' => __( 'Top Header bar Setting', 'phlox')
    );


    $options[] = array(
        'title'       => __('Display Top Header bar', 'phlox'),
        'description' => __('Enable it to display top header bar. You can display socials, message and call info there.', 'phlox'),
        'id'          => 'show_topheader',
        'section'     => 'header-setting-section-topbar',
        'dependency'  => array(),
        'default'     => '1',
        'post_js'     => '$(".aux-top-header").toggle( to );',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Top Header Bar Layout', 'phlox'),
        'description' => __('Specifies top header bar layout.', 'phlox'),
        'id'          => 'site_top_header_layout',
        'section'     => 'header-setting-section-topbar',
        'type'        => 'radio-image',
        'dependency'  => array(
            array(
                 'id'      => 'show_topheader',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'choices' => array(
            'topheader1' => array(
                'label'     => __('Menu left. Social and search right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-1.svg'
            ),
            'topheader2' => array(
                'label'     => __('Message left. Menu and language right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-2.svg'
            ),
            'topheader3' => array(
                'label'     => __('Social left. Cart and search right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-3.svg'
            ),
            'topheader4' => array(
                'label'     => __('Menu left. Message, social, cart, search and language right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-4.svg'
            ),
            'topheader5' => array(
                'label'     => __('Language left. Social, cart and search right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-5.svg'
            ),
            'topheader6' => array(
                'label'     => __('Message left. Social, cart and search right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-6.svg'
            ),
            'topheader7' => array(
                'label'     => __('Menu left. Social, cart and search right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-7.svg'
            ),
            'topheader8' => array(
                'label'     => __('Language and menu left. Message, social, cart and search right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/top-header-layout-8.svg'
            )
        ),
        'partial'     => array(
            'selector'              => '.aux-top-header .aux-wrapper',
            'container_inclusive'   => false,
            'render_callback'       => function(){ echo auxin_get_top_header_markup(); }
        ),
        'transport' => 'postMessage',
        'default'   => 'topheader2'
    );

    $options[] = array(
        'title'       => __('Top Header Bar Background Color', 'phlox'),
        'description' => __('Specifies the background color of top header bar.', 'phlox'),
        'id'          => 'site_top_header_background_color',
        'section'     => 'header-setting-section-topbar',
        'dependency'  => array(
            array(
                 'id'      => 'show_topheader',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value )
                $value = auxin_get_option( 'site_top_header_background_color' );

            $selector  = ".aux-top-header { background-color:%s; }";

            return sprintf( $selector , $value );
        },
        'default'   => '',
        'type'      => 'color'
    );

    $options[] = array(
        'title'       => __('Message on Top Header Bar', 'phlox'),
        'description' => __('Add a message to show on top header bar.', 'phlox'),
        'id'          => 'topheader_message',
        'section'     => 'header-setting-section-topbar',
        'dependency'  => array(
            array(
                 'id'      => 'show_topheader',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'post_js'   => '$(".aux-header-msg p").html( to );',
        'default'   => 'Call us: 123-456-7890',
        'type'      => 'textarea'
    );

    // Sub section - Full screen search options -------------------------------

    $sections[] = array(
        'id'          => 'header-setting-section-fullsscreen-search',
        'parent'      => 'header-setting-section', // section parent's id
        'title'       => __( 'FullScreen Search', 'phlox'),
        'description' => __( 'FullScreen Search', 'phlox')
    );

    $options[] = array(
        'title'       => __('FullScreen Search Skin', 'phlox'),
        'description' => __('Set the fullscreen search skin.', 'phlox'),
        'id'          => 'header_fullscreen_search_skin',
        'section'     => 'header-setting-section-fullsscreen-search',
        'default'     => '',
        'type'        => 'radio-image',
        'transport'   => 'postMessage',
        'post_js'     => '$("#fs-search").alterClass("aux-dark", to );',
        'choices'     => array(
            ''         => array(
                'label'     => __('Light', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/fullscreen-search-light.svg'
            ),
            'aux-dark' => array(
                'label'     => __('Dark', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/fullscreen-search-dark.svg'
            )
        )
    );

   // @TODO: commented for future updates
   //  $options[] = array( 'title'     => __('Show Logo', 'phlox'),
   //                      'description'   => __('Show logo on full screen search', 'phlox'),
   //                      'id'        => 'site_search_full_screen_show_logo',
   //                      'section'   => 'header-setting-section-fullsscreen-search',
   //                      'dependency'=> array(),
   //                      'default'   => '1',
   //                      'type'      => 'switch' );

   //  $options[] = array( 'title'     => __('Background color', 'phlox'),
   //                      'description'   => __('Specifies the background color of fullscreen search', 'phlox'),
   //                      'id'        => 'site_search_full_screen_background_color',
   //                      'section'   => 'header-setting-section-fullsscreen-search',
   //                      'dependency'=> array(),
   //                      'default'   => '#4A9BDC',
   //                      'type'      => 'color' );

   //  $options[] = array( 'title'     => __('Background Image', 'phlox'),
   //                      'description'   => __('Specifies the background color of fullscreen search', 'phlox'),
   //                      'id'        => 'site_search_full_screen_background_image',
   //                      'section'   => 'header-setting-section-fullsscreen-search',
   //                      'dependency'=> array(),
   //                      'default'   => '',
   //                      'type'      => 'image' );

   // $options[] = array( 'title'     => __('Search Text Color', 'phlox'),
   //                      'description'   => __('Specifies the background color of fullscreen search', 'phlox'),
   //                      'id'        => 'site_search_full_screen_text_color',
   //                      'section'   => 'header-setting-section-fullsscreen-search',
   //                      'dependency'=> array(),
   //                      'default'   => '#4A9BDC',
   //                      'type'      => 'color' );




    /* ---------------------------------------------------------------------------------------------------
        Blog Section
    --------------------------------------------------------------------------------------------------- */

    // Blog section ==================================================================

    $sections[] = array(
        'id'          => 'blog-setting-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Blog', 'phlox'),
        'description' => __( 'Blog Setting', 'phlox'),
        'icon'        => 'axicon-doc'
    );

    // Sub section - Blog Single Page -------------------------------

    $sections[] = array(
        'id'          => 'blog-setting-section-single',
        'parent'      => 'blog-setting-section', // section parent's id
        'title'       => __( 'Single Post', 'phlox'),
        'description' => __( 'Single Post Setting', 'phlox')
    );

    //  @TODO: icones should desing
    // $options[] = array(
    //     'title'       => __('Single Post Style', 'phlox'),
    //     'description' => __('Specifies single post style.', 'phlox'),
    //     'id'          => 'post_single_style',
    //     'section'     => 'blog-setting-section-single',
    //     'dependency'  => array(),
    //     'post_js'     => '$(".single-post main.aux-single").alterClass( "*-sidebar", to );',
    //     'choices'     => array(
    //         'no-sidebar' => array(
    //             'label'     => __('No Sidebar', 'phlox'),
    //             'css_class' => 'axiAdminIcon-sidebar-none'
    //         ),
    //         'right-sidebar' => array(
    //             'label'     => __('Right Sidebar', 'phlox'),
    //             'css_class' => 'axiAdminIcon-sidebar-right'
    //         ),
    //     ),
    //     'default'   => 'right-sidebar',
    //     'type'      => 'radio-image'
    // );


    $options[] = array(
        'title'       => __('Single Post Sidebar Position', 'phlox'),
        'description' => __('Specifies position of sidebar on single post.', 'phlox'),
        'id'          => 'post_single_sidebar_position',
        'section'     => 'blog-setting-section-single',
        'dependency'  => array(),
        'post_js'     => '$(".single-post main.aux-single").alterClass( "*-sidebar", to );',
        'choices'     => array(
            'no-sidebar' => array(
                'label'     => __('No Sidebar', 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-none'
            ),
            'right-sidebar' => array(
                'label'     => __('Right Sidebar', 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-right'
            ),
            'left-sidebar'  => array(
                'label'     => __('Left Sidebar' , 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-left'
            ),
            'left2-sidebar' => array(
                'label'     => __('Left Left Sidebar' , 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-left-left'
            ),
            'right2-sidebar' => array(
                'label'     => __('Right Right Sidebar' , 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-right-right'
            ),
            'left-right-sidebar' => array(
                'label'     => __('Left Right Sidebar' , 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            ),
            'right-left-sidebar' => array(
                'label'     => __('Right Left Sidebar' , 'phlox'),
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'default'   => 'right-sidebar',
        'type'      => 'radio-image'
    );

    $options[] =    array(
        'title'       => __('Single Post Sidebar Style', 'phlox'),
        'description' => 'Specifies style of sidebar on single post.',
        'id'          => 'post_single_sidebar_decoration',
        'section'     => 'blog-setting-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'post_single_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-post main.aux-single").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
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
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );


    $options[] =    array(
        'title'       => __('Title Alignment', 'phlox'),
        'description' => __('Specifies title alignment on single post.', 'phlox'),
        'id'          => 'post_single_title_alignment',
        'section'     => 'blog-setting-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-post main .entry-main > .entry-header").alterClass( "aux-text-align-*", "aux-text-align-" + to );',
        'choices'     => array(
            'default' => array(
                'label'     => __('Left', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-left',
            ),
            'center' => array(
                'label'     => __('Center', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-center'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'default'
    );

    $options[] =    array(
        'title'       => __('Narrow Post Context', 'phlox'),
        'description' => __('Enable it to reduce the width of text lines and increase the readability of context (does not affect the width of media).', 'phlox'),
        'id'          => 'post_single_narrow_context',
        'section'     => 'blog-setting-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-post .aux-primary .hentry").toggleClass( "aux-narrow-context", 1 == to );',
        'type'        => 'switch',
        'default'     => '0'
    );


    /*$options[] = array( 'title'     => __('Display Related Posts?', 'phlox'),
                        'description'   => __('Do you want to display related post at the bottom of each blog post? ', 'phlox'),
                        'id'        => 'show_blog_related_posts',
                        'section'   => 'blog-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Related Posts Title', 'phlox'),
                        'description'   => '',
                        'id'        => 'blog_related_posts_title',
                        'section'   => 'blog-setting-section-single',
                        'dependency'=> array(
                                            array(
                                                 'id'      => 'show_blog_related_posts',
                                                 'value'   => array('1'),
                                                 'operator'=> ''
                                            )
                                        ),
                        'default'   => 'Related Posts',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Related Post Size', 'phlox'),
                        'description'   => __('If You choose 1/3 , 3 Related posts are visible.', 'phlox'),
                        'id'        => 'blog_related_posts_size',
                        'section'   => 'blog-setting-section-single',
                        'dependency'=> array(),
                        'choices'   => array(   'three-column'  => '1/3',
                                            'four-column'   => '1/4' ),
                        'default'   => 'three-column',
                        'type'      => 'select' );*/

    $options[] = array(
        'title'       => __('Display Author Section', 'phlox'),
        'description' => sprintf(__('Enable it to display %s author information%s after post content on single post.', 'phlox'), '<strong>', '</strong>'),
        'id'          => 'show_blog_author_section',
        'section'     => 'blog-setting-section-single',
        'dependency'  => array(),
        'transport'   => 'refresh',
        //'post_js'     => '$(".single-post .entry-author-info").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Display Author Biography Text', 'phlox'),
        'description' => sprintf(__('Enable it to display %s author biography text%s in author section on single post.', 'phlox'), '<strong>', '</strong>'),
        'id'          => 'show_blog_author_section_text',
        'section'     => 'blog-setting-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_blog_author_section',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-post .entry-author-info .author-description dd").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Display Author Socials', 'phlox'),
        'description' => sprintf(__('Enable it to display %s author socials%s in author section on single post.', 'phlox'), '<strong>', '</strong>'),
        'id'          => 'show_blog_author_section_social',
        'section'     => 'blog-setting-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_blog_author_section',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$(".single-post .entry-author-info .aux-author-socials").toggle( to );',
        'default'   => '1',
        'type'      => 'switch'
    );

    $options[] = array(
        'title'       => __('Display Like Button', 'phlox'),
        'description' => sprintf(__('Enable it to display %s like button%s on single post. Please note WP Ulike plugin needs to be activaited in order to use this option.', 'phlox'), '<strong>', '</strong>'),
        'id'          => 'show_blog_post_like_button',
        'section'     => 'blog-setting-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-post .entry-info .wpulike").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __( 'Display Next & Previous Posts', 'phlox' ),
        'description' => __( 'Enable it to display links to next and previous posts on single post page.' ),
        'id'          => 'show_post_single_next_prev_nav',
        'section'     => 'blog-setting-section-single',
        'dependency'  => '',
        'transport'   => 'postMessage',
        'post_js'     => '$(".single .aux-next-prev-posts").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] =    array(
        'title'       => __('Skin for Next & Previous Links', 'phlox'),
        'description' => __('Specifies the skin for next and previous navigation block.', 'phlox'),
        'id'          => 'post_single_next_prev_nav_skin',
        'section'     => 'blog-setting-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'show_post_single_next_prev_nav',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport'   => 'refresh',
        'choices'     => array(
            'minimal' => array(
                'label'     => __('Minimal (default)', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-left',
            ),
            'thumb-arrow' => array(
                'label'     => __('Thumbnail with Arrow', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-center'
            ),
            'thumb-no-arrow' => array(
                'label'     => __('Thumbnail without Arrow', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-center'
            ),
            'boxed-image' => array(
                'label'     => __('Navigation with Light Background', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-center'
            ),
            'boxed-image-dark' => array(
                'label'     => __('Navigation with Dark Background', 'phlox'),
                'css_class' => 'axiAdminIcon-text-align-center'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'minimal'
    );

    /*$options[] = array( 'title'     => __('View All button link', 'phlox'),
                        'description'   => __('Specifies a link for "view all" button to blog listing page (the button that comes at the end of latest from blog element ) ', 'phlox'),
                        'id'        => 'blog_view_all_btn_link',
                        'section'   => 'blog-setting-section-single',
                        'dependency'=> array(),
                        'default'   => home_url(),
                        'type'      => 'text' );*/

    // Sub section - Blog Archive Page -------------------------------

    $sections[] = array(
        'id'          => 'blog-setting-section-archive',
        'parent'      => 'blog-setting-section', // section parent's id
        'title'       => __( 'Blog Page', 'phlox'),
        'description' => __( 'Setting for Blog Archive Page', 'phlox')
    );

    $options[] = array(
        'title'       => __('Blog Template', 'phlox'),
        'description' => __('Choose your blog template.', 'phlox'),
        'id'          => 'post_index_template_type',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'choices'     => array(
             // default template
            'default' => array(
                'label'  => __('Default', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-6.svg'
            ),
            '1' => array(
                'label'  => __('Template 1', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-1.svg'
            ),
            '2' => array(
                'label'  => __('Template 2' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-2.svg'
            ),
            '3' => array(
                'label'  => __('Template 3' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-3.svg'
            ),
            '4' => array(
                'label'  => __('Template 4' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-4.svg'
            ),
            '5' => array(
                'label'  => __('Grid' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-9.svg'
            ),
            '6' => array(
                'label'  => __('Masonry' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-7.svg'
            ),
            '7' => array(
                'label'  => __('Timeline' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-8.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'default'
    );

    $options[] = array(
        'title'       => __('Content Layout', 'phlox'),
        'description' => __('Specifies the style of content for each post column.', 'phlox'),
        'id'          => 'post_index_column_content_layout',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_template_type',
                 'value'   => '5',
                 'operator'=> '=='
            ),
            array(
                 'id'      => 'post_index_template_type',
                 'value'   => '6',
                 'operator'=> '=='
            ),
            'relation' => 'or'
        ),
        'type'        => 'radio-image',
        'choices'     => array(
            'full' => array(
                'label'  => __('Full Content' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-9.svg'
            ),
            'entry-boxed' => array(
                'label'  => __('Boxed Content' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-9.svg'
            )
        ),
        'transport'   => 'refresh',
        'default'     => 'full',
    );

    if ( auxin_is_plugin_active( 'wp-ulike/wp-ulike.php')){
        $options[] = array(
            'title'       => __('Display Like Button', 'phlox'),
            'description' => sprintf(__('Enable it to display %s like button%s on gride template blog. Please note WP Ulike plugin needs to be activaited to use this option.', 'phlox'), '<strong>', '</strong>'),
            'id'          => 'show_blog_archive_like_button',
            'section'     => 'blog-setting-section-archive',
            'dependency'  => array(
                array(
                     'id'      => 'post_index_template_type',
                     'value'   => '5',
                     'operator'=> '=='
                )
            ),
            'transport'   => 'postMessage',
            'post_js'     => '$(".aux-template-type-5 .aux-col .wpulike").toggle( to );',
            'default'     => '1',
            'type'        => 'switch'
        );
    }

    $options[] = array(
        'title'       => __('Timeline Alignment', 'phlox'),
        'description' => __('Specifies the alignment of timeline on blog page.', 'phlox'),
        'id'          => 'post_index_timeline_alignment',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_template_type',
                 'value'   => '7',
                 'operator'=> '=='
            )
        ),
        'type'        => 'radio-image',
        'choices'     => array(
            'center'  => array(
                'label' => __('Center', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-8.svg'
            ),
            'left'    => array(
                'label' => __('Left', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-8-left.svg'
            ),
            'right'   => array(
                'label' => __('Right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-8-right.svg'
            )
        ),
        'transport'   => 'refresh',
        'default'     => 'center',
    );

    $options[] = array(
        'title'       => __('Blog Sidebar Position', 'phlox'),
        'description' => __('Specifies the position of sidebar on blog page.', 'phlox'),
        'id'          => 'post_index_sidebar_position',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(),
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
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'dependency'    => array(),
        'post_js'       => '$(".blog .aux-archive, main.aux-home").alterClass( "*-sidebar", to );',
        'type'          => 'radio-image',
        'default'       => 'right-siderbar'
    );

    $options[] = array(
        'title'       => __('Blog Sidebar Style', 'phlox'),
        'description' => __('Specifies the style of sidebar on blog page.', 'phlox'),
        'id'          => 'post_index_sidebar_decoration',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".blog .aux-archive, main.aux-home").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'     => array(
            'simple' => array(
                'label'  => __('Simple' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border' => array(
                'label'  => __('Bordered Sidebar' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap' => array(
                'label'  => __('Overlap Background' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );

    $options[] = array(
        'title'       => __('Blog content length', 'phlox'),
        'description' => sprintf(__('Whether to display%1$ssummary%2$sor%1$sfull%2$scontent for each post on blog page', 'phlox'), '<code>', '</code>'),
        'id'          => 'blog_content_on_listing',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'choices'     => array(
            'full'    => array(
                'label' =>__('Full text', 'phlox'),
                'css_class' => 'axiAdminIcon-blog-content-length-2'
            ),
            'excerpt'    => array(
                'label' => __('Summary'  , 'phlox'),
                'css_class' => 'axiAdminIcon-blog-content-length-1'
            )
        ),
        'default'     => 'full',
        'type'        => 'radio-image'
    );

    $options[] = array(
        'title'       => __('Summery length', 'phlox'),
        'description' => __('Specifies summary character length for each post on blog page.', 'phlox'),
        'id'          => 'blog_content_on_listing_length',
        'section'     => 'blog-setting-section-archive',
        'dependency'  => array(
            array(
                 'id'      => 'blog_content_on_listing',
                 'value'   => array('excerpt'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'default'   => '255',
        'type'      => 'text'
    );


    // Sub section - Blog Archive Page Slider -------------------------------

    $sections[] = array(
        'id'          => 'blog-setting-section-archive-slider',
        'parent'      => 'blog-setting-section', // section parent's id
        'title'       => __( 'Blog Slider', 'phlox'),
        'description' => __( 'Blog Slider Settings', 'phlox')
    );

    $options[] = array(
        'title'       => __('Display Slider', 'phlox'),
        'description' => __('Specifies to insert post slide above blog posts.', 'phlox'),
        'id'          => 'post_archive_slider_show',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(),
        'post_js'     => '$(".aux-wrapper-post-slider").toggle( to );',
        'default'     => '1',
        'type'        => 'switch'
    );





    $options[] = array(
        'title'         => __('Slider location', 'phlox'),
        'description'   => '',
        'id'            => 'post_archive_slider_location',
        'section'       => 'blog-setting-section-archive-slider',
        'dependency'    => array (
            array (
                 'id'    => 'post_archive_slider_show',
                 'value' => array(1)
            )
        ),
        'transport'     => 'refresh',
        'choices'   => array (
            'content' => array(
                'label' =>__('Insert above archive content', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/slider-position-above-content.svg'
            ),
            'block' => array(
                'label' => __('Insert below the header', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/slider-position-blow-header.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'block'
    );


    // $options[] = array( 'title'     => __('Create slides from', 'phlox'),
    //                     'description'   => '',
    //                     'id'        => 'post_archive_slider_post_type',
    //                     'section'   => 'blog-setting-section-archive-slider',
    //                     'dependency'=> array (
    //                                         array (
    //                                              'id'      => 'post_archive_slider_show',
    //                                              'value'   => array(1)
    //                                         )
    //                                 ),
    //                     'choices'   => array (
    //                                             'post'  => __('Posts', 'phlox'),
    //                                             'page'   => __('Pages', 'phlox'),
    //                                         ),
    //                     'type'          => 'select',
    //                     'default'       => 'post' );

    $options[] = array(
        'title'       => __('Slides number', 'phlox'),
        'description' => __('Specifies maximum number of slides in slider.', 'phlox'),
        'id'          => 'post_archive_slider_slides_num',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'partial'       => array(
            'selector'              => '.aux-wrapper-post-slider',
            'container_inclusive'   => true,
            'render_callback'       => function(){ auxin_get_the_archive_slider('post', 'block'); }
        ),
        'default'   => '10',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Exclude posts', 'phlox'),
        'description' => __('Post IDs separated by comma (eg. 53,34,87,25).', 'phlox'),
        'id'          => 'post_archive_slider_exclude',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'     => 'post_archive_slider_show',
                'value'  => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Include posts', 'phlox'),
        'description' => __('Post IDs separated by comma (eg. 53,34,87,25).', 'phlox'),
        'id'          => 'post_archive_slider_include',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Start offset', 'phlox'),
        'description' => __('Specifies number of post to displace or pass over.', 'phlox'),
        'id'          => 'post_archive_slider_offset',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Order by', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_order_by',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'choices'   => array (
            'date'            => __('Date', 'phlox'),
            'menu_order date' => __('Menu Order', 'phlox'),
            'title'           => __('Title', 'phlox'),
            'ID'              => __('ID', 'phlox'),
            'rand'            => __('Random', 'phlox'),
            'comment_count'   => __('Comments', 'phlox'),
            'modified'        => __('Date Modified', 'phlox'),
            'author'          => __('Author', 'phlox'),
        ),
        'type'     => 'select',
        'default'  => 'date'
    );

    $options[] = array(
        'title'     => __('Order direction', 'phlox'),
        'description'   => '',
        'id'        => 'post_archive_slider_order_dir',
        'section'   => 'blog-setting-section-archive-slider',
        'dependency'=> array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'choices'   => array (
                'DESC'  => __('Descending', 'phlox'),
                'ASC'   => __('Ascending', 'phlox'),
        ),
        'type'          => 'select',
        'default'       => 'DESC'
    );

    $options[] = array(
        'title'       => __('Slider skin', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_skin',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'    => 'post_archive_slider_show',
                'value' => array(1)
            )
        ),
        'transport' => 'refresh',
        'choices'   => array (
            'aux-light-skin' => array(
                'label' =>__('Light and boxed', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/slider-skin-1.svg'
            ),
            'aux-dark-skin' => array(
                'label' => __('Dark and boxed', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/slider-skin-2.svg'
            ),
            'aux-full-light-skin' => array(
                'label' => __('Light overlay', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/slider-skin-3.svg'
            ),
            'aux-full-dark-skin' => array(
                'label'  => __('Dark overlay', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/slider-skin-4.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'aux-light-skin'
    );

    $options[] = array(
        'title'       => __('Insert post title', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_add_title',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '1',
        'type'      => 'switch'
    );

    $options[] = array(
        'title'       => __('Insert post meta', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_add_meta',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
               'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            ),
            array(
               'id'      => 'post_archive_slider_add_title',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '1',
        'type'      => 'switch'
    );

    $options[] = array(
        'title'     => __('Grab the image from', 'phlox'),
        'description'   => '',
        'id'        => 'post_archive_slider_image_from',
        'section'   => 'blog-setting-section-archive-slider',
        'dependency'=> array (
            array(
               'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'choices'   => array (
            'auto'      => __('Auto select', 'phlox'),
            'featured'  => __('Featured image', 'phlox'),
            'first'     => __('First image in post', 'phlox'),
            'custom'    => __('Custom image', 'phlox')
        ),
        'type'          => 'select',
        'default'       => 'auto'
    );

    $options[] = array(
        'title'       => __('Background image', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_custom_image',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            ),
            array(
                'id'      => 'post_archive_slider_image_from',
                'value'   => array('custom')
            )
        ),
        'transport'   => 'refresh',
        'default'     => '',
        'type'        => 'image'
    );

    $options[] = array(
        'title'       => __('Exclude posts without image', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_exclude_without_images',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '1',
        'type'      => 'switch' );

    $options[] = array(
        'title'       => __('Slider image width', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_width',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '960',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Slider image height', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_height',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '560',
        'type'      => 'text' );

    $options[] = array(
        'title'       => __('Arrow navigation', 'phlox'),
        'description' => __('Specifies to insert arrow buttons over slider.', 'phlox'),
        'id'          => 'post_archive_slider_arrows',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '0',
        'type'      => 'switch'
    );

    $options[] = array(
        'title'       => __('Space between slides', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_space',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array(
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '5',
        'type'      => 'text'
    );

    $options[] = array(
        'title'       => __('Looped navigation', 'phlox'),
        'description' => '',
        'id'          => 'post_archive_slider_loop',
        'section'     => 'blog-setting-section-archive-slider',
        'dependency'  => array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '1',
        'type'      => 'switch'
    );


    $options[] = array(
        'title'     => __('Slideshow', 'phlox'),
        'description'   => '',
        'id'        => 'post_archive_slider_slideshow',
        'section'   => 'blog-setting-section-archive-slider',
        'dependency'=> array (
            array(
                'id'      => 'post_archive_slider_show',
                'value'   => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '0',
        'type'      => 'switch'
    );

    $options[] = array(
        'title'         => __('Slideshow delay in seconds', 'phlox'),
        'description'   => '',
        'id'            => 'post_archive_slider_slideshow_delay',
        'section'       => 'blog-setting-section-archive-slider',
        'dependency'    => array(
            array(
                'id'     => 'post_archive_slider_show',
                'value' => array(1)
            ),
            array(
                'id'     => 'post_archive_slider_slideshow',
                'value' => array(1)
            )
        ),
        'transport' => 'refresh',
        'default'   => '2',
        'type'      => 'text'
    );



    // Sub section - Blog Taxonomy Page -------------------------------

    $sections[] = array(
        'id'          => 'blog-setting-section-taxonomy',
        'parent'      => 'blog-setting-section', // section parent's id
        'title'       => __( 'Blog Category & tag', 'phlox'),
        'description' => __( 'Blog Category & tag page Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __('Taxonomy Page Template', 'phlox'),
        'description' => 'Choose your category & tag page template.',
        'id'          => 'post_taxonomy_archive_template_type',
        'section'     => 'blog-setting-section-taxonomy',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'choices'     => array(
            // default template
            'default' => array(
                'label'  => __('Default', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-6.svg'
            ),
            // aux-template-type-1
            '1' => array(
                'label'  => __('Template 1', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-1.svg'
            ),
            // aux-template-type-2
            '2' => array(
                'label'  => __('Template 2' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-2.svg'
            ),
            // aux-template-type-3
            '3' => array(
                'label'  => __('Template 3' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-3.svg'
            ),
            // aux-template-type-4
            '4' => array(
                'label'  => __('Template 4' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/blog-layout-4.svg'
            ),
            // @TODO: not released yet
            // '5' => array(
            //     'label'  => __('Grid' , 'phlox'),
            //     'image' => AUX_URL . 'images/visual-select/blog-layout-9.svg'
            // ),
            // '6' => array(
            //     'label'  => __('Masonry' , 'phlox'),
            //     'image' => AUX_URL . 'images/visual-select/blog-layout-7.svg'
            // ),
            // '7' => array(
            //     'label'  => __('Timeline' , 'phlox'),
            //     'image' => AUX_URL . 'images/visual-select/blog-layout-8.svg'
            // )
        ),
        'type'          => 'radio-image',
        'default'       => 'default'
    );

    // @TODO: not released yet
    // if ( auxin_is_plugin_active( 'wp-ulike/wp-ulike.php')){
    //     $options[] = array(
    //         'title'       => __('Display Like Button', 'phlox'),
    //         'description' => sprintf(__('Enable it to display %s like button%s on gride template blog. Please note WP Ulike plugin needs to be activaited to use this option.', 'phlox'), '<strong>', '</strong>'),
    //         'id'          => 'post_taxonomy_show_like_button',
    //         'section'     => 'blog-setting-section-taxonomy',
    //         'dependency'  => array(
    //             array(
    //                  'id'      => 'post_taxonomy_archive_template_type',
    //                  'value'   => '5',
    //                  'operator'=> '=='
    //             )
    //         ),
    //         'transport'   => 'postMessage',
    //         'post_js'     => '$(".aux-template-type-5 .aux-col .wpulike").toggle( to );',
    //         'default'     => '1',
    //         'type'        => 'switch'
    //     );
    // }

    $options[] = array(
        'title'       => __('Taxonomy Page Sidebar Position', 'phlox'),
        'description' => 'Specifies the position of sidebar on category & tag page.',
        'id'          => 'post_taxonomy_archive_sidebar_position',
        'section'     => 'blog-setting-section-taxonomy',
        'dependency'  => array(),
        'post_js'     => '$(".archive.tag main, .archive.category main").alterClass( "*-sidebar", to );',
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
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'right-siderbar'
    );

    $options[] = array(
        'title'       => __('Sidebar Style', 'phlox'),
        'description' => __('Specifies the style of sidebar on category & tag page.', 'phlox'),
        'id'          => 'post_taxonomy_archive_sidebar_decoration',
        'section'     => 'blog-setting-section-taxonomy',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'dependency' => array(),
        'post_js'    => '$(".archive.tag main, .archive.category main").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'     => array(
            'simple' => array(
                'label'  => __('Simple' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border' => array(
                'label'  => __('Bordered Sidebar' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap' => array(
                'label'  => __('Overlap Background' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );

    $options[] = array(
        'title'       => __('Taxonomy content length', 'phlox'),
        'description' => sprintf(__('Whether to display%1$ssummary%2$sor%1$sfull%2$scontent for each post on category & tag page.', 'phlox'), '<code>', '</code>'),
        'id'          => 'post_taxonomy_archive_content_on_listing',
        'section'     => 'blog-setting-section-taxonomy',
        'dependency'  => array(),
        'transport'   => 'refresh',
        'choices'     => array(
            'full'    => array(
                'label' =>__('Full text', 'phlox'),
                'css_class' => 'axiAdminIcon-blog-content-length-2'
            ),
            'excerpt'    => array(
                'label' => __('Summary'  , 'phlox'),
                'css_class' => 'axiAdminIcon-blog-content-length-1'
            )
        ),
        'default'     => 'full',
        'type'        => 'radio-image'
    );

    $options[] = array(
        'title'       => __('Summery length', 'phlox'),
        'description' => __('Specifies summary character length on category & tag page.', 'phlox'),
        'id'          => 'post_taxonomy_archive_on_listing_length',
        'section'     => 'blog-setting-section-taxonomy',
        'dependency'  => array(
            array(
                 'id'      => 'post_taxonomy_archive_content_on_listing',
                 'value'   => array('excerpt'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'default'   => '255',
        'type'      => 'text'
    );


    /* ---------------------------------------------------------------------------------------------------
        News Section
    --------------------------------------------------------------------------------------------------- */

    // News section ==================================================================

    // $sections[] = array(
    //     'id'      => 'news-setting-section',
    //     'parent'  => '', // section parent's id
    //     'title'   => __( 'News', 'phlox'),
    //     'description' => __( 'News Setting', 'phlox'),
    //     'icon'    => 'axicon-calendar-empty'
    // );

    // Sub section - Single News Page -------------------------------
    /*
    $sections[] = array(
        'id'      => 'news-setting-section-single',
        'parent'  => 'blog-setting-section', // section parent's id
        'title'   => __( 'Single News Page', 'phlox'),
        'description' => __( 'Single News Page Setting', 'phlox')
    );


    $options[] = array( 'title'     => __('Single News Sidebar Layout', 'phlox'),
                        'description'   => __('Specifies the position of sidebar on news single page', 'phlox'),
                        'id'        => 'news_sidebar_position',
                        'section'   => 'news-setting-section-single',
                        'dependency'=> array(),
                        'choices' => array(
                                    'no-sidebar' => array(
                                        'label'  => __('No Sidebar', 'phlox'),
                                        'css_class' => 'axiAdminIcon-no-sidebar'
                                    ),
                                    'right-sidebar' => array(
                                        'label'  => __('Right Sidebar', 'phlox'),
                                        'css_class' => 'axiAdminIcon-right-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-left-sidebar' => array(
                                        'label'  => __('Left Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'right-right-sidebar' => array(
                                        'label'  => __('Right Right Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-right-sidebar' => array(
                                        'label'  => __('Left Right Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'right-left-sidebar' => array(
                                        'label'  => __('Right Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    )
                                ),
                        'default'   => 'no-sidebar',
                        'type'          => 'radio-image');

    $options[] = array( 'title'     => __('Display Related News?', 'phlox'),
                        'description'   => __('Do you want to display related news at the bottom of each news post? ', 'phlox'),
                        'id'        => 'show_news_related_posts',
                        'section'   => 'news-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Related News Title', 'phlox'),
                        'description'   => '',
                        'id'        => 'news_related_posts_title',
                        'section'   => 'news-setting-section-single',
                        'dependency'=> array(),
                        'default'   => 'Related News',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Related News Posts Size', 'phlox'),
                        'description'   => __('If You choose 1/3 , 3 Related items are visible.', 'phlox'),
                        'id'        => 'news_related_posts_size',
                        'section'   => 'news-setting-section-single',
                        'dependency'=> array(),
                        'choices'   => array(   'three-column'  => '1/3',
                                            'four-column'   => '1/4' ),
                        'default'   => 'three-column',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display Author Section?', 'phlox'),
                        'description'   => __('Do you want to display author information after each single news? ', 'phlox'),
                        'id'        => 'show_news_author_section',
                        'section'   => 'news-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('View All button link', 'phlox'),
                        'description'   => __('Specifies a link for "view all" button to news listing page (the button that comes at the end of latest news element ) ', 'phlox'),
                        'id'        => 'news_view_all_btn_link',
                        'section'   => 'news-setting-section-single',
                        'dependency'=> array(),
                        'default'   => home_url(),
                        'type'      => 'text' );

    // Sub section - Archive News Page -------------------------------

    $sections[] = array(
        'id'      => 'news-setting-section-archive',
        'parent'  => 'blog-setting-section', // section parent's id
        'title'   => __( 'News Index Page', 'phlox'),
        'description' => __( 'News Index (Archive) Page Setting', 'phlox')
    );


    $options[] = array( 'title'     => __('For each post in news listing page, show', 'phlox'),
                        'description'   => '',
                        'id'        => 'news_content_on_listing',
                        'section'   => 'news-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   'full'      => __('Full text', 'phlox'),
                                                'excerpt'   => __('Summary'  , 'phlox')
                                         ),
                        'default'   => 'excerpt',
                        'type'      => 'select' );


    $options[] = array( 'title'     => __('News Blog Page Template', 'phlox'),
                        'description'   => __('Specifies the template for news blog page', 'phlox'),
                        'id'        => 'news_index_page_template',
                        'section'   => 'news-setting-section-archive',
                        'dependency'=> array(),
                        'choices' => array(
                                    'no-sidebar' => array(
                                        'label'  => __('Template 1', 'phlox'),
                                        'css_class' => 'axiAdminIcon-no-sidebar'
                                    ),
                                    'right-sidebar' => array(
                                        'label'  => __('Template 2', 'phlox'),
                                        'css_class' => 'axiAdminIcon-right-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Template 3' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Template 4' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    )
                                ),
                        'default'   => 'no-sidebar',
                        'type'      => 'radio-image' );

    $options[] = array( 'title'     => __('News Index Page Sidebar Position', 'phlox'),
                        'description'   => __('Specifies the position of sidebar on news category page', 'phlox'),
                        'id'        => 'news_index_sidebar_position',
                        'section'   => 'news-setting-section-archive',
                        'dependency'=> array(),
                        'choices' => array(
                                    'no-sidebar' => array(
                                        'label'  => __('No Sidebar', 'phlox'),
                                        'css_class' => 'axiAdminIcon-no-sidebar'
                                    ),
                                    'right-sidebar' => array(
                                        'label'  => __('Right Sidebar', 'phlox'),
                                        'css_class' => 'axiAdminIcon-right-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-left-sidebar' => array(
                                        'label'  => __('Left Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'right-right-sidebar' => array(
                                        'label'  => __('Right Right Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-right-sidebar' => array(
                                        'label'  => __('Left Right Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'right-left-sidebar' => array(
                                        'label'  => __('Right Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    )
                                ),
                        'type'          => 'radio-image',
                        'default'       => 'right-siderbar' );

    // --- News category page setting -----------------------------------------

    $sections[] = array(
        'id'      => 'news-setting-section-category',
        'parent'  => 'blog-setting-section', // section parent's id
        'title'   => __( 'News Category Page', 'phlox'),
        'description' => __( 'News Category Page Setting', 'phlox')
    );

    $options[] = array( 'title'     => __('News Category Page Template', 'phlox'),
                        'description'   => __('Specifies the template for news category page', 'phlox'),
                        'id'        => 'news_category_page_template',
                        'section'   => 'news-setting-section-category',
                        'dependency'=> array(),
                        'choices' => array(
                                    'no-sidebar' => array(
                                        'label'  => __('Template 1', 'phlox'),
                                        'css_class' => 'axiAdminIcon-no-sidebar'
                                    ),
                                    'right-sidebar' => array(
                                        'label'  => __('Template 2', 'phlox'),
                                        'css_class' => 'axiAdminIcon-right-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Template 3' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Template 4' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    )
                                ),
                        'default'   => 'no-sidebar',
                        'type'      => 'radio-image' );

    $options[] = array( 'title'     => __('Category Sidebar Position', 'phlox'),
                        'description'   => __('Specifies the position of sidebar on news category page', 'phlox'),
                        'id'        => 'news_category_sidebar_position',
                        'section'   => 'news-setting-section-category',
                        'dependency'=> array(),
                        'choices' => array(
                                    'no-sidebar' => array(
                                        'label'  => __('No Sidebar', 'phlox'),
                                        'css_class' => 'axiAdminIcon-no-sidebar'
                                    ),
                                    'right-sidebar' => array(
                                        'label'  => __('Right Sidebar', 'phlox'),
                                        'css_class' => 'axiAdminIcon-right-sidebar'
                                    ),
                                    'left-sidebar' => array(
                                        'label'  => __('Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-left-sidebar' => array(
                                        'label'  => __('Left Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'right-right-sidebar' => array(
                                        'label'  => __('Right Right Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'left-right-sidebar' => array(
                                        'label'  => __('Left Right Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    ),
                                    'right-left-sidebar' => array(
                                        'label'  => __('Right Left Sidebar' , 'phlox'),
                                        'css_class' => 'axiAdminIcon-left-sidebar'
                                    )
                                ),
                        'type'          => 'radio-image',
                        'default'       => 'right-siderbar' );

*/
    /* ---------------------------------------------------------------------------------------------------
        Portfolio Section
    --------------------------------------------------------------------------------------------------- */
    // Portfolio section ==================================================================
    /* TODO: we will add this in futur updates
    $sections[] = array(
        'id'      => 'portfolio-setting-section',
        'parent'  => '', // section parent's id
        'title'   => __( 'Portfolio', 'phlox'),
        'description' => __( 'Portfolio Setting', 'phlox'),
        'icon'    => 'axicon-picture-1'
    );

    // Sub section - Single Portfolio Page -------------------------------

    $sections[] = array(
        'id'      => 'portfolio-setting-section-single',
        'parent'  => 'portfolio-setting-section', // section parent's id
        'title'   => __( 'Portfolio Single Page', 'phlox'),
        'description' => __( 'Portfolio Single Page Setting', 'phlox')
    );


    $options[] = array( 'title'     => __('Display Related Projects?', 'phlox'),
                        'description'   => __('Do you want to display related projects at bottom of the page? ', 'phlox'),
                        'id'        => 'show_portfolio_related_items',
                        'section'   => 'portfolio-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Related Projects Title', 'phlox'),
                        'description'   => '',
                        'id'        => 'portfolio_related_items_title',
                        'section'   => 'portfolio-setting-section-single',
                        'dependency'=> array(),
                        'default'   => 'Related Projects',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Related Project Thumb Size', 'phlox'),
                        'description'   => 'If You choose 1/3 , 3 Related items are visible.',
                        'id'        => 'portfolio_related_items_size',
                        'section'   => 'portfolio-setting-section-single',
                        'dependency'=> array(),
                        'choices'   => array(   'three-column'  => '1/3',
                                                'four-column'   => '1/4'
                                            ),
                        'default'   => 'three-column',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Navigation Type', 'phlox'),
                        'description'   => 'What kind of navigation you need to display on portfolio single pages?',
                        'id'        => 'portfolio_single_nav_type',
                        'section'   => 'portfolio-setting-section-single',
                        'dependency'=> array(),
                        'choices'   => array(   'breadcrumb'    => __('Breadcrumb', 'phlox'),
                                                'next_prev'     => __('Next / Previous', 'phlox'),
                                                'none'          => __('None', 'phlox')
                                            ),
                        'default'   => 'breadcrumb',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display Socials', 'phlox'),
                        'description'   => 'Do you want to display share links on portfolio single pages?',
                        'id'        => 'portfolio_show_share_btns',
                        'section'   => 'portfolio-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );


    // Sub section - Portfolio Archive Page -------------------------------

    $sections[] = array(
        'id'      => 'portfolio-setting-section-archive',
        'parent'  => 'portfolio-setting-section', // section parent's id
        'title'   => __( 'Portfolio Index Page', 'phlox'),
        'description' => __( 'Portfolio Index (Archive) Page Setting', 'phlox')
    );


    $options[] = array( 'title'     => __('Column Number', 'phlox'),
                        'description'   => '',
                        'id'        => 'portfolio_index_page_col_num',
                        'section'   => 'portfolio-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   '1/3'   => __('3 Columns', 'phlox'),
                                                '1/4'   => __('4 Columns', 'phlox'),
                                                '1/5'   => __('5 Columns', 'phlox')
                                            ),
                        'default'   => '1/3',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Number Of Items PerPage', 'phlox'),
                        'description'   => __('How many portfolio items do you want to display on each page?', 'phlox'),
                        'id'        => 'portfolio_index_page_items_perpage',
                        'section'   => 'portfolio-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   =>  auxin_get_range(1, 50, array('0'    => __('All', 'phlox')) ),
                        'default'   => '0',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display Mode', 'phlox'),
                        'description'   => __('Do you want to display portfolio info "Over Thumbnail" or "Under Thumbnail"?', 'phlox'),
                        'id'        => 'portfolio_index_page_display_mode',
                        'section'   => 'portfolio-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   'over'      => __('Text Over Thumbnail' , 'phlox'),
                                                'under'     => __('Text Under Thumbnail', 'phlox')
                                            ),
                        'default'   => 'over',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __("Explore Type", 'phlox'),
                        'description'   => "",
                        'id'        => 'portfolio_index_page_explore_type',
                        'section'   => 'portfolio-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   'regular'       => __('Regular'   , 'phlox'),
                                                'filterable'    => __('Filterable', 'phlox')
                                        ),
                        'default'   => 'regular',
                        'type'      => 'select' );

    // -- Sub section - Portfolio Category Page  -----------------------------------------------

    $sections[] = array(
        'id'      => 'portfolio-setting-section-category',
        'parent'  => 'portfolio-setting-section', // section parent's id
        'title'   => __( 'Portfolio Category Page', 'phlox'),
        'description' => __( 'Portfolio Category Page Setting', 'phlox')
    );


    $options[] = array( 'title'     => __('Column Number', 'phlox'),
                        'description'   => '',
                        'id'        => 'portfolio_category_page_col_num',
                        'section'   => 'portfolio-setting-section-category',
                        'dependency'=> array(),
                        'choices'   => array(   '1/3'   => __('3 Columns', 'phlox'),
                                                '1/4'   => __('4 Columns', 'phlox'),
                                                '1/5'   => __('5 Columns', 'phlox')
                                            ),
                        'default'   => '1/3',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Number Of Items PerPage', 'phlox'),
                        'description'   => __('How many portfolio items do you want to display on each page?', 'phlox'),
                        'id'        => 'portfolio_category_page_items_perpage',
                        'section'   => 'portfolio-setting-section-category',
                        'dependency'=> array(),
                        'choices'   =>  auxin_get_range(1, 50, array('0'    => __('All', 'phlox')) ),
                        'default'   => '0',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display Mode', 'phlox'),
                        'description'   => __('Do you want to display portfolio info "Over Thumbnail" or "Under Thumbnail"?', 'phlox'),
                        'id'        => 'portfolio_category_page_display_mode',
                        'section'   => 'portfolio-setting-section-category',
                        'dependency'=> array(),
                        'choices'   => array(   'over'      => __('Text Over Thumbnail' , 'phlox'),
                                                'under'     => __('Text Under Thumbnail', 'phlox')
                                            ),
                        'default'   => 'over',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __("Explore Type", 'phlox'),
                        'description'   => "",
                        'id'        => 'portfolio_category_page_explore_type',
                        'section'   => 'portfolio-setting-section-category',
                        'dependency'=> array(),
                        'choices'   => array(   'regular'       => __('Regular'   , 'phlox'),
                                                'filterable'    => __('Filterable', 'phlox')
                                            ),
                        'default'   => 'regular',
                        'type'      => 'select' );


    // -- Sub section - Portfolio Data Labels -----------------------------------------------

    $sections[] = array(
        'id'      => 'portfolio-setting-section-meta-labels',
        'parent'  => 'portfolio-setting-section', // section parent's id
        'title'   => __( 'Portfolio Metas', 'phlox'),
        'description' => __( 'Portfolio Custom Meta Labels', 'phlox')
    );

    $options[] = array( 'title'     => __('Numberd of Custom Metas', 'phlox'),
                        'description'   => __('Specifies the number of custom meta fields for portfolio single page', 'phlox').'<br />'.
                                       __('Note: After changing this number, you need to refresh this page to let option panel generate new fields.', 'phlox'),
                        'id'        => 'portfolio_custom_meta_num',
                        'section'   => 'portfolio-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '6',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Portfolio Data Labels', 'phlox'),
                    'description'   => __('Here you can change or translate the label of metas in portfolio page.', 'phlox'),
                    'id'        => '',
                    'section'   => 'portfolio-setting-section-meta-labels',
                    'dependency'=> array(),
                    'default'   => '',
                    'type'      => 'sep' );


    $portfolio_meta_nums = auxin_get_option( 'portfolio_custom_meta_num', '4' );
    if( ! is_numeric( $portfolio_meta_nums ) ){ $portfolio_meta_nums = '4'; }

    $portfolio_meta_default_labels = array(
        __('Skills'         , 'phlox'),
        __('Release date'   , 'phlox'),
        __('Copyright'      , 'phlox'),
        __('Client'         , 'phlox')
    );

    for( $i = 1; $i <= $portfolio_meta_nums; $i++ ) {
        $options[] = array( 'title'     => __('Custom Meta Label '.$i, 'phlox'),
                        'description'   => '',
                        'id'        => 'portfolio_custom_meta_label'.$i,
                        'section'   => 'portfolio-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => isset( $portfolio_meta_default_labels[ $i - 1 ] ) ? $portfolio_meta_default_labels[ $i - 1 ] : '',
                        'type'      => 'text' );
    } */


    /* ---------------------------------------------------------------------------------------------------
        Product Section
    --------------------------------------------------------------------------------------------------- */

    // Product section ==================================================================

    $sections[] = array(
        'id'          => 'product-setting-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Woocommerce', 'phlox'),
        'description' => __( 'Woocommerce Setting', 'phlox'),
        'icon'        => 'axicon-basket-alt'
    );

    // Sub section - Single Product Page -------------------------------

    $sections[] = array(
        'id'          => 'product-setting-section-single',
        'parent'      => 'product-setting-section', // section parent's id
        'title'       => __( 'Single Product Page', 'phlox'),
        'description' => __( 'Single Product Page Setting', 'phlox')
    );

    /*
    $options[] = array( 'title'     => __('Display Related Products?', 'phlox'),
                        'description'   => __('Do you want to display related products at bottom of the page? ', 'phlox'),
                        'id'        => 'show_product_related_items',
                        'section'   => 'product-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Related Products Title', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_related_items_title',
                        'section'   => 'product-setting-section-single',
                        'dependency'=> array(),
                        'default'   => 'Related Products',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Related Product Thumb Size', 'phlox'),
                        'description'   => __('If You choose 1/3 , 3 Related items are visible.', 'phlox'),
                        'id'        => 'product_related_items_size',
                        'section'   => 'product-setting-section-single',
                        'dependency'=> array(),
                        'choices'   => array(   'three-column'  => '1/3',
                                                'four-column'   => '1/4' ),
                        'default'   => 'three-column',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Navigation Type', 'phlox'),
                        'description'   => 'What kind of navigation you need to display on product single pages?',
                        'id'        => 'product_single_nav_type',
                        'section'   => 'product-setting-section-single',
                        'dependency'=> array(),
                        'choices'   => array(   'breadcrumb'    => 'Breadcrumb',
                                                'next_prev'     => 'Next / Previous',
                                                'none'          => 'None' ),
                        'default'   => 'breadcrumb',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display "In Stock"', 'phlox'),
                        'description'   => 'Do you want to display "In Stock" status on product single pages?',
                        'id'        => 'product_show_in_stock',
                        'section'   => 'product-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Display Socials', 'phlox'),
                        'description'   => 'Do you want to display share links on product single pages?',
                        'id'        => 'product_show_share_btns',
                        'section'   => 'product-setting-section-single',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' ); */

     $options[] = array(
        'title'       => __('Single Product Sidebar Position', 'phlox'),
        'description' => __('Specifies the position of sidebar on single product page.', 'phlox'),
        'id'          => 'product_single_sidebar_position',
        'section'     => 'product-setting-section-single',
        'dependency'  => array(),
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
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'transport' => 'refresh',
        'default'   => 'right-sidebar',
        'type'      => 'radio-image'
    );

     $options[] = array(
        'title'       => __('Single Product Sidebar Style', 'phlox'),
        'description' => 'Specifies the style of sidebar on single product page.',
        'id'          => 'product_single_sidebar_decoration',
        'section'     => 'product-setting-section-single',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".single-product .aux-single, main.aux-home").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'     => array(
            'simple' => array(
                'label'  => __('Simple' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border' => array(
                'label'  => __('Bordered Sidebar' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap' => array(
                'label'  => __('Overlap Background' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );


    // Sub section - Product Archive Page -------------------------------

    $sections[] = array(
        'id'          => 'product-setting-section-archive',
        'parent'      => 'product-setting-section', // section parent's id
        'title'       => __( 'Shop Page', 'phlox'),
        'description' => __( 'Shop Page Setting', 'phlox')
    );

    /*
    $options[] = array( 'title'     => __('Column Number', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_index_page_col_num',
                        'section'   => 'product-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   '1/1'   => '1 Column',
                                            '1/2'   => '2 Columns',
                                            '1/3'   => '3 Columns',
                                            '1/4'   => '4 Columns',
                                            '1/5'   => '5 Columns'
                                        ),
                        'default'   => '1/3',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Number Of Items PerPage', 'phlox'),
                        'description'   => __('How many product items do you want to display on each page?', 'phlox'),
                        'id'        => 'product_index_page_items_perpage',
                        'section'   => 'product-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   =>  auxin_get_range(1, 50, array('0'    => __('All', 'phlox')) ),
                        'default'   => '0',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display Mode', 'phlox'),
                        'description'   => __('<b>Grid :</b> mode is the most common type for displaying products.', 'phlox').'<br />'.
                                   __('<b>List :</b> type is perfect for displaying products in something like restaurant menu.', 'phlox').'<br />'.
                                   __('<b>ThumbList :</b> type is similar to "List" type + Thumbnail if it is available  ', 'phlox'),
                        'id'        => 'product_index_page_display_mode',
                        'section'   => 'product-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   'grid'          => __('Grid'    , 'phlox'),
                                                'list'          => __('List'    , 'phlox'),
                                                'thumblist'     => __('Thumbnail List', 'phlox')
                                            ),
                        'default'   => 'grid',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __("Explore Type", 'phlox'),
                        'description'   => "",
                        'id'        => 'product_index_page_explore_type',
                        'section'   => 'product-setting-section-archive',
                        'dependency'=> array(),
                        'choices'   => array(   'regular'       => __('Regular'   , 'phlox'),
                                                'filterable'    => __('Filterable', 'phlox')
                                            ),
                        'default'   => 'regular',
                        'type'      => 'select' );
                        */

    $options[] = array(
        'title'       => __('Shop Page Sidebar Position', 'phlox'),
        'description' => __('Specifies the position of sidebar on shop page.', 'phlox'),
        'id'          => 'product_index_sidebar_position',
        'section'     => 'product-setting-section-archive',
        'dependency'  => array(),
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
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'transport' => 'refresh',
        'default'   => 'right-sidebar',
        'type'      => 'radio-image'
    );

    $options[] = array(
        'title'       => __('Shop Sidebar Style', 'phlox'),
        'description' => __('Specifies the style of sidebar on shop page.', 'phlox'),
        'id'          => 'product_index_sidebar_decoration',
        'section'     => 'product-setting-section-archive',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".woocommerce .aux-archive, main.aux-home").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'     => array(
            'simple' => array(
                'label'  => __('Simple' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border' => array(
                'label'  => __('Bordered Sidebar' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap' => array(
                'label'  => __('Overlap Background' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );

    // -- Sub section - Product Category Page  -----------------------------------------------

    $sections[] = array(
        'id'          => 'product-setting-section-category',
        'parent'      => 'product-setting-section', // section parent's id
        'title'       => __( 'Product Category & Tag', 'phlox'),
        'description' => __( 'Product Category & Tag Page Setting', 'phlox')
    );

    /*
    $options[] = array( 'title'     => __('Column Number', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_category_page_col_num',
                        'section'   => 'product-setting-section-category',
                        'dependency'=> array(),
                        'choices'   => array(   '1/1'   => '1 Column',
                                            '1/2'   => '2 Columns',
                                            '1/3'   => '3 Columns',
                                            '1/4'   => '4 Columns',
                                            '1/5'   => '5 Columns' ),
                        'default'   => '1/3',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Number Of Items PerPage', 'phlox'),
                        'description'   => __('How many product items do you want to display on each page?', 'phlox'),
                        'id'        => 'product_category_page_items_perpage',
                        'section'   => 'product-setting-section-category',
                        'dependency'=> array(),
                        'choices'   =>  auxin_get_range(1, 50, array('0'    => __('All', 'phlox')) ),
                        'default'   => '0',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __('Display Mode', 'phlox'),
                        'description'   => __('<b>Grid :</b> mode is the most common type for displaying products.', 'phlox').'<br />'.
                                   __('<b>List :</b> type is perfect for displaying products in something like restaurant menu.', 'phlox').'<br />'.
                                   __('<b>ThumbList :</b> type is similar to "List" type + Thumbnail if it is available  ', 'phlox'),
                        'id'        => 'product_category_page_display_mode',
                        'section'   => 'product-setting-section-category',
                        'dependency'=> array(),
                        'choices'   => array(   'grid'          => __('Grid'    , 'phlox'),
                                            'list'          => __('List'    , 'phlox'),
                                            'thumblist'     => __('Thumbnail List', 'phlox') ),
                        'default'   => 'grid',
                        'type'      => 'select' );

    $options[] = array( 'title'     => __("Explore Type", 'phlox'),
                        'description'   => "",
                        'id'        => 'product_category_page_explore_type',
                        'section'   => 'product-setting-section-category',
                        'dependency'=> array(),
                        'choices'   => array(   'regular'       => __('Regular'   , 'phlox'),
                                            'filterable'    => __('Filterable', 'phlox') ),
                        'default'   => 'regular',
                        'type'      => 'select' );
                        */

     $options[] = array(
        'title'       => __('Product Taxonomy Sidebar Position', 'phlox'),
        'description' => __('Specifies the position of sidebar on product category & tag page.', 'phlox'),
        'id'          => 'product_category_sidebar_position',
        'section'     => 'product-setting-section-category',
        'dependency'  => array(),
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
                'css_class' => 'axiAdminIcon-sidebar-left-right'
            )
        ),
        'transport' => 'refresh',
        'default'   => 'right-sidebar',
        'type'      => 'radio-image'
    );

    $options[] = array(
        'title'       => __('Product Taxonomy Sidebar Style', 'phlox'),
        'description' => __('Specifies the style of sidebar on product category & tag page.', 'phlox'),
        'id'          => 'product_category_sidebar_decoration',
        'section'     => 'product-setting-section-category',
        'dependency'  => array(
            array(
                 'id'      => 'post_index_sidebar_position',
                 'value'   => 'no-sidebar',
                 'operator'=> '!='
            )
        ),
        'transport'   => 'postMessage',
        'post_js'     => '$(".woocommerce .aux-archive, main.aux-home").alterClass( "aux-sidebar-style-*", "aux-sidebar-style-" + to );',
        'choices'     => array(
            'simple' => array(
                'label'  => __('Simple' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-1.svg'
            ),
            'border' => array(
                'label'  => __('Bordered Sidebar' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-2.svg'
            ),
            'overlap' => array(
                'label'  => __('Overlap Background' , 'phlox'),
                'image' => AUX_URL . 'images/visual-select/sidebar-style-3.svg'
            )
        ),
        'type'          => 'radio-image',
        'default'       => 'border'
    );

    // -- Sub section - Product Category Page  -----------------------------------------------

    $sections[] = array(
        'id'          => 'product-setting-section-category',
        'parent'      => 'product-setting-section', // section parent's id
        'title'       => __( 'Product Category & Tag', 'phlox'),
        'description' => __( 'Product Category & Tag Page Setting', 'phlox')
    );

    // -- Sub section - Product Data Labels -----------------------------------------------

    /*
    $sections[] = array(
        'id'      => 'product-setting-section-meta-labels',
        'parent'  => 'product-setting-section', // section parent's id
        'title'   => __( 'Product Metas', 'phlox'),
        'description' => __( 'Product Custom Meta Labels', 'phlox')
    );

    $options[] = array( 'title'     => __('Product Data Labels', 'phlox'),
                        'description'   => __('Here you can change or translate the label of metas in product page.', 'phlox'),
                        'id'        => '',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'sep' );

    $options[] = array( 'title'     => __('Regular Price Label', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_regular_price_label',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => 'Regular Price',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('"Buy" button Label', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_buy_btn_label',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => 'Buy Now',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 1', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_1',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => 'Manufacturer',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 2', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_2',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => 'Release Date',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 3', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_3',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => 'Part Number',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 4', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_4',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => 'Dimensions',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 5', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_5',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 6', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_6',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 7', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_7',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 8', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_8',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 9', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_9',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'text' );

    $options[] = array( 'title'     => __('Custom Meta Label 10', 'phlox'),
                        'description'   => '',
                        'id'        => 'product_custom_meta_label_0_10',
                        'section'   => 'product-setting-section-meta-labels',
                        'dependency'=> array(),
                        'default'   => '',
                        'type'      => 'text' );

                        */

    /* ---------------------------------------------------------------------------------------------------
        Elements Section
    --------------------------------------------------------------------------------------------------- */
/*
    $options[] = array( 'title' => __('Element 1', 'phlox'),
                        'description'  '',
                        'id'    => '',
                        'default'   => '',
                        'type'  => 'sep' );

    $options[] = array( 'title' => 'Range Field',
                        'description'  'Here goes the descriptionr on for this field.',
                        'id'    => 'range_element1',
                        'choices'> array(   'min' => '20'  ,
                                            'max' => '70',
                                            'step'=> '1' ),
                        'default'   => '30',
                        'type'  => 'range' );

    $options[] = array( 'title' => 'Radio Field',
                        'description'  'Here goes the descriptionr on for this field.',
                        'id'    => 'radio_field2',
                        'choices'> array(   'Option Title 5' => 'option_value_5' ,
                                            'Option Title 6' => 'option_value_6' ),
                        'default'   => 'option_value_5',
                        'type'  => 'radio' );

    $options[] = array( 'title' => 'Sortable List',
                        'description'  'Here goes the descriptionr on for this field.',
                        'id'    => 'draggable_area1',
                        'choices'> array(   'Active'   => array(
                                                                'item_1' => 'section 1',
                                                                'item_2' => 'section 2',
                                                                'item_3' => 'section_3'
                                                             ) ,
                                            'Deactive' => array(
                                                                'item_4' => 'section 4',
                                                                'item_5' => 'section 5',
                                                                'item_6' => 'section_6'
                                                             )
                                        ),
                        'type'  => 'sortable' );

    $options[] = array( 'title' => 'Sortable List',
                        'description'  'Here goes the descriptionr on for this field.',
                        'id'    => 'draggable_area2',
                        'choices'> array(   'Active'   => array(
                                                                'item_1' => 'Block 1',
                                                                'item_2' => 'Block 2',
                                                                'item_3' => 'Block_3'
                                                             ) ,
                                            'Deactive' => array(
                                                                'item_4' => 'Block 4',
                                                                'item_5' => 'Block 5',
                                                                'item_6' => 'Block_6'
                                                             )
                                        ),
                        'type'  => 'sortable' );

    */

    /* ---------------------------------------------------------------------------------------------------
        Footer Section
    --------------------------------------------------------------------------------------------------- */

    // Footer section ==================================================================

    $sections[] = array(
        'id'          => 'footer-setting-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Footer', 'phlox'),
        'description' => __( 'Footer Setting', 'phlox'),
        'icon'        => 'axicon-down-circled'
    );

    // Sub section - Sub Footer -------------------------------

    $sections[] = array(
        'id'          => 'footer-setting-section-subfooter-bar',
        'parent'      => 'footer-setting-section', // section parent's id
        'title'       => __( 'Subfooter Bar', 'phlox'),
        'description' => __( 'Subfooter Bar Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __('Display Subfooter Bar', 'phlox'),
        'description' => __('Enable it to display subfooter bar above of subfooter.', 'phlox'),
        'id'          => 'show_subfooter_bar',
        'section'     => 'footer-setting-section-subfooter-bar',
        'dependency'  => array(),
        'post_js'     => '$(".aux-subfooter-bar").toggle( to );',
        'transport'   => 'postMessage',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'      => __('Subfooter Bar Layout', 'phlox'),
        'description' => __('Specifies layout of subtoofer bar.', 'phlox'),
        'id'         => 'subfooter_bar_layout',
        'section'    => 'footer-setting-section-subfooter-bar',
        'dependency' => array(
            array(
                 'id'      => 'show_subfooter_bar',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'post_js' => 'var bar = $(".aux-subfooter-bar"); bar.find(".container").toggleClass( "aux-fold", "vertical-none-boxed" == to || "vertical-small-boxed" == to ); bar.alterClass("vertical-*", to );',
        'transport' => 'postMessage',
        'choices'   => array(
            'vertical-none-full'   =>  array(
                'label' => __('Full', 'phlox'),
                'css_class' => 'axiAdminIcon-padding-none'
            ),
            'vertical-none-boxed'   =>  array(
                'label' => __('Boxed', 'phlox'),
                'css_class' => 'axiAdminIcon-padding-x'
            ),
            'vertical-small-full'   =>  array(
                'label' => __('Full with small vertical padding', 'phlox'),
                'css_class' => 'axiAdminIcon-padding-y'
            ),
            'vertical-small-boxed'  =>  array(
                'label' => __('Boxed with small vertical padding', 'phlox'),
                'css_class' => 'axiAdminIcon-padding-x-y'
            )
        ),
        'default'   => 'vertical-small-boxed',
        'type'      => 'radio-image'
    );

    $options[] = array(
        'title'       => __( 'Background Color', 'phlox'),
        'id'          => 'subfooter_bar_layout_bg_color',
        'description' => __( 'Specifies background color of subfooter bar.', 'phlox'),
        'section'     => 'footer-setting-section-subfooter-bar',
        'type'        => 'color',
        'dependency'  => array(
            array(
                 'id'      => 'show_subfooter_bar',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'subfooter_bar_layout_bg_color' );
            }
            return $value ? ".aux-subfooter-bar { background-color:$value; }" : '';
        },
        'transport' => 'postMessage',
        'default'   => ''
    );


    // Sub section - Sub Footer -------------------------------

    $sections[] = array(
        'id'          => 'footer-setting-section-subfooter',
        'parent'      => 'footer-setting-section', // section parent's id
        'title'       => __( 'Sub Footer', 'phlox'),
        'description' => __( 'Sub Footer Setting', 'phlox')
    );


    $options[] = array(
        'title'       => __('Display Subfooter', 'phlox'),
        'description' => __('Enable it to display subfooter on all pages.', 'phlox'),
        'id'          => 'show_subfooter',
        'section'     => 'footer-setting-section-subfooter',
        'dependency'  => array(),
        'post_js'     => '$(".subfooter").toggle( to );',
        'transport'   => 'postMessage',
        'default'     => '',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Subfooter Layout', 'phlox'),
        'description' => __('Select layout for subfooter widget columns.', 'phlox'). '<br>' .
                       sprintf( __('It generates some widgetareas for subfooter based on the layout, which you need to %s fill them.%s', 'phlox'),
                               '<a href="'.admin_url('widgets.php').'" target="_blank">', '</a>'  ),
        'id'         => 'subfooter_layout',
        'section'    => 'footer-setting-section-subfooter',
        'dependency' => array(
            array(
                 'id'      => 'show_subfooter',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'transport' => 'refresh',
        'choices'   => array(
            '1-1'   =>  array(
                'label' => __('1 Column', 'phlox'),
                'css_class' => 'axiAdminIcon-grid1'
            ),
            '1-2_1-2'   =>  array(
                'label' => __('2 Columns- 1/2  1/2' , 'phlox'),
                'css_class' => 'axiAdminIcon-grid11'
            ),
            '2-3_1-3'   => array(
                'label' => __('2 Columns- 2/3  1/3', 'phlox'),
                'css_class' => 'axiAdminIcon-grid21'
            ),
            '1-3_2-3'   => array(
                'label' => __('2 Columns- 1/3  2/3', 'phlox'),
                'css_class' => 'axiAdminIcon-grid12'
            ),
            '3-4_1-4'   => array(
                'label' => __('2 Columns- 3/4  1/4', 'phlox'),
                'css_class' => 'axiAdminIcon-grid31'
            ),
            '1-4_3-4'   => array(
                'label' => __('2 Columns- 1/4  3/4', 'phlox'),
                'css_class' => 'axiAdminIcon-grid13'
            ),
            '1-3_1-3_1-3'  => array(
                'label' => __('3 Columns- 1/3  1/3  1/3', 'phlox'),
                'css_class' => 'axiAdminIcon-grid111'
            ) ,
            '1-2_1-4_1-4'  => array(
                'label' => __('3 Columns- 1/2  1/4  1/4', 'phlox'),
                'css_class' => 'axiAdminIcon-grid211'
            ) ,
            '1-4_1-4_1-2'  => array(
                'label' => __('3 Columns- 1/4  1/4  1/2', 'phlox'),
                'css_class' => 'axiAdminIcon-grid112'
            ),
            '1-4_1-4_1-4_1-4' => array(
                'label' => __('4 Columns- 1/4  1/4  1/4  1/4', 'phlox'),
                'css_class' => 'axiAdminIcon-grid1111'
            ),
            '1-5_1-5_1-5_1-5_1-5' => array(
                'label' => __('5 Columns- 1/5  1/5  1/5  1/5  1/5', 'phlox'),
                'css_class' => 'axiAdminIcon-grid11111'
            )
        ),
        'default'   => '1-3_1-3_1-3',
        'type'      => 'radio-image'
    );


    $options[] = array(
        'title'       => __( 'Background Color', 'phlox'),
        'id'          => 'subfooter_layout_bg_color',
        'description' => __( 'Specifies background color of subfooter.', 'phlox'),
        'section'     => 'footer-setting-section-subfooter',
        'type'        => 'color',
        'dependency' => array(
            array(
                 'id'      => 'show_subfooter',
                 'value'   => array('1'),
                 'operator'=> ''
            )
        ),
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'subfooter_layout_bg_color' );
            }
            return $value ? ".aux-subfooter { background-color:$value; }" : '';
        },
        'transport' => 'postMessage',
        'default'   => ''
    );


    // Sub section - Footer -------------------------------

    $sections[] = array(
        'id'          => 'footer-setting-section-footer',
        'parent'      => 'footer-setting-section', // section parent's id
        'title'       => __( 'Footer', 'phlox'),
        'description' => __( 'Footer Setting', 'phlox')
    );

    $options[] = array(
        'title'       => __('Display Footer', 'phlox'),
        'description' => __('Enable it to display footer on all pages.', 'phlox'),
        'id'          => 'show_site_footer',
        'section'     => 'footer-setting-section-footer',
        'dependency'  => array(),
        'post_js'     => '$(".aux-site-footer").toggle( to );',
        'transport'   => 'postMessage',
        'default'     => '1',
        'type'        => 'switch'
    );

    $options[] = array(
        'title'       => __('Footer Logo', 'phlox'),
        'description' => __('This image appears as site logo on footer section.', 'phlox'),
        'id'          => 'site_secondary_logo_image',
        'section'     => 'footer-setting-section-footer',
        'dependency'  => array(
            array(
                 'id'      => 'show_site_footer',
                 'value'   => array('1'),
                 'operator'=> '=='
            )
        ),
        'default'     => '',
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-logo-footer .aux-logo-anchor',
            'container_inclusive'   => false,
            'render_callback'       => function(){ echo _auxin_get_logo_image('', 'secondary'); }
        ),
        'type'        => 'image'
    );

    $options[] = array(
        'title'       => __('Footer Logo Height', 'phlox'),
        'description' => __('Specifies maximum height of logo in footer.', 'phlox'),
        'id'          => 'site_secondary_logo_max_height',
        'section'     => 'footer-setting-section-footer',
        'dependency'  => array(
            array(
                 'id'      => 'show_site_footer',
                 'value'   => array('1'),
                 'operator'=> '=='
            )
        ),
        'default'        => '40',
        'transport'      => 'postMessage',
        'style_callback' => function( $value = null ){
            if( ! $value ){
                $value = auxin_get_option( 'site_secondary_logo_max_height' );
            }
            $value = trim( $value, 'px');
            return $value ? ".aux-logo-footer .aux-logo-anchor, .aux-logo-footer .aux-logo-anchor img { max-height:{$value}px; }" : '';
        },
        'type'        => 'text'
    );

    $options[] = array(
        'title'       => __('Footer Layout', 'phlox'),
        'description' => __('Specifies the footer layout.', 'phlox'),
        'id'          => 'site_footer_components_layout',
        'section'     => 'footer-setting-section-footer',
        'type'        => 'radio-image',
        'dependency' => array(
            array(
                 'id'      => 'show_site_footer',
                 'value'   => array('1'),
                 'operator'=> '=='
            )
        ),
        'transport'   => 'postMessage',
        'partial'     => array(
            'selector'              => '.aux-site-footer .aux-wrapper',
            'container_inclusive'   => false,
            'render_callback'       => function(){ echo auxin_get_footer_components_markup(); }
        ),
        'choices'     => array(
            'footer_preset1' => array(
                'label' => __('Footer Preset 1', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/footer-layout-1.svg'
            ),
            'footer_preset2' => array(
                'label' => __('Footer Preset 2', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/footer-layout-2.svg'
            ),
            'footer_preset3' => array(
                'label' => __('Footer Preset 3', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/footer-layout-3.svg'
            ),
            'footer_preset4' => array(
                'label' => __('Footer Preset 4', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/footer-layout-4.svg'
            )
        ),
        'default'   => 'footer_preset1'
    );

    /* @TODO
    $options[] = array( 'title'     => __('Display Socials in Footer?', 'phlox'),
                        'description'   => __('If you check this option socials appear in footer (you can edit socials via social setting section)', 'phlox'),
                        'id'        => 'show_socials_in_footer',
                        'section'   => 'footer-setting-section-footer',
                        'dependency'=> array(),
                        'default'   => '1',
                        'type'      => 'switch' );

    $options[] = array( 'title'     => __('Footer Background Color', 'phlox'),
                        'description'   => __('Specifies the background color for footer', 'phlox'),
                        'id'        => 'site_footer_bg_color',
                        'section'   => 'footer-setting-section-footer',
                        'dependency'=> array(),
                        'default'   => '#1A1A1A',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Footer Text Color', 'phlox'),
                        'description'   => __('Specifies the text and link colors in footer', 'phlox'),
                        'id'        => 'site_footer_text_color',
                        'section'   => 'footer-setting-section-footer',
                        'dependency'=> array(),
                        'default'   => '#6D6D6D',
                        'type'      => 'color' );

    $options[] = array( 'title'     => __('Footer Separator Color', 'phlox'),
                        'description'   => __('Specifies the color of separator line between links', 'phlox'),
                        'id'        => 'site_footer_sep_color',
                        'section'   => 'footer-setting-section-footer',
                        'dependency'=> array(),
                        'default'   => '#292929',
                        'type'      => 'color' );
    */

    $options[] = array(
        'title'       => __('Footer Copyright Text', 'phlox'),
        'description' => 'Enter your copyright text to display on footer.',
        'id'          => 'copyright',
        'section'     => 'footer-setting-section-footer',
        'dependency' => array(
            array(
                 'id'      => 'show_site_footer',
                 'value'   => array('1'),
                 'operator'=> '=='
            )
        ),
        'post_js'     => '$(".aux-copyright small").html( to );',
        'default'     => __( "&copy; 2016 Company. All rights reserved", 'phlox' ),
        'type'        => 'textarea'
    );


    /* ---------------------------------------------------------------------------------------------------
        Tools Section
    --------------------------------------------------------------------------------------------------- */

    $auxin_active_post_types = auxin_get_possible_post_types( true );

    // Tools parent section ==============================================================

    $sections[] = array(
        'id'          => 'tools-setting-section',
        'parent'      => '', // section parent's id
        'title'       => __( 'Extras', 'phlox'),
        'description' => __( 'Extras', 'phlox'),
        'icon'        => 'axicon-tools'
    );

    // Sub section - Sidebar Generator in Tools -------------------------------
    /*
    $sections[] = array(
        'id'      => 'tools-setting-section-sidebargenerator',
        'parent'  => 'tools-setting-section', // section parent's id
        'title'   => __( 'Sidebar Generator', 'phlox'),
        'description' => __( 'Sidebar Generator Options', 'phlox')
    );

    $options[] = array( 'title'     => __('Enter New Sidebar Name', 'phlox'),
                        'description'   => __('Here goes the descriptionr on for this field.', 'phlox'),
                        'id'        => 'sidebar_add_field',
                        'section'   => 'tools-setting-section-sidebargenerator',
                        'dependency'=> array(),
                        'type'      => 'sidebar' );

                        */


    // Sub section - Import in Tools -------------------------------

    $sections[] = array(
        'id'          => 'tools-setting-section-import',
        'parent'      => 'tools-setting-section', // section parent's id
        'title'       => __( 'Import Theme Options', 'phlox'),
        'description' => __( 'Import Theme Options', 'phlox'),
        'add_to'      => 'option_panel'
    );

    $options[] = array(
        'title'       => __('Theme options code', 'phlox'),
        'description' => 'Paste the exported theme options code to import into theme.',
        'id'          => 'auxin_import_options',
        'section'     => 'tools-setting-section-import',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'import',
        'add_to'      => 'option_panel'
    );






    // Sub section - Export in Tools -------------------------------

    $sections[] = array(
        'id'          => 'tools-setting-section-export',
        'parent'      => 'tools-setting-section', // section parent's id
        'title'       => __( 'Export Theme Options', 'phlox'),
        'description' => __( 'Export Theme Options', 'phlox'),
        'add_to'      => 'option_panel'
    );

    $options[] = array(
        'title'       => __('Export Theme Options', 'phlox'),
        'description' => __('Your theme options code which you can import later.', 'phlox'),
        'id'          => 'auxin_export_options',
        'section'     => 'tools-setting-section-export',
        'dependency'  => array(),
        'default'     => '',
        'type'        => 'export',
        'add_to'      => 'option_panel'
    );

    // Sub section - Go to top options -------------------------------

    $sections[] = array(
        'id'          => 'tools-setting-section-goto-top',
        'parent'      => 'tools-setting-section', // section parent's id
        'title'       => __( 'Go to Top Button ', 'phlox'),
        'description' => __( 'Go to Top Button Options', 'phlox')
    );

    $options[] = array(
        'title'     => __('Display Go to top button', 'phlox'),
        'description' => __('Enable it to display Go to Top button.', 'phlox'),
        'id'        => 'show_goto_top_btn',
        'section'   => 'tools-setting-section-goto-top',
        'dependency'=> array(),
        'transport' => 'postMessage',
        'post_js'   => '$(".aux-goto-top-btn").toggle( to );',
        'default'   => '1',
        'type'      => 'switch'
    );

    $options[] = array(
        'title'       => __('Animate scroll', 'phlox'),
        'description' => __('Specifies whether animate or instantly go to top of page, when goto top button clicked.', 'phlox'),
        'id'          => 'goto_top_animate',
        'section'     => 'tools-setting-section-goto-top',
        'dependency'  => array(
            array(
                'id' => 'show_goto_top_btn',
                'value' => array( '1' )
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$(".aux-goto-top-btn").data( "animate-scroll", to );',
        'default'   => '1',
        'type'      => 'switch'
    );


    $options[] = array(
        'title'       => __('Go to top button position', 'phlox'),
        'description' => __('Specifies the position of Go to Top button.', 'phlox'),
        'id'          => 'goto_top_alignment',
        'section'     => 'tools-setting-section-goto-top',
        'dependency'  => array(
            array(
                'id' => 'show_goto_top_btn',
                'value' => array( '1' )
            )
        ),
        'choices'   => array(
            'left'   =>  array(
                'label'     => __('Left', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/goto-top-left.svg'
            ),
            'center'   =>  array(
                'label'     => __('Center', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/goto-top-center.svg'
            ),
            'right'   =>  array(
                'label'     => __('Right', 'phlox'),
                'image' => AUX_URL . 'images/visual-select/goto-top-right.svg'
            )
        ),
        'transport' => 'postMessage',
        'post_js'   => '$(".aux-goto-top-btn").alterClass( "aux-align-btn-*", "aux-align-btn-" + to );',
        'default'   => 'right',
        'type'      => 'radio-image'
    );


    return array( 'fields' => $options, 'sections' => $sections );
}

add_filter( 'auxin_defined_option_fields_sections', 'auxin_define_options_info' );
