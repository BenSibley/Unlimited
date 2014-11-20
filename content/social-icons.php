<?php

$social_sites = ct_unlimited_social_site_list();

// any inputs that aren't empty are stored in $active_sites array
foreach($social_sites as $social_site) {
	if( strlen( get_theme_mod( $social_site ) ) > 0 ) {
		$active_sites[] = $social_site;
	}
}

// for each active social site, add it as a list item
if( ! empty($active_sites) ) {

	echo "<ul class='social-media-icons'>";

	foreach ($active_sites as $active_site) {

		if( $active_site == 'email' ) { ?>
			<li><a class="email" target="_blank" href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>"><i class="fa fa-envelope"></i></a></li>
		<?php } elseif( $active_site ==  "flickr" || $active_site ==  "dribbble" || $active_site ==  "instagram" || $active_site ==  "soundcloud" || $active_site ==  "spotify" || $active_site ==  "vine" || $active_site ==  "yahoo" || $active_site ==  "codepen" || $active_site ==  "delicious" || $active_site ==  "stumbleupon" || $active_site ==  "deviantart" || $active_site ==  "digg" || $active_site ==  "hacker-news" || $active_site == "vk" || $active_site == 'weibo' || $active_site == 'tencent-weibo') { ?>
			<li><a class="<?php echo $active_site; ?>" target="_blank" href="<?php echo esc_url(get_theme_mod( $active_site )); ?>"><i class="fa fa-<?php echo esc_attr($active_site); ?>"></i></a></li>
		<?php } else { ?>
			<li><a class="<?php echo $active_site; ?>" target="_blank" href="<?php echo esc_url(get_theme_mod( $active_site )); ?>"><i class="fa fa-<?php echo esc_attr($active_site); ?>-square"></i></a></li>
		<?php }
	}

	echo "</ul>";
}