<?php
/**
 * Option panel model
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



/**
*   Manages default options for option panel
*/
class Auxin_Option_Panel_Data {

    /**
     * List of sections and options
     *
     * @var array
     */
    public $data = array();




    /**
     * Magic method to set value for properties
     *
     * @param   string    The property name
     * @param   string    The property value
     * @return  bool      True, if the value was set to property successfully, False on failure.
     */
    public function __set( $name, $value ){

        // set default options for option panel
        if( 'default' === $name ){
            $this->data['unserialized_defaults'] = maybe_unserialize( $value );
            $this->data['serialized_defaults'  ] = maybe_serialize  ( $value );
            return true;
        }

        $this->data[ $name ] = $value;

        return true;
    }


    /**
     * Magic method to get the value of accessible properties
     *
     * @param   string   The property name
     * @return  string  The value of property
     */
    public function __get( $name ){

        // get default options for option panel
        if( 'default' == $name && isset( $this->data['unserialized_defaults'] ) ){
            return $this->data['unserialized_defaults'];
        }

        // get auxin raw options
        if( 'raw_options' == $name ){
            if( empty( $this->data['raw_options'] ) ){
                $this->data['raw_options'] = get_option( THEME_ID.'_options' );
            }
            return $this->data['raw_options'];
        }

        // filter the default capability for sections and fields
        if( 'default_capability' == $name ){
            if( empty( $this->data['default_capability'] ) ){
                $this->data['default_capability'] = apply_filters( 'auxin_option_default_capability', 'edit_theme_options' );
            }
            return $this->data['default_capability'];
        }

        // get parsed auxin option ( the main options list in auxin )
        if( 'auxin_options' == $name ){
            if( empty( $this->data['auxin_options'] ) ){
                $this->data['auxin_options'] = wp_parse_args(
                    get_option( THEME_ID.'_formatted_options' , array() ),
                    $this->default_option_values
                );
            }
            return $this->data['auxin_options'];
        }

        // get the list of default values for options
        if( 'default_option_values' == $name ){
            if( empty( $this->data['default_option_values'] ) ){
                $this->data['default_option_values'] = array();

                foreach ( $this->fields as $field_info ) {
                    if( ! empty( $field_info['id'] ) ){
                        $this->data['default_option_values'][ $field_info['id'] ] = empty( $field_info['default'] ) ? '' : $field_info['default'];
                    }
                }
            }
            return $this->data['default_option_values'];
        }

        // hook in and get defined options
        if( 'defined_options' == $name ){
            if( empty( $this->data['defined_options'] ) ){
                $this->data['defined_options'] = apply_filters( 'auxin_defined_option_fields_sections', array( 'fields' => array(), 'sections' => array() ) );
            }
            return $this->data['defined_options'];
        }

        // get the list of fields
        if( 'fields' == $name ){
            if( empty( $this->data['fields'] ) ){
                $this->data['fields'] = $this->defined_options['fields'];
            }
            return $this->data['fields'];
        }

        // get the list of sections
        if( 'sections' == $name ){
            if( empty( $this->data['sections'] ) ){
                $this->data['sections'] = $this->defined_options['sections'];
            }
            return $this->data['sections'];
        }

        // get the list of sidebars
        if( 'sidebars' == $name ){
            if( empty( $this->data['sidebars'] ) ){
                $this->data['sidebars'] = get_option( THEME_ID.'_sidebars' );
            }
            return $this->data['sidebars'];
        }

        if( isset( $this->data[ $name ] ) ){
            return $this->data[ $name ];
        }

        return array();
    }



    /**
     * Generates and prints option keys for WPML XML file (calling manually)
     *
     * @return string
     */
    public function wpml_export(){
        $raw_options = get_option( THEME_ID.'_options');

        foreach ( $raw_options as $key => $value ) {
            echo '&lt;key name="' .$value['name']. '" /&gt;<br />';
        }
    }

}
