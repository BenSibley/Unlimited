<div class="post-meta">
	<div class="date-meta">
		<a href="<?php echo esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) ) ); ?>"
		   title="<?php echo esc_attr__( 'Posts from', 'unlimited' ); ?> <?php echo get_the_date( 'F' ); ?>">
			<i class="fas fa-calendar" aria-hidden="true"></i>
			<span><?php echo date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'c' ) ) ); ?></span>
		</a>
	</div>
	<div class="author-meta">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
		   title="<?php echo esc_attr__( 'Posts by', 'unlimited' ); ?> <?php the_author(); ?>">
			<i class="fas fa-user" aria-hidden="true"></i>
			<span><?php the_author(); ?></span>
		</a>
	</div>
	<div class="comments-meta">
		<a href="<?php echo esc_url( get_comments_link() ); ?>" title="<?php echo esc_html__( 'Comments for this post', 'unlimited' ); ?>">
			<i class="fas fa-comment" aria-hidden="true"></i>
			<span>
				<?php
				if ( ! comments_open() && get_comments_number() < 1 ) :
					comments_number( esc_html__( 'Comments closed', 'unlimited' ), esc_html__( 'One Comment', 'unlimited' ), esc_html_x( '% Comments', 'noun: 5 comments', 'unlimited' ) );
				else :
					comments_number( esc_html__( 'Leave a Comment', 'unlimited' ), esc_html__( 'One Comment', 'unlimited' ), esc_html_x( '% Comments', 'noun: 5 comments', 'unlimited' ) );
				endif;
				?>
			</span>
		</a>
	</div>
</div>