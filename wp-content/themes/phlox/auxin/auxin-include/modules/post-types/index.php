<?php
/**
 * Post types and metafields
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

 */

function auxin_add_post_type_metafields(){

    $all_post_types = auxin_get_possible_post_types(true);
    $all_post_types['post'] = true;
    $all_post_types['page'] = true;

    $auxin_is_admin  = is_admin();

    foreach ( $all_post_types as $post_type => $is_post_type_allowed ) {

        if( ! $is_post_type_allowed ){
            continue;
        }

        switch( $post_type ) {

            case 'page':

                $meta_hub_id    = 'axi_meta_hub_page';
                $meta_hub_title = __('Page Options', 'phlox');
                $meta_hub_type  = array( $post_type );

                break;

            case 'post':

                $meta_hub_id    = 'axi_meta_hub_post';
                $meta_hub_title = __('Post Options', 'phlox');
                $meta_hub_type  = array( $post_type );

            default:
                break;
        }

        // Load metabox fields on admin
        if( $auxin_is_admin ){

            $models = apply_filters( 'auxin_admin_metabox_models_'. $post_type, array(), $post_type, $meta_hub_id );

            if( ! empty( $models ) ){

                // if there is just one model, you can pass the Auxin_Metabox as $models
                if( $models instanceof Auxin_Metabox ){
                    $models->render();

                }  elseif( is_array( $models ) ){
                    $hub_models = array();

                    foreach ( $models as $model ) {

                        if( $model instanceof Auxin_Metabox ){
                            $model->render();
                        } elseif( $model['model'] instanceof Auxin_Metabox ){
                            $model['model']->render();
                        } elseif( $model instanceof Auxin_Metabox_Model ){
                            $hub_models[] = array( 'model' => $model, 'priority' => '10');
                        } elseif( $model['model'] instanceof Auxin_Metabox_Model ){
                            $hub_models[] = $model;
                        }

                    }

                    if( ! empty( $hub_models ) ){
                        // Create Metabox hub instance
                        $metabox_hub        = new Auxin_Metabox_Hub();
                        $metabox_hub->id    = $meta_hub_id;
                        $metabox_hub->title = $meta_hub_title;
                        $metabox_hub->type  = $meta_hub_type;

                        $metabox_hub->add_models( $hub_models );
                        $metabox_hub->maybe_render();
                    }

                }

            }

        }


    }


}

add_action( 'init', 'auxin_add_post_type_metafields' );
