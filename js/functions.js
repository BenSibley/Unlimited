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
            $('#site-header').find('.search-field').attr('tabindex', -1);
        } else {
            $(this).addClass('open');
            if( $(window).width() < 600 ) {
                socialIcons.addClass('fade');
            }
            $('#site-header').find('.search-field').attr('tabindex', 0);
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
    // add the double-click if the menu is made smaller again
    $(window).resize(function(){
        if( $(window).width() < 800 ) {
            enableTouchDropdown();
        }
    });

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

        // don't enforce if screen resized over 800px
        if( $(window).width() < 800 ) {

            // if the menu item is not showing children
            if ($(this).hasClass('closed')) {
                // prevent link from being visited
                e.preventDefault();
                // add an open class
                $(this).addClass('open');
                // get the submenu
                var submenu = $(this).children('ul');
                // set variable
                var submenuHeight = 0;
                // get height of all menu items in submenu combined
                submenu.children('li').each(function () {
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
                parentList.css('max-height', parseInt(parentListHeight + submenuHeight));

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