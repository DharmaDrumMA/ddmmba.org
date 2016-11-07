<?php
/**
 * Auxin Customize Control Class
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

class Auxin_Customize_Code_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_code';


    public $mode = 'javascript';


    public $button_labels = array();




    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );

        $this->button_labels = wp_parse_args( $this->button_labels, array(
            'description'  => __( 'The description', 'phlox' ),
            'label'        => __( 'Submit', 'phlox' )
        ));

        add_action( 'customize_preview_init' , array( $this, 'custom_script' ) );
    }

    public function custom_script(){
        if( 'javascript' !== $this->mode ){
            return;
        }

        wp_enqueue_script( 'customize-preview' );

        ob_start();
        ?>
        ;( function( $ ) {

            wp.customize( '<?php echo $this->setting->id; ?>', function( value ) {
                value.bind( function( to ) {
                    var $body  = $( 'body' ),
                    dom_id = '<?php echo $this->setting->id; ?>_script';
                    $body.find( '#' + dom_id ).remove();
                    $body.append( '<' + 'script id=\"'+ dom_id +'\" >try{ ' + to + ' } catch(ex) { console.error( "Custom JS:", ex.message ); }</script' + '>' ).find( '#' + dom_id );
                });
            });

        } )( jQuery );
        <?php
        $js = ob_get_clean();

        wp_add_inline_script( 'customize-preview', $js, 'after' );
    }

    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
            // editoe mode
            $editor_mode = ! empty( $this->mode ) ? $this->mode : 'javascript';
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <textarea id="<?php echo $this->setting->id; ?>" class="code_editor" rows="5" <?php $this->link(); ?> placeholder="<?php esc_attr_e( $this->setting->default ); ?>"
            data-code-editor="<?php echo $editor_mode; ?>" ><?php echo stripslashes( $this->value() ); ?></textarea>

            <?php if( 'javascript' == $this->mode && $this->button_labels['label'] ){ ?>
            <button class="<?php echo $this->setting->id; ?>-submit button button-primary"><?php echo $this->button_labels['label']; ?></button>
            <?php } ?>
        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Typography Control class.
 */
class Auxin_Customize_Typography_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_typography';




     public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );

        add_action( 'customize_preview_init' , array( $this, 'live_google_font_loading_script' ) );
    }


    /**
     * Adds javascript for preview on changes for each control
     */
    public function live_google_font_loading_script(){
        wp_enqueue_script( 'customize-preview' );

        ob_start();
        ?>
        // will trigger on changes for all controls
        ;( function( $ ) {
            wp.customize( '<?php echo $this->setting->id; ?>', function( value ) {
                value.bind( function( to ) {
                    var components = to.match("_gof_(.*):");
                    if( components.length > 1 ){
                        var face = components[1];
                        face = face.split(' ').join('+'); // convert spaces to "+" char

                        var google_font_url = '//fonts.googleapis.com/css?family='+ face +
                                              ':400,900italic,900,800italic,800,700italic,700,600italic,600,500italic,500,400italic,300italic,300,200italic,200,100italic,100';

                        var $body  = $( 'body' ),
                        dom_id = '<?php echo $this->setting->id; ?>_font';
                        $body.find( '#' + dom_id ).remove();
                        $body.append( '<link rel=\"stylesheet\" id=\"' + dom_id + '\" href=\"' + google_font_url + '\" type=\"text/css\" />' );
                    }
                });
            });
        } )( jQuery );
        <?php
        $js = ob_get_clean();

        wp_add_inline_script( 'customize-preview', $js, 'after' );
    }


    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif;


            $fields_output     = '';
            $typography_stored = array();
            $raw_options       = get_option( THEME_ID.'_options'  );

            // Font face and thickness

            // Get default value for font info
            if( ! $typo_info = auxin_get_option( $this->id ) ){ // get stored value if available
                 // otherwise use default value
                $typo_info = isset( $this->default ) ? $this->default : '';
            }

            // temporary fix for compatibility with old stored data. will deprecated in 1.3
            if( isset( $typo_info['font'] ) ){
                $typo_info = $typo_info['font'];
            }

            $fields_output .= '<div class="typo_fields_wrapper typo_font_wrapper" >';
            $fields_output .= '<input type="text" class="axi-font-field" name="'.$this->id.'" id="'. $this->id.'" ' . $this->get_link() . ' value="'.$typo_info.'"  />';
            $fields_output .= '</div>';

            $fields_output .= "</label><hr />";

        echo $fields_output;
    }

}


/**
 * Customize Radio_Image Control class.
 */
