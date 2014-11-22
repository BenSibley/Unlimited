<?php get_header(); ?>

<?php

/* Category header */
if( is_category() ){ ?>
	<div class='archive-header'>
		<i class="fa fa-folder-open"></i>
		<h2>
			<?php _e('Category archive for:', 'unlimited'); ?>
			<?php single_cat_title(); ?>
		</h2>
	</div>
<?php
}
/* Tag header */
elseif( is_tag() ){ ?>
	<div class='archive-header'>
		<i class="fa fa-tag"></i>
		<h2>
			<?php _e('Tag:', 'unlimited'); ?>
			<?php single_tag_title(); ?>
		</h2>
	</div>
<?php
}
/* Author header */
elseif( is_author() ){ ?>
	<div class='archive-header'>
	<p><?php _e('These Posts are by:', 'unlimited'); ?></p><?php
	$author = get_userdata(get_query_var('author')); ?>
	<h2><?php echo $author->nickname; ?></h2>
	</div><?php
}

// The loop
if ( have_posts() ) :
    while (have_posts() ) :
        the_post();

        /* Blog */
        if( is_home() ) {
            get_template_part( 'content', 'archive' );
        }
        /* Post */
        elseif( is_singular( 'post' ) ) {
            get_template_part( 'content' );
        }
        /* Page */
        elseif( is_page() ) {
            get_template_part( 'content', 'page' );
        }
        /* Attachment */
        elseif( is_attachment() ) {
            get_template_part( 'content', 'attachment' );
        }
        /* Archive */
        elseif( is_archive() ) {
            get_template_part( 'content', 'archive' );
        }
        /* Custom Post Type */
        else {
            get_template_part( 'content' );
        }
    endwhile;
endif; ?>

<?php ct_unlimited_post_navigation(); ?>

<?php get_footer(); ?>