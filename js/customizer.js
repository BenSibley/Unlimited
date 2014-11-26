( function( $ ) {

    /*
     * Following functions are for utilizing the postMessage transport setting
     */

    wp.customize( 'logo_upload', function( value ) {
        value.bind(function (newval) {
            // get the <a> holding the logo/site title
            var logoContainer = $('#site-title').find('a');
            // get the name of the site from the <a>
            var siteTitle = logoContainer.attr('title');
            // if there is an image, add the image markup
            if (newval) {
                var logo = "<span class='screen-reader-text'>" + siteTitle + "</span><img id='logo' class='logo' src='" + newval + "' alt='" + siteTitle + "' />";
            }
            // otherwise just use the site title
            else {
                var logo = siteTitle;
            }
            // empty the content first
            logoContainer.empty();
            // replace with the new logo markup
            logoContainer.append(logo);
        });
    });
    // Site title and description.
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( $('.site-title').find('img').length == 0 ) {
                $( '.site-title a' ).text( to );
            }
        } );
    } );
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.site-description' ).text( to );
        } );
    } );

    var socialSites = ['twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram', 'reddit', 'soundcloud', 'spotify', 'vine','yahoo', 'behance', 'codepen', 'delicious', 'stumbleupon', 'deviantart', 'digg', 'git', 'hacker-news', 'steam', 'vk', 'weibo', 'tencent-weibo', 'email' ];

    // for each social site setting
    for ( var site in socialSites ) {

        wp.customize( socialSites[site], function (value) {
            value.bind(function (to) {

                // empty the social icons list
                $('.social-media-icons').empty();

                // for each social icon input in customizer
                $('html', window.parent.document).find('#accordion-section-ct_unlimited_social_media_icons').find('input').each(function() {

                    if( $(this).val().length > 0 ) {

                        var siteName = $(this).attr('data-customize-setting-link');

                        if( siteName == 'email' ) {
                            $('.social-media-icons').append( '<li><a target="_blank" href="mailto:' + $(this).val() + '"><i class="fa fa-envelope"></i></a></li>' );
                        }
                        if( siteName == "flickr" || siteName == "dribbble" || siteName == "instagram" || siteName == "soundcloud" || siteName == "spotify" || siteName == "vine" || siteName == "yahoo" || siteName == "codepen" || siteName == "delicious" || siteName == "stumbleupon" || siteName == "deviantart" || siteName == "digg" || siteName == "hacker-news" || siteName == "vk" || siteName == 'weibo' || siteName == 'tencent-weibo' ) {
                            $('.social-media-icons').append('<li><a class="' + siteName + '" target="_blank" href="' + $(this).val() + '"><i class="fa fa-' + siteName + '"></i></a></li>');
                        } else {
                            $('.social-media-icons').append('<li><a class="' + siteName + '" target="_blank" href="' + $(this).val() + '"><i class="fa fa-' + siteName + '-square"></i></a></li>');
                        }
                    }
                });
            });
        });
    }

    wp.customize( 'search_bar', function( value ) {
        value.bind( function( to ) {
            if( to == 'hide' ) {
                $('#site-header').find('.search-form-container').css('display', 'none');
            } else {
                $('#site-header').append();
            }
        } );
    } );

    // add multiple select styling
    $('#comment-display-control').multipleSelect({
        selectAll: false
    });

    // hide the display none option
    $.each($('.ms-drop.bottom').find('li'), function(){

        if( $(this).find('input').val() == 'none' ) {
            $(this).hide();
        }
    });

    // Don't show the do not show text
    function ctHideNoneText() {

        // hide the "Do not show" text from the list of selected options
        $('.ms-choice span:contains("Do not show")').each(function(){

            // remove the text
            $(this).html($(this).html().split("Do not show").join(""));

            // remove trailing commas left over
            if( $(this).html().trim().slice(-1) == ',' ) {
                $(this).html( $(this).html().trim().slice(0, -1) );
            }
            // text to display instead if empty
            if (!$(this).text().trim().length) {
                $(this).text("Comments not displaying");
            }
        });
    }
    ctHideNoneText();

    $('.ms-drop.bottom').find('li').bind('click', ctHideNoneText);

} )( jQuery );