class Auxin_Customize_Radio_Image_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_radio_image';



    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <select class="visual-select-wrapper" <?php $this->link(); ?>>
                <?php
                foreach ( $this->choices as $choice_id => $choice_info ){

                    $data_class  = isset( $choice_info['css_class'] ) && ! empty( $choice_info['css_class'] ) ? 'data-class="'. $choice_info['css_class'].'"' : '';
                    $data_symbol = empty( $data_class ) && isset( $choice_info['image'] ) && ! empty( $choice_info['image'] ) ? 'data-symbol="'. $choice_info['image'].'"' : '';

                    if( isset( $choice_info['presets'] ) && ! empty( $choice_info['presets'] ) ){
                        // $presets[ $choice_id ] = $choice_info['presets'];
                    }
                    echo sprintf( '<option value="%s" %s %s %s>%s</option>', $choice_id, selected( $this->value(), $choice_id, false ) , $data_symbol, $data_class, $choice_info['label'] );}
                ?>
            </select>
        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Icon Control class.
 */
class Auxin_Customize_Icon_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_icon';


    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
        $font_icons = Auxin()->font_icons->get_icons_list('fontastic');
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <select <?php $this->link(); ?> class="meta-select aux-fonticonpicker">
                <?php
                echo '<option value="">' . __('Choose ..', 'phlox') . '</option>';

                if( is_array( $font_icons ) ){
                    foreach ( $font_icons as $icon ) {
                        $icon_id = trim( $icon->classname, '.' );
                        echo '<option value="'. $icon_id .'" '. selected( $this->value(), $icon_id, false ) .' >'. $icon->name . '</option>';
                    }
                }
                ?>
            </select>
        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Textarea Control class.
 */
class Auxin_Customize_Textarea_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_textarea';

    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
            <textarea rows="5" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        </label>

        <hr />
    <?php
    }
}


/**
 * Customize Editor Control class.
 */
class Auxin_Customize_Editor_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_editor';

    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <?php wp_editor( stripslashes( $this->value() ), $this->id, array( 'media_buttons' => false ) ); ?>
        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Select2 Multiple Control class.
 */
class Auxin_Customize_Select2_Multiple_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_select2_multiple';



    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <select class="aux-orig-select2 aux-admin-select2 aux-select2-multiple" multiple="multiple" <?php $this->link(); ?>>
                <?php
                foreach ( $this->choices as $value => $label )
                    echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
                ?>
            </select>
        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Select2 Control class.
 */
class Auxin_Customize_Select2_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_select2';



    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <select class="aux-orig-select2 aux-admin-select2 aux-select2-single" <?php $this->link(); ?>>
                <?php
                foreach ( $this->choices as $value => $label )
                    echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
                ?>
            </select>
        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Select Control class.
 */
class Auxin_Customize_Select_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_select';



    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <select <?php $this->link(); ?>>
                <?php
                foreach ( $this->choices as $value => $label )
                    echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
                ?>
            </select>
        </label>
        <hr />
    <?php
    }
}



/**
 * Customize Media Control class.
 */
class Auxin_Customize_Media_Control extends Auxin_Customize_Control {

    /**
     * Control type
     */
    public $type = 'auxin_media';

    /**
     * Media control mime type.
     */
    public $mime_type = 'image';

    /**
     * Max number of attachments
     */
    public $limit = 9999;

    /**
     * Allow multiple uploads
     */
    public $multiple = true;


    /**
     * Button labels.
     *
     * @var array
     */
    public $button_labels = array();



    /**
     * Constructor.
     *
     * @param WP_Customize_Manager $manager Customizer bootstrap instance.
     * @param string               $id      Control ID.
     * @param array                $args    Optional. Arguments to override class property defaults.
     */
    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );

        $this->button_labels = wp_parse_args( $this->button_labels, array(
            'add'          => __( 'Add File', 'phlox' ),
            'change'       => __( 'Change File', 'phlox' ),
            'submit'       => __( 'Select File', 'phlox' ),
            'remove'       => __( 'Remove', 'phlox' ),
            'frame_title'  => __( 'Select File', 'phlox' ),
            'frame_button' => __( 'Choose File', 'phlox' )
        ));

    }


    /**
     * Enqueue control related scripts/styles.
     *
     */
    public function enqueue() {
        wp_enqueue_media();
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since 3.4.0
     */
    public function to_json() {
        parent::to_json();

        $this->json['settings'] = array();
        foreach ( $this->settings as $key => $setting ) {
            $this->json['settings'][ $key ] = $setting->id;
        }

        $value = $this->value();

        $this->json['type']           = $this->type;
        $this->json['priority']       = $this->priority;
        $this->json['active']         = $this->active();
        $this->json['section']        = $this->section;
        $this->json['content']        = $this->get_content();
        $this->json['label']          = $this->label;
        $this->json['description']    = $this->description;
        $this->json['instanceNumber'] = $this->instance_number;

        $this->json['mime_type']      = $this->mime_type;
        $this->json['button_labels']  = $this->button_labels;

        $this->json['canUpload']      = current_user_can( 'upload_files' );
        $this->json['value']          = $value;
        $this->json['attachments']    = array('-4' => '' );


        if ( $value ) {
            if( $att_ids = explode( ',', $value ) ){
                $this->json['attachments'] += auxin_get_the_resized_attachment_src( (array) $att_ids, 80, 80, true );
            }
        }

    }


    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
        <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>

            <div class="axi-attachmedia-wrapper" >

                <input type="text" class="white" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?>
                                   data-media-type="<?php echo $this->mime_type; ?>" data-limit="<?php echo $this->limit; ?>" data-multiple="<?php echo $this->multiple; ?>"
                                   data-add-to-list="<?php echo $this->button_labels['add']; ?>"
                                   data-uploader-submit="<?php echo $this->button_labels['submit']; ?>"
                                   data-uploader-title="<?php echo $this->button_labels['frame_title']; ?>"
                                   />
            <?php
                // Store attachment src for avertaAttachMedia field
                if( $att_ids = explode( ',', $this->value() ) ){
                    $this->manager->attach_ids_list += auxin_get_the_resized_attachment_src( $att_ids, 80, 80, true );
                }
            ?>
            </div>

        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Switch Control class.
 */
class Auxin_Customize_Switch_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_switch';


    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
    ?>
        <label>
        <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
            <input type="checkbox" class="aux_switch" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />

        </label>
        <hr />
    <?php
    }
}


