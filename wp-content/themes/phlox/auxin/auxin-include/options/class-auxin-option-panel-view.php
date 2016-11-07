<?php
/**
 * Option panel view
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



class Auxin_Option_Panel_View {

    /**
     * The instance of model (data) class
     *
     * @var array
     */
    private $model = null;

    /**
     * The instance of controller class
     *
     * @var array
     */
    private $controller = null;

    /**
     * The instance of filed generator class
     *
     * @var array
     */
    private $generator = null;

    /**
     * Raw options
     *
     * @var array
     */
    private $_nonce;

    /**
     * The screen hook suffix for option panel page
     *
     * @var string
     */
    public $screen_hook_suffix;

    /**
     * The admin page slug for option panel
     *
     * @var string
     */
    public $panel_page_slug = 'auxin-options';



    function __construct( $model, $controller ){
        $this->model      = $model;
        $this->controller = $controller;
        $this->generator  = new Auxin_Option_Panel_Field( $model );

        // create nonce for use on ajax requests
        $this->_nonce = wp_create_nonce( 'auxin-optp-nonce' );

    }

    /**
     * Process the sections, controls and render and print option panel markup
     *
     * @return void
     */
    public function display(){

        // Do not run update checks when rendering the controls.
        remove_action( 'admin_init', '_maybe_update_core' );
        remove_action( 'admin_init', '_maybe_update_plugins' );
        remove_action( 'admin_init', '_maybe_update_themes' );

        $this->controller->sort_fields();
        $this->controller->sort_sections();

        $this->render_options_panel();
        $this->print_script_dependencies();
    }

    /**
     * Render and print option panel markup
     *
     * @return void
     */
    function render_options_panel(){

        $tabs_output  = '';
        $fiels_output = '';

        echo auxin_get_option( 'site_mobile_header_background_color' );
        // outputs option panel skeleton
    ?>
        <div class="av3_container axi-option-panel-wrapper auxin-ns" >

            <div class="av3_option_panel clearfix">

                <div class="init_op_overlay">
                    <?php if ( ! AUXIN_NO_BRAND ) { ?>
                    <img src="<?php echo AUX_URL. 'images/brands/auxin_logo_loading.gif'; ?>" />
                    <?php } ?>
                </div>

                <?php do_action( 'auxin_before_option_panel_content' ); ?>

                <form class="auxin_options_form" method="get" enctype="multipart/form-data" data-nonce="<?php echo $this->_nonce;?>" >

                    <div class="auxin_options_content clearfix">

                        <div class="axi-tabs-bg"></div>
                        <div class="axi-subtabs-bg"></div>

                        <ul class="tabs">
                            <?php /* outputs main tab menus */ ?>
                            <?php echo $this->get_tabs_output(); ?>
                        </ul>

                        <ul class="tabs-content">
                            <?php /* outputs sections content */ ?>
                            <?php echo $this->get_fields_output(); ?>
                        </ul>
                    </div>

                    <div class="control_bar_wrapper">
                        <div class="actions_control_bar last_bar float_bar clearfix">
                            <div class="right" style="width:300px;">
                                <a href="#" class="button blue large save_all_btn" style="float:right;margin-top:0;" ><?php _e('Save All Options', 'phlox'); ?></a>
                                <img class="ajax-loading" src="<?php echo AUX_URL ;?>images/other/wpspin_light.gif" style="padding:8px 10px;" alt />
                            </div>
                            <a href="#" class="button black large reset_all_btn" style="margin-top:0;"><?php _e('Reset all options', 'phlox'); ?></a>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    <?php
    }

    /**
     * Generate tabs and tabs-content output
     *
     * @return void
     */
    private function get_tabs_output(){

        $tabs_output = sprintf( '<li class="auxin-logo" title="%s"><span>%s</span></li>',
                         'Auxin'.' V'. AUXIN_VERSION ." | ". __( "An exclusive framework for averta themes.", 'phlox'),
                         'V'. AUXIN_VERSION );


        // Generate tabs markup and store in $tabs_output var
        foreach ( $this->model->sorted_sections as $top_level_section_id => $classified_sub_sections ) {

            if( isset( $classified_sub_sections[ $top_level_section_id ] ) ){
                /* start: starts a new sub_section */
                $tab_id = str_replace( ' ', '-', strtolower( $top_level_section_id ) );
                $this_tab = $classified_sub_sections[ $top_level_section_id ];

                // Check if user have required capability for accessing this section
                if( ! current_user_can( $this_tab['capability'] ) ){
                    continue;
                }

                $icon_name = isset( $this_tab['icon'] ) ? $this_tab['icon'] : '';


                if( count( $classified_sub_sections ) > 1 ) {

                    $tab_title = ! empty( $this_tab['description'] ) ? $this_tab['description'] : $this_tab['title'];
                    /* Add the new tab and open sub_section list */
                    $tabs_output .= '<li><a class="'.$icon_name.'" href="#'.$tab_id.'">'.$this_tab['title'].'</a><ul><li class="not-tab"><h3>'.$tab_title.'</h3></li>';
                } else {
                    /* Add the new tab for this sub_section */
                    $tabs_output .= '<li><a class="'.$icon_name.'" href="#'.$tab_id.'">'.$this_tab['title'].'</a></li>';
                }
            }

            foreach ( $classified_sub_sections as $sub_section ) {

                // Ignore adding top level section twice
                if( $sub_section['id'] == $top_level_section_id ){
                    continue;
                }

                // Check if user have required capability for accessing this section
                if( ! current_user_can( $sub_section['capability'] ) ){
                    continue;
                }

                /* start: starts a new sub_section */
                $tab_id = str_replace( ' ', '-', strtolower( $sub_section['id'] ) );

                /* Add the new tab for this sub_section */
                $tabs_output .= '<li><a href="#'.$tab_id.'">'.$sub_section['title'].'</a></li>';
            }

            /* close sub_section list */
            if( count( $classified_sub_sections ) > 1 ){
                $tabs_output .= '</ul></li>';
            }

        }

        $this->tabs_output = apply_filters( 'auxin_option_panel_tabs_output', $tabs_output, $this->model );

        return $this->tabs_output;
    }

    /**
     * Loop through options, collect attachment list, generate fields and return them
     *
     * @return void
     */
    protected function get_fields_output(){

        // loop through options and create option panel fields
        $fields_output   = "";

        $this->generator->dependency_list = array();
        $this->generator->attach_ids_list = array( '-1' => ''); // Collect attachment id and srcs for attachMedia script

        foreach ( $this->model->sorted_fields as $section_id => $section_options ) {

            /* start the new section */
            $fields_output .= '<li id="'. $section_id .'">'. $this->get_section_title( $section_id );

            foreach( $section_options as $option ){
                $fields_output .= $this->get_field_output( $option );
            }
        }

        return apply_filters( 'auxin_option_panel_fields_output', $fields_output, $this->model );
    }

    /**
     * Retrieves markup for a specific field
     *
     * @param  array $option  An array of info for field
     * @return string         Field markup
     */
    function get_field_output( $option ){
        return $this->generator->get_field_output( $option );
    }

    /**
     * Get the title of the section
     *
     * @param  int   $section_id  The section ID
     * @return string             The section title
     */
    protected function get_section_title( $section_id ){
        $title = '';

        if( isset( $this->model->sections[ $section_id ] ) ){
            $title = isset( $this->model->sections[ $section_id ]['description'] ) && ! empty( $this->model->sections[ $section_id ]['description'] ) ?
                   '<h3 class="main-section-title">'. $this->model->sections[ $section_id ]['description'] .'</h3>' : '';
        }

        return apply_filters( 'auxin_option_panel_section_title', $title, $section_id, $this );
    }

    /**
     * Print dependency script for fields in option panel
     *
     * @return void
     */
    private function print_script_dependencies(){
        // echo js dependencies
        printf( "\n<script>window.auxin = window.auxin || {}; window.auxin.optionpanel = window.auxin.optionpanel || {};</script>" );
        printf( "<script>auxin.optionpanel = %s;</script>", json_encode( $this->generator->dependency_list ) );

        printf( "\n<script>window.auxin.attachmedia = window.auxin.attachmedia || {};</script>" );
        printf( "<script>auxin.attachmedia = %s;</script>", json_encode( array_unique( $this->generator->attach_ids_list ) ) );
    }

}
