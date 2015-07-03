<?php

// Load the core theme framework.
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

// theme setup
if( ! function_exists( 'unlimited_theme_setup' ) ) {
	function unlimited_theme_setup() {

		/* Get action/filter hook prefix. */
		$prefix = hybrid_get_prefix();

		// add Hybrid core functionality
		add_theme_support( 'hybrid-core-template-hierarchy' );
		add_theme_support( 'loop-pagination' );
		add_theme_support( 'cleaner-gallery' );

		// add functionality from WordPress core
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		// load theme options page
		require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );

		// add inc folder files
		foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*' ) as $filename ) {
			include $filename;
		}

		// load text domain
		load_theme_textdomain( 'unlimited', get_template_directory() . '/languages' );

		// register Primary menu
		register_nav_menus( array(
			'primary' => __( 'Primary', 'unlimited' )
		) );
	}
}
add_action( 'after_setup_theme', 'unlimited_theme_setup', 10 );

// remove filters adding partial micro-data due to validation issues
function unlimited_remove_hybrid_filters() {
	remove_filter( 'the_author_posts_link', 'hybrid_the_author_posts_link', 5 );
	remove_filter( 'get_comment_author_link', 'hybrid_get_comment_author_link', 5 );
	remove_filter( 'get_comment_author_url_link', 'hybrid_get_comment_author_url_link', 5 );
	remove_filter( 'comment_reply_link', 'hybrid_comment_reply_link_filter', 5 );
	remove_filter( 'get_avatar', 'hybrid_get_avatar', 5 );
	remove_filter( 'post_thumbnail_html', 'hybrid_post_thumbnail_html', 5 );
	remove_filter( 'comments_popup_link_attributes', 'hybrid_comments_popup_link_attributes', 5 );
}
add_action('after_setup_theme', 'unlimited_remove_hybrid_filters');

// turn off cleaner gallery if Jetpack gallery functions being used
function unlimited_remove_cleaner_gallery() {

	if( class_exists( 'Jetpack' ) && ( Jetpack::is_module_active( 'carousel' ) || Jetpack::is_module_active( 'tiled-gallery' ) ) ) {
		remove_theme_support( 'cleaner-gallery' );
	}
}
add_action( 'after_setup_theme', 'unlimited_remove_cleaner_gallery', 11 );

// register widget areas
function unlimited_register_widget_areas(){

    /* register primary sidebar widget area */
    hybrid_register_sidebar( array(
        'name'         => __( 'Primary Sidebar', 'unlimited' ),
        'id'           => 'primary',
        'description'  => __( 'Widgets in this area will be shown in the sidebar next to the main post content', 'unlimited' )
    ) );
}
add_action('widgets_init','unlimited_register_widget_areas');

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
if( ! function_exists( 'unlimited_customize_comments' ) ) {
	function unlimited_customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				// if is post author
				if ( $comment->user_id === $post->post_author ) {
					unlimited_profile_image_output();
				} else {
					echo get_avatar( get_comment_author_email(), 48, '', get_comment_author() );
				}
				?>
				<div class="author-name">
					<span><?php comment_author_link(); ?></span> <?php _x( 'said:', 'unlimited', 'the commenter said the following:' ); ?>
				</div>
			</div>
			<div class="comment-content">
				<?php
				if ( $comment->comment_approved == '0' ) :
					echo "<em>" . __( 'Your comment is awaiting moderation.', 'unlimited' ) . "</em><br />";
				endif;
				comment_text(); ?>
				<div class="comment-date"><?php comment_date(); ?></div>
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => __( 'Reply', 'unlimited' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'before'     => '|'
				) ) ); ?>
				<?php edit_comment_link( __( 'Edit', 'unlimited' ), '|' ); ?>
			</div>
		</article>
	<?php
	}
}

