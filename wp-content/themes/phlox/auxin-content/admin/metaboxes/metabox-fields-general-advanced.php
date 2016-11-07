<?php
 /**
 * Add custom code meta box Model
 *
 * 
 * @package    auxin
 * @author     averta (c) 2010-2016
 * @link       http://averta.net

*/

function auxin_metabox_fields_general_advanced(){

    $model         = new Auxin_Metabox_Model();
    $model->id     = 'general-advanced';
    $model->title  = __('Advanced Setting', 'phlox');
    $model->fields = array(

        array(
            'title'         => __('Custom CSS class name for body', 'phlox'),
            'description'   => __('You can define custom CSS class name for this page. It helpful for targeting this page by custom CSS code.', 'phlox'),
            'id'            => 'aux_custom_body_class',
            'type'          => 'textbox',
            'default'       => '' // default value
        ),

        array(
            'title'         => __('Custom CSS Code', 'phlox'),
            'description'   => __('Attention: The following custom CSS code will be applied ONLY to this page.', 'phlox').'<br />'.
                           __('For defining global CSS roles, please use custom CSS field on option panel.', 'phlox'),
            'id'            => 'aux_page_custom_css',
            'type'          => 'code',
            'mode'          => 'css',
            'default'       => ''
        )

    );

    return $model;
}


