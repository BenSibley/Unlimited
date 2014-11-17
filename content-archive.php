<div <?php post_class(); ?>>
	<?php ct_unlimited_featured_image(); ?>
	<div class="post-meta">
		<?php get_template_part('content/post-meta'); ?>
	</div>
	<div class='post-header'>
		<h1 class='post-title'><?php the_title(); ?></h1>
		<p class="post-categories">Posted in Marketing, and Design</p>
	</div>
	<div class="post-content">
		<article>
			<?php ct_unlimited_excerpt(); ?>
		</article>
	</div>
</div>