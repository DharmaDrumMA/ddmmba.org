<?php
/**
 * Attachment Fields Class.
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

/**
 *
 */
class Attachment_Fields {

    private $fields = array();

  function __construct( $fields = null ) {
    if( isset( $fields ) && is_array( $fields ) )
      $this->$fields = $fields;
  }

    public function add( $field ){
        if( is_array( $field ) )
            $this->fields[] = $field;
    }

    public function init(){
        add_filter( 'attachment_fields_to_edit', array( $this, 'addFields'  ), 11, 2 );
        add_filter( 'attachment_fields_to_save', array( $this, 'saveFields' ), 11, 2 );
    }

    public function addFields( $form_fields, $post ){
        return $form_fields;
    }

    public function saveFields( $post, $attachment ) {
        return $post;
    }
}



// $attach_fields = new Attachment_Fields();

// $attchments_options = array(
//     'image_copyright' => array(
//         'label'       => '',
//         'input'       => 'text',
//         'helps'       => '',
//         'application' => 'image',
//         'exclusions'  => array( 'audio', 'video' ),
//         'required'    => true,
//         'error_text'  => __( 'Field is required', 'phlox' )
//     ),
//     'image_rating' => array(
//         'label'       => __( 'rating', 'phlox' ),
//         'input'       => 'radio',
//         'options' => array(
//             '1' => 1,
//             '2' => 2,
//             '3' => 3,
//             '4' => 4,
//             '5' => 5
//         ),
//         'application' => 'image',
//         'exclusions'   => array( 'audio', 'video' )
//     )
// );
// $attach_fields->init();
