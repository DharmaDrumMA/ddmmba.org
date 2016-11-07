/* Javascript prototypes
 *=========================================================================*/
String.prototype.capFirstLetter  = function() { return this.charAt(0).toUpperCase() + this.slice(1); };
String.prototype.capFirstLetters = function() { return this.toLowerCase().replace(/\b[a-z]/g, function(letter) { return letter.toUpperCase(); });};
// add array.indexOf support for IE
if(!Array.prototype.indexOf){Array.prototype.indexOf=function(elt){var len=this.length>>>0;var from=Number(arguments[1])||0;from=(from<0)?Math.ceil(from):Math.floor(from);if(from<0)from+=len;for(;from<len;from++){if(from in this&&this[from]===elt)return from}return-1}};
// switch a checkbox on and off
jQuery.fn.auxSwitch = function( status ) { if( this.prop("checked") != status ){ this.trigger("click"); } return this; };
// create js namespace by string
function auxinCreateNamespace(n){for(var e=n.split("."),a=window,i="",r=e.length,t=0;r>t;t++)"window"!=e[t]&&(i=e[t],a[i]=a[i]||{},a=a[i]);return a;}

String.prototype.auxReplaceAll = function(search, replacement) { var target = this; return target.split(search).join(replacement); };

// set post meta key and value in local storage
Storage.prototype.setPostMeta = function( postID, metaKey, metaValue ) {
    if( ! ( postID && metaKey && metaValue ) ){ return; }

    var postMetaObj = this.getItem('auxin_post_meta');
    postMetaObj = JSON.parse(postMetaObj) || {};
    postMetaObj[ metaKey+'_'+postID ] = { "id": postID, "meta_key": metaKey, "meta_value": metaValue };
    return this.setItem( 'auxin_post_meta', JSON.stringify( postMetaObj ) );
};

// get post meta key and value in local storage
Storage.prototype.getPostMeta = function( postID, metaKey, defaultValue ) {
    if( ! ( postID && metaKey) ){ return; }
    defaultValue = defaultValue || '';

    var postMetaObj = this.getItem('auxin_post_meta');
    postMetaObj = JSON.parse(postMetaObj) || {};
    return ( postMetaObj[ metaKey+'_'+postID ] && postMetaObj[ metaKey+'_'+postID ][ 'meta_value' ] ) || '';
};

// set url hash on page start in admin edit pages
window.location.hash = localStorage.getPostMeta( auxin.post && auxin.post.id, 'edit_fragment' );

// serialize the form to JSON
jQuery.fn.serializeObject=function(){"use strict";var a={},b=function(b,c){var d=a[c.name];"undefined"!=typeof d&&d!==null?jQuery.isArray(d)?d.push(c.value):a[c.name]=[d,c.value]:a[c.name]=c.value};return jQuery.each(this.serializeArray(),b),a};


/**
 * jQuery alterClass plugin
 *
 * Remove element classes with wildcard matching. Optionally add classes:
 *   $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
 *
 * Copyright (c) 2011 Pete Boere (the-echoplex.net)
 * Free under terms of the MIT license: http://www.opensource.org/licenses/mit-license.php
 * https://gist.github.com/peteboere/1517285
 */
(function ( $ ) {

$.fn.alterClass = function ( removals, additions ) {

    var self = this;

    if ( removals.indexOf( '*' ) === -1 ) {
        // Use native jQuery methods if there is no wildcard matching
        self.removeClass( removals );
        return !additions ? self : self.addClass( additions );
    }

    var patt = new RegExp( '\\s' +
            removals.
                replace( /\*/g, '[A-Za-z0-9-_]+' ).
                split( ' ' ).
                join( '\\s|\\s' ) +
            '\\s', 'g' );

    self.each( function ( i, it ) {
        var cn = ' ' + it.className + ' ';
        while ( patt.test( cn ) ) {
            cn = cn.replace( patt, ' ' );
        }
        it.className = $.trim( cn );
    });

    return !additions ? self : self.addClass( additions );
};

})( jQuery );
