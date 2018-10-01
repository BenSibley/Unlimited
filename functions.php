<?php
//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/deprecated.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/user-profile.php' );

//----------------------------------------------------------------------------------
//	Include review request
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'dnh/handler.php' );
new WP_Review_Me( array(
		'days_after' => 14,
		'type'       => 'theme',
		'slug'       => 'unlimited',
		'message'    => __( 'Hey! Sorry to interrupt, but you\'ve been using Unlimited for a little while now. If you\'re happy with this theme, could you take a minute to leave a review? <i>You won\'t see this notice again after closing it.</i>', 'unlimited' )
	)
);


if ( ! function_exists( ( 'ct_unlimited_set_content_width' ) ) ) {
	function ct_unlimited_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 655;
		}
	}
}
add_action( 'after_setup_theme', 'ct_unlimited_set_content_width', 0 );

if ( ! function_exists( 'unlimited_theme_setup' ) ) {
	function unlimited_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'unlimited_infinite_scroll_render'
		) );

		// Add WooCommerce support
		add_theme_support( 'woocommerce' );
		// Add support for WooCommerce image gallery features
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		load_theme_textdomain( 'unlimited', get_template_directory() . '/languages' );

		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'unlimited' )
		) );
	}
}
add_action( 'after_setup_theme', 'unlimited_theme_setup', 10 );

if ( ! function_exists( 'unlimited_register_widget_areas' ) ) {
	function unlimited_register_widget_areas() {
		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'unlimited' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar next to the main post content', 'unlimited' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'unlimited_register_widget_areas' );

if ( ! function_exists( 'unlimited_customize_comments' ) ) {
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
					<span><?php comment_author_link(); ?></span> <?php esc_html_x( 'said:', 'the commenter said the following:', 'unlimited' ); ?>
				</div>
			</div>
			<div class="comment-content">
				<?php
				if ( $comment->comment_approved == '0' ) :
					echo "<em>" . esc_html__( 'Your comment is awaiting moderation.', 'unlimited' ) . "</em><br />";
				endif;
				comment_text(); ?>
				<div class="comment-date"><?php comment_date(); ?></div>
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => esc_html_x( 'Reply', 'verb', 'unlimited' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'before'     => '|'
				) ) ); ?>
				<?php edit_comment_link( esc_html_x( 'Edit', 'verb', 'unlimited' ), '|' ); ?>
			</div>
		</article>
		<?php
	}
}

if ( ! function_exists( 'unlimited_update_fields' ) ) {
	function unlimited_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . esc_html__( '(optional)', 'unlimited' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
	            <label for="author">' . esc_html_x( "Name", "noun", "unlimited" ) . $label . '</label>
	            <input placeholder="' . esc_attr__( "John Doe", "unlimited" ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';
		$fields['email'] =
			'<p class="comment-form-email">
	            <label for="email">' . esc_html_x( "Email", "noun", "unlimited" ) . $label . '</label>
	            <input placeholder="' . esc_attr__( "name@email.com", "unlimited" ) . '" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
	        </p>';
		$fields['url'] =
			'<p class="comment-form-url">
	            <label for="url">' . esc_html__( "Website", "unlimited" )  . '</label>
	            <input placeholder="' . esc_attr__( "http://example.com", "unlimited" ) . '" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'unlimited_update_fields' );

if ( ! function_exists( 'unlimited_update_comment_field' ) ) {
	function unlimited_update_comment_field( $comment_field ) {

		// don't filter the WooCommerce review form
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( is_woocommerce() ) {
				return $comment_field;
			}
		}
		
		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . esc_html_x( "Comment", "noun", "unlimited" ) . '</label>
	            <textarea required placeholder="' . esc_attr__( "Enter Your Comment", "unlimited" ) . '&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'unlimited_update_comment_field' );

if ( ! function_exists( 'unlimited_remove_comments_notes_after' ) ) {
	function unlimited_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'unlimited_remove_comments_notes_after' );

if ( ! function_exists( 'ct_unlimited_filter_read_more_link' ) ) {
	function ct_unlimited_filter_read_more_link( $custom = false ) {
		global $post;
		$ismore             = strpos( $post->post_content, '<!--more-->' );
		$read_more_text     = get_theme_mod( 'read_more_text' );
		$new_excerpt_length = get_theme_mod( 'excerpt_length' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';
		$output = '';

		// add ellipsis for automatic excerpts
		if ( empty( $ismore ) && $custom !== true  ) {
			$output .= $excerpt_more;
		}
		// Because i18n text cannot be stored in a variable
		if ( empty( $read_more_text ) ) {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read more', 'unlimited' ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		} else {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		}
		return $output;
	}
}
add_filter( 'the_content_more_link', 'ct_unlimited_filter_read_more_link' ); // more tags
add_filter( 'excerpt_more', 'ct_unlimited_filter_read_more_link', 10 ); // automatic excerpts

// handle manual excerpts 
if ( ! function_exists( 'ct_unlimited_filter_manual_excerpts' ) ) {
	function ct_unlimited_filter_manual_excerpts( $excerpt ) {
		$excerpt_more = '';
		if ( has_excerpt() ) {
			$excerpt_more = ct_unlimited_filter_read_more_link( true );
		}
		return $excerpt . $excerpt_more;
	}
}
add_filter( 'get_the_excerpt', 'ct_unlimited_filter_manual_excerpts' );

if ( ! function_exists( 'ct_unlimited_excerpt' ) ) {
	function ct_unlimited_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( $show_full_post === 'yes' || $ismore ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( 'unlimited_custom_excerpt_length' ) ) {
	function unlimited_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 25 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 25;
		}
	}
}
add_filter( 'excerpt_length', 'unlimited_custom_excerpt_length', 99 );

if ( ! function_exists( 'unlimited_remove_more_link_scroll' ) ) {
	function unlimited_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'unlimited_remove_more_link_scroll' );

// Yoast OG description has "Read the postTitle of the Post" due to its use of get_the_excerpt(). This fixes that.
function ct_unlimited_update_yoast_og_description( $ogdesc ) {
	$read_more_text = get_theme_mod( 'read_more_text' );
	if ( empty( $read_more_text ) ) {
		$read_more_text = esc_html__( 'Read more', 'unlimited' );
	}
	$ogdesc = substr( $ogdesc, 0, strpos( $ogdesc, $read_more_text ) );

	return $ogdesc;
}
add_filter( 'wpseo_opengraph_desc', 'ct_unlimited_update_yoast_og_description' );

if ( ! function_exists( 'unlimited_featured_image' ) ) {
	function unlimited_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_unlimited_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

if ( ! function_exists( 'unlimited_social_array' ) ) {
	function unlimited_social_array() {

		$social_sites = array(
			'twitter'       => 'unlimited_twitter_profile',
			'facebook'      => 'unlimited_facebook_profile',
			'instagram'     => 'unlimited_instagram_profile',
			'linkedin'      => 'unlimited_linkedin_profile',
			'pinterest'     => 'unlimited_pinterest_profile',
			'youtube'       => 'unlimited_youtube_profile',
			'rss'           => 'unlimited_rss_profile',
			'email'         => 'unlimited_email_profile',
			'phone'         => 'unlimited_phone_profile',
			'email_form'    => 'unlimited_email_form',
			'amazon'        => 'unlimited_amazon_profile',
			'bandcamp'      => 'unlimited_bandcamp_profile',
			'behance'       => 'unlimited_behance_profile',
			'bitbucket'     => 'unlimited_bitbucket_profile',
			'codepen'       => 'unlimited_codepen_profile',
			'delicious'     => 'unlimited_delicious_profile',
			'deviantart'    => 'unlimited_deviantart_profile',
			'digg'          => 'unlimited_digg_profile',
			'discord'       => 'unlimited_discord_profile',
			'dribbble'      => 'unlimited_dribbble_profile',
			'etsy'          => 'unlimited_etsy_profile',
			'flickr'        => 'unlimited_flickr_profile',
			'foursquare'    => 'unlimited_foursquare_profile',
			'github'        => 'unlimited_github_profile',
			'google-plus'   => 'unlimited_google_plus_profile',
			'google-wallet' => 'unlimited_google-wallet_profile',
			'hacker-news'   => 'unlimited_hacker-news_profile',
			'meetup'        => 'unlimited_meetup_profile',
			'mixcloud'      => 'unlimited_mixcloud_profile',
			'ok-ru'         => 'unlimited_ok_ru_profile',
			'paypal'        => 'unlimited_paypal_profile',
			'podcast'       => 'unlimited_podcast_profile',
			'qq'            => 'unlimited_qq_profile',
			'quora'         => 'unlimited_quora_profile',
			'ravelry'       => 'unlimited_ravelry_profile',
			'reddit'        => 'unlimited_reddit_profile',
			'skype'         => 'unlimited_skype_profile',
			'slack'         => 'unlimited_slack_profile',
			'slideshare'    => 'unlimited_slideshare_profile',
			'snapchat'      => 'unlimited_snapchat_profile',
			'soundcloud'    => 'unlimited_soundcloud_profile',
			'spotify'       => 'unlimited_spotify_profile',
			'stack-overflow' => 'unlimited_stack_overflow_profile',
			'steam'         => 'unlimited_steam_profile',
			'stumbleupon'   => 'unlimited_stumbleupon_profile',
			'telegram'      => 'unlimited_telegram_profile',
			'tencent-weibo' => 'unlimited_tencent_weibo_profile',
			'tumblr'        => 'unlimited_tumblr_profile',
			'twitch'        => 'unlimited_twitch_profile',
			'vimeo'         => 'unlimited_vimeo_profile',
			'vine'          => 'unlimited_vine_profile',
			'vk'            => 'unlimited_vk_profile',
			'wechat'        => 'unlimited_wechat_profile',
			'weibo'         => 'unlimited_weibo_profile',
			'whatsapp'      => 'unlimited_whatsapp_profile',
			'xing'          => 'unlimited_xing_profile',
			'yahoo'         => 'unlimited_yahoo_profile',
			'yelp'          => 'unlimited_yelp_profile',
			'500px'         => 'unlimited_500px_profile'
		);

		return apply_filters( 'unlimited_social_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'unlimited_social_icons_output' ) ) {
	function unlimited_social_icons_output( $source ) {

		$social_sites = unlimited_social_array();
		$square_icons = array(
			'twitter',
			'vimeo',
			'youtube',
			'pinterest',
			'reddit',
			'tumblr',
			'steam',
			'xing',
			'github',
			'google-plus',
			'behance',
			'facebook'
		);

		// store the site name and url
		foreach ( $social_sites as $social_site => $profile ) {

			if ( $source == 'header' ) {
				if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
					$active_sites[ $social_site ] = $social_site;
				}
			} elseif ( $source == 'author' ) {
				if ( strlen( get_the_author_meta( $profile ) ) > 0 ) {
					$active_sites[ $profile ] = $social_site;
				}
			}
		}

		if ( ! empty( $active_sites ) ) {

			echo "<ul class='social-media-icons'>";

			foreach ( $active_sites as $key => $active_site ) {

				// get the URL
				if ( $source == 'author' ) {
					$url = get_the_author_meta( $key );
				} elseif ( $source == 'header' ) {
					$url = get_theme_mod( $active_site );
				}

				// get the class (square OR plain)
				if ( in_array( $active_site, $square_icons ) ) {
					$class = 'fab fa-' . $active_site . '-square';
				} elseif ( $active_site == 'rss' ) {
					$class = 'fas fa-rss';
				} elseif ( $active_site == 'email_form' ) {
					$class = 'far fa-envelope';
				} elseif ( $active_site == 'podcast' ) {
					$class = 'fas fa-podcast';
				} elseif ( $active_site == 'ok-ru' ) {
					$class = 'fab fa-odnoklassniki';
				} elseif ( $active_site == 'wechat' ) {
					$class = 'fab fa-weixin';
				} elseif ( $active_site == 'phone' ) {
					$class = 'fas fa-phone';
				} else {
					$class = 'fab fa-' . $active_site;
				}

				if ( $active_site == 'email' ) {
					?>
					<li>
						<a class="email" target="_blank"
						   href="mailto:<?php echo antispambot( is_email( $url ) ); ?>">
							<i class="fas fa-envelope" title="<?php echo esc_attr_x( 'email', 'noun', 'unlimited' ); ?>"></i>
							<span class="screen-reader-text"><?php echo esc_html_x('email', 'noun', 'unlimited'); ?></span>
						</a>
					</li>
				<?php
				} elseif ( $active_site == 'skype' ) { ?>
					<li>
						<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
						   href="<?php echo esc_url( $url, array( 'http', 'https', 'skype') ); ?>">
							<i class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $active_site ); ?>"></i>
							<span class="screen-reader-text"><?php echo esc_html( $active_site ); ?></span>
						</a>
					</li>
				<?php } elseif ( $active_site == 'phone' ) { ?>
					<li>
						<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
								href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'tel' ) ); ?>">
							<i class="<?php echo esc_attr( $class ); ?>"></i>
							<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
						</a>
					</li>
				<?php } else { ?>
					<li>
						<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
						   href="<?php echo esc_url( $url ); ?>">
							<i class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $active_site ); ?>"></i>
							<span class="screen-reader-text"><?php echo esc_html( $active_site ); ?></span>
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
if ( ! function_exists( 'unlimited_wp_page_menu' ) ) {
	function unlimited_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset"
			)
		);
	}
}

if ( ! function_exists( 'unlimited_body_class' ) ) {
	function unlimited_body_class( $classes ) {

		global $post;

		$layout    = get_theme_mod( 'layout' );
		$full_post = get_theme_mod( 'full_post' );

		if ( $layout == 'left' ) {
			$classes[] = 'left-sidebar';
		}
		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}

		// add all historic singular classes
		if ( is_singular() ) {
			$classes[] = 'singular';
			if ( is_singular( 'page' ) ) {
				$classes[] = 'singular-page';
				$classes[] = 'singular-page-' . $post->ID;
			} elseif ( is_singular( 'post' ) ) {
				$classes[] = 'singular-post';
				$classes[] = 'singular-post-' . $post->ID;
			} elseif ( is_singular( 'attachment' ) ) {
				$classes[] = 'singular-attachment';
				$classes[] = 'singular-attachment-' . $post->ID;
			}
		}

		return $classes;
	}
}
add_filter( 'body_class', 'unlimited_body_class' );

if ( ! function_exists( 'unlimited_post_class' ) ) {
	function unlimited_post_class( $classes ) {
		$classes[] = 'entry';

		return $classes;
	}
}
add_filter( 'post_class', 'unlimited_post_class' );

if ( ! function_exists( 'unlimited_custom_css_output' ) ) {
	function unlimited_custom_css_output() {

		if ( function_exists( 'wp_get_custom_css' ) ) {
			$custom_css = wp_get_custom_css();
		} else {
			$custom_css = get_theme_mod( 'custom_css' );
		}

		if ( $custom_css ) {
			$custom_css = ct_unlimited_sanitize_css( $custom_css );
			wp_add_inline_style( 'style', $custom_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'unlimited_custom_css_output', 20 );

if ( ! function_exists( 'unlimited_sticky_post_marker' ) ) {
	function unlimited_sticky_post_marker() {

		if ( is_sticky() && !is_archive() && !is_search() ) {
			echo '<span class="sticky-status">' . esc_html__( "Featured Post", "unlimited" ) . '</span>';
		}
	}
}
add_action( 'archive_post_before', 'unlimited_sticky_post_marker' );

if ( ! function_exists( 'unlimited_reset_customizer_options' ) ) {
	function unlimited_reset_customizer_options() {

		// validate name and value
		if ( empty( $_POST['unlimited_reset_customizer'] ) || 'unlimited_reset_customizer_settings' !== $_POST['unlimited_reset_customizer'] ) {
			return;
		}
		// validate nonce
		if ( ! wp_verify_nonce( $_POST['unlimited_reset_customizer_nonce'], 'unlimited_reset_customizer_nonce' ) ) {
			return;
		}
		// validate user permissions
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'logo_upload',
			'search_bar',
			'layout',
			'full_post',
			'excerpt_length',
			'read_more_text',
			'comments_display',
			'custom_css'
		);

		$social_sites = unlimited_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'unlimited_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=unlimited-options' );
		$redirect = add_query_arg( 'unlimited_status', 'deleted', $redirect );

		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'unlimited_reset_customizer_options' );

if ( ! function_exists( 'unlimited_delete_settings_notice' ) ) {
	function unlimited_delete_settings_notice() {

		if ( isset( $_GET['unlimited_status'] ) ) {

			if ( $_GET['unlimited_status'] == 'deleted' ) {
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Customizer settings deleted.', 'unlimited' ); ?></p>
				</div>
				<?php
			} else if ( $_GET['unlimited_status'] == 'activated' ) {
				?>
				<div class="updated">
					<p><?php printf( esc_html__( '%s successfully activated!', 'unlimited' ), wp_get_theme( get_template() ) ); ?></p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_notices', 'unlimited_delete_settings_notice' );

if ( ! function_exists( 'unlimited_add_meta_elements' ) ) {
	function unlimited_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'unlimited_add_meta_elements', 1 );

/* Move the WordPress generator to a better priority. */
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );

if ( ! function_exists( 'unlimited_infinite_scroll_render' ) ) {
	function unlimited_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

if ( ! function_exists( 'unlimited_get_content_template' ) ) {
	function unlimited_get_content_template() {

		if ( is_home() || is_archive() ) {
			get_template_part( 'content-archive', get_post_type() );
		} else {
			get_template_part( 'content', get_post_type() );
		}
	}
}

// allow skype URIs to be used
if ( ! function_exists( 'ct_unlimited_allow_skype_protocol' ) ) {
	function ct_unlimited_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';
		$protocols[] = 'whatsapp';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_unlimited_allow_skype_protocol' );

if ( ! function_exists( 'ct_unlimited_nav_dropdown_buttons' ) ) {
	function ct_unlimited_nav_dropdown_buttons( $item_output, $item, $depth, $args ) {

		if ( $args->theme_location == 'primary' ) {

			if ( in_array( 'menu-item-has-children', $item->classes ) || in_array( 'page_item_has_children', $item->classes ) ) {
				$item_output = str_replace( $args->link_after . '</a>', $args->link_after . '</a><button class="toggle-dropdown" aria-expanded="false" name="toggle-dropdown"><span class="screen-reader-text">' . esc_html__( "open dropdown menu", "unlimited" ) . '</span></button>', $item_output );
			}
		}

		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_unlimited_nav_dropdown_buttons', 10, 4 );

// trigger theme switch on link click and send to Appearance menu
function ct_unlimited_welcome_redirect() {

	$welcome_url = add_query_arg(
		array(
			'page'             => 'unlimited-options',
			'unlimited_status' => 'activated',
		),
		admin_url( 'themes.php' )
	);
	wp_safe_redirect( esc_url_raw( $welcome_url ) );
}
add_action( 'after_switch_theme', 'ct_unlimited_welcome_redirect' );

//----------------------------------------------------------------------------------
// Add paragraph tags for author bio displayed in content/archive-header.php.
// the_archive_description includes paragraph tags for tag and category descriptions, but not the author bio. 
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_unlimited_modify_archive_descriptions' ) ) {
	function ct_unlimited_modify_archive_descriptions( $description ) {

		if ( is_author() ) {
			$description = wpautop( $description );
		}
		return $description;
	}
}
add_filter( 'get_the_archive_description', 'ct_unlimited_modify_archive_descriptions' );

//----------------------------------------------------------------------------------
// Output the markup for the optional scroll-to-top arrow 
//----------------------------------------------------------------------------------
function ct_unlimited_scroll_to_top_arrow() {
	$setting = get_theme_mod('scroll_to_top');
	
	if ( $setting == 'yes' ) {
		echo '<button id="scroll-to-top" class="scroll-to-top"><span class="screen-reader-text">'. __('Scroll to the top', 'unlimited') .'</span><i class="fa fa-arrow-up"></i></button>';
	}
}
add_action( 'body_after', 'ct_unlimited_scroll_to_top_arrow');