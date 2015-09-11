<?php

// set the content width
if ( ! isset( $content_width ) ) {
	$content_width = 655;
}

// theme setup
if( ! function_exists( 'unlimited_theme_setup' ) ) {
	function unlimited_theme_setup() {

		// add functionality from WordPress core
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
		) );

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

// register widget areas
function unlimited_register_widget_areas(){

    /* register primary sidebar widget area */
    register_sidebar( array(
        'name'         => __( 'Primary Sidebar', 'unlimited' ),
        'id'           => 'primary',
        'description'  => __( 'Widgets in this area will be shown in the sidebar next to the main post content', 'unlimited' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
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
					echo get_avatar( get_comment_author_email(), 48, '', get_comment_author() );
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

		// get user set excerpt length
		$new_excerpt_length = get_theme_mod('excerpt_length');

		// if set to 0, return nothing
		if ( $new_excerpt_length === 0 ) {
			return '';
		}
		// else add the ellipsis
		else {
			return '&#8230;';
		}

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
	}
	// return 0 if user explicitly sets it to 0
	elseif ( $new_excerpt_length === 0 ) {
		return 0;
	}
	else {
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
			$title = empty($image_alt_text) ? '' : "title='" . esc_attr( $image_alt_text ) . "'";

			// set to true
			$has_image = true;
		}
		if ( $has_image == true ) {

			// on posts/pages display the featured image
			if ( is_singular() ) {
				$featured_image = "<div class='featured-image' style=\"background-image: url('" . esc_url( $image ) . "')\" $title></div>";
			} // on blog/archives display with a link
			else {
				$featured_image = "
	                <div class='featured-image' style=\"background-image: url('" . esc_url( $image ) . "')\" $title>
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

// associative array of social media sites
if ( !function_exists( 'unlimited_social_array' ) ) {
	function unlimited_social_array() {

		$social_sites = array(
			'twitter'       => 'unlimited_twitter_profile',
			'facebook'      => 'unlimited_facebook_profile',
			'google-plus'   => 'unlimited_google_plus_profile',
			'pinterest'     => 'unlimited_pinterest_profile',
			'linkedin'      => 'unlimited_linkedin_profile',
			'youtube'       => 'unlimited_youtube_profile',
			'vimeo'         => 'unlimited_vimeo_profile',
			'tumblr'        => 'unlimited_tumblr_profile',
			'instagram'     => 'unlimited_instagram_profile',
			'flickr'        => 'unlimited_flickr_profile',
			'dribbble'      => 'unlimited_dribbble_profile',
			'rss'           => 'unlimited_rss_profile',
			'reddit'        => 'unlimited_reddit_profile',
			'soundcloud'    => 'unlimited_soundcloud_profile',
			'spotify'       => 'unlimited_spotify_profile',
			'vine'          => 'unlimited_vine_profile',
			'yahoo'         => 'unlimited_yahoo_profile',
			'behance'       => 'unlimited_behance_profile',
			'codepen'       => 'unlimited_codepen_profile',
			'delicious'     => 'unlimited_delicious_profile',
			'stumbleupon'   => 'unlimited_stumbleupon_profile',
			'deviantart'    => 'unlimited_deviantart_profile',
			'digg'          => 'unlimited_digg_profile',
			'github'        => 'unlimited_github_profile',
			'hacker-news'   => 'unlimited_hacker-news_profile',
			'steam'         => 'unlimited_steam_profile',
			'vk'            => 'unlimited_vk_profile',
			'weibo'         => 'unlimited_weibo_profile',
			'tencent-weibo' => 'unlimited_tencent_weibo_profile',
			'email'         => 'unlimited_email_profile'
		);

		return apply_filters( 'unlimited_social_array_filter', $social_sites );
	}
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

	global $post;

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

	// add all historic singular classes
	if ( is_singular() ) {
		$classes[] = 'singular';
		if ( is_singular('page') ) {
			$classes[] = 'singular-page';
			$classes[] = 'singular-page-' . $post->ID;
		} elseif ( is_singular('post') ) {
			$classes[] = 'singular-post';
			$classes[] = 'singular-post-' . $post->ID;
		} elseif ( is_singular('attachment') ) {
			$classes[] = 'singular-attachment';
			$classes[] = 'singular-attachment-' . $post->ID;
		}
	}
	return $classes;
}
add_filter( 'body_class', 'unlimited_body_class' );

function unlimited_post_class( $classes ) {

	$classes[] = 'entry';

	return $classes;
}
add_filter( 'post_class', 'unlimited_post_class' );

// custom css output
function unlimited_custom_css_output(){

	$custom_css = get_theme_mod('custom_css');

	/* output custom css */
	if( $custom_css ) {
		$custom_css = wp_filter_nohtml_kses( $custom_css );
		wp_add_inline_style( 'style', $custom_css );
	}
}
add_action('wp_enqueue_scripts', 'unlimited_custom_css_output', 20);

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

function unlimited_loop_pagination(){

	global $wp_query;

	// If there's not more than one page, return nothing.
	if ( 1 >= $wp_query->max_num_pages ) {
		return;
	}

	/* Set up some default arguments for the paginate_links() function. */
	$defaults = array(
		'base'         => add_query_arg( 'paged', '%#%' ),
		'format'       => '',
		'mid_size'     => 1
	);

	$loop_pagination = '<nav class="pagination loop-pagination">';
	$loop_pagination .= paginate_links( $defaults );
	$loop_pagination .= '</nav>';

	return $loop_pagination;
}

// Adds useful meta tags
function unlimited_add_meta_elements() {

	$meta_elements = '';

	/* Charset */
	$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", get_bloginfo( 'charset' ) );

	/* Viewport */
	$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

	/* Theme name and current version */
	$theme    = wp_get_theme( get_template() );
	$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
	$meta_elements .= $template;

	echo $meta_elements;
}
add_action( 'wp_head', 'unlimited_add_meta_elements', 1 );

/* Move the WordPress generator to a better priority. */
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );