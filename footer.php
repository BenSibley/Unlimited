</div> <!-- .main -->

<?php get_sidebar( 'primary' ); ?>

</div> <!-- .overflow-container -->

<footer class="site-footer" role="contentinfo">
    <h3><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('title'); ?></a></h3>
    <span><?php bloginfo('description'); ?></span>
    <div class="design-credit">
        <span>
            <?php
                $site_url = 'http://www.competethemes.com/unlimited/';
                $footer_text = sprintf( __( '<a target="_blank" href="%s">Unlimited WordPress Theme</a> by Compete Themes.', 'unlimited' ), esc_url( $site_url ) );
                echo $footer_text;
            ?>
        </span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>