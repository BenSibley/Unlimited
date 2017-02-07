<div <?php post_class(); ?>>
	<?php do_action( 'archive_post_before' ); ?>
	<article>
		<?php unlimited_featured_image(); ?>
		<?php get_template_part( 'content/post-meta' ); ?>
		<div class="post-padding-container">
			<div class='post-header'>
				<h2 class='post-title'><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
				</h2>
				<?php get_template_part( 'content/post-categories' ); ?>
			</div>
			<?php do_action( 'archive_post_content_after' ); ?>
			<div class="post-content">
				<?php ct_unlimited_excerpt(); ?>
			</div>
			<?php do_action( 'archive_post_content_after' ); ?>
		</div>
	</article>
	<?php do_action( 'archive_post_after' ); ?>
</div>