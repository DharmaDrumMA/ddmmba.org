
/*=======================================================================================
 *  Post Formats - toggles post formats setting metaboxes 
 *  Contains all edit page customizations
 *======================================================================================*/

;(function($){
    
    // get screen optipn panel
    var $screen_settings = $('form#adv-settings .metabox-prefs');
    // select video setting from screen options 
    $video = $screen_settings.find('input#axi_post_video_meta_box-hide');
    
    
    // select audio setting from screen options 
    $audio = $screen_settings.find('input#axi_post_audio_meta_box-hide');
    // select link setting from screen options 
    $link = $screen_settings.find('input#axi_post_link_meta_box-hide'  );
    // select quote setting from screen options 
    $quote = $screen_settings.find('input#axi_post_quote_meta_box-hide');
    // select gallery setting from screen options 
    $gallery = $screen_settings.find('input#axi_post_gallery_meta_box-hide');
    
    var $formats = [$video, $audio, $link, $quote, $gallery];
    
    
    $post_format_selector = $('div#post-formats-select input[name="post_format"]');
    
    $post_format_selector.on('click', function(){
        auxin_show_selected_post_format( $(this).val() , $formats);
    });
    
    // select current post format when page loaded
    var initial_val = $post_format_selector.filter(":checked").val();
    setTimeout(function(){
        auxin_show_selected_post_format( initial_val , $formats);
    }, 100);


    function auxin_show_selected_post_format(name, $formats){
        
        for(var i in $formats){
            //is selected post format?
            var isSelected = ($formats[i].val() !== "axi_post_" + name + "_meta_box") ;
            $formats[i].prop("checked", isSelected).trigger("click");
        }
    }
    
})(jQuery);
