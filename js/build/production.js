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

    var body = $('body');
    var siteHeader = $('#site-header');
    var toggleNav = $('#toggle-navigation');
    var toggleDropdown = $('.toggle-dropdown');
    var menuPrimary = $('#menu-primary');
    var menuPrimaryItems = $('#menu-primary-items');
    var dropdownMenuItems = $('.menu-item').children('a').add( $('.page-item').children('a') );
    // var menuParents = $('.menu-item-has-children, .page_item_has_children');

    objectFitAdjustment();

    $(window).resize(function(){
        objectFitAdjustment();
    });

    // add fitvids to all vids in posts/pages
    $('.post').fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    // Jetpack infinite scroll event that reloads posts.
    $( document.body ).on( 'post-load', function () {
        objectFitAdjustment();
    } );

    // open search bar
    body.on('click', '#search-icon', openSearchBar);

    // display the primary menu at mobile widths
    toggleNav.on('click', openPrimaryMenu);

    // open dropdown menus
    toggleDropdown.on('click', openDropdownMenu);

    // enforce double-click for parent menu items when a touch event is registered
    // $(window).on('touchstart', enableTouchDropdown );

    /* allow keyboard access/visibility for dropdown menu items */
    dropdownMenuItems.focus(function(){
        $(this).parents('ul, li').addClass('focused');
    });
    dropdownMenuItems.focusout(function(){
        $(this).parents('ul, li').removeClass('focused');
    });

    function openSearchBar(){

        // get the social icons
        var socialIcons = siteHeader.find('.social-media-icons');

        // if search bar already open
        if( $(this).hasClass('open') ) {

            // remove styling class
            $(this).removeClass('open');

            // remove styling class
            if( socialIcons.hasClass('fade') ) {
                socialIcons.removeClass('fade');
            }

            // make search input inaccessible to keyboards
            siteHeader.find('.search-field').attr('tabindex', -1);

        } else {

            // add styling class
            $(this).addClass('open');

            // if search input is still 100%, add styling class
            if( window.innerWidth < 600 ) {
                socialIcons.addClass('fade');
            }

            // make search input keyboard accessible
            siteHeader.find('.search-field').attr('tabindex', 0);

            // handle mobile width search bar sizing
            if( window.innerWidth < 600 ) {

                // distance to other side (35px is width of icon space)
                var leftDistance = window.innerWidth * 0.9375 - 35;

                siteHeader.find('.search-form').css('left', -leftDistance + 'px')
            }

        }
    }

    function openPrimaryMenu() {

        // get height of the menu
        var menuHeight = calculateMenuHeight();

        // if menu open
        if( $(this).hasClass('open') ) {

            // remove styling class
            $(this).removeClass('open');

            // close all ULs by removing increased max-height
            $('#menu-primary, #menu-primary-items ul, .menu-unset ul').removeAttr('style');

            // close all ULs and require 2 clicks again when reopened
            $('.menu-item-has-children').each(function(){
                if( $(this).hasClass('open') ) {
                    $(this).removeClass('open');
                    $(this).addClass('closed');
                }
            });

            // change screen reader text
            $(this).children('span').text(ct_unlimited_objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

        } else {

            // add styling class to reveal primary menu
            $(this).addClass('open');

            // open to show whole menu plus 48px of padding for style
            menuPrimary.css('max-height', menuHeight + 48);

            // change screen reader text
            $(this).children('span').text(ct_unlimited_objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');
        }
    }

    // get height of primary menu
    function calculateMenuHeight() {

        var menuHeight = '';

        if( menuPrimaryItems.length ) {
            menuHeight = menuPrimaryItems.height();
        } else {
            menuHeight = $('.menu-unset').height();
        }
        return menuHeight;
    }
    
    function openDropdownMenu() {

        // get the button's parent (li)
        var menuItem = $(this).parent();

        if( menuItem.hasClass('open') ) {

            menuItem.removeClass('open');

            // remove max-height added by JS when opened
            $(this).siblings('ul').css('max-height', 0);

            // change screen reader text
            $(this).children('span').text(ct_unlimited_objectL10n.openChildMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

            if( window.innerWidth < 800 ) {
                // just needs long enough for the 0.15s animation fo play out
                setTimeout(function () {

                    // adjust containing .menu-primary to fit newly expanded list
                    var menuHeight = calculateMenuHeight();

                    // adjust to the height
                    menuPrimary.css('max-height', menuHeight + 48);

                }, 200)
            }
        } else {

            var ulHeight = 0;

            menuItem.addClass('open');

            // get all dropdown children and use their height to set the new max height
            $(this).siblings('ul').find('li').each(function () {
                ulHeight = ulHeight + $(this).height() + ( $(this).height() * 1.5 );
            });

            // set the new max height (for smoother transitions)
            $(this).siblings('ul').css('max-height', ulHeight);

            // change screen reader text
            $(this).children('span').text(ct_unlimited_objectL10n.closeChildMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');

            if( window.innerWidth < 800 ) {
                // just needs long enough for the 0.15s animation fo play out
                setTimeout(function () {

                    // adjust containing .menu-primary to fit newly expanded list
                    var menuHeight = calculateMenuHeight();

                    // adjust to the height
                    menuPrimary.css('max-height', menuHeight + 48);

                }, 200)
            }
        }
    }

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if( !('object-fit' in document.body.style) ) {

            $('.featured-image').each(function () {

                if ( !$(this).parent().parent('.entry').hasClass('ratio-natural') ) {

                    var image = $(this).children('img').add($(this).children('a').children('img'));

                    // don't process images twice (relevant when using infinite scroll)
                    if ( image.hasClass('no-object-fit') ) {
                        return;
                    }

                    image.addClass('no-object-fit');

                    // if the image is not wide enough to fill the space
                    if (image.outerWidth() < $(this).outerWidth()) {

                        // is it also not wide enough?
                        if (image.outerWidth() < $(this).outerWidth()) {

                            image.css({
                                'width': '100%',
                                'min-width': '100%',
                                'max-width': '100%',
                                'height': 'auto',
                                'min-height': '100%',
                                'max-height': 'none'
                            });
                        }
                    }
                    // if the image is not tall enough to fill the space
                    if (image.outerHeight() < $(this).outerHeight()) {

                        image.css({
                            'height': '100%',
                            'min-height': '100%',
                            'max-height': '100%',
                            'width': 'auto',
                            'min-width': '100%',
                            'max-width': 'none'
                        });
                    }
                }
            });
        }
    }
});

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }

        element.focus();
    }

}, false);