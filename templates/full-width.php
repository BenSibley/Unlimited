<?php
/**
 * Template Name: Full-width
 */

get_header(); ?>

<div id="loop-container" class="loop-container">
	<?php
	if ( have_posts() ) :
		while (have_posts() ) :
			the_post();
			get_template_part( 'content', 'page' );
		endwhile;
	endif;
	?>
</div>

<?php get_footer();