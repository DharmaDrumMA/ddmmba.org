<?php
/**
 * Class for generating option panel fields
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

class Auxin_Option_Panel_Field {

    /**
     * The instance of model (data) class
     *
     * @var array
     */
    private $model = null;


    public $attach_ids_list = '';
    public $raw_options     = array();

    /**
     * Dependency scripts for option panel fields
     *
     * @var array
     */
    public $dependency_list = array();

    /**
     * Presets list for option panel fields
     *
     * @var array
     */
    public $presets_list = array();


    function __construct( $model ){
        $this->model = $model;

        // get raw theme options
        $this->raw_options = get_option( THEME_ID.'_options'  );
    }


    protected function get_field_header( $option ){

        $field_wrapper_class = isset( $option['wrapper_class'] ) ? esc_attr( $option['wrapper_class'] ) : '';

        // Field container start
        $field_header_output = '<div class="panel_field field-row '. $field_wrapper_class .'" >';

        // title
        $field_header_output .= '<label>' . $option['title'] . '</label>';

        // start field wrapper
        $field_header_output .= '<div class="panel_elements">';

        return $field_header_output;
    }


    protected function get_field_footer(){
        // Field wrapper end
        $field_footer_output  = '</div><!-- End-field-wrapper -->';

        // Field container end
        $field_footer_output .= '</div><!-- End-field-container -->';

        return $field_footer_output;
    }


    protected function get_field_values( $option, $key = null ){
        $values = array();

        /* Get default value */
        $values['default'] = isset( $option['default'] ) ? $option['default'] : '';

        /* Get the stored value for this field */
        $values['stored'] = isset( $option['id'] ) && isset( $this->raw_options[ $option['id'] ] ) ? $this->raw_options[ $option['id'] ] : '';

        $values['real'] = '' === $values['stored'] ? $values['default'] : $values['stored'];

        if( ! is_null( $key ) && isset( $values[ $key ] ) ){
            return $values[ $key ];
        }

        return $values;
    }

    // Fields ===  get_field_{$field_type}  ====================================

    protected function get_field_text( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<input type="text" class="white" name="'.$option['id'].'" id="'.$option['id'].
                              '" value="'.$values['real'].'" placeholder="'. esc_attr( $values['default'] ).'" />';

            // Description
            if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_number( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

        $data_attrs  = '';
        if( isset( $option['choices']['min'] ) ){
            $data_attrs .= ' data-min="' . $option['choices']['min'] . '"';
        }
        if( isset( $option['choices']['max'] ) ){
            $data_attrs .= ' data-max="' . $option['choices']['max'] . '"';
        }
        if( isset( $option['choices']['step'] ) ){
            $data_attrs .= ' data-step="' . $option['choices']['step'] . '"';
        }

            // The Field
            $fields_output .= '<input type="text" class="white auxin-admin-numeric" name="'.$option['id'].'" id="'.$option['id'].
                              '" value="'.$values['real'].'" placeholder="'. esc_attr( $values['default'] ) .'" '. $data_attrs .' />';

            // Description
            if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_color( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="mini-color-wrapper" >';
            // Field
            $fields_output .= '<input type="text" id="'.$option['id'].'" name="'.$option['id'].'" value="'.$values['real'].'" >';

            $fields_output .= '</div>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_palette( $option ){

        $values = $this->get_field_values( $option );

        if( isset( $option['choices'] ) && ! empty( $option['choices'] ) ){

            $colors = array_values( $option['choices'] );
            printf( "\n<script>window.auxin.admin = window.auxin.admin || {};</script>" );
            printf( "\n<script>window.auxin.admin.palette = window.auxin.admin.palette || {};</script>" );
            printf( "<script>auxin.admin.palette.%s = %s;</script>", $option['id'], json_encode( $colors ) );
        }

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="mini-color-wrapper auxin-color-palette" >';
            // Field
            $fields_output .= '<input type="text" id="'.$option['id'].'" name="'.$option['id'].'" value="'.$values['real'].'" >';

            $fields_output .= '</div>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_image_old( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<fieldset class="uploader">';

                // The Field
                $fields_output .= '<input type="text" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$values['real'].'" />';
                // Upload btn
                $fields_output .= '<input type="button" class="blue medium" value="'.__("Browse", 'phlox').'" />';
                // Remove btn
                $fields_output .= '<input type="button" class="grey medium" value="'.__("Remove", 'phlox').'" />';

                $fields_output .= '<div class="imgHolder"><div class="img-placeholder"></div><strong title="'.__("Remove image", 'phlox').'" class="close"></strong>';

                $fields_output .= '<img alt src="" /></div>';

            $fields_output .= '</fieldset>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_image( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="axi-attachmedia-wrapper" >';

                // The Field
                $fields_output .= '<input type="text" class="white" name="'.$option['id'].'" id="'.$option['id'].'"
                                   value="'.$values['real'].'" placeholder="'.$values['default'].'"
                                   data-media-type="image" data-limit="1" data-multiple="0"
                                   data-add-to-list="'.__('Add Image', 'phlox').'"
                                   data-uploader-submit="'.__('Add Image', 'phlox').'"
                                   data-uploader-title="'.__('Select Image', 'phlox').'"
                                   />';

                // Store attachment src for avertaAttachMedia field
                if( $att_ids = explode( ',', $values['real'] ) ){
                    $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                }

                // Description
                if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

            // end field wrapper
            $fields_output .= '</div>';

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_images( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="axi-attachmedia-wrapper" >';

                // The Field
                $fields_output .= '<input type="text" class="white" name="'.$option['id'].'" id="'.$option['id'].'"
                                   value="'.$values['real'].'" placeholder="'.$values['default'].'"
                                   data-media-type="image" data-limit="999" data-multiple="true"
                                   data-add-to-list="'.__('Add Image(s)', 'phlox').'"
                                   data-uploader-submit="'.__('Add Image', 'phlox').'"
                                   data-uploader-title="'.__('Select Image', 'phlox').'"
                                   />';

                // Store attachment src for avertaAttachMedia field
                if( $att_ids = explode( ',', $values['real'] ) ){
                    $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                }

                // Description
                if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

            // end field wrapper
            $fields_output .= '</div>';

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_video( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="axi-attachmedia-wrapper" >';

                // The Field
                $fields_output .= '<input type="text" class="white" name="'.$option['id'].'" id="'.$option['id'].'"
                                   value="'.$values['real'].'" placeholder="'.$values['default'].'"
                                   data-media-type="video" data-limit="999" data-multiple="true"
                                   data-add-to-list="'.__('Add Video', 'phlox').'"
                                   data-uploader-submit="'.__('Add Video', 'phlox').'"
                                   data-uploader-title="'.__('Select Video', 'phlox').'"
                                   />';

                // Store attachment src for avertaAttachMedia field
                if( $att_ids = explode( ',', $values['real'] ) ){
                    $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                }

                // Description
                if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

            // end field wrapper
            $fields_output .= '</div>';

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_audio( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="axi-attachmedia-wrapper" >';

                // The Field
                $fields_output .= '<input type="text" class="white" name="'.$option['id'].'" id="'.$option['id'].'"
                                   value="'.$values['real'].'" placeholder="'.$values['default'].'"
                                   data-media-type="audio" data-limit="999" data-multiple="true"
                                   data-add-to-list="'.__('Add Audio', 'phlox').'"
                                   data-uploader-submit="'.__('Add Audio', 'phlox').'"
                                   data-uploader-title="'.__('Select Audio', 'phlox').'"
                                   />';

                // Store attachment src for avertaAttachMedia field
                if( $att_ids = explode( ',', $values['real'] ) ){
                    $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                }

                // Description
                if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

            // end field wrapper
            $fields_output .= '</div>';

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_media( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<div class="axi-attachmedia-wrapper" >';

                // The Field
                $fields_output .= '<input type="text" class="white" name="'.$option['id'].'" id="'.$option['id'].'"
                                   value="'.$values['real'].'" placeholder="'.$values['default'].'"
                                   data-limit="999" data-multiple="true"
                                   data-add-to-list="'.__('Add Media', 'phlox').'"
                                   data-uploader-submit="'.__('Add Media', 'phlox').'"
                                   data-uploader-title="'.__('Select Media', 'phlox').'"
                                   />';

                // Store attachment src for avertaAttachMedia field
                if( $att_ids = explode( ',', $values['real'] ) ){
                    $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                }

                // Description
                if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

            // end field wrapper
            $fields_output .= '</div>';

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_textarea( $option ){

        $values = $this->get_field_values( $option );

        // Field container start
        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<textarea name="'.$option['id'].'" id="'.$option['id'].'" placeholder="'.$values['default'].'" >'.stripslashes( $values['real'] ).'</textarea>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_editor( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            ob_start();

            wp_editor( stripslashes( $values['stored'] ), $option['id'], array( 'media_buttons' => false ) );

            // The Field
            $fields_output .= ob_get_clean();

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_select( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<select name="'.$option['id'].'" id="'.$option['id'].'">';

                if( isset( $option['choices'] ) ){

                    foreach( $option['choices'] as $key => $value ){
                        $fields_output .= '<option value="'.$key.'" '. selected( $key, $values['real'], false ) .'>'.$value.'</option>';
                    }

                }

            $fields_output .= '</select>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_background( $option ){

        $bg_fields = array();

        $bg_fields['color'] = array(
            'title'       => __( 'Background Color', 'phlox'),
            'description' => __( 'Specifies the color of website background.', 'phlox'),
            'default'     => '',
            'type'        => 'color'
        );

        $bg_fields['image'] = array(
            'title'       =>  __('Background Image', 'phlox'),
            'description' =>  __('You can upload custom image for site background.', 'phlox').
                              '<br/>'.__('Note: if you set custom image, default image backgrounds will be ignored.', 'phlox'),
            'default' =>  '',
            'type'    =>  'image'
        );

        $bg_fields['size'] = array(
            'title'       =>  __('Background Size', 'phlox'),
            'description' =>  __('Specifies the background size.', 'phlox'),
            'choices'     => array(
                'auto' => array(
                    'label'     => __('Auto', 'phlox'),
                    'css_class' => 'axiAdminIcon-bg-size-1',
                ),
                'cover' => array(
                    'label'     => __('Cover', 'phlox'),
                    'css_class' => 'axiAdminIcon-bg-size-2'
                ),
                'contain' => array(
                    'label'     => __('Contain', 'phlox'),
                    'css_class' => 'axiAdminIcon-bg-size-3'
                )
            ),
            'default'   =>  'auto',
            'type'      =>  'radio-image'
        );

        $bg_fields['pattern'] = array(
            'title'       => __('Background Pattern', 'phlox'),
            'description' => __('Here you can select one of these patterns as site image background.', 'phlox'),
            'choices'     => auxin_get_background_patterns(),
            'default'     => '',
            'type'        => 'radio-image'
        );

        $bg_fields['position'] = array(
            'title'       =>  __('Background Position', 'phlox'),
            'description' =>  __('Specifies background image alignment.', 'phlox'),
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
            'default'   => 'left top',
            'type'      => 'radio-image'
        );

        $bg_fields['repeat'] = array(
            'title'       =>  __( 'Background Repeat', 'phlox'),
            'description' =>  __( 'Specifies how background image repeats.', 'phlox'),
            'choices'     =>  array(
                'no-repeat' => array(
                    'label'     => __('No repeat', 'phlox'),
                    'css_class' => 'axiAdminIcon-none',
                    'image'     => 'img.png'
                ),
                'repeat' => array(
                    'label'     => __('Repeat horizontally and vertically', 'phlox'),
                    'css_class' => 'axiAdminIcon-repeat-xy',
                    'image'     => 'img.png'
                ),
                'repeat-x' => array(
                    'label'     => __('Repeat horizontally', 'phlox'),
                    'css_class' => 'axiAdminIcon-repeat-x',
                    'image'     => 'img.png'
                ),
                'repeat-y' => array(
                    'label'     => __('Repeat vertically', 'phlox'),
                    'css_class' => 'axiAdminIcon-repeat-y',
                    'image'     => 'img.png'
                )
            ),
            'default'   =>  'repeat',
            'type'      =>  'radio-image'
        );

        $bg_fields['attach'] = array(
            'title'     =>  __('Background Attachment', 'phlox'),
            'description'   =>  __('Specifies the background is fixed or scrollable as user scrolls the document.', 'phlox'),
            'choices'   => array(
                'fixed' => array(
                    'label'     => __('Fixed', 'phlox'),
                    'css_class' => 'axiAdminIcon-none',
                    'image'     => 'img.png'
                ),
                'scroll' => array(
                    'label'     => __('Scroll', 'phlox'),
                    'css_class' => 'axiAdminIcon-repeat-xy',
                    'image'     => 'img.png'
                )
            ),
            'default'   =>  'fixed',
            'type'      =>  'radio-image'
        );









        $fields_output = '';

        foreach ( $bg_fields as $bg_field_type => $bg_field ) {

            // if background field type was defined, generate the corresponding field
            if( isset( $option['default'][ $bg_field_type ] ) ){

                // set default value for field
                if( ! empty( $option['default'][ $bg_field_type ] ) ){
                    $bg_field['default'] = $option['default'][ $bg_field_type ];
                }
                // set title for field if it was defined
                if( isset( $option['choices'][ $bg_field_type ]['title'] ) ){
                    $bg_field['title'] = $option['choices'][ $bg_field_type ]['title'];
                }
                // set description for field if it was defined
                if( isset( $option['choices'][ $bg_field_type ]['description'] ) ){
                    $bg_field['description'] = $option['choices'][ $bg_field_type ]['description'];
                }
                // set dependency for field if it was defined
                if( isset( $option['choices'][ $bg_field_type ]['dependency'] ) ){
                    $bg_field['dependency'] = $option['choices'][ $bg_field_type ]['dependency'];
                // otherwise, set the common dependency
                } elseif ( isset( $option['dependency'] ) ) {
                    $bg_field['dependency'] = $option['dependency'];
                }

                // set id for each field and same section for all background fields
                $bg_field['id']      = $option['id'] .'_'. $bg_field_type;
                $bg_field['section'] = $option['section'];

                $fields_output .= $this->get_field_output( $bg_field );
            }
        }

        return $fields_output;
    }


    protected function get_field_select2( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<select class="aux-orig-select2 aux-admin-select2 aux-select2-single" name="'.$option['id'].'" id="'.$option['id'].'">';

                if( isset( $option['choices'] ) ){

                    foreach( $option['choices'] as $key => $value ){
                        $fields_output .= '<option value="'.$key.'" '. selected( $key, $values['real'], false ) .'>'.$value.'</option>';
                    }

                }

            $fields_output .= '</select>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_dropdown_pages( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
           $fields_output .= wp_dropdown_pages( array(
                'depth'                 => 0,
                'child_of'              => 0,
                'selected'              => 0,
                'echo'                  => 0,
                'name'                  => $option['id'],
                'id'                    => $option['id'], // string
                'class'                 => "aux-orig-select2 aux-admin-select2 aux-select2-single", // string
                'show_option_none'      => __('Select Page ..', 'phlox'), // string
                'option_none_value'     => '', // string
                'show_option_no_change' => null // string
            ));

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_select2_multiple( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<select class="aux-orig-select2 aux-admin-select2 aux-select2-multiple" name="'.$option['id'].'" id="'.$option['id'].'" multiple="multiple" >';

                if( isset( $option['choices'] ) ){

                    foreach( $option['choices'] as $key => $value ){

                        // Which options should be selected
                        if( is_array( $values['real'] ) ){
                            $active_attr = in_array( $key , $values['real'] ) ? 'selected' : '';
                        } else {
                            $active_attr = ( $key == $values['real'] ) ? 'selected' : '';
                        }
                        $fields_output .= '<option value="'.$key.'" '.$active_attr.'>'.$value.'</option>';
                    }

                }

            $fields_output .= '</select>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_radio_image( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<select name="'.$option['id'].'" id="'.$option['id'].'" class="visual-select-wrapper" >';
            // Support for 'choices' param for defining option sub itmes
            $option['options'] = isset( $option['choices'] ) && ! empty( $option['choices'] ) ? $option['choices'] : $option['options'];

                $presets = array();

                foreach( $option['options'] as $id => $option_info ){

                    // Which options should be selected
                    $active_attr = ( $id == $values['real'] ) ? 'selected' : '';

                    $data_class  = isset( $option_info['css_class'] ) && ! empty( $option_info['css_class'] ) ? 'data-class="'. $option_info['css_class'].'"' : '';
                    $data_symbol = empty( $data_class ) && isset( $option_info['image'] ) && ! empty( $option_info['image'] ) ? 'data-symbol="'. $option_info['image'].'"' : '';

                    if( isset( $option_info['presets'] ) && ! empty( $option_info['presets'] ) ){
                        $presets[ $id ] = $option_info['presets'];
                    }

                    $fields_output .= sprintf( '<option value="%s" %s %s %s>%s</option>', $id, $active_attr, $data_symbol, $data_class, $option_info['label'] );
                }

                if( ! empty( $presets ) ){
                    $this->presets_list[ $option['id'] ] = $presets;
                }

            $fields_output .= '</select>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_switch( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // is element selected or not
            $active_attr = ( $values['real'] == '1' ) ? 'value="checked" checked="checked"' : '';

            // Field
            $fields_output .= '<label><input type="checkbox" class="aux_switch" id="'.$option['id'].'" name="'.$option['id'].'" '.$active_attr.' ></label>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_checkbox( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // is element selected or not
            $active_attr = ( $values['stored'] === true || $values['stored'] === 'checked' || $values['stored'] === 'true' ) ?
                             'value="checked" checked="checked"' : '';

            // Field
            $fields_output .= '<label><input type="checkbox" id="'.$option['id'].'" name="'.$option['id'].'" '.$active_attr.' ></label>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_icon( $option ){

        $font_icons = Auxin()->font_icons->get_icons_list('fontastic');

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<select name="'.$option['id'].'" id="'.$option['id'].'" class="meta-select aux-fonticonpicker" >';
                $fields_output .= '<option value="">' . __('Choose ..', 'phlox') . '</option>';

                if( is_array( $font_icons ) ){
                    foreach ( $font_icons as $icon ) {
                        $icon_id = trim( $icon->classname, '.' );
                        $fields_output .= '<option value="'. $icon_id .'" '. selected( $values['real'], $icon_id, false ) .' >'. $icon->name . '</option>';
                    }
                }

            $fields_output .= '</select>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_upload( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<fieldset class="uploader">';

                // The Field
                $fields_output .= '<input type="text" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$values['real'].'" />';
                // Upload btn
                $fields_output .= '<input type="button" class="blue medium" value="'.__("Browse", 'phlox').'" />';
                // Remove btn
                $fields_output .= '<input type="button" class="grey medium" value="'.__("Remove", 'phlox').'" />';

                $fields_output .= '<div class="imgHolder"><div class="img-placeholder"></div><strong title="'.__("Remove image", 'phlox').'" class="close"></strong>';

                $fields_output .= '<img alt src="" /></div>';

            $fields_output .= '</fieldset>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_code( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // editoe mode
            $editor_mode = isset($option['mode']) && ! empty($option['mode']) ? $option['mode'] : 'javascript';

            // The Field
            $fields_output .= '<textarea class="code_editor" name="'.$option['id'].'" id="'.$option['id'].'" placeholder="'.$values['default'].'" data-code-editor="'.$editor_mode.'" >'.stripslashes( $values['real'] ).'</textarea>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_import( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<textarea name="'.$option['id'].'" id="'.$option['id'].'" style="width:100%;" ></textarea>';

            // Upload btn
            $fields_output .= '<input  id="'.$option['id'].'_btn" type="button" class="white" value="'.__("Import", 'phlox').'" />';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }

    protected function get_field_info( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_radio( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            $fields_output .= '<fieldset>';

                foreach($option['options'] as $key => $value){

                    // Which options should be selected
                    $active_attr = ($value == $values['real'])?'checked="checked"':'';

                    // Field
                    $fields_output .= '<label>'.$key.'<input type="radio" id="'.$option['id'].'" name="'.$option['id'].'" value="'.$value.'" '.$active_attr.' ></label>';
                }

            $fields_output .= '</fieldset>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_slider( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // Field
            $fields_output .= '<div class="axi_ui_slider"><input type="range" min="'.$option['options']['min'].'" max="'.$option['options']['max'].'" step="'.$option['options']['step'].'" id="'.$option['id'].'" name="'.$option['id'].'" value="'.$values['real'].'" /></div>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_sortable( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = $this->get_field_header( $option );

            // get options from db if is defined
            $user_defined_value = empty( $user_defined_value) ? $option['options'] : $user_defined_value;

            $fields_output .= '<fieldset id="'.$option['id'].'" class="draggable-area">';

                foreach( $user_defined_value as $key => $value ){

                    $fields_output .= '<div class="one_half">';
                        $fields_output .= '<h4 class="area-title">'.$key.'</h4>';

                        $fields_output .= '<ul class="sortbox area">';

                            foreach( $value as $key => $val ){
                                $fields_output .= '<li id="'.$key.'" class="rect">'.$val.'</li>';
                            }

                        $fields_output .= '</ul>';
                    $fields_output .= '</div>';

                }

            $fields_output .= '</fieldset>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_sidebar( $option ){

        $values = $this->get_field_values( $option );

        $fields_output  = '<div class="sidebar-generator" >';

        $fields_output .= $this->get_field_header( $option );


                $fields_output .= '<fieldset class="addField" >';

                    // The Field
                    $fields_output .= '<input type="text" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$values['real'].'" />';
                    // Add btn
                    $fields_output .= '<a href="" class="white button blue" >'.__( "Create sidebar", 'phlox').'</a>';

                $fields_output .= '</fieldset>';

                // Description
                if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

            // end field wrapper
            $fields_output .= '</div><!-- End-field-wrapper -->';


            $fields_output .= '<h4 class="area-title">'.__('Created Sidebars', 'phlox').'</h4>';

            $fields_output .= '<ul class="area">';

                // $fields_output .= '<li class="sidebar-rect sidebartemp hidden"   title="'.__("Remove sidebar", 'phlox').'"><span class="label">Global</span><span class="close">x</span></li>';

                if( isset( $auxin_sidebars )  && ! empty( $auxin_sidebars ) ){
                    foreach( $auxin_sidebars as $key => $val ){
                        $fields_output .= '<li data-name="'.$val.'" class="sidebar-rect" title="'.__("Remove sidebar", 'phlox').'"><span class="label">'.$val.'</span><span class="close">x</span></li>';
                    }
                }

            $fields_output .= '</ul>';

        $fields_output .= '</div><!-- End-field-container -->';

        // Field container end
        // $fields_output .= $field_container_end;

        $fields_output .= '</div>';

        return $fields_output;
    }


    protected function get_field_sep( $option ){

        $values = $this->get_field_values( $option );

        $fields_output = $this->get_field_header( $option );
        //$fields_output = $field_container_start;

            $desc = empty( $option['description'] ) ? '' : '<span>'.$option['description'].'</span>';

            // The Field
            $fields_output .= '<div class="section-legend" ><p>'. $option['title']. '</p>' .$desc. '</div>';

        $fields_output .= $this->get_field_footer();
        //$fields_output .= $field_container_end;

        return $fields_output;
    }


    protected function get_field_add(){

        $values = $this->get_field_values( $option );

        $fields_output = $this->get_field_header( $option );

            $fields_output .= '<fieldset class="addField" >';

                // The Field
                $fields_output .= '<input type="text" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$values['real'].'" />';
                // Add btn
                $fields_output .= '<a href="" class="white button" >'.__("Create sidebar", 'phlox').'</a>';

            $fields_output .= '</fieldset>';

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_typography( $option ){

        $values = $this->get_field_values( $option );

        $fields_output = $this->get_field_header( $option );

            $typography_stored = array();

            // Get default value
            if( isset( $this->raw_options[ $option['id'] ] ) && ! empty( $this->raw_options[ $option['id'] ] ) ){ // get stored value if available
                $typography_stored['font'] = $this->raw_options[ $option['id'] ];
            } else { // otherwise use default value
                $typography_stored['font'] = isset( $option['default']['font'] ) ? $option['default']['font'] : '';
            }

            if( isset( $this->raw_options[ $option['id'].'_color' ] ) && ! empty( $this->raw_options[ $option['id'].'_color' ] ) ){ // get stored value if available
                $typography_stored['color'] = $this->raw_options[ $option['id'].'_color' ];
            } else { // otherwise use default value
                $typography_stored['color'] = isset( $option['default']['color'] ) ? $option['default']['color'] : '';
            }

            // Font face and thickness
            if( isset( $option['default']['font'] ) ) {
                $fields_output .= '<div class="typo_fields_wrapper typo_font_wrapper" >';
                $fields_output .= '<input type="text" class="axi-font-field" name="'.$option['id'].'" id="'. $option['id'].'_font" value="'.$typography_stored['font'].'"  />';
                $fields_output .= '</div>';
            }

            // Font Color
            if( isset( $option['default']['color'] ) ) {
                $fields_output .= '<div class="mini-color-wrapper typo_fields_wrapper" ><label>'.__( 'Color', 'phlox').'</label><br/>';
                $fields_output .= '<input type="text" id="'.$option['id'].'_color" name="'.$option['id'].'_color" value="'.$typography_stored['color'].'" >';
                $fields_output .= '</div>';
            }

            // Description
            if( isset( $option['description'] ) ){ $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    protected function get_field_default( $option ){

        $values = $this->get_field_values( $option );

        $fields_output = $this->get_field_header( $option );

            // The Field
            $fields_output .= '<input type="'.$option['type'].'" class="white" name="'.$option['id'].'" id="'.$option['id'].'" value="'.$values['real'].'" placeholder="'.$values['default'].'" />';

            // Description
            if( isset( $option['description'] ) ) { $fields_output .= '<p>'.$option['description'].'</p>'; }

        $fields_output .= $this->get_field_footer();

        return $fields_output;
    }


    public function get_field_output( $option ){

        /**
         * Renders the control and sections.
         * Populate according to option type
         *
         * Additional input types such as `email`, `url`, `number`, `hidden` and `date` are supported implicitly.
         */
        switch ( $option['type'] ) {

            // Textfield field
            case 'textbox':
                $option['type'] = 'text';
                auxin_error( sprintf( __( '<strong>%s</strong> control type is deprecated. Use <strong>%s</strong> instead!', 'phlox' ), 'textbox', $option['type'] ) );
            break;

            // Color field
            case 'color-alpha':
                $option['type'] = 'color';
            break;

            // WordPress editor field
            case 'wp-editor':
                $option['type'] = 'editor';
                auxin_error( sprintf( __( '<strong>%s</strong> control type is deprecated. Use <strong>%s</strong> instead!', 'phlox' ), 'wp-editor', $option['type'] ) );
            break;

            // Select/dropdown field
            case 'dropdown':
                $option['type'] = 'select';
                auxin_error( sprintf( __( '<strong>%s</strong> control type is deprecated. Use <strong>%s</strong> instead!', 'phlox' ), 'dropdown', $option['type'] ) );
            break;

            // Visual select field
            case 'vselect':
                $option['type'] = 'radio-image';
                auxin_error( sprintf( __( '<strong>%s</strong> control type is deprecated. Use <strong>%s</strong> instead!', 'phlox' ), 'vselect', $option['type'] ) );
            break;

            // Range/slider element
            case 'range':
                $option['type'] = 'slider';
            break;

            case 'attachment':
                $option['type'] = 'images';
            break;

        }// end switch

        // convert '-' in control names to have better method names
        $option['type'] = str_replace( '-', '_', $option['type'] );

        if( ! method_exists( $this, "get_field_{$option['type']}" ) ){
            $option['type'] = 'default';
        }

        $output = apply_filters( 'auxin_option_panel_get_output_field', '', $option['type'], $option );
        if( ! empty( $output ) ){
            return $output;
        }

        $this->collect_field_dependency_script( $option );
        $output = call_user_func( array( $this, "get_field_{$option['type']}" ), $option );

        return $output;
    }


    /**
     * Add generated dependency script of this field to dependency_list
     *
     * @param  array $option  An array of info for field
     * @return void
     */
    function collect_field_dependency_script( $option ){

        $field_dependencies = $this->get_field_dependency_script( $option );

        if( $field_dependencies ){
            $this->dependency_list[ $option['id'] ] = $field_dependencies;
        }
    }

    /**
     * Process field dependencies and generate required JavaScript code
     *
     * @param  array $option  An array of info for field
     * @return string         JavaScript code for dependency management of current field
     */
    protected function get_field_dependency_script( $option ){

        $field_dependencies = array();

        if( isset( $option['dependency'] ) && ! empty( $option['dependency'] ) ){
            $dependencies = (array) $option['dependency'];

            foreach ( $dependencies as $depend_id => $depend ) {

                if( 'relation' === $depend_id ) {
                    $field_dependencies[ $depend_id ] = $depend;
                    continue;
                }

                if( ! isset( $depend['id'] ) || ! ( isset( $depend['value'] ) && ! empty( $depend['value'] ) ) ){ continue; }

                $depend['value'] = (array)$depend['value'];

                // if we had the same dependency id, combine corresponding values in an array
                if( isset( $field_dependencies[ $depend['id'] ]['value'] ) ){
                    $depend['value'] = wp_parse_args( $depend['value'] , $field_dependencies[ $depend['id'] ]['value'] );
                }
                // make sure there is no duplication in values array
                $field_dependencies[ $depend['id'] ]['value'] = array_unique( $depend['value'] );

                // if the operator was not defined or was defined as '=' by mistake
                $field_dependencies[ $depend['id'] ]['operator'] = isset( $depend['operator'] ) && ( '=' !== $depend['operator'] )  ? $depend['operator'] : '==';
            }

        }

        return $field_dependencies;
    }

}
