<!DOCTYPE html>
<!--[if IE 8 ]>
<html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
	<?php wp_head(); ?>
</head>

<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>
	<?php do_action( 'body_before' ); ?>
	<a class="skip-content" id="skip-content" href="#main"><?php esc_html_e( 'Skip to content', 'unlimited' ); ?></a>
	<div id="overflow-container" class="overflow-container">
		<header class="site-header" id="site-header" role="banner">
			<?php do_action( 'header_before' ); ?>
			<div id="header-inner" class="header-inner">
				<?php unlimited_social_icons_output( 'header' ); ?>
				<?php
				if ( get_theme_mod( 'search_bar' ) != 'hide' ) {
					get_template_part( 'content/search-bar' );
				}
				?>
				<div id="title-container" class="title-container">
					<?php get_template_part( 'logo' ); ?>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				</div>
			</div>
			<?php do_action( 'primary_menu_before' ); ?>
			<?php get_template_part( 'menu', 'primary' ); ?>
			<button id="toggle-navigation" class="toggle-navigation" aria-expanded="false">
				<span class="screen-reader-text"><?php echo esc_html_x( 'open menu', 'verb: open the menu', 'unlimited' ); ?></span>
				<i class="fas fa-bars" title="<?php esc_html_e( 'primary menu icon', 'unlimited' ); ?>"></i>
			</button>
			<?php do_action( 'header_after' ); ?>
		</header>
		<?php do_action( 'before_main' ); ?>
		<div class="max-width">
			<?php if ( function_exists('yoast_breadcrumb') ) {
				yoast_breadcrumb( '<p id="breadcrumbs">' , '</p>' );
			} ?>
			<section id="main" class="main" role="main">
				<?php do_action( 'main_before' ); ?>