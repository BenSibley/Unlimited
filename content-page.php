<div <?php post_class(); ?>>
	<?php hybrid_do_atomic( 'page_before' ); ?>
	<article>
		<?php ct_unlimited_featured_image(); ?>
		<div class="post-padding-container">
			<div class='post-header'>
				<h1 class='post-title'><?php the_title(); ?></h1>
			</div>
			<?php hybrid_do_atomic( 'page_content_before' ); ?>
			<div class="post-content">
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','unlimited'), 'after' => '</p>', ) ); ?>
			</div>
			<?php hybrid_do_atomic( 'page_content_after' ); ?>
			<?php comments_template(); ?>
		</div>
	</article>
	<?php hybrid_do_atomic( 'page_after' ); ?>
</div>