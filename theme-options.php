<?php

function unlimited_register_theme_page() {
	add_theme_page( __( 'Unlimited Dashboard', 'unlimited' ), __( 'Unlimited Dashboard', 'unlimited' ), 'edit_theme_options', 'unlimited-options', 'unlimited_options_content', 'unlimited_options_content' );
}
add_action( 'admin_menu', 'unlimited_register_theme_page' );

function unlimited_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => site_url(),
			'return' => add_query_arg( 'page', 'unlimited-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	?>
	<div id="unlimited-dashboard-wrap" class="wrap">
		<h2><?php _e( 'Unlimited Dashboard', 'unlimited' ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="content content-customization">
			<h3><?php _e( 'Customization', 'unlimited' ); ?></h3>
			<p><?php _e( 'Click the "Customize" link in your menu, or use the button below to get started customizing Unlimited.', 'unlimited' ); ?></p>
			<p>
				<a class="button-primary"
				   href="<?php echo esc_url( $customizer_url ); ?>"><?php _e( 'Use Customizer', 'unlimited' ) ?></a>
			</p>
		</div>
		<div class="content content-support">
			<h3><?php _e( 'Support', 'unlimited' ); ?></h3>
			<p><?php _e( "You can find the knowledgebase, changelog, support forum, and more in the Unlimited Support Center.", "unlimited" ); ?></p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/documentation/unlimited-support-center/"><?php _e( 'Visit Support Center', 'unlimited' ); ?></a>
			</p>
		</div>
		<div class="content content-premium-upgrade">
			<h3><?php _e( 'Unlimited Pro Plugin', 'unlimited' ); ?></h3>
			<p><?php _e( 'Download the Unlimited Pro plugin and unlock custom colors, new layouts, background images, and more...', 'unlimited' ); ?></p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/unlimited-pro/"><?php _e( 'See Full Feature List', 'unlimited' ); ?></a>
			</p>
		</div>
		<div class="content content-resources">
			<h3><?php _e( 'WordPress Resources', 'unlimited' ); ?></h3>
			<p><?php _e( "Save time and money searching for WordPress products by following our recommendations.", "unlimited" ); ?></p>
			<p>
				<a target="_blank" class="button-primary"
				   href="https://www.competethemes.com/wordpress-resources/"><?php _e( 'View Resources', 'unlimited' ); ?></a>
			</p>
		</div>
		<div class="content content-delete-settings">
			<h3><?php _e( 'Reset Unlimited Settings', 'unlimited' ); ?></h3>
			<p>
				<?php printf( __( "<strong>Warning:</strong> Clicking this button will erase the Unlimited theme's current settings in the <a href='%s'>Customizer</a>", 'unlimited' ), esc_url( $customizer_url ) ); ?>
			</p>
			<form method="post">
				<input type="hidden" name="unlimited_reset_customizer" value="unlimited_reset_customizer_settings"/>
				<p>
					<?php wp_nonce_field( 'unlimited_reset_customizer_nonce', 'unlimited_reset_customizer_nonce' ); ?>
					<?php submit_button( __( 'Reset Customizer Settings', 'unlimited' ), 'delete', 'delete', false ); ?>
				</p>
			</form>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }