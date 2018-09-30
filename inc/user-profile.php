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
				<h3><?php esc_html_e( 'Social Profiles', 'unlimited' ); ?></h3>
			</th>
		</tr>
		<?php
		foreach ( $social_sites as $key => $social_site ) {

			$label = ucfirst( $key );

			if ( $key == 'google-plus' ) {
				$label = esc_html__('Google Plus', 'unlimited');
			} elseif ( $key == 'rss' ) {
				$label = esc_html__('RSS', 'unlimited');
			} elseif ( $key == 'soundcloud' ) {
				$label = esc_html__('SoundCloud', 'unlimited');
			} elseif ( $key == 'slideshare' ) {
				$label = esc_html__('SlideShare', 'unlimited');
			} elseif ( $key == 'codepen' ) {
				$label = esc_html__('CodePen', 'unlimited');
			} elseif ( $key == 'stumbleupon' ) {
				$label = esc_html__('StumbleUpon', 'unlimited');
			} elseif ( $key == 'deviantart' ) {
				$label = esc_html__('DeviantArt', 'unlimited');
			} elseif ( $key == 'hacker-news' ) {
				$label = esc_html__('Hacker News', 'unlimited');
			} elseif ( $key == 'google-wallet' ) {
				$label = esc_html__('Google Wallet', 'unlimited');
			} elseif ( $key == 'whatsapp' ) {
				$label = esc_html__('WhatsApp', 'unlimited');
			} elseif ( $key == 'qq' ) {
				$label = esc_html__('QQ', 'unlimited');
			} elseif ( $key == 'vk' ) {
				$label = esc_html__('VK', 'unlimited');
			} elseif ( $key == 'wechat' ) {
				$label = esc_html__('WeChat', 'unlimited');
			} elseif ( $key == 'tencent-weibo' ) {
				$label = esc_html__('Tencent Weibo', 'unlimited');
			} elseif ( $key == 'paypal' ) {
				$label = esc_html__('PayPal', 'unlimited');
			} elseif ( $key == 'email_form' ) {
				$label = esc_html__('Contact Form', 'unlimited');
			} elseif ( $key == 'stack-overflow' ) {
				$label = esc_html__('Stack Overflow', 'unlimited');
			} elseif ( $key == 'ok-ru' ) {
				$label = esc_html__('OK.ru', 'unlimited');
			}

			?>
			<tr>
				<th>
					<?php if ( $key == 'email' ) : ?>
						<label for="<?php echo esc_attr($key); ?>-profile"><?php esc_html_e( 'Email Address', 'unlimited' ); ?></label>
					<?php else : ?>
						<label for="<?php echo esc_attr($key); ?>-profile"><?php echo esc_html($label); ?></label>
					<?php endif; ?>
				</th>
				<td>
					<?php if ( $key == 'email' ) { ?>
						<input type='text' id='<?php echo esc_attr($key); ?>-profile' class='regular-text'
						       name='<?php echo esc_attr($key); ?>-profile'
						       value='<?php echo is_email( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
					<?php } elseif ( $key == 'skype' ) { ?>
						<input type='url' id='<?php echo esc_attr($key); ?>-profile' class='regular-text'
						       name='<?php echo esc_attr($key); ?>-profile'
						       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ), array( 'http', 'https', 'skype' ) ); ?>'/>
					<?php } else { ?>
						<input type='url' id='<?php echo esc_attr($key); ?>-profile' class='regular-text'
						       name='<?php echo esc_attr($key); ?>-profile'
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