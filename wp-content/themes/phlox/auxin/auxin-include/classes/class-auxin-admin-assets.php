<?php
/**
 * Admin Asset Manager
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



class Auxin_Admin_Assets {

    function __construct(){
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue'  ) );

        add_action( 'admin_init', array( $this, 'add_editor_styles' ) );
    }


    public function enqueue( $hook_suffix ){
        // Enqueue styles and scripts
        $this->enqueue_admin_styles(  $hook_suffix );
        $this->enqueue_admin_scripts( $hook_suffix );

        // Print auxin javascript object
        $this->print_auxin_js_object( $hook_suffix );
    }


    /**
     * Add Admin styles
     *
     * @return void
     */
    function enqueue_admin_styles( $hook_suffix ) {
        global $pagenow;

        // register admin custom styles /////////////////////////////////////////////////

        wp_register_style('auxin-jquery-ui'        , ADMIN_CSS_URL. 'other/auxin-jquery-ui.css');

        // Enqueue admin custom styles /////////////////////////////////////////////////

        wp_enqueue_style('auxin_admin_style'        , ADMIN_CSS_URL. 'admin.css');
        wp_enqueue_style('auxin_elements_icon'      , ADMIN_CSS_URL. 'auxin-elements-icon/auxin-elements-icon.css');
        wp_enqueue_style('noty', ADMIN_CSS_URL. 'other/noty.css');

        // It will be called only on option panel page
        if( isset( $_GET['page'] ) && 'admin.php' == $pagenow && 'auxin-options' == $_GET['page'] ){
            wp_enqueue_style( 'auxin_option_panel', AUX_INC_URL. 'options/assets/css/option-panel.css' );
            wp_enqueue_style( 'auxin-jquery-ui' );
        }

        if( is_rtl() ){
            wp_enqueue_style( 'auxin_rtl_style', ADMIN_CSS_URL. 'rtl.css' );
        }

    }

    /**
     * Change the default font of TinyMCE to Open Sans
     */
    public function add_editor_styles(){
        $font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Open+Sans:100,300,400,600,700' );
        add_editor_style( array( $font_url, ADMIN_CSS_URL. 'other/editor-style.css' ) );
    }


    /**
     * Add Admin Scripts
     *
     * @return void
     */
    function enqueue_admin_scripts( $hook_suffix ) {
        global $pagenow;

        $screen = get_current_screen();

        // register admin custom scripts /////////////////////////////////////////////////

        // Base64 1.0
        wp_register_script('base64'                 , ADMIN_JS_URL . 'libs/base64.js', null, "1.0" );

        // Ace editor 1.1.7
        wp_register_script('ace-editor'             , ADMIN_JS_URL . 'libs/ace/ace.js', null, "1.1.7");

        // Contains all essential plugins
        wp_register_script('auxin_plugins'          , ADMIN_JS_URL . 'plugins.js',
            array( 'jquery', 'jquery-ui-slider', 'jquery-ui-sortable', 'base64', 'ace-editor')
        );

        // Contains all general scripts
        wp_register_script('auxin_script'           , ADMIN_JS_URL . 'scripts.js'  , array('jquery', 'auxin_plugins', 'media-upload') );

        // Contains jquery easing functions
        wp_register_script('jquery_easing'          , ADMIN_JS_URL . 'libs/jquery.easing.min.js');


        // Enqueue admin custom scripts /////////////////////////////////////////////////

        wp_enqueue_script('json2' );


        if( auxin_is_theme_admin_page() ){
            // load media uploader
            wp_enqueue_media();

            wp_enqueue_script('auxin_plugins');
            wp_enqueue_script('auxin_script');
        }

        // Only load on option panel screen
        if( isset( $_GET['page'] ) && 'admin.php' == $pagenow && 'auxin-options' == $_GET['page'] ){
            // Contains scripts for saving option panel data to database
            wp_enqueue_media();
            wp_enqueue_script('auxin_options', AUX_INC_URL. 'options/assets/js/option-panel.js', array('json2', 'auxin_plugins', 'jquery-ui-spinner'), "2.2" );
        }

        // on widgets page
        if( is_currentpage_id('phlox_page_auxin-system') ){
            wp_enqueue_script('auxin_plugins');
        }
    }


    /**
     * Create essential js global vars
     *
     * @return void
     */
    function print_auxin_js_object( $hook_suffix ){
        global $post;

        $upload_dir = wp_get_upload_dir();

        wp_localize_script( 'jquery', 'auxin', apply_filters( 'auxin_admin_js_object', array(

            'themeurl'      => THEME_URL ,
            'adminurl'      => admin_url(),
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'uploadbaseurl' => $upload_dir['baseurl'],
            'earlyms'       => false,
            'admin'         => array(
                'ace' => array(
                    'showGutter'                => true,
                    'theme'                     => 'tomorrow',
                    'tabSize'                   => 4,
                    'useSoftTabs'               => true,
                    'maxLines'                  => 55,
                    'minLines'                  => 25,
                    'enableBasicAutocompletion' => true,
                    'execute'                   => __('Execute', 'phlox' )
                ),
                'visualIconSelector' => array(
                    'toggleBtnLabel' => __('Visual Icon Selector', 'phlox' )
                ),
                'fontSelector'  => array(
                    'previewTextLabel' => __('Preview text:', 'phlox' ),
                    'fontLabel'        => __('Font:'        , 'phlox' ),
                    'fontSizeLabel'    => __('Size:'        , 'phlox' ),
                    'fontStyleLabel'   => __('Style:'       , 'phlox' ),
                    'googleFonts'      => __('Google Fonts' , 'phlox' ),
                    'systemFonts'      => __('System Fonts' , 'phlox' ),
                    'geaFonts'         => __('Google Early Access', 'phlox' ),
                    'customFonts'      => __('Custom Fonts' , 'phlox' )
                ),
                'fonts'         => AXI_FONT()->get_fonts_list(),
                'colorpicker'   => array(
                    'cancelText'    => __('Cancel', 'phlox' ),
                    'chooseText'    => __('Apply' , 'phlox' )
                )
            ),
            'post' => array(
                'id'    => ( isset( $post->ID ) ? $post->ID : '' )
            )

        )));
    }

}