/**
 * Customize Color Control class.
 */
class Auxin_Customize_Color_Control extends Auxin_Customize_Control {
    /**
     * @access public
     * @var string
     */
    public $type = 'auxin_color';

    /**
     * @access public
     * @var array
     */
    public $statuses;

    /**
     * Constructor.
     *
     * @param WP_Customize_Manager $manager Customizer bootstrap instance.
     * @param string               $id      Control ID.
     * @param array                $args    Optional. Arguments to override class property defaults.
     */
    public function __construct( $manager, $id, $args = array() ) {
        $this->statuses = '';
        parent::__construct( $manager, $id, $args );
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     */
    public function to_json() {
        parent::to_json();
        $this->json['statuses'] = $this->statuses;
        $this->json['defaultValue'] = $this->setting->default;
    }

    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
        ?>
        <label>
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif;
            if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo $this->description; ?></span>
            <?php endif; ?>
            <div class="mini-color-wrapper">
                <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
            </div>
        </label>
        <hr />
        <?php
    }
}


/**
 * Customize Base Control class.
 */
class Auxin_Customize_Input_Control extends Auxin_Customize_Control {
    /**
     *
     */
    public $type = 'auxin_base';

    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     */
    public function render_content() {
        $real_type  = $this->type;

        if( isset( $this->input_attrs['type'] ) ){
            $this->type = $this->input_attrs['type'];
        }

        parent::render_content();

        $this->type = $real_type;
        echo "<hr />";
    }
}



/**
 * Customize Base Control class.
 */
class Auxin_Customize_Control extends WP_Customize_Control {

    // The control dependencies
    protected $dependency;



    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );

        if( isset( $this->dependency['relation'] ) ){
            $this->dependency[] = array( 'relation' => $this->dependency['relation'] );
            unset( $this->dependency['relation'] );
        } else {
            $this->dependency[] = array( 'relation' => 'and' );
        }

        add_action( 'customize_preview_init' , array( $this, 'preview_script' ) );
    }


    /**
     * Adds javascript for preview on changes for each control
     */
    public function preview_script(){
        wp_enqueue_script( 'customize-preview' );

        ob_start();
        ?>
        // will trigger on changes for all controls
        ;( function( $ ) {
            wp.customize( '<?php echo $this->setting->id; ?>', function( value ) {
                value.bind( function( to ) {
                    $(window).trigger('resize');
                });
            });
        } )( jQuery );
        <?php
        $js = ob_get_clean();

        wp_add_inline_script( 'customize-preview', $js, 'after' );
    }


    /**
     * Enqueue scripts/styles for the color picker.
     */
    public function enqueue() {
        wp_enqueue_script('wp-util');
        wp_enqueue_script('auxin_plugins');
        wp_enqueue_script('auxin_script');
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     */
    public function to_json() {
        parent::to_json();

        $field_dependencies = array();

        if( ! empty( $this->dependency ) ){
            $dependencies = (array) $this->dependency;

            foreach ( $dependencies as $depend_id => $depend ) {

                if( 'relation' === $depend_id ) {
                    //$field_dependencies[] = array( $depend_id => $depend );
                    continue;
                }

                if( ! isset( $depend['id'] ) || ! ( isset( $depend['value'] ) && ! empty( $depend['value'] ) ) ){ continue; }

                // make sure there is no duplication in values array
                $depend['value'] = array_unique( (array) $depend['value'] );

                // if the operator was not defined or was defined as '=' by mistake
                $depend['operator'] = isset( $depend['operator'] ) && ( '=' !== $depend['operator'] )  ? $depend['operator'] : '==';

                $field_dependencies[ $depend_id ] = $depend;
            }
            $field_dependencies[ $depend_id ] = $depend;

        }


        $this->json['dependencies'] = $field_dependencies;
    }

}
