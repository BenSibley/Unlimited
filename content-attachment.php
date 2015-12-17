<div <?php post_class(); ?>>
	<?php do_action( 'attachment_before' ); ?>
	<article>
		<div class="post-padding-container">
			<div class='post-header'>
				<h1 class='post-title'><?php the_title(); ?></h1>
			</div>
			<?php do_action( 'attachment_content_before' ); ?>
			<div class="post-content">
				<?php the_content(); ?>
			</div>
			<?php do_action( 'attachment_content_after' ); ?>
			<?php get_template_part( 'content/post-nav-attachment' ); ?>
			<?php comments_template(); ?>
		</div>
	</article>
	<?php do_action( 'attachment_after' ); ?>
</div>