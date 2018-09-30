<?php

function unlimited_add_social_profile_settings( $user ) {

	$user_id = get_current_user_id();

	if ( ! current_user_can( 'edit_posts', $user_id ) ) {
		return false;
	}

	$social_sites = unlimited_social_array();

	?>
	<table class="form-table">
		<tr>
			<th>
				<h3><?php _e( 'Social Profiles', 'unlimited' ); ?></h3>
			</th>
		</tr>
		<?php
		foreach ( $social_sites as $key => $social_site ) {

			$label = ucfirst( $key );

			if ( $key == 'google-plus' ) {
				$label = __('Google Plus', 'unlimited');
			} elseif ( $key == 'rss' ) {
				$label = __('RSS', 'unlimited');
			} elseif ( $key == 'soundcloud' ) {
				$label = __('SoundCloud', 'unlimited');
			} elseif ( $key == 'slideshare' ) {
				$label = __('SlideShare', 'unlimited');
			} elseif ( $key == 'codepen' ) {
				$label = __('CodePen', 'unlimited');
			} elseif ( $key == 'stumbleupon' ) {
				$label = __('StumbleUpon', 'unlimited');
			} elseif ( $key == 'deviantart' ) {
				$label = __('DeviantArt', 'unlimited');
			} elseif ( $key == 'hacker-news' ) {
				$label = __('Hacker News', 'unlimited');
			} elseif ( $key == 'google-wallet' ) {
				$label = __('Google Wallet', 'unlimited');
			} elseif ( $key == 'whatsapp' ) {
				$label = __('WhatsApp', 'unlimited');
			} elseif ( $key == 'qq' ) {
				$label = __('QQ', 'unlimited');
			} elseif ( $key == 'vk' ) {
				$label = __('VK', 'unlimited');
			} elseif ( $key == 'wechat' ) {
				$label = __('WeChat', 'unlimited');
			} elseif ( $key == 'tencent-weibo' ) {
				$label = __('Tencent Weibo', 'unlimited');
			} elseif ( $key == 'paypal' ) {
				$label = __('PayPal', 'unlimited');
			} elseif ( $key == 'email_form' ) {
				$label = __('Contact Form', 'unlimited');
			} elseif ( $key == 'stack-overflow' ) {
				$label = __('Stack Overflow', 'unlimited');
			} elseif ( $key == 'ok-ru' ) {
				$label = __('OK.ru', 'unlimited');
			}

			?>
			<tr>
				<th>
					<?php if ( $key == 'email' ) : ?>
						<label for="<?php echo $key; ?>-profile"><?php _e( 'Email Address', 'unlimited' ); ?></label>
					<?php else : ?>
						<label for="<?php echo $key; ?>-profile"><?php echo $label; ?></label>
					<?php endif; ?>
				</th>
				<td>
					<?php if ( $key == 'email' ) { ?>
						<input type='text' id='<?php echo $key; ?>-profile' class='regular-text'
						       name='<?php echo $key; ?>-profile'
						       value='<?php echo is_email( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
					<?php } elseif ( $key == 'skype' ) { ?>
						<input type='url' id='<?php echo $key; ?>-profile' class='regular-text'
						       name='<?php echo $key; ?>-profile'
						       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ), array( 'http', 'https', 'skype' ) ); ?>'/>
					<?php } else { ?>
						<input type='url' id='<?php echo $key; ?>-profile' class='regular-text'
						       name='<?php echo $key; ?>-profile'
						       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	</table>
	<?php
}

add_action( 'show_user_profile', 'unlimited_add_social_profile_settings' );
add_action( 'edit_user_profile', 'unlimited_add_social_profile_settings' );

function unlimited_save_social_profiles( $user_id ) {

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	$social_sites = unlimited_social_array();

	foreach ( $social_sites as $key => $social_site ) {
		if ( $key == 'email' ) {
			// if email, only accept 'mailto' protocol
			if ( isset( $_POST["$key-profile"] ) ) {
				update_user_meta( $user_id, $social_site, sanitize_email( $_POST["$key-profile"] ) );
			}
		} elseif ( $key == 'skype' ) {
			// accept skype protocol
			if ( isset( $_POST["$key-profile"] ) ) {
				update_user_meta( $user_id, $social_site, esc_url_raw( $_POST["$key-profile"], array( 'http', 'https', 'skype' ) ) );
			}
		} else {
			if ( isset( $_POST["$key-profile"] ) ) {
				update_user_meta( $user_id, $social_site, esc_url_raw( $_POST["$key-profile"] ) );
			}
		}
	}
}

add_action( 'personal_options_update', 'unlimited_save_social_profiles' );
add_action( 'edit_user_profile_update', 'unlimited_save_social_profiles' );