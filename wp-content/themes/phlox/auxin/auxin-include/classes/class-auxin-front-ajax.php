<?php
/**
 * Auxin front ajax
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;


class Auxin_Front_Ajax {


    function __construct(){
        add_action( 'wp_ajax_template_markup_query'   , array( $this, 'template_markup_query' ) );
    }


    /**
     * Retrieves the markup of a template
     *
     * AJAX Handler: template_markup_query
     */
    public function template_markup_query() {

        // verify nonce
        /*
        if ( ! wp_verify_nonce( $_POST['nonce'], 'auxin-template-markup' ) ) {
            wp_send_json_error( 'No naughty business please! '. $_POST['nonce'] );
        }
        */

        $post_args = array(
            'wp_query'            => '', // the instance of wp_query. On using this option, the wp_query_args will be ignored (optional)
            'wp_query_args'       => array(), // wp_query args
            'variables'           => array(), // all array containing variables that we intent to send to template_part
            'template_start_wrap' => '', // start tag before each template part
            'template_end_wrap'   => '', // end tag after each template part
            'template_part'       => '', // the related URI of template part. E.g 'templates/theme-parts/entry/post-column.php'
            'in_loop_callback'    => '', // name of a function that will be called within the loop. Should return the results in an array
            'template_id'         => ''  // an id to differ this request from the others (optional)
        );

        // Get the passed value for each of required parameters
        foreach ( $post_args as $arg_key => $arg_value ) {
            if( ! empty( $_POST[ $arg_key ] ) ){
                $post_args[ $arg_key ] = $_POST[ $arg_key ];
            }
        }

        // extract the passed parameters
        extract( $post_args );
        extract( $variables );

        // start caching the result
        ob_start();

        if( empty( $wp_query ) && ! empty( $wp_query_args ) ){
            $wp_query = new WP_Query( $wp_query_args );
        }

        if( $wp_query && $have_posts = $wp_query->have_posts() ){

            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();

                // process and return required variables in loop by using a custom function
                if( $in_loop_callback ){
                    if( $callback_vars = call_user_func( $in_loop_callback, $variables ) ){
                        extract( $callback_vars );
                    }
                }

                echo $template_start_wrap;
                include( locate_template( 'templates/theme-parts/entry/post-column.php' ) );
                echo $template_end_wrap;
            }
        }

        wp_reset_postdata();

        // get and filter the markup
        $template_markup = ob_get_clean();
        $template_markup = apply_filters( 'auxin_ajax_template_markup_query', $template_markup, $post_args );

        // output the results
        wp_send_json_success( $template_markup );
    }

}
