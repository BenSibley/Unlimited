jQuery(document).ready(function($){

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
        if( $(this).hasClass('open') ) {
            $(this).removeClass('open');
            $('#menu-primary').css('max-height', 0);
        } else {
            $(this).addClass('open');
            $('#menu-primary').css('max-height', 300);
        }

    }
});
