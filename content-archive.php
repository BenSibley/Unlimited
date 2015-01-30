<div <?php post_class(); ?>>
	<?php hybrid_do_atomic( 'archive_post_before' ); ?>
	<article>
		<?php unlimited_featured_image(); ?>
		<?php get_template_part('content/post-meta'); ?>
		<div class="post-padding-container">
			<div class='post-header'>
				<h1 class='post-title'><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php get_template_part('content/post-categories'); ?>
			</div>
			<?php hybrid_do_atomic( 'archive_post_content_after' ); ?>
			<div class="post-content">
				<?php unlimited_excerpt(); ?>
			</div>
			<?php hybrid_do_atomic( 'archive_post_content_after' ); ?>
		</div>
	</article>
	<?php hybrid_do_atomic( 'archive_post_after' ); ?>
</div>