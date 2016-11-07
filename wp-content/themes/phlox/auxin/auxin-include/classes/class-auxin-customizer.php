<?php
/**
 * Customizer manager for auxin framework
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

class Auxin_Customizer{

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Sections and panels
     *
     * @var array
     */
    public $sections = array();

    /**
     * List of fields
     *
     * @var array
     */
    public $fields = array();


    private $nonce;


    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    function __construct(){
        add_action( 'customize_register'                , array( $this, 'customize_register') );
        add_action( 'customize_controls_print_styles'   , array( $this, 'controls_style'    ) );
        add_action( 'customize_preview_init'            , array( $this, 'preview_init'      ) );

        add_action( 'wp_ajax_auxin_customizer'          , array( $this, 'ajax_get'          ) );

        $this->nonce = wp_create_nonce( 'aux-customizer-nonce' );
    }


    public function ajax_get(){

        if( isset( $_REQUEST['setting_id'] ) ){

            if( isset( $_REQUEST['nonce'] ) ){
                if( ! wp_verify_nonce( $_REQUEST['nonce'], 'aux-customizer-nonce' ) ){
                    wp_send_json_error( 'Access denied [aux103].' );
                }
            } else {
                wp_send_json_error( 'Access denied [aux107].' );
            }

            if( ! isset( $_REQUEST['setting_id'] ) ){
                wp_send_json_error( 'Setting value not found' );
            }

            $setting_id    = $_REQUEST['setting_id'];
            $setting_value = $_REQUEST['setting_value'];

            $field = Auxin_Option::api()->controller->get_field( $setting_id );
            $style = '';

            if( isset( $field['style_callback'] ) && ! empty( $field['style_callback'] ) ){
                $style = call_user_func( $field['style_callback'], $setting_value );
            }
            wp_send_json_success( $style );
        }

        wp_send_json_error( 'Setting ID not found' );
    }

    /**
     * Gets the setting and sections and generates the customizer controllers and other parts
     */
    public function render( $sections = null, $fields = null ){

        if( is_array( $sections ) ){
            $this->sections = $sections;
        }
        if( is_array( $fields ) ){
            $this->fields = $fields;
        }

    }


    public function maybe_render(){
        $sorted_sections = Auxin_Option::api()->controller->sort_sections();
        $sorted_fields   = Auxin_Option::api()->controller->sort_fields();

        if( empty( $sorted_sections ) || empty( $sorted_fields ) ){
            return;
        }

        $this->render( $sorted_sections, $sorted_fields );
    }



    public function exclude_types(){
        $excludes = array(
              'import',
              'export'
        );
        return apply_filters( 'auxin_customizer_excludes_types', $excludes );
    }


    public function controls_style() {
        wp_enqueue_style( 'auxin_customizer_css',  ADMIN_CSS_URL . 'other/customizer.css', NULL, NULL, 'all' );

        wp_localize_script( 'jquery', 'auxinCustomizerNonce', $this->nonce );
    }


    public function preview_init(){
        wp_enqueue_script( 'customize-preview' );
        wp_enqueue_script( 'auxin-customizer', ADMIN_JS_URL . 'solo/customizer.js' , array( 'jquery', 'customize-preview' ), '', true );

        wp_localize_script( 'customize-preview', 'auxinCustomizerNonce', $this->nonce );
    }


    public function customize_register( WP_Customize_Manager $wp_customize ){

        $wp_customize->attach_ids_list = array( '-1' => ''); // Collect attachment id and srcs for attachMedia script

        $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

        require_once 'class-auxin-customize-control.php';


        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial( 'blogname', array(
                'selector'            => '.site-title a',
                'container_inclusive' => false,
                'render_callback'     => function(){ bloginfo( 'name' ); }
            ) );
            $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
                'selector'            => '.site-description',
                'container_inclusive' => false,
                'render_callback'     => function(){ bloginfo( 'description' ); }
            ) );

        }

        // Add Sections
        // =====================================================================

        foreach ( $this->sections as $panel_id => $sections ) {
            foreach ( $sections as $section ) {

                // Skip if the section is not allowed to be added to customizer
                if( 'option_panel' == $section['add_to'] ){
                    continue;
                }

                if( isset( $section['parent'] ) ){

                    if( empty( $section['parent'] ) ){

                        // Add Panel
                        // -----------------------------------------------------

                        $wp_customize->add_panel( $section['id'], array(
                            'title'           => $section['title'],
                            'description'     => $section['description'],
                            'priority'        => $section['priority'],
                            'active_callback' => '', // @TODO $section['active_callback']
                            'icon'            => $section['icon'] // Meanwhile this param is not available in customizer
                        ));

                    } else {

                        // Add Section
                        // -----------------------------------------------------

                        $wp_customize->add_section( $section['id'], array(
                            'title'           => $section['title'],
                            'description'     => $section['description'],
                            'panel'           => $section['parent'],
                            'priority'        => $section['priority'],
                            'active_callback' => '', // @TODO $section['active_callback'],
                            'icon'            => $section['icon'], // Meanwhile this param is not available in customizer
                            'capability'      => 'edit_theme_options'//$section['capability']
                        ));

                    }
                }
            }
        }

        // Add Fields
        // =====================================================================

        foreach ( $this->fields as $section_id => $fields ) {
            foreach ( $fields as $field_id => $field ) {

                // Skip if the section is not allowed to be added to customizer
                if( isset( $field['add_to'] ) && 'option_panel' == $field['add_to'] ){
                    continue;
                }

                // Skip excluded types
                if( in_array( $field['type'], $this->exclude_types() ) ){
                    continue;
                }


                // Add setting
                // -------------------------------------------------------------

                $this->add_setting( $wp_customize, $field );

                // Controls
                // -------------------------------------------------------------

                $this->add_control( $wp_customize, $field );

                // Partials and placement
                // -------------------------------------------------------------

                $this->add_partial( $wp_customize, $field );

            }
        }



    }


    protected function add_setting( $wp_customize, $field ){

        $wp_customize->add_setting( new Auxin_Customize_Setting( $wp_customize, $field['id'], array(
            'capability'           => $field['capability'],
            'theme_supports'       => $field['theme_supports'], // Rarely needed.
            'default'              => is_array( $field['default'] ) ? '': $field['default'],
            'transport'            => $field['transport'], // refresh or postMessage
            'post_js'              => $field['post_js'],
            'style_callback'       => $field['style_callback'],
            'sanitize_callback'    => $field['sanitize_callback'],
            'sanitize_js_callback' => $field['sanitize_js_callback'] // Basically to_json.
        )));

    }


    protected function add_partial( $wp_customize, $field ){

        if( $field['partial']['selector'] && $field['partial']['render_callback'] ){

            $partial_args = array(
                'settings'        => $field['id'],
                'selector'        => $field['partial']['selector'],
                'render_callback' => $field['partial']['render_callback']
            );

            $wp_customize->selective_refresh->add_partial( $field['id'] . '_partial', $partial_args );
        }

    }


    protected function add_control( $wp_customize, $field ){

        $control_type_class = 'WP_Customize_Control';

        $control_args = array(
            'settings'        => $field['id'],
            'label'           => $field['title'],
            'type'            => $field['type'],  // type of control (checkbox, textarea, radio, select, dropdown-pages, number, url, tel, email, time, date, )
            'section'         => $field['section'],  // Required, core or custom.
            'priority'        => $field['priority'],      // Within the section.
            'choices'         => $field['choices'],
            'mode'            => $field['mode'],
            'description'     => $field['description'],
            'dependency'      => $field['dependency'],
            'default'         => $field['default'],
            'active_callback' => ''
        );

        switch ( $field['type'] ) {

            case 'color':
                $control_args['type'] = 'auxin_color';
                $control_type_class = 'Auxin_Customize_Color_Control';
                break;

            case 'images':
                $control_args['type']          = 'auxin_media';
                $control_args['mime_type']     = 'image';
                $control_args['limit']         = 9999;
                $control_args['multiple']      = '1';
                $control_args['button_labels'] = array(
                    'add'          => __( 'Add Image', 'phlox' ),
                    'change'       => __( 'Change Image', 'phlox' ),
                    'submit'       => __( 'Submit Image', 'phlox' ),
                    'frame_title'  => __( 'Select Image', 'phlox' ),
                    'frame_button' => __( 'Choose Image', 'phlox' )
                );
                $control_type_class = 'Auxin_Customize_Media_Control';
                break;

            case 'image':
                $control_args['type']          = 'auxin_media';
                $control_args['mime_type']     = 'image';
                $control_args['limit']         = 1;
                $control_args['multiple']      = '0';
                $control_args['button_labels'] = array(
                    'add'          => __( 'Add Image', 'phlox' ),
                    'change'       => __( 'Change Image', 'phlox' ),
                    'submit'       => __( 'Submit Image', 'phlox' ),
                    'frame_title'  => __( 'Select Image', 'phlox' ),
                    'frame_button' => __( 'Choose Image', 'phlox' )
                );
                $control_type_class = 'Auxin_Customize_Media_Control';
                break;

            case 'audio':
                $control_args['type']          = 'auxin_media';
                $control_args['mime_type']     = 'audio';
                $control_args['limit']         = 1;
                $control_args['multiple']      = '0';
                $control_args['button_labels'] = array(
                    'add'          => __( 'Add Audio', 'phlox' ),
                    'change'       => __( 'Change Audio', 'phlox' ),
                    'submit'       => __( 'Submit Audio', 'phlox' ),
                    'frame_title'  => __( 'Select Audio', 'phlox' ),
                    'frame_button' => __( 'Choose Audio', 'phlox' )
                );
                $control_type_class = 'Auxin_Customize_Media_Control';
                break;

            case 'video':
                $control_args['type']          = 'auxin_media';
                $control_args['mime_type']     = 'video';
                $control_args['limit']         = 1;
                $control_args['multiple']      = '0';
                $control_args['button_labels'] = array(
                    'add'          => __( 'Add Video', 'phlox' ),
                    'change'       => __( 'Change Video', 'phlox' ),
                    'submit'       => __( 'Submit Video', 'phlox' ),
                    'frame_title'  => __( 'Select Video', 'phlox' ),
                    'frame_button' => __( 'Choose Video', 'phlox' )
                );
                $control_type_class = 'Auxin_Customize_Media_Control';
                break;

            case 'switch':
                $control_args['type'] = 'auxin_switch';
                $control_type_class = 'Auxin_Customize_Switch_Control';
                break;

            case 'select':
                $control_args['type'] = 'auxin_select';
                $control_type_class = 'Auxin_Customize_Select_Control';
                break;

            case 'select2':
                $control_args['type'] = 'auxin_select2';
                $control_type_class = 'Auxin_Customize_Select2_Control';
                break;

            case 'select2-multiple':
                $control_args['type'] = 'auxin_select2_multiple';
                $control_type_class = 'Auxin_Customize_Select2-_Multiple_Control';
                break;

            case 'editor':
                $control_args['type'] = 'auxin_editor';
                $control_type_class = 'Auxin_Customize_Editor_Control';
                break;

            case 'textarea':
                $control_args['type'] = 'auxin_textarea';
                $control_type_class = 'Auxin_Customize_Textarea_Control';
                break;

            case 'icon':
                $control_args['type'] = 'auxin_icon';
                $control_type_class = 'Auxin_Customize_Icon_Control';
                break;

            case 'text':
                $control_args['type'] = $field['type'];
                $control_type_class = 'WP_Customize_Control';
                break;

            case 'typography':
                $control_args['type'] = 'auxin_typography';
                $control_type_class = 'Auxin_Customize_Typography_Control';
                break;

            case 'radio-image':
                $control_args['type'] = 'auxin_radio_image';
                $control_type_class = 'Auxin_Customize_Radio_Image_Control';
                break;

            case 'code':
                $control_args['type'] = 'auxin_code';
                $control_args['button_labels'] = $field['button_labels'];
                $control_type_class = 'Auxin_Customize_Code_Control';
                break;

            default:
                $control_args['type'] = 'auxin_base';
                $control_args['input_attrs'] = array('type' => $field['type'] );
                $control_type_class = 'Auxin_Customize_Input_Control';
                //$control_args['type'] = $field['type'];
                //$control_type_class = 'WP_Customize_Control';
                break;
        }

        $wp_customize->add_control(
            new $control_type_class( $wp_customize, $field['id'] . '_control', $control_args )
        );

    }


}

