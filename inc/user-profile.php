<?php

// add the social profile boxes to the user screen.
function unlimited_add_social_profile_settings($user) {

	// get current user ID
	$user_id = get_current_user_id();

	// only added for contributors and above
	if ( ! current_user_can( 'edit_posts', $user_id ) ) return false;

	// get social sites
	$social_sites = unlimited_social_array();

	?>
	<table class="form-table">
		<tr>
			<th><h3><?php _e('Social Profiles', 'unlimited'); ?></h3></th>
		</tr>
		<?php
		foreach($social_sites as $key => $social_site) { ?>
			<tr>
				<th>
					<?php if( $key == 'email' ) : ?>
						<label for="<?php echo $key; ?>-profile"><?php echo ucfirst($key); ?> <?php _e('Address:', 'unlimited'); ?></label>
					<?php else : ?>
						<label for="<?php echo $key; ?>-profile"><?php echo ucfirst($key); ?> <?php _e('Profile:', 'unlimited'); ?></label>
					<?php endif; ?>
				</th>
				<td>
					<?php if( $key == 'email' ) : ?>
						<input type='text' id='<?php echo $key; ?>-profile' class='regular-text' name='<?php echo $key; ?>-profile' value='<?php echo is_email(get_the_author_meta($social_site, $user->ID )); ?>' />
					<?php else : ?>
						<input type='url' id='<?php echo $key; ?>-profile' class='regular-text' name='<?php echo $key; ?>-profile' value='<?php echo esc_url(get_the_author_meta($social_site, $user->ID )); ?>' />
					<?php endif; ?>
				</td>
			</tr>
		<?php }	?>
	</table>
<?php
}

add_action( 'show_user_profile', 'unlimited_add_social_profile_settings' );
add_action( 'edit_user_profile', 'unlimited_add_social_profile_settings' );

function unlimited_save_social_profiles($user_id) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) { return false; }

	$social_sites = unlimited_social_array();

	foreach ($social_sites as $key => $social_site) {
		if( $key == 'email' ) {
			// if email, only accept 'mailto' protocol
			if( isset( $_POST["$key-profile"] ) ){
				update_user_meta( $user_id, $social_site, sanitize_email( $_POST["$key-profile"] ) );
			}
		} else {
			if( isset( $_POST["$key-profile"] ) ){
				update_user_meta( $user_id, $social_site, esc_url_raw( $_POST["$key-profile"] ) );
			}
		}
	}
}

add_action( 'personal_options_update', 'unlimited_save_social_profiles' );
add_action( 'edit_user_profile_update', 'unlimited_save_social_profiles' );