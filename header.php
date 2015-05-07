<!DOCTYPE html>

<!--[if IE 8 ]><html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]><html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>

	<!--[if IE 8 ]>
	<script src="<?php echo trailingslashit( get_template_directory_uri() ) . 'js/build/html5shiv.min.js'; ?>"></script>
	<![endif]-->

    <?php wp_head(); ?>

</head>

<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>

	<?php hybrid_do_atomic( 'body_before' ); ?>

	<!--skip to content link-->
	<a class="skip-content" id="skip-content" href="#main"><?php _e('Skip to content', 'unlimited'); ?></a>

	<div id="overflow-container" class="overflow-container">

		<header class="site-header" id="site-header" role="banner">

			<?php hybrid_do_atomic( 'header_before' ); ?>

			<?php unlimited_social_icons_output('header'); ?>

			<?php if( get_theme_mod( 'search_bar' ) != 'hide' ) get_template_part('content/search-bar'); ?>

			<div id="title-container" class="title-container">
				<?php get_template_part('logo')  ?>
				<p class="site-description"><?php bloginfo('description'); ?></p>
			</div>

			<?php hybrid_do_atomic( 'primary_menu_before' ); ?>

			<?php get_template_part( 'menu', 'primary' ); ?>

			<button id="toggle-navigation" class="toggle-navigation" aria-expanded="false">
				<span class="screen-reader-text"><?php _e('open menu', 'unlimited'); ?></span>
				<i class="fa fa-bars" title="<?php _e('primary menu icon', 'unlimited'); ?>"></i>
			</button>

			<?php hybrid_do_atomic( 'header_after' ); ?>

		</header>

		<?php hybrid_do_atomic( 'before_main' ); ?>

		<div class="max-width">

			<section id="main" class="main" role="main">

			<?php hybrid_do_atomic( 'main_before' ); ?>