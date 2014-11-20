<div <?php post_class(); ?>>
	<article>
		<?php ct_unlimited_featured_image(); ?>
		<?php get_template_part('content/post-meta'); ?>
		<div class="post-padding-container">
			<div class='post-header'>
				<h1 class='post-title'><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php get_template_part('content/post-categories'); ?>
			</div>
			<div class="post-content">
				<?php ct_unlimited_excerpt(); ?>
			</div>
		</div>
	</article>
</div>