</section> <!-- .main -->

<?php get_sidebar( 'primary' ); ?>

<footer class="site-footer" role="contentinfo">
	<div class="footer-content">
	    <h4><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo('title'); ?></a></h4>
		<p><?php bloginfo('description'); ?></p>
	</div>
    <div class="design-credit">
        <span>
            <?php
                $site_url = 'https://www.competethemes.com/unlimited/';
                $footer_text = sprintf( __( '<a target="_blank" href="%s">Unlimited WordPress Theme</a> by Compete Themes', 'unlimited' ), esc_url( $site_url ) );
                echo $footer_text;
            ?>
        </span>
    </div>
</footer>

</div>

<?php wp_footer(); ?>
</body>
</html>