/* added HTML5 placeholders for each default field and aria-required to required */
if( ! function_exists( 'unlimited_update_fields' ) ) {
	function unlimited_update_fields( $fields ) {

		// get commenter object
		$commenter = wp_get_current_commenter();

		// are name and email required?
		$req = get_option( 'require_name_email' );

		// required or optional label to be added
		if ( $req == 1 ) {
			$label = '*';
		} else {
			$label = ' ' . __("optional", "unlimited");
		}

		// adds aria required tag if required
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields['author'] =
			'<p class="comment-form-author">
	            <label>' . __( "Name", "unlimited" ) . $label . '</label>
	            <input placeholder="' . __( "John Doe", "unlimited" ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['email'] =
			'<p class="comment-form-email">
	            <label>' . __( "Email", "unlimited" ) . $label . '</label>
	            <input placeholder="' . __( "name@email.com", "unlimited" ) . '" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';

		$fields['url'] =
			'<p class="comment-form-url">
	            <label>' . __( "Website", "unlimited" ) . '</label>
	            <input placeholder="' . __( "http://example.com", "unlimited" ) . '" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter('comment_form_default_fields','unlimited_update_fields');

if( ! function_exists( 'unlimited_update_comment_field' ) ) {
	function unlimited_update_comment_field( $comment_field ) {

		$comment_field =
			'<p class="comment-form-comment">
	            <label>' . __( "Comment", "unlimited" ) . '</label>
	            <textarea required placeholder="' . __( "Enter Your Comment", "unlimited" ) . '&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter('comment_form_field_comment','unlimited_update_comment_field');

// remove allowed tags text after comment form
if( ! function_exists( 'unlimited_remove_comments_notes_after' ) ) {
	function unlimited_remove_comments_notes_after( $defaults ) {

		$defaults['comment_notes_after'] = '';

		return $defaults;
	}
}

add_action('comment_form_defaults', 'unlimited_remove_comments_notes_after');

// excerpt handling
if( ! function_exists( 'unlimited_excerpt' ) ) {
	function unlimited_excerpt() {

		// make post variable available
		global $post;

		// check for the more tag
		$ismore = strpos( $post->post_content, '<!--more-->' );

		// get the show full post setting
		$show_full_post = get_theme_mod( 'full_post' );

		// if show full post is on and not on a search results page
		if ( ( $show_full_post == 'yes' ) && ! is_search() ) {

			// use the read more link if present
			if ( $ismore ) {
				the_content( __( 'Read More', 'unlimited' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
			} else {
				the_content();
			}
		} // use the read more link if present
		elseif ( $ismore ) {
			the_content( __( 'Read More', 'unlimited' ) . "<span class='screen-reader-text'>" . get_the_title() . "</span>" );
		} // otherwise the excerpt is automatic, so output it
		else {
			the_excerpt();
		}
	}
}

// filter the link on excerpts
if( ! function_exists( 'unlimited_excerpt_read_more_link' ) ) {
	function unlimited_excerpt_read_more_link( $output ) {
		global $post;

		return $output . "<p><a class='more-link' href='" . get_permalink() . "'>" . __( 'Read More', 'unlimited' ) . "<span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
	}
}
add_filter('the_excerpt', 'unlimited_excerpt_read_more_link');

// switch [...] to ellipsis on automatic excerpt
if( ! function_exists( 'unlimited_new_excerpt_more' ) ) {
	function unlimited_new_excerpt_more( $more ) {
		return '&#8230;';
	}
}
add_filter('excerpt_more', 'unlimited_new_excerpt_more');

// turns of the automatic scrolling to the read more link 
if( ! function_exists( 'unlimited_remove_more_link_scroll' ) ) {
	function unlimited_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );

		return $link;
	}
}
add_filter( 'the_content_more_link', 'unlimited_remove_more_link_scroll' );

// change the length of the excerpts
function unlimited_custom_excerpt_length( $length ) {

	$new_excerpt_length = get_theme_mod('excerpt_length');

	// if there is a new length set and it's not 15, change it
	if( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ){
		return $new_excerpt_length;
	} else {
		return 25;
	}
}
add_filter( 'excerpt_length', 'unlimited_custom_excerpt_length', 99 );

// for displaying featured images
if( ! function_exists( 'unlimited_featured_image' ) ) {
	function unlimited_featured_image() {

		// get post object
		global $post;

		// default to no featured image
		$has_image = false;

		// establish featured image var
		$featured_image = '';

		// if post has an image
		if ( has_post_thumbnail( $post->ID ) ) {

			// get the featured image ID
			$image_id = get_post_thumbnail_id( $post->ID );

			// get the image's alt text
			$image_alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);

			// get the full-size version of the image
			$image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' );

			// set $image = the url
			$image = $image[0];

			// if alt text is empty, nothing else equal to title string
			$title = empty($image_alt_text) ? '' : "title='$image_alt_text'";

			// set to true
			$has_image = true;
		}
		if ( $has_image == true ) {

			// on posts/pages display the featued image
			if ( is_singular() ) {
				$featured_image = "<div class='featured-image' style=\"background-image: url('" . $image . "')\" $title></div>";
			} // on blog/archives display with a link
			else {
				$featured_image = "
	                <div class='featured-image' style=\"background-image: url('" . $image . "')\" $title>
	                    <a href='" . get_permalink() . "'>" . get_the_title() . "</a>
	                </div>
	                ";
			}
		}

		// allow videos to be added
		$featured_image = apply_filters( 'ct_unlimited_featured_image', $featured_image );

		if( $featured_image ) {
			echo $featured_image;
		}
	}
}

// fix for bug with Disqus saying comments are closed
if ( function_exists( 'dsq_options' ) ) {
    remove_filter( 'comments_template', 'dsq_comments_template' );
    add_filter( 'comments_template', 'dsq_comments_template', 99 ); // You can use any priority higher than '10'
}

// associative array of social media sites
function unlimited_social_array(){

	$social_sites = array(
		'twitter' => 'unlimited_twitter_profile',
		'facebook' => 'unlimited_facebook_profile',
		'google-plus' => 'unlimited_google_plus_profile',
		'pinterest' => 'unlimited_pinterest_profile',
		'linkedin' => 'unlimited_linkedin_profile',
		'youtube' => 'unlimited_youtube_profile',
		'vimeo' => 'unlimited_vimeo_profile',
		'tumblr' => 'unlimited_tumblr_profile',
		'instagram' => 'unlimited_instagram_profile',
		'flickr' => 'unlimited_flickr_profile',
		'dribbble' => 'unlimited_dribbble_profile',
		'rss' => 'unlimited_rss_profile',
		'reddit' => 'unlimited_reddit_profile',
		'soundcloud' => 'unlimited_soundcloud_profile',
		'spotify' => 'unlimited_spotify_profile',
		'vine' => 'unlimited_vine_profile',
		'yahoo' => 'unlimited_yahoo_profile',
		'behance' => 'unlimited_behance_profile',
		'codepen' => 'unlimited_codepen_profile',
		'delicious' => 'unlimited_delicious_profile',
		'stumbleupon' => 'unlimited_stumbleupon_profile',
		'deviantart' => 'unlimited_deviantart_profile',
		'digg' => 'unlimited_digg_profile',
		'github' => 'unlimited_github_profile',
		'hacker-news' => 'unlimited_hacker-news_profile',
		'steam' => 'unlimited_steam_profile',
		'vk' => 'unlimited_vk_profile',
		'weibo' => 'unlimited_weibo_profile',
		'tencent-weibo' => 'unlimited_tencent_weibo_profile',
		'email' => 'unlimited_email_profile'
	);
	return $social_sites;
}

// used in unlimited_social_icons_output to return urls
function unlimited_get_social_url($source, $site){

	if( $source == 'header' ) {
		return get_theme_mod($site);
	} elseif( $source == 'author' ) {
		return get_the_author_meta($site);
	}
}

// output social icons
if( ! function_exists('unlimited_social_icons_output') ) {
	function unlimited_social_icons_output($source) {

		// get social sites array
		$social_sites = unlimited_social_array();

		// store the site name and url
		foreach ( $social_sites as $social_site => $profile ) {

			if( $source == 'header') {

				if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
					$active_sites[$social_site] = $social_site;
				}
			}
			elseif( $source == 'author' ) {

				if ( strlen( get_the_author_meta( $profile ) ) > 0 ) {
					$active_sites[$profile] = $social_site;
				}
			}
		}

		// for each active social site, add it as a list item
		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				if ( $active_site == 'email' ) {
					?>
					<li>
						<a class="email" target="_blank" href="mailto:<?php echo antispambot( is_email( unlimited_get_social_url( $source, $key ) ) ); ?>">
							<i class="fa fa-envelope" title="<?php _e('email icon', 'unlimited'); ?>"></i>
						</a>
					</li>
				<?php } elseif ( $active_site == "flickr" || $active_site == "dribbble" || $active_site == "instagram" || $active_site == "soundcloud" || $active_site == "spotify" || $active_site == "vine" || $active_site == "yahoo" || $active_site == "codepen" || $active_site == "delicious" || $active_site == "stumbleupon" || $active_site == "deviantart" || $active_site == "digg" || $active_site == "hacker-news" || $active_site == "vk" || $active_site == 'weibo' || $active_site == 'tencent-weibo' ) { ?>
					<li>
						<a class="<?php echo $active_site; ?>" target="_blank" href="<?php echo esc_url( unlimited_get_social_url( $source, $key ) ); ?>">
							<i class="fa fa-<?php echo esc_attr( $active_site ); ?>" title="<?php printf( __('%s icon', 'unlimited'), $active_site ); ?>"></i>
						</a>
					</li>
				<?php } else { ?>
					<li>
						<a class="<?php echo $active_site; ?>" target="_blank" href="<?php echo esc_url( unlimited_get_social_url( $source, $key ) ); ?>">
							<i class="fa fa-<?php echo esc_attr( $active_site ); ?>-square" title="<?php printf( __('%s icon', 'unlimited'), $active_site ); ?>"></i>
						</a>
					</li>
				<?php
				}
			}
			echo "</ul>";
		}
	}
}

