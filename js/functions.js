jQuery(document).ready(function($){

    var body = $('body');
    var siteHeader = $('#site-header');
    var toggleNav = $('#toggle-navigation');
    var toggleDropdown = $('.toggle-dropdown');
    var menuPrimary = $('#menu-primary');
    var menuPrimaryItems = $('#menu-primary-items');
    var dropdownMenuItems = $('.menu-item').children('a').add( $('.page-item').children('a') );

    objectFitAdjustment();
        
    $(window).on('resize', function(){
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

            $(this).find('.screen-reader-text').text(ct_unlimited_objectL10n.openSearchBar);

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

            // Update screen reader text
            $(this).find('.screen-reader-text').text(ct_unlimited_objectL10n.closeSearchBar);

            // Put keyboard focus on the input
            $(this).siblings('form').find('input').focus();

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

            setTimeout(function() {
                menuPrimary.removeClass('open');
            }, 250);
              
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

            menuPrimary.addClass('open');

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

            setTimeout(function() {
                menuItem.removeClass('open');
            }, 150);

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

    // ===== Scroll to Top ==== //

    if ( $('#scroll-to-top').length !== 0 ) {
        $(window).on( 'scroll', function() {
            if ($(this).scrollTop() >= 200) {        // If page is scrolled more than 50px
                $('#scroll-to-top').addClass('visible');    // Fade in the arrow
            } else {
                $('#scroll-to-top').removeClass('visible');   // Else fade out the arrow
            }
        });
        $('#scroll-to-top').click(function(e) {      // When arrow is clicked
            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 600);
            $('#skip-content').focus();
        });
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

// wait to see if a touch event is fired
var hasTouch;
window.addEventListener('touchstart', setHasTouch, false );

// require a double-click on parent dropdown items
function setHasTouch() {

    hasTouch = true;

    // Remove event listener once fired
    window.removeEventListener('touchstart', setHasTouch);

    // get the width of the window
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth;


    // don't require double clicks for the toggle menu
    if (x > 799) {
        enableTouchDropdown();
    }
}

// require a second click to visit parent navigation items
function enableTouchDropdown(){

    // get all the parent menu items
    var menuParents = document.getElementsByClassName('menu-item-has-children');

    // add a 'closed' class to each and add an event listener to them
    for (i = 0; i < menuParents.length; i++) {
        menuParents[i].className = menuParents[i].className + " closed";
        menuParents[i].addEventListener('click', openDropdown);
    }
}

// check if an element has a class
function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

// open the dropdown without visiting parent link
function openDropdown(e){

    // if has 'closed' class...
    if(hasClass(this, 'closed')){
        // prevent link from being visited
        e.preventDefault();
        // remove 'closed' class to enable link
        this.className = this.className.replace('closed', '');
    }
}