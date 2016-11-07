<?php
/**
 * Add slider setting meabox
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

global $post;

function auxin_metabox_fields_general_slider(){

    // get all fleslider and nivo slider ids ----------------------------
    $slider_ids = array( 'none' => __( 'Choose ..', 'phlox') );

    $args = array(
      'post_type'   => 'slider',
      'orderby'     => "date",
      'post_status' => 'publish',
      'posts_per_page' => -1
    );

    $th_query = null;
    $th_query = new WP_Query( $args );
    if( $th_query->have_posts() ):  while ( $th_query->have_posts() ) : $th_query->the_post();
        $opts = get_post_meta( $th_query->post->ID, 'slider-data', true );
        if( ! isset( $opts ) || ! is_array( $opts ) ) continue;
        $type = $opts["type"];
        $slider_ids[ $th_query->post->ID ] = '['.$type.' ] '. get_the_title();
        unset( $args, $opts, $type );
    endwhile; endif;
    wp_reset_query();


    // Get all revslider aliases----------------------------------------

    if ( class_exists('RevSlider') ) { // if revSlider is active

        $slider = new RevSlider();
        $arrSliders = $slider->getArrSliders();

        foreach ($arrSliders as $slider) {
            $slider_ids[ $slider->getAlias() ] = '[revo ] '.$slider->getTitle();
        }

        unset( $slider, $arrSliders );
    }

    // Get all layeslider items ----------------------------------------

    // Check if the file is available to prevent warnings
    if( class_exists('LS_Sliders') ) {

        // Get WPDB Object
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "layerslider";

        // Get sliders
        $sliders = $wpdb->get_results( "SELECT * FROM $table_name
                                        WHERE flag_hidden = '0' AND flag_deleted = '0'
                                        ORDER BY date_c ASC LIMIT 100" );

        // Iterate over the sliders
        foreach($sliders as $key => $item) {
            $slider_ids['ls_'.$item->id] = '[layer] '. $item->name;
        }

    }

    // Get all cute slider items ---------------------------------------

    // Check if the file is available to prevent warnings
    if( function_exists('cuteslider_init') ) {

        // Get WPDB Object
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "cuteslider";

        // Get sliders
        $sliders = $wpdb->get_results( "SELECT * FROM $table_name
                                        WHERE flag_hidden = '0' AND flag_deleted = '0'
                                        ORDER BY date_c ASC LIMIT 100" );

        // Iterate over the sliders
        foreach($sliders as $key => $item) {
            $slider_ids['cs_'.$item->id] = '[cute ] '. $item->name;
        }

    }

    if ( defined( 'MSWP_AVERTA_VERSION' ) ) {

        $ms_sliders = get_masterslider_names( true );
        foreach ($ms_sliders as $ms_id => $ms_label ) {
            $slider_ids['ms_'.$ms_id] = '[Master] '. $ms_label;
        }
    }

    $slider_ids = apply_filters( 'auxin_page_header_slider_ids', $slider_ids );

    /*==================================================================================================

        Add Page Option meta box

     *=================================================================================================*/

    $model        = new Auxin_Metabox_Model();
    $model->title = __( 'Slider Options', 'phlox');
    $model->type  = array('page');
    $model->fields= array(

        array(
            'title'         => __('Page Slider', 'phlox'),
            'description'   => __('Please select the slider you want to display at top of the page.<br/>Revolution Slider Items are marked by [revo], Cuteslider by [cute], Layer slider by [layer]. Others are Items from Nivo & Flex slider."', 'phlox'),
            'id'            => 'top_slider_id',
            'type'          => 'select',
            'choices'       => $slider_ids
        ),
        array(
            'title'         => __('Slider Width', 'phlox'),
            'description'   => __('If you choose "Full", the slider fits to the page width.<br/>Please note that this option is only for FlexSlider, NivoSlider & LayerSlider ( [flex], [nivo] ). For other slider types, you can set this option directly in slider\'s panel.', 'phlox'),
            'id'            => 'top_slider_width',
            'type'          => 'select',
            'default'       => 'full',
            'choices'       => array(
                 'full'  => __('Full' , 'phlox'),
                 'boxed' => __('Boxed', 'phlox')
            )
        ),
        array(
            'title'         => __('Slider Divider', 'phlox'),
            'description'   => __('You can select a divider to be displaied at the bottom of slider.', 'phlox'),
            'id'            => 'top_slider_divider',
            'type'          => 'select',
            'default'       => '',
            'choices'       => array(
                'pattern'   => __('Pattern', 'phlox'),
                'border'    => __('Border' , 'phlox'),
                ''          => __('None'   , 'phlox')
            )
        )

    );

    return $model;
}
