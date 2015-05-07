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