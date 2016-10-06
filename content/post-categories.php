<?php

$categories = get_the_category( $post->ID );
$separator  = ', ';
$output     = __( 'Posted in', 'unlimited' ) . ' ';

if ( $categories ) {
	echo '<p class="post-categories">';
	foreach ( $categories as $category ) {
		if ( $category === end( $categories ) && $category !== reset( $categories ) ) {
			$output .= __( 'and', 'unlimited' ) . ' ';
		}
		$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'unlimited' ), $category->name ) ) . '">' . esc_html( $category->cat_name ) . '</a>' . $separator;
	}
	echo trim( $output, $separator );
	echo "</p>";
}