/*! auxin - Option panel scripts - v2.1.0 - 2016-10
 *  Scripts for auxin option panel. 
 *  Don't edit this file, this file is generated from src/_*.js files. 
 *  http://averta.net
 */



/*! 
 * ================== auxin/auxin-include/options/assets/js/src/_ajax-save.js =================== 
 **/ 

////// Init ajax save for option panel /////////////////////////////////

// adds click listener to save buttons, to save options via ajax
(function($){

    var $optionpanel, $form, serializeForm, formData,
        $saveBtn;

    $(function(){

        // get the main wrapper of option panel
        $optionpanel = $(".av3_option_panel");
        if( ! $optionpanel.length ) return;

        // get options form element
        $form = $optionpanel.find('form.auxin_options_form');
        if( ! $form.length ) return;

        if( auxin.utils.serializeForm ){
          serializeForm = new auxin.utils.serializeForm( $form[0] );
        }

        fadeOverlay();
        bindSaveBtn();
        bindResetBtn();
        bindImportBtn();
    });

    function fadeOverlay(){
        $optionpanel.find(".init_op_overlay").animate({"opacity":0}, 100, function(){ $(this).hide(0); });
    }

    function bindSaveBtn(){

        function onSaveBtnClicked(event){
            event.preventDefault();

            $saveBtn = $(this);

            if( $saveBtn.hasClass("axi-busy") ){
                return false;
            }

            // Show loading image
            $loading = $saveBtn.siblings('img');
            $loading.removeClass('ajax-loading');
            $saveBtn.addClass("axi-busy");

            formData = serializeForm.getJSON();
            formData = B64.encode( formData );

            console.log(serializeForm.getObject());

            // collect fields data in an object to post
            data = {
                nonce:   $form.data('nonce'),
                action:  'auxin_options',
                options: formData,
                type:   'save',
                sidebar: auxin_get_sidebars_name()
            };

            // send data to wp_ajax
            sendData( data, $loading );
        }

        // add click listener to save buttons
        $saveBtn = $form.find('.save_all_btn').bind('click', onSaveBtnClicked );

        jQuery(document).bind('keydown', function(e) {
            if( ( e.metaKey || e.ctrlKey ) && (e.which == 83) ){
                e.preventDefault();
                $saveBtn.trigger('click');
                return false;
            }
        });
    }

    function bindResetBtn(){

        // add click listener to reset buttons
        $form.find('.reset_all_btn').on('click', function(event){
          event.preventDefault();
          $this= $(this);

          if( ! confirm( "Are you sure you want to reset all options? All your changes will be reset to defaults." ) ){
            return;
          }

          // Show loading image
          $loading = $this.siblings('img');
          $loading.removeClass('ajax-loading');

          // collect fields data in an object to post
          data = {
            nonce:   $form.data('nonce'),
            action:  'auxin_options',
            options: [],
            type: 'reset'
          };

          // send data to wp_ajax
          sendData(data, $loading);
        });

    }

    function bindImportBtn(){

        // add click listener to import button
        $form.find('#auxin_import_options_btn').on('click', function(event){
            event.preventDefault();
            $this= $(this);

            // Show loading image
            $loading = $this.siblings('img');
            $loading.removeClass('ajax-loading');

            // get import data
            var importData    = $this.siblings('textarea').val();

            // collect fields data in an object to post
            data = {
                nonce:   $form.data('nonce'),
                action:  'auxin_import_ops',
                options: importData
            };

            // send data to wp_ajax
            sendData(data, $loading);
        });
    }


    // sends data to wp_ajax to save options in database
    function sendData( dataObject, $loading) {

        $.post(
            auxin.ajaxurl,
            dataObject,
            function(res){
                // if data sent successfuly
            if( res.success === true ){
                noty({  "text": res.message,"layout":"center", "animateOpen" : {"height" :"toggle" , "opacity":"toggle"}, "animateClose": {"opacity":"toggle"},"closeButton":false, "closeOnSelfClick":true, "closeOnSelfOver":false,
                    "speed":700,
                    "timeout":2000,
                    "type":"success"});

                // reload the page if options are reseted
                          if(res.type == "reset" || res.type == "import"){ window.location.reload(); }

            }else{
                noty({  "text": res.message,"layout":"center", "animateOpen" : {"height" :"toggle" , "opacity":"toggle"}, "animateClose": {"opacity":"toggle"},"closeButton":false, "closeOnSelfClick":true, "closeOnSelfOver":false,
                    "speed":700,
                    "timeout":8000,
                    "type":"error"});
            }

            // hide loading image
            $loading.addClass('ajax-loading');
            $saveBtn.removeClass("axi-busy");
        });
    }

})(jQuery);


