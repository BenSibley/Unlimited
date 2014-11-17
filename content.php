<div <?php post_class(); ?>>
    <?php ct_unlimited_featured_image(); ?>
    <div class="post-meta">
        <?php get_template_part('content/post-meta'); ?>
    </div>
    <div class='post-header'>
        <h1 class='post-title'><?php the_title(); ?></h1>
		<?php get_template_part('content/post-categories'); ?>
    </div>
    <div class="post-content">
        <article>
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','unlimited'), 'after' => '</p>', ) ); ?>
        </article>
    </div>
	<?php get_template_part('content/post-nav'); ?>
	<?php get_template_part('content/post-author'); ?>
    <?php get_template_part('content/post-tags'); ?>
	<?php comments_template(); ?>
</div>