// retrieves the attachment ID from the file URL
function unlimited_get_image_id($url) {

    // Split the $url into two parts with the wp-content directory as the separator
    $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

    // Get the host of the current site and the host of the $url, ignoring www
    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

    // Return nothing if there aren't any $url parts or if the current host and $url host do not match
    if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
        return;
    }

    // Now we're going to quickly search the DB for any attachment GUID with a partial path match
    // Example: /uploads/2013/05/test-image.jpg
    global $wpdb;

    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );

    // Returns null if no attachment is found
    return $attachment[0];
}

function unlimited_profile_image_output(){

    // use User's profile image, else default to their Gravatar
    if(get_the_author_meta('unlimited_user_profile_image')){

        // get the id based on the image's URL
        $image_id = unlimited_get_image_id(get_the_author_meta('unlimited_user_profile_image'));

        // retrieve the thumbnail size of profile image (60px)
        $image_thumb = wp_get_attachment_image($image_id, array(60,60), false, array('alt' => get_the_author() ) );

        // display the image
        echo $image_thumb;

    } else {
        echo get_avatar( get_the_author_meta( 'ID' ), 60, '', get_the_author() );
    }
}

function unlimited_wp_backwards_compatibility() {

	// not using this function, simply remove it so use of "has_image_size" doesn't break < 3.9
	if( version_compare( get_bloginfo('version'), '3.9', '<' ) ) {
		remove_filter( 'image_size_names_choose', 'hybrid_image_size_names_choose' );
	}
}
add_action('init', 'unlimited_wp_backwards_compatibility');

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling difficult and confusing. Using this wrapper to add a unique class to make styling easier.
 */
