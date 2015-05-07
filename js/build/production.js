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

    // add fitvids to all vids in posts/pages
    $('.post').fitVids();

    /*
     * Open the search bar in site-header on click.
     * Selector added as a parameter so that it works when search bar
     * is added via ajax in the Customizer
     */
    $('body').on('click', '#search-icon', openSearchBar);

    function openSearchBar(){

        // get the social icons
        var socialIcons = $('#site-header').find('.social-media-icons');

        // if search bar already open
        if( $(this).hasClass('open') ) {

            // remove styling class
            $(this).removeClass('open');

            // remove styling class
            if( socialIcons.hasClass('fade') ) {
                socialIcons.removeClass('fade');
            }

            // make search input inaccessible to keyboards
            $('#site-header').find('.search-field').attr('tabindex', -1);

        } else {

            // add styling class
            $(this).addClass('open');

            // if search input is still 100%, add styling class
            if( $(window).width() < 600 ) {
                socialIcons.addClass('fade');
            }

            // make search input keyboard accessible
            $('#site-header').find('.search-field').attr('tabindex', 0);

            // handle mobile width search bar sizing
            if( $(window).width() < 600 ) {

                // distance to other side (35px is width of icon space)
                var leftDistance = $(window).width() * 0.9375 - 35;

                $('#site-header').find('.search-form').css('left', -leftDistance + 'px')
            }

        }
    }

    // display the primary menu at mobile widths
    $('#toggle-navigation').on('click', openPrimaryMenu);

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
            $(this).children('span').text(objectL10n.openMenu);

            // change aria text
            $(this).attr('aria-expanded', 'false');

        } else {

            // add styling class to reveal primary menu
            $(this).addClass('open');

            // open to show whole menu plus 48px of padding for style
            $('#menu-primary').css('max-height', menuHeight + 48);

            // change screen reader text
            $(this).children('span').text(objectL10n.closeMenu);

            // change aria text
            $(this).attr('aria-expanded', 'true');
        }
    }

    // get height of primary menu
    function calculateMenuHeight() {

        if( $('#menu-primary-items').length ) {
            var menuHeight = $('#menu-primary-items').height();
        } else {
            var menuHeight = $('.menu-unset').height();
        }
        return menuHeight;
    }

    // enforce double-click for parent menu items when a touch event is registered
    $(window).on('touchstart', enableTouchDropdown );

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

    // open the dropdown without visiting parent link on first click
    function openDropdown(e){

        // if the menu item is not showing children
        if ($(this).hasClass('closed')) {

            // prevent link from being visited
            e.preventDefault();

            // add an open class
            $(this).addClass('open');

            // remove 'closed' class to enable link
            $(this).removeClass('closed');

            // get the submenu
            var submenu = $(this).children('ul');

            // set variable
            var submenuHeight = 0;

            // get height of all menu items in submenu combined
            submenu.children('li').each(function () {
                submenuHeight = submenuHeight + $(this).height();
            });

            // set ul max-height to the height of all it's children li
            submenu.css('max-height', submenuHeight);

            var listItem = $(this);

            // get the containing ul if it exists
            var parentList = listItem.parent('.sub-menu, .children');

            // get the height
            var parentListHeight = parentList.height();

            // expand the height of the parent ul so that it's child can show
            parentList.css('max-height', parseInt(parentListHeight + submenuHeight));

            // only open the primary menu if clicked menu item is in primary menu
            if( $(this).parents().hasClass('menu-primary-items') || $(this).parents().hasClass('menu-unset') ) {

                // just needs long enough for the 0.15s animation fo play out
                setTimeout(function () {

                    // adjust containing .menu-primary to fit newly expanded list
                    var menuHeight = calculateMenuHeight();

                    // adjust to the height
                    $('#menu-primary').css('max-height', menuHeight + 48);

                }, 200)
            }
        }
    }

    /* allow keyboard access/visibility for dropdown menu items */
    $('.menu-item a, .page_item a').focus(function(){
        $(this).parents('ul').addClass('focused');
    });
    $('.menu-item a, .page_item a').focusout(function(){
        $(this).parents('ul').removeClass('focused');
    });

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