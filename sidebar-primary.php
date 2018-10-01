<?php 

// don't output on WooCommerce pages like Cart and Checkout
if ( function_exists( 'is_woocommerce' ) ) {
	if ( is_cart() || is_checkout() || is_account_page() ) {
			return;
	}
}

if ( is_active_sidebar( 'primary' ) && !is_page_template( 'templates/full-width.php' ) ) : ?>
	<aside class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">
		<h1 class="screen-reader-text"><?php esc_html_e('Sidebar', 'unlimited'); ?></h1>
		<?php dynamic_sidebar( 'primary' ); ?>
	</aside>
<?php endif;