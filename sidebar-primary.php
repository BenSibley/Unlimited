<?php if ( is_active_sidebar( 'primary' ) && ! is_page_template('templates/full-width.php') ) : ?>

    <aside class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">

        <?php dynamic_sidebar( 'primary' ); ?>

    </aside><!-- #sidebar-primary -->

<?php endif; ?>