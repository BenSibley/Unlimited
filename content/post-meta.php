<div id="post-meta" class="post-meta">
	<div class="date-meta">
		<a href="<?php echo get_month_link( get_the_date('Y'), get_the_date('n') ); ?>" title="Posts from <?php echo get_the_date('F'); ?>">
			<i class="fa fa-calendar"></i>
			<span><?php echo date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'n/j/Y' ) ) ); ?></span>
		</a>
	</div>
	<div class="author-meta">
		<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="Posts by <?php the_author(); ?>">
			<i class="fa fa-user"></i>
			<span><?php the_author(); ?></span>
		</a>
	</div>
	<div class="comments-meta">
		<a href="<?php echo get_comments_link(); ?>" title="Comments for this post">
			<i class="fa fa-comment"></i>
			<span>
				<?php
				if( ! comments_open() && get_comments_number() < 1 ) :
					comments_number( __( 'Comments closed', 'unlimited' ), __( 'One Comment', 'unlimited'), __( '% Comments', 'unlimited' ) );
				else :
					comments_number( __( 'Leave a Comment', 'unlimited' ), __( 'One Comment', 'unlimited'), __( '% Comments', 'unlimited' ) );
				endif;
				?>
			</span>
		</a>
	</div>
</div>