function unlimited_wp_page_menu() {
	wp_page_menu(array(
			"menu_class" => "menu-unset"
		)
	);
}

function unlimited_body_class( $classes ) {

	/* get layout chosen by user */
	$layout = get_theme_mod('layout');

	/* get full post setting */
	$full_post = get_theme_mod('full_post');

	/* if sidebar left layout */
	if($layout == 'left') {
		$classes[] = 'left-sidebar';
	}
	/* if full post setting on */
	if( $full_post == 'yes' ) {
		$classes[] = 'full-post';
	}
	return $classes;
}
add_filter( 'body_class', 'unlimited_body_class' );

// custom css output
function unlimited_custom_css_output(){

	$custom_css = get_theme_mod('custom_css');

	/* output custom css */
	if( $custom_css ) {
		wp_add_inline_style( 'style', $custom_css );
	}
}
add_action('wp_enqueue_scripts', 'unlimited_custom_css_output');

function unlimited_sticky_post_marker() {

	if( is_sticky() && !is_archive() ) {
		echo '<span class="sticky-status">Featured Post</span>';
	}
}
add_action( 'archive_post_before', 'unlimited_sticky_post_marker' );

function unlimited_reset_customizer_options() {

	// validate name and value
	if( empty( $_POST['unlimited_reset_customizer'] ) || 'unlimited_reset_customizer_settings' !== $_POST['unlimited_reset_customizer'] )
		return;

	// validate nonce
	if( ! wp_verify_nonce( $_POST['unlimited_reset_customizer_nonce'], 'unlimited_reset_customizer_nonce' ) )
		return;

	// validate user permissions
	if( ! current_user_can( 'manage_options' ) )
		return;

	// delete customizer mods
	remove_theme_mods();

	$redirect = admin_url( 'themes.php?page=unlimited-options' );
	$redirect = add_query_arg( 'unlimited_status', 'deleted', $redirect);

	// safely redirect
	wp_safe_redirect( $redirect ); exit;
}
add_action( 'admin_init', 'unlimited_reset_customizer_options' );

