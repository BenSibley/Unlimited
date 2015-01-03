<?php hybrid_do_atomic( 'main_after' ); ?>

</section> <!-- .main -->

<?php get_sidebar( 'primary' ); ?>

</div><!-- .max-width -->

<footer id="site-footer" class="site-footer" role="contentinfo">

	<?php hybrid_do_atomic( 'footer_before' ); ?>

	<div class="footer-content">
	    <h4 class="site-title"><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo('title'); ?></a></h4>
		<p class="site-description"><?php bloginfo('description'); ?></p>
	</div>

	<?php hybrid_do_atomic( 'footer_widgets' ); ?>

    <div class="design-credit">
        <span>
            <?php
                $site_url = 'https://www.competethemes.com/unlimited/?utm_source=Footer%20Link&utm_medium=Referral&utm_campaign=Unlimited%20Footer%20Link';
                $footer_text = sprintf( __( '<a target="_blank" href="%s">Unlimited WordPress Theme</a> by Compete Themes', 'unlimited' ), esc_url( $site_url ) );
                echo apply_filters('footer_text', $footer_text );
            ?>
        </span>
    </div>

	<?php hybrid_do_atomic( 'footer_after' ); ?>

</footer>

</div><!-- .overflow-container -->

<?php wp_footer(); ?>

<!--[if IE 8 ]>
<script src="<?php echo trailingslashit( get_template_directory_uri() ) . 'js/build/respond.min.js'; ?>"></script>
<![endif]-->

<?php hybrid_do_atomic( 'body_after' ); ?>

</body>

</html>