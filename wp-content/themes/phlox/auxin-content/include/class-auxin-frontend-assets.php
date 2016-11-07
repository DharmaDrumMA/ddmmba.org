<?php
/**
 * Style and script manager for front end
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

class Auxin_Frontend_Assets {

    // Name prefixe for assets
    public $prefix = 'axi-';

    // default assets version
    public $version = '2.0.0';


    /**
     * Construct
     */
    public function __construct() {

        add_filter( 'auxin_header_inline_styles', array( $this, 'inline_header_styles'  ), 11, 2 );
        add_filter( 'auxin_footer_inline_script', array( $this, 'inline_footer_scripts' ), 11, 2 );


        add_action( 'wp_enqueue_scripts'   , array( $this, 'load_assets'  ), 15 );
        add_action( 'login_enqueue_scripts', array( $this, 'login_assets' ), 11 );
    }

    /**
     * Register and load frontend scripts
     *
     * @return void
     */
    public function load_assets(){
        global $wp_scripts, $post;

        $theme_data    = wp_get_theme();
        $this->version = $theme_data->Version;

        /*-----------------------------------------------------------------------------------*/
        /*  JS
        /*-----------------------------------------------------------------------------------*/

        // backward compatibility for WordPress installations prior to 4.6
        if( ! wp_script_is('imagesloaded') )
            wp_register_script( 'imagesloaded' , THEME_URL . 'js/solo/jquery.imagesloaded.min.js' , array('jquery'), null, TRUE );

        if( ! wp_script_is('masonry') )
            wp_register_script( 'masonry'      , THEME_URL . 'js/libs/plugins/masonry.js' , null, null, TRUE );

        wp_register_script('mapapi'        , 'http://maps.google.com/maps/api/js', null, null, TRUE );

        wp_enqueue_script( 'auxin-js' , THEME_URL . 'js/all.min.js' , array('jquery', 'masonry', 'imagesloaded', 'mapapi'), $this->version, TRUE );
        wp_enqueue_style('auxin-base', THEME_URL . 'css/theme-styles.css' , NULL, $theme_data->Version );
        //wp_enqueue_style('auxin-overwrite' , THEME_URL . 'css/other/overwrite.css'        , array('auxin-main', 'auxin-custom'), $theme_data->Version );

        /*
        if((defined('ICL_LANGUAGE_CODE') && ICL_LANGUAGE_CODE == "fa") || is_rtl()) {
            wp_enqueue_style('auxin-rtl2'     , $tmp_dir . 'css/rtl.css'                 , array("auxin-base2", "auxin-main2", "superfish2"), $theme_data->Version);
        }
        */

        //  Prints the custom inline styles of the page in header //////////////
        if( $css = apply_filters( 'auxin_header_inline_styles', '', $post ) ){
            wp_add_inline_style( 'auxin-main', stripslashes( $css ) );
        }

        // Enqueue front-end custom styles /////////////////////////////////////

        $this->load_fonts();
        $this->load_custom_css_file();
        $this->load_custom_js_file();

        do_action( 'auxin_enqueue_script', $post );
    }


    /**
     * Load selected fonts in front-end
     *
     * @return void
     */
    function load_fonts(){

        $font_urls = get_option( THEME_ID. '_font_urls' );

        if( !is_array( $font_urls ) ){
            return false;
        }

        foreach ( $font_urls as $style_id => $font_enqueue_url ) {
            wp_enqueue_style ( $style_id, $font_enqueue_url, NULL, $GLOBALS[THEME_ID."_custom_css_ver"] );
        }
    }


    /**
     * Load custom css code in front-end
     *
     * @return void
     */
    function load_custom_css_file(){

        // load custom.css if the directory is writable. else use inline css fallback
        $inline_css = get_option( THEME_ID.'_inline_custom_css', '' );

        if( empty( $inline_css ) ){

            $uploads   = wp_get_upload_dir();
            $css_file  = $uploads['baseurl'] . '/' . THEME_ID . '/custom.css';

            wp_enqueue_style( 'auxin-custom', set_url_scheme( $css_file ), array('auxin-base'), $GLOBALS[THEME_ID."_custom_css_ver"] );
        }
    }


    /**
     * Load custom js code in front-end
     *
     * @return void
     */
    function load_custom_js_file(){

        // Dont enqueue custom js file if it is cutomizer preview (use inline instead to try/catch)
        if( is_customize_preview() ){
            return;
        }

        // load custom.js if the directory is writable. else use inline js fallback
        $inline_js = get_option( THEME_ID.'_inline_custom_js', '' );

        if( empty( $inline_js ) ){

            $uploads   = wp_get_upload_dir();
            $js_file  = $uploads['baseurl'] . '/' . THEME_ID . '/custom.js';

            wp_enqueue_script( 'auxin-custom-js', set_url_scheme( $js_file ), array('auxin-plugins'), get_option( THEME_ID.'_custom_js_ver' , 1.0 ), true );
        }
    }


    // -------------------------------------------------------------------------


    /**
     * Adds custom styles in page header if inline custom css is set.
     *
     * @return void
     */
    function inline_header_styles( $css, $post ){

        // dont add the inline custom css when customizer is active.
        if( ! is_customize_preview() ){

            // This var represents the styles in option panel
            $inline_css = get_option( THEME_ID.'_inline_custom_css', '' );

            // if custom.css is not writable, print css styles in page header
            if( ! empty( $inline_css ) ) {
                if( current_user_can( 'manage_options' ) ){
                    $css .= "\n". sprintf( "<!-- Note for admin: The custom.css file in [%s] is not writeable, so the theme uses inline css callback instead. -->\n",
                                           "wp-content/uploads/". THEME_ID. "/custom.css" ) . "\n";
                }
                $css .= $inline_css . "\n";
            }

        // add a customize version on custom css when customizer is active ( the user-defined css code is ignored )
        } else {
            $css .= "\n". "<!-- Note for admin: The custom styles for customizer. -->\n" . "\n";
            $css .= auxin_get_custom_css_string( array( 'auxin_user_custom_css' ) );
        }

        //echo $css; die();

        // Add custom CSS code of the page to header
        if( $post && ! is_404() ) {
            $css .= get_post_meta( $post->ID, 'aux_page_custom_css', true );
        }

        return $css;
    }



    /**
     * Adds custom scripts in page footer if inline custom js is set.
     *
     * @return void
     */
    function inline_footer_scripts( $js, $post ){

        // Force to add custom js inline if it was customizer preview
        if( is_customize_preview() ){
            $custom_js = get_option( THEME_ID.'_custom_js_string' );
            $js .= $custom_js ? 'try{ '. $custom_js .' } catch(ex) { console.error( "Custom JS:", ex.message ); }' : '';

        } else {

            // get global custom JS code from option panel
            $user_custom_js = get_option( THEME_ID.'_inline_custom_js', '' );

            // if custom.js is not writable, print js scripts in page footer
            if( ! empty( $user_custom_js ) ) {
                if( current_user_can( 'manage_options' ) ){
                    $js .= "\n". sprintf( "// Note for admin: The custom.js file in [%s] is not writeable, so the theme uses inline js callback instead. \n",
                                          "wp-content/uploads/". THEME_ID. "/custom.js" );
                }
                $js .= $user_custom_js . "\n";
            }

        }

        return $js;
    }


    /**
     * Load custom styles for skins on login page
     *
     * @return void
     */
    public function login_assets() {
        wp_register_style( 'login-auxin', ADMIN_CSS_URL. 'login.css', array('login'), '1.1' );
        wp_print_styles( 'login-auxin' );
    }

}

return new Auxin_Frontend_Assets();
