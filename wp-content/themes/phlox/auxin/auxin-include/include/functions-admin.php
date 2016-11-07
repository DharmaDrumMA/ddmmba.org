<?php


/**
 * Retrieves the changelog remotely
 *
 * @param  string $item_name  The name of the project that we intend to get the info of
 * @return string             The changelog context
 */
function auxin_get_remote_changelog( $item_name = '' ){

    if( empty( $item_name ) ){
        $item_name = THEME_ID;
    }

    global $wp_version;

    $args = array(
        'user-agent' => 'WordPress/'. $wp_version.'; '. get_site_url(),
        'timeout'    => ( ( defined('DOING_CRON') && DOING_CRON ) ? 30 : 10 ),
        'body'       => array(
            'action'    => 'text',
            'cat'       => 'changelog',
            'item-name' => 'phlox',
            'view'      => 'pre',
            'pl'        => 'true'
        )
    );

    $request = wp_remote_get( 'http://api.averta.net/envato/items/', $args );

    if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
        return new WP_Error( 'no_response', 'Error while receiving remote data' );
    }

    $response = $request['body'];

    return $response;
}


/**
 * Retrieves the list of available demos for current theme
 *
 * @return array List of demos
 */
function auxin_get_demo_info_list(){

    $demos_list = array(
        'the-journey' => array(
            'id'            => 'the-journey',
            'title'         => __('The Journey', 'phlox'),
            'desc'          => __('Create your awesome Journey Website using this demo as a starter. Best choice for adventure looks.', 'phlox'),
            'preview_url'   => 'http://averta.net/phlox/demo/journey/',
            'thumb_url'     => AUX_CON_URL . 'embeds/demos/journey-blog/banner.jpg',
            'file'          => AUX_CON . 'embeds/demos/journey-blog/data.xml'
        ),
        'classic-blog' => array(
            'id'            => 'classic-blog',
            'title'         => __('Classic Blog', 'phlox'),
            'desc'          => __('Create your classic good looking Blog using this demo as a starter. Best choice for a classic blogger.', 'phlox'),
            'preview_url'   => 'http://averta.net/phlox/demo/classic-blog/',
            'thumb_url'     => AUX_CON_URL . 'embeds/demos/classic-blog/banner.jpg',
            'file'          => AUX_CON . 'embeds/demos/classic-blog/data.xml'
        ),
        'food-blog' => array(
            'id'            => 'food-blog',
            'title'         => __('Food Blog', 'phlox'),
            'desc'          => __('Create your awesome Food Website using this demo as a starter. Best choice for restaurant looks.', 'phlox'),
            'preview_url'   => 'http://averta.net/phlox/demo/food/',
            'thumb_url'     => AUX_CON_URL . 'embeds/demos/food-blog/banner.jpg',
            'file'          => AUX_CON . 'embeds/demos/food-blog/data.xml'
        )
    );

    return apply_filters( 'auxin_get_demo_info_list', $demos_list );
}