////// get sortable section's data /////////////////////////////////////////////////

(function($){

  $(function(){

    // get options form element
      $form = $('div.av3_option_panel form.auxin_options_form');


      // get sidebar manager section
      $sidebar_section = $form.find('.sidebar-generator');

      // get sidebars wrapper
      $sidebar_wrap = $sidebar_section.find('.panel_field ul.area');

      // get all available sidebars
      $sidebars     = $sidebar_wrap.children('li:not( .hidden )');

      // get name of all sidebars
      var names = auxin_get_sidebars_name();

      // on remove sidebar clicked
      $sidebar_wrap.find('.close').on('click', function(){
          $parent = $(this).parent();
          // remove from sidebar list
          names.splice(names.indexOf( $parent.data('name') ), 1);

          $parent.slideUp(300, function(){
              $parent.remove();
          });
      });


      // get "add new" field
      $addField = $sidebar_section.find('.addField');

      // on "add new" clicked
      $addField.children('a.button').on('click',function(e) {
        e.preventDefault();

          var $this = $(this);
          var $input= $this.siblings('input');

          var val   = $input.val();
          if( val !== '' && val != ' '){

              if( ! is_in_list(val, names) ){
                  names.push(val);
                  $input.val('').focus();
                  $newSidebar = $('<li class="sidebar-rect"></li>');
                  $closeBtn   = $('<span class="close">x</span>').appendTo($newSidebar);
                  $sideLabel  = $('<span class="label">Global</span>').appendTo($newSidebar);

                  $newSidebar.data('name', val)
                         .children('span.label')
                           .text(val).end()
                           .appendTo($sidebar_wrap);

              } else {
                  // sidebar name already exist.
                  confirm( 'Sidebar name already exist.' );
              }

          } else {
            // sidebar name already exist.
              confirm( 'Invalid sidebar name.' );
          }
      });

      // on hit "enter" in input field
      $addField.children('input').on('keypress', function(e){
          if(e.keyCode == '13'){
              var $btn  = $(this).siblings('a.button').trigger('click');
          }
      });

  });

  // checks whether a value is in list or not
  function is_in_list(name, list){
      if(!name || !list)  return false;

      for(var i = 0, len = list.length; i < len ; ++i )
          if(list[i] == name)
              return true;

      return false;
  }

})(jQuery);


// return names of registered sidebars in array
function auxin_get_sidebars_name(){
    // get all available sidebars
    $sidebars = jQuery('.auxin_options_form .sidebar-generator .area li');
    var names  = [];
    $sidebars.each(function(index) {
        names.push( $sidebars.eq(index).data('name') );
    });
    return names;
}











;


/*! 
 * ================== auxin/auxin-include/options/assets/js/src/_float-save-bar.js =================== 
 **/ 

////// Float option panel save button ///////////////////////////////////

(function($){
	
	var $panel, $ctrlsWrapper, $ctrlsBar;


	$(function(){
		$panel         = $('div.av3_option_panel');

		// skip if option panel not found
		if( ! $panel.length ) return;

		$ctrlsWrapper  = $panel.find('.control_bar_wrapper');
		$ctrlsWrapper.height( $ctrlsWrapper.height() );
		$ctrlsBar     = $panel.find('.actions_control_bar');

		$(window).on('DOMContentLoaded load resize scroll', inViewportHandler); 
		$(window).on('resize', resizeHandler);
		resizeHandler();
	});
	

	function inViewportHandler () {
		if( ! $ctrlsWrapper ) return;

		if( isElementInViewport( $ctrlsWrapper[0] ) ){
			$panel.removeClass('fixed-controls');
		} else {
			$panel.addClass('fixed-controls');
		}
	}

	function resizeHandler(){
		if( ! $ctrlsBar ) return;

		$ctrlsBar.css( "left", $('#adminmenuwrap').width() );
	}
	

	function isElementInViewport (el) {

	    if (typeof jQuery === "function" && el instanceof jQuery) {
	        el = el[0];
	    }

	    var rect = el.getBoundingClientRect();

	    return (
	        rect.top >= 0 &&
	        rect.left >= 0 &&
	        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) + 30 &&
	        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
	    );
	}

})(jQuery);


/*! 
 * ================== auxin/auxin-include/options/assets/js/src/_serialize-form.js =================== 
 **/ 

/*
 *  serializeForm - v1.1.0 (2014-11-26)
 *  https://www.averta.net
 *
 *  Script to collect the values of all elements in form element
 *
 *  Copyright (c) 2010-2014 averta <http://averta.net>
 *  License:
 */

// Check for auxin namespaces
window.auxin = window.auxin || {}; window.auxin.utils = window.auxin.utils || {};


window.auxin.utils.serializeForm = (function($, window, document, undefined){
    "use strict";

    /*
     * Constructor
     */
    function SerializeForm( form, json ){
        if ( ! form || form.nodeName !== "FORM" ) {
            return;
        }
        this.form = form;
        this.outputObject = {};

        this.excludes = ["auxin_export_options", "auxin_import_options"];
    }

    // shortcut for prototype object
    var p = SerializeForm.prototype;

    /*
     * public function
     */
    p.collectObject = function(){

        var i, elems = this.form.elements;

        for (i = elems.length - 1; i >= 0; i = i - 1) {
            if (elems[i].name === "") {
                    continue;
            }

            switch (elems[i].nodeName) {

                case 'INPUT':
                        switch (elems[i].type) {
                        case 'text':
                        case 'hidden':
                        case 'password':
                        case 'button':
                        case 'reset':
                        case 'submit':
                                this.addData( elems[i].name , elems[i].value );
                                break;
                        case 'checkbox':
                                var elem_value = elems[i].checked ? '1' : '0';
                                console.log( elems[i].name, elem_value );
                                this.addData( elems[i].name , elem_value );
                                break;
                        case 'radio':
                                this.addData( elems[i].name , elems[i].value );
                                break;
                        }
                        break;
                        case 'file':
                        break;
                case 'TEXTAREA':
                        this.addData( elems[i].name , elems[i].value );
                        break;
                case 'SELECT':
                        switch (elems[i].type) {
                            // if the select dom is NOT multiple
                            case 'select-one':
                                this.addData( elems[i].name , elems[i].value );
                                break;

                            // if the select dom is multiple
                            case 'select-multiple':
                                // loop through options and store selected options in array
                                // meanwhile, selectedOptions is not cross browser
                                var result = [], select = elems[i];
                                for (var j = 0, len = select.options.length; j < len; j++ ) {
                                    if ( select.options[j].selected ) {
                                        result.push( select.options[j].value );
                                    }
                                }
                                this.addData( select.name , result );
                                break;
                        }
                        break;
                case 'BUTTON':
                        switch (elems[i].type) {
                        case 'reset':
                        case 'submit':
                        case 'button':
                            this.addData( elems[i].name , elems[i].value );
                            break;
                        }
                        break;
            }
        }

        return this.outputObject;
    };

    /*
     * Get form data in Object
     */
    p.getObject = function(){
        return this.collectObject();
    };

    /*
     * Get form data in JSON format
     */
    p.getJSON = function(){
        return JSON.stringify( this.collectObject() );
    };

    /*
     * Append data to data collector
     */
    p.addData = function( prop, value ){
        for ( var i = 0, l = this.excludes.length; i < l; i++ ) {
            if( this.excludes[i] == prop ){
                return;
            }
        }
        this.outputObject[prop] = value;
    };

    return SerializeForm;

})(jQuery, window, document);