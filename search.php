<?php get_header(); ?>
	<div class="post-header search-header">
		<h1 class="post-title">
			<?php
			global $wp_query;
			$total_results = $wp_query->found_posts;
			$s             = htmlentities( $s );
			if ( $total_results ) {
				printf( _n( '%d search result for "%s"', '%d search results for "%s"', $total_results, 'unlimited' ), $total_results, $s );
			} else {
				printf( __( 'No search results for "%s"', 'unlimited' ), $s );
			}
			?>
		</h1>
		<?php get_search_form(); ?>
	</div>
	<div id="loop-container" class="loop-container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				get_template_part( 'content', 'archive' );
			endwhile;
		endif;
		?>
	</div>
<?php
the_posts_pagination();

// only display bottom search bar if there are search results
$total_results = $wp_query->found_posts;
if ( $total_results ) {
	?>
	<div class="post-header search-header bottom">
		<p><?php _e( "Can't find what you're looking for?  Try refining your search:", "unlimited" ); ?></p>
		<?php get_search_form(); ?>
	</div>
<?php }
get_footer(); ?>