<div class="post-author">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 60, '', get_the_author() ); ?>
	<h2>
		<?php echo get_the_author(); ?>
	</h2>
	<?php unlimited_social_icons_output( 'author' ) ?>
	<p>
		<?php the_author_meta( 'description' ); ?>
	</p>
	<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php esc_html_e( 'View more posts', 'unlimited' ); ?></a>
</div>