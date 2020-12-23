<?php do_action( 'main_after' ); ?>

</section><!-- .main -->

<?php get_sidebar( 'primary' ); ?>

</div><!-- .max-width -->
<?php 
// Elementor `footer` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
?>
<footer id="site-footer" class="site-footer" role="contentinfo">
	<?php do_action( 'footer_before' ); ?>
	<div class="footer-content">
		<h1 class="site-title">
			<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'title' ); ?></a>
		</h1>
		<p class="site-description"><?php bloginfo( 'description' ); ?></p>
	</div>
	<?php do_action( 'footer_widgets' ); ?>
	<div class="design-credit">
        <span>
            <?php
            $footer_text = sprintf( __( '<a target="_blank" href="%1$s" rel="nofollow">%2$s WordPress Theme</a> by Compete Themes', 'unlimited' ), 'https://www.competethemes.com/unlimited/', wp_get_theme( get_template() ) );
            $footer_text = apply_filters( 'footer_text', $footer_text );
            echo do_shortcode( wp_kses_post( $footer_text ) );
            ?>
        </span>
	</div>
	<?php do_action( 'footer_after' ); ?>
</footer>
<?php endif; ?>
</div><!-- .overflow-container -->

<?php do_action( 'body_after' ); ?>
<?php wp_footer(); ?>

</body>

</html>