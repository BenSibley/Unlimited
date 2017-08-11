<?php

function unlimited_register_theme_page() {
	add_theme_page( sprintf( __( '%s Dashboard', 'unlimited' ), wp_get_theme( get_template() ) ), sprintf( __( '%s Dashboard', 'unlimited' ), wp_get_theme( get_template() ) ), 'edit_theme_options', 'unlimited-options', 'unlimited_options_content', 'unlimited_options_content' );
}
add_action( 'admin_menu', 'unlimited_register_theme_page' );

function unlimited_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => get_home_url(),
			'return' => add_query_arg( 'page', 'unlimited-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	?>
	<div id="unlimited-dashboard-wrap" class="wrap">
		<h2><?php printf( __( '%s Dashboard', 'unlimited' ), wp_get_theme( get_template() ) ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php _e( 'Get Started', 'unlimited' ); ?></h3>
				<p><?php printf( __( 'Not sure where to start? The <strong>%1$s Getting Started Guide</strong> will take you step-by-step through every feature in %1$s.', 'unlimited' ), wp_get_theme( get_template() ) ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/help/getting-started-unlimited/"><?php _e( 'View Guide', 'unlimited' ); ?></a>
				</p>
			</div>
			<?php if ( !function_exists( 'ct_unlimited_pro_init' ) ) : ?>
				<div class="content content-premium-upgrade">
					<h3><?php printf( __( '%s Pro Plugin', 'unlimited' ), wp_get_theme( get_template() ) ); ?></h3>
					<p><?php printf( __( 'Download the %s Pro plugin and unlock custom colors, new layouts, background images, and more...', 'unlimited' ), wp_get_theme( get_template() ) ); ?></p>
					<p>
						<a target="_blank" class="button-primary"
						   href="https://www.competethemes.com/unlimited-pro/"><?php _e( 'See Full Feature List', 'unlimited' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
			<div class="content content-review">
				<h3><?php _e( 'Leave a Review', 'unlimited' ); ?></h3>
				<p><?php printf( __( 'Help others find %s by leaving a review on wordpress.org.', 'unlimited' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/unlimited/reviews/"><?php _e( 'Leave a Review', 'unlimited' ); ?></a>
			</div>
			<div class="content content-presspad">
				<h3><?php esc_html_e( 'Turn Unlimited into a Mobile App', 'unlimited' ); ?></h3>
				<p><?php printf( esc_html__( '%s can be converted into a mobile app and listed on the App Store and Google Play Store with the help of PressPad News. Read our tutorial to learn more.', 'unlimited' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://www.competethemes.com/help/convert-mobile-app-unlimited/"><?php esc_html_e( 'Read Tutorial', 'unlimited' ); ?></a>
			</div>
			<div class="content content-delete-settings">
				<h3><?php printf( __( 'Reset %s Settings', 'unlimited' ), wp_get_theme( get_template() ) ); ?></h3>
				<p>
					<?php printf( __( '<strong>Warning:</strong> Clicking this button will erase the %2$s theme\'s current settings in the <a href="%1$s">Customizer</a>.', 'unlimited' ), esc_url( $customizer_url ), wp_get_theme( get_template() ) ); ?>
				</p>
				<form method="post">
					<input type="hidden" name="unlimited_reset_customizer" value="unlimited_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'unlimited_reset_customizer_nonce', 'unlimited_reset_customizer_nonce' ); ?>
						<?php submit_button( __( 'Reset Customizer Settings', 'unlimited' ), 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }