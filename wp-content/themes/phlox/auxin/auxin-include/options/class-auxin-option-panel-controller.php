<?php
/**
 * Option panel controller
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


class Auxin_Option_Panel_Controller {
    /**
     * [$model description]
     * @var null
     */
    public $model = null;



    function __construct( $model ){
        $this->model = $model;
    }

    //=== Add option section ===================================================

    public function add_sections( $sections ){
        if( is_array( $sections ) ){
            $this->model->sections = array_merge( $this->model->sections, $sections );
        }
        $this->sort_sections();
    }

    public function add_section( $section ){
        if( is_array( $section ) ){
            $this->model->sections[] = $section;
        }
    }

    //=== Add option field =====================================================

    public function add_fields( $fields ){
        if( is_array( $fields ) ){
            $this->model->fields = array_merge( $this->model->fields, $fields );
        }
        $this->sort_fields();
    }

    public function add_field( $field ){
        if( is_array( $field ) ){
            $this->model->fields[] = $field;
        }
    }

    //=== Get option field =====================================================

    public function get_fields(){
        return $this->model->fields;
    }

    public function get_field( $field_id ){

        $fields = $this->get_fields();
        foreach ( $fields as $field ) {
            if(  $field_id == $field['id'] ){
                return $field;
            }
        }
        return false;
    }

    //=== Get option sections ==================================================

    public function get_sections(){
        return $this->model->sections;
    }

    public function get_section( $section_id ){

        $sections = $this->get_sections();
        foreach ( $sections as $section ) {
            if(  $section_id == $section['id'] ){
                return $section;
            }
        }
        return false;
    }

    //=== Sort sections ========================================================

    /**
     * Sort and input (unsorted) fields by section id
     *
     * @return void
     */
    public function sort_fields(){

        $option_fields  = $this->get_fields();

        if( ! is_array( $option_fields ) ){
            return new WP_Error( 'auxin-options', 'No options is defined.' );
        }

        // classify options by sections
        $sorted_fields = array();

        foreach( $option_fields as $field ){
            if( ! isset( $field['id'] ) ){
                axpp( 'ID is missing for following option =>' );
            }

            // Sanitize the field
            $field = $this->_sanitize_field( $field );

            if( isset( $field['section'] ) ){
                $sorted_fields[ $field['section'] ][ $field['id'] ] = $field;
            }
        }

        $this->model->sorted_fields = apply_filters( 'auxin_option_panel_fields', $sorted_fields );

        return $this->model->sorted_fields;
    }

    /**
     * Sort and input (unsorted) sections by section id
     *
     * @return void
     */
    public function sort_sections(){

        $sections = $this->get_sections();

        if( ! is_array( $sections ) ){
            return new WP_Error( 'auxin-options', 'No section is defined.' );
        }

        // classify sections hierarchy
        $sorted_sections   = array();
        $unsorted_sections = array();

        foreach( $sections as $section ){
            $section = $this->_sanitize_section( $section );

            if( isset( $section['id'] ) ){
                if( ! empty( $section['parent'] ) ){
                    // If it is section
                    $sorted_sections[ $section['parent'] ][ $section['id'] ] = $section;
                } else {
                    // If it is Panel
                    $sorted_sections[ $section['id'] ][ $section['id'] ] = $section;
                }

                $unsorted_sections[ $section['id'] ] = $section;
            }
        }

        $this->model->sorted_sections = apply_filters( 'auxin_option_panel_sections', $sorted_sections );
        $this->model->sections        = $unsorted_sections;

        return $this->model->sorted_sections;
    }

    //=== Sanitize sections ====================================================

    private function _sanitize_section( $section ){

        if( ! isset( $section['parent'] ) ){
            $section['parent'] = '';
        }

        $section['title']           = isset( $section['title']      ) ? $section['title']       : '';
        $section['description']     = isset( $section['description']) ? $section['description'] : '';
        $section['priority']        = isset( $section['priority']   ) ? esc_attr( $section['priority']   ) : 10;
        $section['capability']      = isset( $section['capability'] ) ? esc_attr( $section['capability'] ) : $this->model->default_capability;
        $section['icon']            = isset( $section['icon']       ) ? esc_attr( $section['icon']       ) : '';
        $section['add_to']          = isset( $section['add_to']     ) ? esc_attr( $section['add_to']     ) : 'all';
        $section['active_callback'] = isset( $section['active_callback'] ) ? esc_attr( $section['active_callback'] ) : '';

        return $section;
    }


    private function _sanitize_field( $field ){

        $field = wp_parse_args( $field, array(
            'type'                 => '',
            'capability'           => '',
            'theme_supports'       => '',
            'default'              => '',
            'choices'              => array(),
            'mode'                 => '',
            'description'          => '',
            'priority'             => 10,
            'transport'            => 'postMessage',
            'active_callback'      => '',
            'dependency'           => array(),
            'partial'              => array(),
            'post_js'              => '',
            'button_labels'        => array(),
            'style_callback'       => '',
            'sanitize_callback'    => '',
            'sanitize_js_callback' => ''
        ));

        if( ! is_array( $field['partial'] ) ){
            $field['partial'] = (array) $field['partial'];
        }

        if( ! isset( $field['partial']['selector'] ) ){
            $field['partial']['selector'] = '';
        }
        if( ! isset( $field['partial']['render_callback'] ) ){
            $field['partial']['render_callback'] = '';
        }
        if( ! isset( $field['partial']['container_inclusive'] ) ){
            $field['partial']['container_inclusive'] = false;
        }

        return $field;
    }



    /**
     * Save theme options
     *
     */
    public function ajax_save_panel_options(){

        // verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'auxin-optp-nonce' ) ) {
            wp_send_json_error( __( 'Authorization failed', 'phlox' ) );
        }

        // ignore the request if the current user doesn't have sufficient permissions
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( __( 'Insufficient permissions', 'phlox' ) );
        }

        // store style options in custom CSS file
        $this->save_custom_styles();
        $this->save_custom_scripts();

        // action hook which triggers after updating auxin options
        do_action( 'auxin_options_updated', $_POST['type'], $_POST );

        // create and output the response
        if( $_POST['type'] == 'reset' ){
            $response = json_encode( array( 'success' => true, 'type' => 'reset', 'message' => __( 'All options are reseted', 'phlox' ) ) );
        } else {
            $response = json_encode( array( 'success' => true, 'type' => 'save' , 'message' => __( 'All options updated', 'phlox' )     ) );
        }

        echo $response;

        exit;// IMPORTANT
    }


    /**
     * Import options to option panel
     *
     */
    public function ajax_import_panel_options() {
        header( "Content-Type: application/json" );

        // verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'auxin-optp-nonce' ) ) {
            echo json_encode( array( 'success' => false, 'message' => __('Authorization failed', 'phlox') ) );
            exit( 'No naughty business please! '. $_POST['nonce']);
        }

        // ignore the request if the current user doesn't have sufficient permissions
        if ( !current_user_can( 'edit_posts' ) ) {
            echo json_encode( array( 'success' => false, 'message' => __( 'Insufficient permissions', 'phlox') ) );
            exit( 'insufficient permissions' );
        }

        $import_data = $_POST['options'];

        // store style options in custom CSS file
        $this->save_custom_styles();
        $this->save_custom_scripts();

        // action hook which triggers after updating auxin options
        do_action( 'auxin_options_updated', 'import', $_POST );

        // create and output the response
        $response = json_encode( array( 'success' => true, 'type' => 'import', 'message' => __( 'All options Imported', 'phlox') ) );
        echo $response;

        exit;// IMPORTANT
    }


    /**
     * Gets current style options and stores them in a CSS file
     * This function will be called by option panel's save handler
     *
     * @return void
     */
    public function save_custom_styles(){
        // creates css query for loading google fonts, and registers css to enqueue by wp
        AXI_FONT()->parse_typography();

        auxin_save_custom_css();
    }


    /**
     *  Gets current custom Javascript codes and stores them in a JS file
     *  This function will be called by option panel's save handler
     *
     *  @return void
     */
    public function save_custom_scripts(){
        auxin_save_custom_js();
    }


    /**
     * Generates and stores the custom asset files
     *
     * @return void
     */
    public function save_custom_assets(){
        $this->save_custom_styles();
        $this->save_custom_scripts();
    }

}