function unlimited_delete_settings_notice() {

	if ( isset( $_GET['unlimited_status'] ) ) {
		?>
		<div class="updated">
			<p><?php _e( 'Customizer settings deleted', 'unlimited' ); ?>.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'unlimited_delete_settings_notice' );

if ( ! function_exists( '_wp_render_title_tag' ) ) :
	function unlimited_add_title_tag() {
		?>
		<title><?php wp_title( ' | ' ); ?></title>
	<?php
	}
	add_action( 'wp_head', 'unlimited_add_title_tag' );
endif;

// show notice telling users about avatar change coming in v1.50
function ct_unlimited_avatar_notice() {

	// if not dismissed previously, show message
	if ( get_option( 'ct_unlimited_dismiss_avatar_notice' ) != true ) {

		// set link with full explanation
		// linking to my site and redirecting as a precaution to maintain control
		$url = 'https://www.competethemes.com/unlimited-avatar-redirect/';
		?>
		<div id="unlimited-avatar-notice" class="update-nag notice is-dismissible">
			<p><?php printf( __( 'Custom avatars are being removed from Unlimited in v1.09. Please <a target="_blank" href="%s">follow these instructions</a> before the next update', 'unlimited' ), esc_url($url) ); ?>.</p>
		</div>
	<?php
	}
}
add_action( 'admin_notices', 'ct_unlimited_avatar_notice' );

// remove the notice permanently if user clicks the "x" button
function ct_unlimited_dismiss_avatar_notice() {

	// get the dismissed value
	$dismissed = $_POST['dismissed'];

	// if set to true, update option
	if( $dismissed == true ) {
		update_option('ct_unlimited_dismiss_avatar_notice', true);
	}
	die();
}
add_action( 'wp_ajax_dismiss_unlimited_avatar_notice', 'ct_unlimited_dismiss_avatar_notice' );