( function( $ ) {

    // establish variables for common site elements
    var panel = $('html', window.parent.document);
    var body = $('body');
    var siteHeader = $('#site-header');
    var socialMediaIcons = siteHeader.find('.social-media-icons');

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
                var logo = "<span class='screen-reader-text'>" + siteTitle + "</span><img id='logo' class='logo' src='" + newval + "' alt='" + siteTitle + " logo' />";
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
            var tagline = $('.site-description');
            if( tagline.length == 0 ) {
                $('#title-container').append('<p class="site-description"></p>');
            }
            tagline.text( to );
        } );
    } );

    var socialSites = ['twitter', 'facebook', 'google-plus', 'pinterest', 'linkedin', 'youtube', 'vimeo', 'tumblr', 'instagram', 'flickr', 'dribbble', 'rss', 'reddit', 'soundcloud', 'spotify', 'vine', 'yahoo', 'behance', 'codepen', 'delicious', 'stumbleupon', 'deviantart', 'digg', 'github', 'hacker-news', 'snapchat', 'bandcamp', 'etsy', 'quora', 'ravelry', 'yelp', 'amazon', 'google-wallet', 'twitch', 'meetup', 'telegram', 'podcast', 'foursquare', 'slack', 'slideshare', 'skype', 'whatsapp', 'qq', 'wechat', 'xing', '500px', 'steam', 'vk', 'paypal', 'weibo', 'tencent-weibo', 'email', 'email_form' ];

    // for each social site setting
    for ( var site in socialSites ) {

        wp.customize( socialSites[site], function (value) {
            value.bind(function (to) {

                // check again
                socialMediaIcons = siteHeader.find('.social-media-icons');

                if( ! socialMediaIcons.length ) {
                    if( siteHeader.find('.search-form-container').length ) {
                        siteHeader.find('.search-form-container').before('<ul class="social-media-icons"></ul>');
                    } else {
                        $('#title-container').before('<ul class="social-media-icons"></ul>');
                    }
                    // store newly added element
                    socialMediaIcons = siteHeader.find('.social-media-icons');
                }

                // empty the social icons list
                socialMediaIcons.empty();

                // icons that should use a special square icon
                var squareIcons = ['linkedin', 'twitter', 'vimeo', 'youtube', 'pinterest', 'reddit', 'tumblr', 'steam', 'xing', 'github', 'google-plus', 'behance', 'facebook'];

                var selector = panel.find('#sub-accordion-section-unlimited_social_media_icons').find('input');

                if ( selector.length == false ) {
                    selector = panel.find('#accordion-section-unlimited_social_media_icons').find('input')
                }

                // for each social icon input in customizer
                $('html', window.parent.document).find(selector).each(function() {

                    if( $(this).val().length > 0 ) {

                        var siteName = $(this).attr('data-customize-setting-link');

                        if ( $.inArray( siteName, squareIcons ) > -1 ) {
                            var siteClass = 'fa fa-' + siteName + '-square';
                        } else {
                            var siteClass = 'fa fa-' + siteName;
                        }

                        if( siteName == 'email' ) {
                            socialMediaIcons.append( '<li><a target="_blank" href="mailto:' + $(this).val() + '"><i class="fa fa-envelope"></i></a></li>' );
                        }
                        if ( siteName == 'email_form' ) {
                            socialMediaIcons.append('<li><a class="' + siteName + '" target="_blank" href="' + $(this).val() + '"><i class="fa fa-envelope-o"></i></a></li>');
                        }
                        else {
                            socialMediaIcons.append('<li><a class="' + siteName + '" target="_blank" href="' + $(this).val() + '"><i class="' + siteClass + '"></i></a></li>');
                        }
                    }
                });
            });
        });
    }
    // live update layout
    wp.customize( 'layout', function( value ) {
        value.bind( function( to ) {
            if( to === 'left') {
                $( 'body' ).addClass( 'left-sidebar' );
            } else {
                $( 'body' ).removeClass( 'left-sidebar' );
            }
        } );
    } );
    // live update search bar
    wp.customize( 'search_bar', function( value ) {
        value.bind( function( to ) {

            // if no, remove it
            if( to == 'hide' ) {
                $('#site-header').find('.search-form-container').remove();
            }
            // else ajax in the content
            else {

                // set up data object
                var data = {
                    action: 'update_search_bar',
                    security: '<?php echo $ajax_nonce; ?>'
                };
                // post data received from PHP response
                jQuery.post(ajaxurl, data, function(response) {

                    // if valid response
                    if( response ){
                        $('#title-container').before(response);
                    }
                });
            }
        } );
    } );

    // move any existing CSS into separate style element to avoid affecting other CSS
    // not from the Custom CSS box

    // get the Custom CSS
    var customCSS = panel.find('#customize-control-custom_css').find('textarea').val();
    // get all the CSS in the inline element
    var allCSS = $('#style-inline-css').text();
    // remove the Custom CSS from the other CSS
    allCSS = allCSS.replace(customCSS, '');
    // update the CSS in the inline element w/o the custom css
    $('#style-inline-css').text(allCSS);
    // put custom CSS in its own style element
    body.append('<style id="style-inline-custom-css" type="text/css">' + customCSS + '</style>');

    var setting = 'custom_css';
    if ( panel.find('#sub-accordion-section-custom_css').length ) {
        setting = 'custom_css[unlimited]';
    }

    // Custom CSS
    wp.customize( setting, function( value ) {
        value.bind( function( to ) {
            $('#style-inline-custom-css').remove();
            if ( to != '' ) {
                to = '<style id="style-inline-custom-css" type="text/css">' + to + '</style>';
                body.append( to );
            }
        } );
    } );

} )( jQuery );