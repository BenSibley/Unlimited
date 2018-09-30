<?php

$categories = get_the_category( $post->ID );
$separator  = ', ';
$output     = esc_html_x( 'Posted in', 'posted in category', 'unlimited' ) . ' ';

if ( $categories ) {
	echo '<p class="post-categories">';
	foreach ( $categories as $category ) {
		if ( $category === end( $categories ) && $category !== reset( $categories ) ) {
			$output .= esc_html_x( 'and', 'category 1, category 2, AND category 3', 'unlimited' ) . ' ';
		}
		$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( esc_html_x( "View all posts in %s", 'View all posts in category', 'unlimited' ), $category->name ) ) . '">' . esc_html( $category->cat_name ) . '</a>' . $separator;
	}
	echo trim( $output, $separator );
	echo "</p>";
}