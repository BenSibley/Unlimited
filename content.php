<div <?php post_class(); ?>>
	<?php hybrid_do_atomic( 'post_before' ); ?>
	<article>
	    <?php ct_unlimited_featured_image(); ?>
        <?php get_template_part('content/post-meta'); ?>
		<div class="post-padding-container">
		    <div class='post-header'>
		        <h1 class='post-title'><?php the_title(); ?></h1>
				<?php get_template_part('content/post-categories'); ?>
		    </div>
			<?php hybrid_do_atomic( 'post_content_before' ); ?>
		    <div class="post-content">
		        <?php the_content(); ?>
		        <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','unlimited'), 'after' => '</p>', ) ); ?>
		    </div>
			<?php hybrid_do_atomic( 'post_content_after' ); ?>
			<?php get_template_part('content/post-nav'); ?>
			<?php get_template_part('content/post-author'); ?>
		    <?php get_template_part('content/post-tags'); ?>
			<?php comments_template(); ?>
		</div>
	</article>
	<?php hybrid_do_atomic( 'post_after' ); ?>
</div>