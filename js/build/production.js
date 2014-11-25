/*global jQuery */
/*jshint browser:true */
/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */

;(function( $ ){

    'use strict';

    $.fn.fitVids = function( options ) {
        var settings = {
            customSelector: null,
            ignore: null
        };

        if(!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement("div");
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }

        if ( options ) {
            $.extend( settings, options );
        }

        return this.each(function(){
            var selectors = [
                'iframe[src*="player.vimeo.com"]',
                'iframe[src*="youtube.com"]',
                'iframe[src*="youtube-nocookie.com"]',
                'iframe[src*="kickstarter.com"][src*="video.html"]',
                'object',
                'embed'
            ];

            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }

            var ignoreList = '.fitvidsignore';

            if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }

            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

            $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                    return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                    $this.attr('height', 9);
                    $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('id')){
                    var videoID = 'fitvid' + Math.floor(Math.random()*999999);
                    $this.attr('id', videoID);
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

    $('.post').fitVids();

    $('#search-icon').on('click', openSearchBar);

    function openSearchBar(){

        var socialIcons = $('#site-header').find('.social-media-icons');

        if( $(this).hasClass('open') ) {
            $(this).removeClass('open');
            if( socialIcons.hasClass('fade') ) {
                socialIcons.removeClass('fade');
            }
        } else {
            $(this).addClass('open');
            if( $(window).width() < 600 ) {
                socialIcons.addClass('fade');
            }
        }
    }

    $('#toggle-navigation').on('click', openPrimaryMenu);

    function openPrimaryMenu() {

        var menuHeight = calculateMenuHeight();

        if( $(this).hasClass('open') ) {
            $(this).removeClass('open');
            // close menu and all submenus
            $('#menu-primary, #menu-primary-items ul, .menu-unset ul').removeAttr('style');
            $('.menu-item-has-children').each(function(){
                if( $(this).hasClass('open') ) {
                    $(this).removeClass('open');
                    $(this).addClass('closed');
                }
            });
        } else {
            $(this).addClass('open');
            // open to show whole menu plus 48px of padding for style
            $('#menu-primary').css('max-height', menuHeight + 48);
        }
    }
    function calculateMenuHeight() {

        if( $('#menu-primary-items').length > 0 ) {
            var menuHeight = $('#menu-primary-items').height();
        } else {
            var menuHeight = $('.menu-unset').height();
        }
        return menuHeight;
    }
    // enable double-click menu parent items right away
    if( $(window).width() < 800 ) {
        enableTouchDropdown();
    } else {
        // wait to see if a touch event is fired
        var hasTouch;
        $(window).on('touchstart', enableTouchDropdown, false );
    }

    // require a second click to visit parent navigation items
    function enableTouchDropdown(){

        // Remove event listener once fired
        $(window).off('touchstart', enableTouchDropdown);

        // get all the parent menu items
        var menuParents = $('.menu-item-has-children, .page_item_has_children');

        // add a 'closed' class to each and add an event listener to them
        menuParents.addClass('closed');
        menuParents.on('click', openDropdown);
    }

    // open the dropdown without visiting parent link
    function openDropdown(e){

        // if the menu item is not showing children
        if( $(this).hasClass('closed') ) {
            // prevent link from being visited
            e.preventDefault();
            // add an open class
            $(this).addClass('open');
            // get the submenu
            var submenu = $(this).children('ul');
            // set variable
            var submenuHeight = 0;
            // get height of all menu items in submenu combined
            submenu.children('li').each(function(){
                submenuHeight = submenuHeight + $(this).height();
            });
            // set new max-height to open submenu
            submenu.css('max-height', submenuHeight);
            // remove 'closed' class to enable link
            $(this).removeClass('closed');

            var listItem = $(this);

            // get the containing ul if it exists
            var parentList = listItem.parent('.sub-menu, .children');

            // get the height
            var parentListHeight = parentList.height();

            // expand the height of the parent ul so that it's child can show
            parentList.css('max-height', parseInt(parentListHeight + submenuHeight) );

            // just needs long enough for the 0.15s animation fo play out
            setTimeout( function(){

                // adjust containing .menu-primary to fit newly expanded list
                var menuHeight = calculateMenuHeight();

                // adjust to the height
                $('#menu-primary').css('max-height', menuHeight + 48);

            }, 200)
        }
    }
});

/*
 * Adapted from: http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
 */
jQuery(document).ready(function($){
// Uploading files
    var file_frame;

    $('#unlimited-user-profile-upload').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $( this ).data( 'uploader_title' ),
            button: {
                text: $( this ).data( 'uploader_button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            // change input's value to the attachment url
            $(event.currentTarget).prev().val(attachment.url);

            // Do something with attachment.id and/or attachment.url here
            $('#user_profile_image').val(attachment.url);

            $('#image-preview').attr('src', attachment.url);
        });

        // Finally, open the modal
        file_frame.open();
    });
});