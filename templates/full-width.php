<?php
/**
 * Template Name: Full-width
 */
?>

<?php get_header(); ?>

<div <?php post_class(); ?>>
	<article>
		<?php ct_unlimited_featured_image(); ?>
		<div class="post-padding-container">
			<div class='post-header'>
				<h1 class='post-title'><?php the_title(); ?></h1>
			</div>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','unlimited'), 'after' => '</p>', ) ); ?>
			</div>
			<?php comments_template(); ?>
		</div>
	</article>
</div>

<?php get_footer(); ?>