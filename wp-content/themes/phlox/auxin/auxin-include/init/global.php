<?php
/**
 * Essential core functions here
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


/**
 * Get custom css version
 * This option will be used to clear browser cache when new options are saved in custom css
 */
$GLOBALS[ THEME_ID.'_custom_css_ver'] = get_option( THEME_ID.'_custom_css_ver' , 1.0 );


/// Auto-load auxin classes on demand //////////////////////////////////////////


// Auto-load classes on demand
if ( function_exists( "__autoload" ) ) {
    spl_autoload_register( "__autoload" );
}
spl_autoload_register( 'auxin_classes_autoload' );



/**
 * Auto-load auxin classes on demand to reduce memory consumption
 *
 * @param mixed $class
 * @return void
 */
function auxin_classes_autoload( $class ) {
    $path  = null;
    $class = strtolower( $class );
    $file = 'class-' . str_replace( '_', '-', $class ) . '.php';

    // the possible pathes containing classes
    $possible_pathes = array(
        AUX_INC_REL . 'classes/',
        AUX_INC_REL . 'options/'
    );

    foreach ( $possible_pathes as $path ) {
        if( is_readable( THEME_DIR . $path . $file ) ){
            locate_template( $path . $file, true, true );
            return;
        }
    }
}


/// auxin Options //////////////////////////////////////////////////////////////


/**
 * Get all auxin options
 *
 * @return  array    An array containing all auxin options
 */
function auxin_options(){
    return Auxin_option::api()->data->auxin_options;
}


/**
 * Get all defined options (fields) that are hooked in auxin
 *
 * @return  array    An array containing all defined options
 */
function auxin_get_defined_options(){
    return Auxin_option::api()->data->defined_options;
}


/**
 * Get a list containing the default value of options
 *
 * @return  array    An array containing the default value of options
 */
function auxin_default_option_values(){
    Auxin_option::api()->data->default_option_values;
}


/**
 * Get auxin option value
 *
 * @param   string  $option_name    A unique name for option
 * @param   string  $default_value  A value to return by function if option_value not found
 * @return  string|array            Option_value or default_value
 */
function auxin_get_option( $option_name, $default_value = '' ){
    if( ! $option_name ) return;

    /**
     * Filter the value of an existing option before it is retrieved.
     *
     * The dynamic portion of the hook name, `$option_name`, refers to the option name.
     *
     *
     * @param bool|mixed $pre_option   Value to return instead of the option value.
     *                                 Default false to skip it.
     * @param string     $option_name  Option name.
     */
    $pre = apply_filters( 'pre_auxin_option_' . $option_name, 'unpredictableZvidSidXisudpdido899e8', $option_name );
    if ( 'unpredictableZvidSidXisudpdido899e8' !== $pre )
        return $pre;

    $auxin_options = auxin_options();
    return isset( $auxin_options[ $option_name ] ) ? $auxin_options[ $option_name ]: $default_value;
}


/**
 * Checks if needle exists in option value
 *
 * @param  string  $option_name   The option name to search in
 * @param  boolean $needle        The searched needle
 * @return boolean                Returns True if needle found in option, False otherwise
 */
function in_auxin_option( $option_name, $needle = true ){
    $option_val = (array) auxin_get_option( $option_name );

    if( is_bool( $needle ) ){
        $needle = $needle ? "true" : "false";
    }

    return in_array( $needle, $option_val ) ;
}


/**
 * Sanitize auxin options
 *
 * @param  array $raw_options Theme options in array
 * @return array              An array containing valid theme options
 */
function sanitize_auxin_options( $raw_options ){
    if( ! empty( $raw_options ) && is_array( $raw_options ) ){
        return $raw_options;
    }
    return array();
}


/**
 * Update option value in auxin options, if option_name does not exist then insert new option
 *
 * @param   string $option_name     A unique name for option
 * @param   string $option_value    The option value
 *
 * @return boolean                  True if option value has changed, false if not or if update failed.
 */
function auxin_update_option( $option_name, $option_value = '' ){
    if( ! $option_name ) return false;

    $auxin_options = auxin_options();
    $old_value     = ! empty( $auxin_options[ $option_name ] ) ? $auxin_options[ $option_name ] : null;

    /**
     * Filters an option before its value is (maybe) updated.
     *
     * @param mixed  $option_value     The new, unserialized option value.
     * @param string $option_name    Name of the option.
     * @param mixed  $old_value The old option value.
     */
    $option_value = apply_filters( 'pre_auxin_update_option', $option_value, $option_name, $old_value );

    // If the new and old values are the same, no need to update.
    if ( $option_value === $old_value )
        return false;

    $auxin_options = auxin_options();
    $auxin_options[ $option_name ] = $option_value;
    // update the auxin options cache
    Auxin_option::api()->data->auxin_options = $auxin_options;

    return update_option( THEME_ID.'_formatted_options', $auxin_options );
}


/**
 * Update/overwrite all auxin options
 *
 * @param   string $option          All formatted options in an array
 *
 * @return boolean                  True if option value has changed, false if not or if update failed.
 */
function auxin_update_options( $options ){
    if( ! $options ) return false;

    // pass the panel options through a filter and store them in raw options
    // THEME_ID.'_options' is the options used for displaying values in option panel not front-end
    $options = apply_filters( 'auxin_before_update_raw_options', $options );
    update_option( THEME_ID.'_options' , $options );

    // update current in use theme options
    $auxin_options = auxin_options();

    if( empty( $options ) || ! is_array( $options ) ){
        $options = array();
    }

    // pass the options through a filter and store them in formatted options
    $auxin_options = apply_filters( 'auxin_before_update_options', $options );
    // update the auxin options cache
    Auxin_option::api()->data->auxin_options = $auxin_options;

    // save formatted/usable options
    return update_option( THEME_ID.'_formatted_options', $auxin_options );
}


/// auxin presets //////////////////////////////////////////////////////////////


/**
 * Get all auxin presets
 *
 * @return  array    An array containing all auxin presets
 */
function auxin_get_presets(){
    return get_option( THEME_ID.'_auxin_presets' , array() );
}


/**
 * Update/overwrite all auxin presets
 *
 * @param   string $preset          All formatted presets in an array
 *
 * @return boolean                  True if preset value has changed, false if not or if update failed.
 */
function auxin_update_presets( $presets ){
    if( ! $presets ) return false;

    // pass the presets through a filter and store them in formatted presets
    $auxin_presets = apply_filters( 'auxin_before_update_presets', $presets );

    // save formatted/usable presets
    return update_preset( THEME_ID.'_auxin_presets', $auxin_presets );
}


/**
 * Get auxin preset values
 *
 * @param   string  $preset_name    A unique name for preset
 * @param   string  $default_value  A value to return by function if preset_value not found
 * @return  string|array            preset_value or default_value
 */
function auxin_get_preset( $preset_name, $default_value = '' ){
    if( ! $preset_name ) return;

    $auxin_presets = auxin_presets();
    return isset( $auxin_presets[ $preset_name ] ) ? $auxin_presets[ $preset_name ] : $default_value;
}


/**
 * Update preset value in auxin presets, if preset_name does not exist then insert new preset
 *
 * @param   string $preset_name     A unique name for preset
 * @param   string $preset_value    The preset value
 *
 * @return boolean                  True if preset value has changed, false if not or if update failed.
 */
function auxin_update_preset( $preset_name, $preset_value = '' ){
    if( ! $preset_name ) return false;

    $auxin_presets = auxin_presets();
    $auxin_presets[ $preset_name ] = $preset_value;
    return update_preset( THEME_ID.'_auxin_presets', $auxin_presets );
}
