<?php
/**
 * Template Name: Full-width
 */

get_header();

// The loop
if ( have_posts() ) :
	while (have_posts() ) :
		the_post();
		get_template_part( 'content', 'page' );
	endwhile;
endif;

get_footer();