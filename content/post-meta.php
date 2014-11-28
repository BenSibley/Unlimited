<div id="post-meta" class="post-meta">
	<div class="date-meta">
		<i class="fa fa-calendar"></i>
		<a href="<?php echo get_month_link( get_the_date('Y'), get_the_date('n') ); ?>" title="Posts from <?php echo get_the_date('F'); ?>">
			<?php echo date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'n/j/Y' ) ) ); ?>
		</a>
	</div>
	<div class="author-meta">
		<i class="fa fa-user"></i>
		<?php the_author_posts_link(); ?>
	</div>
	<div class="comments-meta">
		<i class="fa fa-comment"></i>
		<a href="<?php echo get_comments_link(); ?>" title="Comments for this post">
			<?php
			if( ! comments_open() && get_comments_number() < 1 ) :
				comments_number( __( 'Comments closed', 'unlimited' ), __( 'One Comment', 'unlimited'), __( '% Comments', 'unlimited' ) );
			else :
				comments_number( __( 'Leave a Comment', 'unlimited' ), __( 'One Comment', 'unlimited'), __( '% Comments', 'unlimited' ) );
			endif;
			?>
		</a>
	</div>
</div>