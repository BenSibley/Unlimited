<div class="post-meta">
	<div class="date-meta">
		<a href="<?php echo esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) ) ); ?>"
		   title="Posts from <?php echo get_the_date( 'F' ); ?>">
			<i class="fa fa-calendar" aria-hidden="true"></i>
			<span><?php echo date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'r' ) ) ); ?></span>
		</a>
	</div>
	<div class="author-meta">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
		   title="Posts by <?php the_author(); ?>">
			<i class="fa fa-user" aria-hidden="true"></i>
			<span><?php the_author(); ?></span>
		</a>
	</div>
	<div class="comments-meta">
		<a href="<?php echo esc_url( get_comments_link() ); ?>" title="Comments for this post">
			<i class="fa fa-comment" aria-hidden="true"></i>
			<span>
				<?php
				if ( ! comments_open() && get_comments_number() < 1 ) :
					comments_number( __( 'Comments closed', 'unlimited' ), __( 'One Comment', 'unlimited' ), _x( '% Comments', 'noun: 5 comments', 'unlimited' ) );
				else :
					comments_number( __( 'Leave a Comment', 'unlimited' ), __( 'One Comment', 'unlimited' ), _x( '% Comments', 'noun: 5 comments', 'unlimited' ) );
				endif;
				?>
			</span>
		</a>
	</div>
</div>