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
});
