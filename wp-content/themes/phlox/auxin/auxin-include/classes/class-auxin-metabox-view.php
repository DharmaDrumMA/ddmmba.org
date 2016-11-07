<?php
/**
 * Class for displayng and saving fields for a custom metabox
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}



class Auxin_Metabox_View extends Auxin_Metabox_Model{

    public $dependency_list   = array();
    public $section_row_start = '<div class="%s" %s>';
    public $section_row_end   = '</div>';

    public $attach_ids_list   = array();

    public $presets_list      = array();



    /**
     * Show Metabox Fields in Edit Page
     *
     * @return void
     */
    public function display_meta_box() {

        $this->fields = $this->get_filtered_fields();

        wp_nonce_field( $this->id , $this->id.'-nonce' );

        $layout_mode = $this->context == 'side'?'min_mode':"";

        echo '<div class="av3_container" style="margin:10px 0;" >';
            printf('<div class="form-table meta-box axi-metabox-container %s">', $layout_mode);

            echo $this->get_fields_output( $this->fields );

            echo   '</div>',
        '</div>';

        $this->print_dependencies();
    }

    /**
     * Prints or returns markup for metafields
     *
     * @param  array   $fields List of all fields
     * @param  boolean $return Whether to print or return the markup. false means print the markup
     * @return string|boolean          Returns the markup if @$return is set to true otherwise boolean on success
     */
    public function get_fields_output( $fields ){

        global $post;

        $output = '';

        // Collect attachment id and srcs for attachMedia field
        $this->attach_ids_list   = array( '-1' => '' ); // this default value forces json_decode to always geberate js object, not array

        foreach ( $fields as $field ) {
            $output .= $this->get_field_output( $field );
        }

        // Add attaxhment thumbnails to srcmap js array
        $output .= sprintf( "\n<script>window.auxin = window.auxin || {}; window.auxin.attachmedia = window.auxin.attachmedia || {};" );
        $output .= sprintf( "var srcmap = %s; for( var att_id in srcmap ) { auxin.attachmedia[ att_id ] = srcmap[ att_id ]; }</script>\n", json_encode( array_unique( $this->attach_ids_list ) ) );

        return $output;
    }

    /**
     * Generates subfields for major field types (background, ..)
     *
     * @param  array $fields  An array of fields
     * @return array          An array of fields containing new sub fields
     */
    function sanitize_fields( $fields ){
        foreach ( $fields as $field_key => $field ) {

            if( 'background' == $field['type'] ){
                $child_fields = $this->get_background_child_fields( $field );
                unset( $fields[ $field_key ] );
                $fields = array_merge( $fields, $child_fields );
            }
        }
        return $fields;
    }


    public function get_field_output( $field ){

            // dependency manager needs id for all elements, so if sep element doesn't have id, generate a unique id
            if( in_array( $field['type'], array('sep') ) ){
                if( ! isset( $field['id'] ) ){
                    $field['id'] = uniqid( $field['type'] );
                }
            }

            $this->watch_for_field_dependencies( $field );

            switch ( $field['type'] ) {

                //If Textbox
                case 'textbox':

                    $field['type'] = 'text';
                    break;

                //If Colorpicker
                case 'colorpicker':

                    $field['type'] = 'color';
                    break;

                //If WordPress Editor
                case 'wpeditor':

                    $field['type'] = 'editor';
                    break;

                //If select/dropdown
                case 'dropdown':

                    $field['type'] = 'select';
                    break;

                //If visual select
                case 'visualselect':
                case 'vselect':

                    $field['type'] = 'radio-image';
                    break;

                //If checkbox field
                case 'checkbox':

                    $field['type'] = 'switch';
                    break;
            }


            // convert '-' in control names to have better method names
            $field['type'] = str_replace( '-', '_', $field['type'] );

            if( ! method_exists( $this, "get_field_{$field['type']}" ) ){
                $field['type'] = 'text';
            }

            $output_filter = apply_filters( 'auxin_get_meta_field_output', '', $field['type'], $field );
            if( ! empty( $output_filter ) ){
                return  $output_filter;
            }

            return call_user_func( array( $this, "get_field_{$field['type']}" ), $field );
    }



    public function get_field_url_caption( $field ){
        return '';

        $cap_meta = isset( $field['id'] ) ? get_post_meta( $post->ID, $field['id']."_caption", true ) : "";

        echo $this->section_row_start,
                '<label >' .$field_label. '</label>',
                '<div class="section-row-right" >',

                    '<fieldset class="uploader" >',

                        '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $field['stored_value'] ? $field['stored_value'] : $field['std'], '" />',

                        '<input type="button" class="white" value="', $field['upload_std'] ,'" style="margin-right:5px;" />';

            if( ! empty( $field['remove_std'] ) ){
                   echo '<input type="button" class="white alert" value="', $field['remove_std'] ,'" />';
            }
            echo        '<div class="imgHolder"><strong title="'.__("Remove image", 'phlox' ).'" class="close">X</strong>',
                        '<img alt src="" /></div>',

                    '</fieldset>',
                '</div>',
                '<label >', ( isset( $field['caption_label'] ) ? $field['caption_label']: '' ), '</label>',
                '<div class="section-row-right" >',

                    '<fieldset class="uploader" >',

                        '<input type="text" name="', $field['id']."_caption", '" id="', $field['id']."_caption", '" value="', $cap_meta ? $cap_meta : "", '" style="width:80%;" />',

                        '<p >'. $field['description'].'</p>',

                    '</fieldset>',
                '</div>',
            $this->section_row_end;
    }



    public function get_field_upload( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<fieldset class="uploader" >'.

                        sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s" />' , $field['id'], $field['value'] ).

                        '<input type="button" class="blue medium" value="'. $field['upload_std'] .'" style="margin-right:5px;" />';

            if( ! empty( $field['remove_std'] ) ){
                   $output .= '<input type="button" class="grey medium" value="'. $field['remove_std'] .'" />';
            }
            $output .=  '<div class="imgHolder">'.
                        '<div class="img-placeholder"></div>'.
                        '<strong title="' . __( "Remove image", 'phlox' ) . '" class="close">X</strong>'.
                        '<img alt src="" /></div>'.
                        '<p >'. $field['description'].'</p>'.

                    '</fieldset>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_sep( $field ){
        $desc = empty( $field['description'] ) ? '' : '<span>'.$field['description'].'</span>';
        $field_label = isset( $field['title'] ) ? $field['title'] : '';

        return '<div class="section-legend field-row" id="'.$field['id'].'"  ><p>' .$field_label. '</p>'. $desc .'</div>';
    }


    public function get_field_icon( $field ){

        $font_icons = Auxin()->font_icons->get_icons_list('fontastic');

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    sprintf( '<select name="%1$s" id="%1$s" class="aux-fonticonpicker aux-select" data-value="%2$s" >', $field['id'], $field['stored_value'] ).
                        '<option value="">' . __('Choose ..', 'phlox' ) . '</option>';
                        if( is_array( $font_icons ) ){
                            foreach ( $font_icons as $icon ) {
                                $icon_id = trim( $icon->classname, '.' );
                                $output .= '<option value="'. $icon_id .'" '. selected( $field['stored_value'], $icon_id, false ) .' >'. $icon->name . '</option>';
                            }
                        }
            $output .= '</select>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_switch( $field ){

        $this->set_sanitize_field_data( $field );

        if( '1' == $field['stored_value'] || 'on' == $field['stored_value'] ){
            $active_attr = ' checked="checked"';
            $field['stored_value'] = '1';
        } elseif( '0' == $field['stored_value'] ){
            $active_attr = '';
        } elseif( isset( $field['default'] ) && auxin_is_true( $field['default'] ) ){
            $active_attr = ' checked="checked"';
        } else {
            $active_attr = '';
        }

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<label><input type="checkbox" class="aux_switch" id="'.$field['id'].'" name="'.$field['id'].'" '.$active_attr.' ></label>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_radio_image( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    sprintf( '<select name="%1$s" id="%1$s" class="meta-select visual-select-wrapper" >', $field['id'] );

            $presets = array();

            foreach ( $field['choices'] as $id => $option_info ) {

                /* Which options should be selected */
                $active_attr = ( $id == $field['value'] ) ? 'selected' : '';

                $data_class  = isset( $option_info['css_class'] ) && ! empty( $option_info['css_class'] ) ? 'data-class="'. $option_info['css_class'].'"' : '';
                $data_symbol = empty( $data_class ) && isset( $option_info['image'] ) && ! empty( $option_info['image'] ) ? 'data-symbol="'. $option_info['image'].'"' : '';

                $output     .= sprintf( '<option value="%s" %s %s %s>%s</option>', $id, $active_attr, $data_symbol, $data_class, $option_info['label'] );

                if( isset( $option_info['presets'] ) && ! empty( $option_info['presets'] ) ){
                    $presets[ $id ] = $option_info['presets'];
                }

            }

            if( ! empty( $presets ) ){
                $this->presets_list[ $field['id'] ] = $presets;
            }

            $output .= '</select>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_select( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    sprintf( '<select name="%1$s" id="%1$s" class="meta-select" data-value="%2$s" style="width:150px" >', $field['id'], $field['value'] );
            foreach ( $field['choices'] as $key => $value ) {
                $output .= '<option value="'. $key .'" '. selected( $field['stored_value'], $key, false ) .' >'. $value . '</option>';
            }

            $output .= '</select>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_select2( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    sprintf( '<select name="%1$s" id="%1$s" class="aux-orig-select2 aux-admin-select2 aux-select2-single" data-value="%2$s" style="width:150px" >', $field['id'], $field['value'] );

            foreach ( $field['choices'] as $key => $value ) {
                $output .= '<option value="'. $key .'" '. selected( $field['stored_value'], $key, false ) .' >'. $value . '</option>';
            }

            $output .= '</select>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_code( $field ){

        $this->set_sanitize_field_data( $field );

        /* editoe mode */
        $editor_mode = isset( $field['mode'] ) && ! empty($field['mode']) ? $field['mode'] : 'javascript'; // javascript, css,

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<textarea class="code_editor" name="'.$field['id'].'" id="'.$field['id'].'" placeholder="'. esc_attr( $field['default'] ) .'" '.
                    'data-code-editor="'. $editor_mode .'" >'.stripslashes( $field['value'] ).'</textarea>'.
                    '<p>'. $field['description'].'</p>'.
                '</div>'.
            $this->section_row_end;

        return $output;
    }


    public function get_field_editor( $field ){

        $this->set_sanitize_field_data( $field );

        ob_start();
        wp_editor( $field['value'], $field['id'], array( 'media_buttons' => false ) );
        $editor = ob_get_clean();

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    $editor.
                    '<p>'. $field['description'].'</p>'.
                '</div>'.
            $this->section_row_end;

        return $output;
    }


    public function get_field_color( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<div class="mini-color-wrapper" >'.
                        sprintf( '<input type="text" class="colorpickerField" name="%1$s" id="%1$s" value="%2$s" />', $field['id'], $field['value'] ).
                    '</div>'.
                    '<p>'. $field['description'].'</p>'.
                '</div>'.
            $this->section_row_end;

        return $output;
    }


    public function get_field_text( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s" />', $field['id'], $field['value'] ).
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_textarea( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    sprintf( '<textarea name="%1$s" id="%1$s">%2$s', $field['id'], esc_textarea( $field['value'] ) ). '</textarea>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        return $output;
    }


    public function get_field_image( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<div class="axi-attachmedia-wrapper" >'.
                        sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s"
                                 data-media-type="image" data-limit="1" data-multiple="0"
                                 data-add-to-list="%3$s"
                                 data-uploader-submit="%4$s"
                                 data-uploader-title="%5$s"
                                 >',
                                 $field['id'], $field['value'],
                                 __('Add Image', 'phlox' ), __('Add Image', 'phlox' ), __('Select Image', 'phlox' )
                                ).
                    '</div>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        /* Store attachment src for avertaAttachMedia field */
        if( $att_ids = explode( ',', $field['stored_value'] ) ){
            $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
        }

        return $output;
    }

    public function get_field_images( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<div class="axi-attachmedia-wrapper" >'.
                        sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s"
                                 data-media-type="image" data-limit="999" data-multiple="true"
                                 data-add-to-list="%3$s"
                                 data-uploader-submit="%4$s"
                                 data-uploader-title="%5$s"
                                 >',
                                 $field['id'], $field['value'],
                                 __('Add Image(s)', 'phlox' ), __('Add Image', 'phlox' ), __('Select Image', 'phlox' )
                                ).
                    '</div>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        /* Store attachment src for avertaAttachMedia field */
        if( $att_ids = explode( ',', $field['stored_value'] ) ){
            $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
        }

        return $output;
    }


    public function get_field_audio( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<div class="axi-attachmedia-wrapper" >'.
                        sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s"
                                 data-media-type="audio" data-limit="999" data-multiple="true"
                                 data-add-to-list="%3$s"
                                 data-uploader-submit="%4$s"
                                 data-uploader-title="%5$s"
                                 >',
                                 $field['id'], $field['value'],
                                 __('Add Audio', 'phlox' ), __('Add Audio', 'phlox' ), __('Select Audio', 'phlox' )
                                ).
                    '</div>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        /* Store attachment src for avertaAttachMedia field */
        if( $att_ids = explode( ',', $field['stored_value'] ) ){
            $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
        }

        return $output;
    }


    public function get_field_video( $field ){

        $this->set_sanitize_field_data( $field );

        $output = sprintf( $this->section_row_start, $field['class_names'], $field['attributes'] ).
                '<label >' .$field['title']. '</label>'.
                '<div class="section-row-right" >'.
                    '<div class="axi-attachmedia-wrapper" >'.
                        sprintf( '<input type="text" name="%1$s" id="%1$s" value="%2$s"
                                 data-media-type="video" data-limit="999" data-multiple="true"
                                 data-add-to-list="%3$s"
                                 data-uploader-submit="%4$s"
                                 data-uploader-title="%5$s"
                                 >',
                                 $field['id'], $field['value'],
                                 __('Add Video', 'phlox' ), __('Add Video', 'phlox' ), __('Select Video', 'phlox' )
                                ).
                    '</div>'.
                    '<p >'. $field['description'].'</p>'.
                '</div>'.
             $this->section_row_end;

        /* Store attachment src for avertaAttachMedia field */
        if( $att_ids = explode( ',', $field['stored_value'] ) ){
            $this->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
        }

        return $output;
    }


    public function set_sanitize_field_data( &$field ){
        global $post;

        $field['class_names']  = $this->get_field_wrapper_class_names( $field );
        $field['attributes']   = $this->get_field_wrapper_attributes( $field  );

        $field['title']        = isset( $field['title']   ) ? $field['title']   : '';
        $field['description']  = isset( $field['description']    ) ? $field['description']    : '';
        $field['default']      = isset( $field['default'] ) ? $field['default'] : '';
        // get current post meta data
        $field['stored_value'] = isset( $field['id']    ) ? get_post_meta( $post->ID, $field['id'], true ) : '';
        $field['value']        = $field['stored_value'] ? $field['stored_value'] : $field['default'];
    }


    public function get_background_child_fields( $field ){
        $bg_fields = array();

        $bg_fields['color'] = array(
            'title'         => __( 'Background Color', 'phlox' ),
            'description'   => __( 'Specifies the color of website background', 'phlox' ),
            'default'       => '',
            'type'          => 'color'
        );

        $bg_fields['repeat'] = array(
            'title'         =>  __( 'Background repeat', 'phlox' ),
            'description'   =>  __( 'Specifies how background image repeats', 'phlox' ),
            'type'          =>  'radio-image',
            'choices'       =>  array(
                'no-repeat' => array(
                    'label'     => __('No repeat', 'phlox' ),
                    'css_class' => 'axiAdminIcon-none',
                    'image'     => 'img.png'
                ),
                'repeat' => array(
                    'label'     => __('Repeat horizontally and vertically', 'phlox' ),
                    'css_class' => 'axiAdminIcon-repeat-xy',
                    'image'     => 'img.png'
                ),
                'repeat-x' => array(
                    'label'     => __('Repeat horizontally', 'phlox' ),
                    'css_class' => 'axiAdminIcon-repeat-x',
                    'image'     => 'img.png'
                ),
                'repeat-y' => array(
                    'label'     => __('Repeat vertically', 'phlox' ),
                    'css_class' => 'axiAdminIcon-repeat-y',
                    'image'     => 'img.png'
                )
            ),
            'default'   =>  'repeat'
        );

        $bg_fields['attach'] = array(
            'title'         =>  __('Background attachment', 'phlox' ),
            'description'   =>  __('Specifies the background is fixed or scrollable as user scrolls the document', 'phlox' ),
            'choices'       => array(
                'scroll' => array(
                    'label'     => __('Scroll', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bg-attachment-scroll',
                    'image'     => 'img.png'
                ),
                'fixed' => array(
                    'label'     => __('Fixed', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bg-attachment-fixed',
                    'image'     => 'img.png'
                ),
            ),
            'default'   =>  'scroll',
            'type'      =>  'radio-image'
        );

        $bg_fields['position'] = array(
            'title'         =>  __('Background position', 'phlox' ),
            'description'   =>  __('Specifies background image alignment', 'phlox' ),
            'choices'       => array(
                'left top' => array(
                    'label'     => __('Left top', 'phlox' ),
                    'css_class' => 'axiAdminIcon-top-left'
                ),
                'center top' => array(
                    'label'     => __('Center top', 'phlox' ),
                    'css_class' => 'axiAdminIcon-top-center'
                ),
                'right top' => array(
                    'label'     => __('Right top', 'phlox' ),
                    'css_class' => 'axiAdminIcon-top-right'
                ),

                'left center' => array(
                    'label'     => __('Left center', 'phlox' ),
                    'css_class' => 'axiAdminIcon-center-left'
                ),
                'center center' => array(
                    'label'     => __('Center center', 'phlox' ),
                    'css_class' => 'axiAdminIcon-center-center'
                ),
                'right center' => array(
                    'label'     => __('Right center', 'phlox' ),
                    'css_class' => 'axiAdminIcon-center-right'
                ),

                'left bottom' => array(
                    'label'     => __('Left bottom', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bottom-left'
                ),
                'center bottom' => array(
                    'label'     => __('Center bottom', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bottom-center'
                ),
                'right bottom' => array(
                    'label'     => __('Right bottom', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bottom-right'
                )
            ),
            'default'   => 'left top',
            'type'      => 'radio-image'
        );

        $bg_fields['size'] = array(
            'title'         =>  __('Background size', 'phlox' ),
            'description'   =>  __('Specifies the background size.', 'phlox' ),
            'choices'       => array(
                'auto' => array(
                    'label'     => __('Auto', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bg-size-1',
                    'image'     => 'img.png'
                ),
                'contain' => array(
                    'label'     => __('Contain', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bg-size-2'
                ),
                'cover' => array(
                    'label'     => __('Cover', 'phlox' ),
                    'css_class' => 'axiAdminIcon-bg-size-3'
                )
            ),
            'default'   =>  'auto',
            'type'      =>  'radio-image'
        );

        $bg_fields['pattern'] = array(
            'title'         => __('Background pattern', 'phlox' ),
            'description'   => __('Here you can select one of these patterns as site image background.', 'phlox' ),
            'choices'       => auxin_get_background_patterns( array( '' => array( 'label' =>__('None', 'phlox'), 'image' => AUX_URL . 'images/visual-select/none-pattern.svg' ) ), 'before' ),
            'default'   => '',
            'type'      => 'radio-image'
        );

        $bg_fields['image'] = array(
            'title'         =>  __('Background image', 'phlox' ),
            'description'   =>  __('You can upload custom image for site background', 'phlox' ).
                              '<br/>'.__('Note: if you set custom image, default image backgrounds will be ignored.', 'phlox' ),
            'default'       =>  '',
            'type'          =>  'image'
        );

         $bg_fields['video_mp4'] = array(
            'title'         =>  __('Background video MP4', 'phlox' ),
            'description'   =>  __('You can upload custom video for title background', 'phlox' ).
                              '<br/>'.__('Note: if you set custom image, default image backgrounds will be ignored.', 'phlox' ),
            'default'       =>  '',
            'type'          =>  'video'
        );

          $bg_fields['video_ogg'] = array(
            'title'         =>  __('Background video Ogg', 'phlox' ),
            'description'   =>  __('You can upload custom video for title background', 'phlox' ).
                              '<br/>'.__('Note: if you set custom image, default image backgrounds will be ignored.', 'phlox' ),
            'default'       =>  '',
            'type'          =>  'video'
        );

           $bg_fields['video_webm'] = array(
            'title'         =>  __('Background video WebM', 'phlox' ),
            'description'   =>  __('You can upload custom video for title background', 'phlox' ).
                              '<br/>'.__('Note: if you set custom image, default image backgrounds will be ignored.', 'phlox' ),
            'default'       =>  '',
            'type'          =>  'video'
        );

        $child_fields = array();
        // $fields_output = '';

        foreach ( $bg_fields as $bg_field_type => $bg_field ) {

            // if background field type was defined, generate the corresponding field
            if( isset( $field['default'][ $bg_field_type ] ) ){

                // set default value for field
                if( ! empty( $field['default'][ $bg_field_type ] ) ){
                    $bg_field['default'] = $field['default'][ $bg_field_type ];
                }
                // set title for field if it was defined
                if( isset( $field['choices'][ $bg_field_type ]['title'] ) ){
                    $bg_field['title'] = $field['choices'][ $bg_field_type ]['title'];
                }
                // set description for field if it was defined
                if( isset( $field['choices'][ $bg_field_type ]['description'] ) ){
                    $bg_field['description'] = $field['choices'][ $bg_field_type ]['description'];
                }
                // set dependency for field if it was defined
                if( isset( $field['choices'][ $bg_field_type ]['dependency'] ) ){
                    $bg_field['dependency'] = $field['choices'][ $bg_field_type ]['dependency'];
                // otherwise, set the common dependency
                } elseif ( isset( $field['dependency'] ) ) {
                    $bg_field['dependency'] = $field['dependency'];
                }

                // set id for each field and same section for all background fields
                $bg_field['id']      = $field['id'] .'_'. $bg_field_type;

                // set this field as a defined field too
                // $this->fields[] = $bg_field;

                $child_fields[] = $bg_field;
                // $fields_output .= $this->get_field_output( $bg_field );
            }
        }

        return $child_fields;
        // return $fields_output;
    }



    /**
     * Retrieves all class names for the wrapper of a field
     *
     * @param  array $field  The field info
     * @return string        class names for wrapper of the field
     */
    public function get_field_wrapper_class_names( $field ){
        $classes = array( 'section-row', 'field-row' );
        return join( ' ', apply_filters( 'auxin_meta_field_wrapper_classes', array_filter( $classes ), $field ) );
    }

    /**
     * Retrieves all attributes for the wrapper of a field
     *
     * @param  array $field  The field info
     * @return string        attributes for wrapper of the field
     */
    public function get_field_wrapper_attributes( $field ){
        $atts = apply_filters( 'auxin_meta_field_wrapper_attributes', array(), $field );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        return $attributes;
    }

    /**
     * Loop to collect dependency map of metafields
     *
     * @param  array  $field field options
     * @return void
     */
    public function watch_for_field_dependencies( $field = array() ){
        if( empty( $field ) ){
            return;
        }

        $field_dependencies = array();

        if( isset( $field['dependency'] ) && ! empty( $field['dependency'] ) ){
            $dependencies = (array) $field['dependency'];

            foreach ( $dependencies as $depend_id => $depend ) {

                if( 'relation' === $depend_id ) {
                    $field_dependencies[ $depend_id ] = $depend;
                    continue;
                }

                if( ! isset( $depend['id'] ) || ! ( isset( $depend['value'] ) && ! empty( $depend['value'] ) ) ){ continue; }

                $field_dependencies[ $depend['id'] ] = array( 'value' => (array)$depend['value'] );

                if( isset( $depend['callback'] ) ) {
                    $field_dependencies[ $depend['id'] ]['callback'] = $depend['callback'];
                }
            }

        }

        if( $field_dependencies ){
            $this->dependency_list[ $field['id'] ] = $field_dependencies;
        }
    }


    /**
     * Print metafield dependencies
     *
     * @return string  JSON string containing metafield dependencies
     */
    public function print_dependencies(){
        // echo js dependencies
        printf( '<script>auxinCreateNamespace("auxin.metabox.%1$s"); auxin.metabox.%1$s.dependencies = %2$s;', $this->id, json_encode( $this->dependency_list ) );
        printf( 'auxin.metabox.%1$s.presets = %2$s;</script>', $this->id, json_encode( $this->presets_list ) );
    }


    /**
     * Save all meta fields in this metaboxes
     *
     * @param  int $post_id The post id which the fields belong to that post.
     * @return boolean      Returns true on sucess and false or int on failure
     */
    public function save_meta_box( $post_id ) {
        global $post;

        // Verify the nonce before proceeding.
        if ( ! isset( $_POST[$this->id.'-nonce'] ) || ! wp_verify_nonce( $_POST[$this->id.'-nonce'], $this->id ) ){
            return $post_id;
        }

        // Get the post type object.
        $post_type = get_post_type_object( $post->post_type );

        // Check if the current user has permission to edit the post.
        if ( ! current_user_can( $post_type->cap->edit_post, $post->ID ) ){
            return $post->ID;
        }

        // check autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post->ID;
        }

        // sanitize the fields
        $this->fields = $this->sanitize_fields( $this->fields );

        foreach ( $this->fields as $field ) {
            if( $field['type'] == "sep" ) continue;

            $this->update_field( $field );
        }

        return true;
    }


    /**
     * Save value of a meta field
     *
     * @param  int $post_id The post id which the field belongs to that post.
     * @return boolean      Returns true on sucess and false or int on failure
     */
    protected function update_field( $field ){
        global $post;

        $old = get_post_meta( $post->ID, $field['id'], true );

        $new = isset( $_POST[ $field['id'] ] ) ? $_POST[ $field['id'] ] : '';

        $allow_save = ( $new && $new != $old ) || in_array( $field['type'], array( "upload", "url_caption", "checkbox", "switch" ) );

        if ( $allow_save ) {

            if( in_array( $field['type'], array( 'checkbox', 'switch' ) ) ) {
                $new = ( 'on' == $new || '1' == $new ) ? '1' : '0';
            }

            // if its attachment field save image caption too
            if( $field['type'] == "url_caption" ) {

                update_post_meta( $post->ID, $field['id']."_caption", $_POST[$field['id']."_caption"] );
                update_post_meta( $post->ID, $field['id'], auxin_get_the_relative_file_url( $new )    );

            // if its internal uploaded file, convert url to relative url
            } elseif( $field['type'] == "upload" ) {
                update_post_meta( $post->ID, $field['id'], auxin_get_the_relative_file_url( $new ) );

            // save other fields
            } else {
                update_post_meta( $post->ID, $field['id'], $new );
            }

        } elseif ( '' == $new && $old ) {
            delete_post_meta( $post->ID, $field['id'], $old );
        }

    }


}
