jQuery(document).ready(function($){

    $('.post').fitVids();

    $('#search-icon').on('click', openSearchBar);

    function openSearchBar(){

        if( $(this).hasClass('open') ) {
            $(this).removeClass('open');
            $('#site-header').find('.social-media-icons').removeClass('fade');
        } else {
            $(this).addClass('open');

            if( $(window).width())
            $('#site-header').find('.social-media-icons').addClass('fade');
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
            })
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
    if( $(window).width() < 900 ) {
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
        var menuParents = $('.menu-item-has-children');

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
