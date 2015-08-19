<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'unlimited_add_customizer_content' );

function unlimited_add_customizer_content( $wp_customize ) {

	/***** Reorder default sections *****/

	$wp_customize->get_section('title_tagline')->priority     = 1;
	$wp_customize->get_section('static_front_page')->priority = 5;
	$wp_customize->get_section('static_front_page')->title    = __('Front Page', 'unlimited');

	/***** Add PostMessage Support *****/
	
	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	/***** Add Custom Controls *****/

	// create url input control
	class unlimited_url_input_control extends WP_Customize_Control {
		// create new type called 'url'
		public $type = 'url';
		// the content to be output in the Customizer
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="url" <?php $this->link(); ?> value="<?php echo esc_url_raw( $this->value() ); ?>" />
			</label>
		<?php
		}
	}
	// number input control
	class unlimited_number_input_control extends WP_Customize_Control {
		public $type = 'number';

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="number" <?php $this->link(); ?> value="<?php echo $this->value(); ?>" />
			</label>
		<?php
		}
	}
	// create textarea control
	class unlimited_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
		<?php
		}
	}

	// create multi-checkbox/select control
	class unlimited_Multi_Checkbox_Control extends WP_Customize_Control {
		public $type = 'multi-checkbox';

		public function render_content() {

			if ( empty( $this->choices ) )
				return;
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select id="comment-display-control" <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
					<?php
					foreach ( $this->choices as $value => $label ) {
						$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
						echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
					}
					?>
				</select>
			</label>
		<?php }
	}
	// create ad controls
	class unlimited_description_color_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> to change your colors.', 'unlimited'), $link ) . "</p>";
		}
	}
	class unlimited_description_header_image_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> for advanced header image functionality.', 'unlimited'), $link ) . "</p>";
		}
	}
	class unlimited_description_background_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> for advanced background image and texture functionality.', 'unlimited'), $link ) . "</p>";
		}
	}
	class unlimited_description_font_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> to change your font.', 'unlimited'), $link ) . "</p>";
		}
	}
	class unlimited_description_fixed_menu_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> to change your menu to a fixed style.', 'unlimited'), $link ) . "</p>";
		}
	}
	class unlimited_description_display_control_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> to get hide/show controls.', 'unlimited'), $link ) . "</p>";
		}
	}
	class unlimited_description_footer_text_control extends WP_Customize_Control {

		public function render_content() {
			$link = 'https://www.competethemes.com/unlimited-pro/';
			echo "<p>" . sprintf( __('Activate the <a target="_blank" href="%s">Unlimited Pro Plugin</a> to customize the footer text.', 'unlimited'), $link ) . "</p>";
		}
	}


	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'unlimited_logo_upload', array(
		'title'      => __( 'Logo', 'unlimited' ),
		'priority'   => 20,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'logo_upload', array(
		'default'           => '',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'logo_image', array(
			'label'    => __( 'Upload custom logo.', 'unlimited' ),
			'section'  => 'unlimited_logo_upload',
			'settings' => 'logo_upload',
		)
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = unlimited_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'unlimited_social_media_icons', array(
		'title'          => __('Social Media Icons', 'unlimited'),
		'priority'       => 25,
	) );

	// create a setting and control for each social site
	foreach( $social_sites as $social_site => $value ) {
		// if email icon
		if( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( "$social_site", array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'unlimited_sanitize_email',
				'transport'         => 'postMessage'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'   => $social_site . ' ' . __('address:', 'unlimited' ),
				'section' => 'unlimited_social_media_icons',
				'priority'=> $priority,
			) );
		} else {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
				'transport'         => 'postMessage'
			) );
			// control
			$wp_customize->add_control( new unlimited_url_input_control(
				$wp_customize, $social_site, array(
					'label'   => $social_site . ' ' . __('url:', 'unlimited' ),
					'section' => 'unlimited_social_media_icons',
					'priority'=> $priority,
				)
			) );
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Search Bar *****/

	// section
	$wp_customize->add_section( 'unlimited_search_bar', array(
		'title'      => __( 'Search Bar', 'unlimited' ),
		'priority'   => 30,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'search_bar', array(
		'default'           => 'show',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'unlimited_sanitize_all_show_hide_settings',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'search_bar', array(
		'type' => 'radio',
		'label' => __('Show search bar at top of site?', 'unlimited'),
		'section' => 'unlimited_search_bar',
		'setting' => 'search_bar',
		'choices' => array(
			'show' => __('Show', 'unlimited'),
			'hide' => __('Hide', 'unlimited')
		),
	) );

	/***** Layout *****/

	// section
	$wp_customize->add_section( 'unlimited_layout', array(
		'title'      => __( 'Layouts', 'unlimited' ),
		'priority'   => 45,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'layout', array(
		'default'           => 'right',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'unlimited_sanitize_layout_settings',
		'transport'         => 'postMessage'
	) );

	$unlimited_pro = 'https://www.competethemes.com/unlimited-pro/';

	$description_layout = sprintf( __('Want more layouts? <a target="_blank" href="%s">Check out Unlimited Pro</a>', 'unlimited'), $unlimited_pro );

	// control
	$wp_customize->add_control( 'layout', array(
		'label'          => __( 'Choose your layout:', 'unlimited' ),
		'description'    => $description_layout,
		'section'        => 'unlimited_layout',
		'settings'       => 'layout',
		'type'           => 'radio',
		'choices'        => array(
			'right'   => __('Right sidebar', 'unlimited'),
			'left'  => __('Left sidebar', 'unlimited'),
		)
	) );

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'unlimited_blog', array(
		'title'      => __( 'Blog', 'unlimited' ),
		'priority'   => 60,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'unlimited_sanitize_yes_no_settings',
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'          => __( 'Show full posts on blog?', 'unlimited' ),
		'section'        => 'unlimited_blog',
		'settings'       => 'full_post',
		'type'           => 'radio',
		'choices'        => array(
			'yes'   => __('Yes', 'unlimited'),
			'no'  => __('No', 'unlimited'),
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '25',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_number_input_control(
		$wp_customize, 'excerpt_length', array(
			'label'          => __( 'Excerpt length', 'unlimited' ),
			'section'        => 'unlimited_blog',
			'settings'       => 'excerpt_length',
			'type'           => 'number',
		)
	) );

	/***** Comment Display *****/

	// section
	$wp_customize->add_section( 'unlimited_comments_display', array(
		'title'      => __( 'Comment Display', 'unlimited' ),
		'priority'   => 65,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'comments_display', array(
		'default'           => array('post','page','attachment','none'),
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'unlimited_sanitize_comments_setting',
	) );
	// control
	$wp_customize->add_control( new unlimited_Multi_Checkbox_Control(
		$wp_customize, 'comments_display', array(
			'label'          => __( 'Show comments on:', 'unlimited' ),
			'section'        => 'unlimited_comments_display',
			'settings'       => 'comments_display',
			'type'           => 'multi-checkbox',
			'choices'        => array(
				'post'   => __('Posts', 'unlimited'),
				'page'  => __('Pages', 'unlimited'),
				'attachment'  => __('Attachments', 'unlimited'),
				'none'  => __('Do not show', 'unlimited')
			)
		)
	) );

	/***** Custom CSS *****/

	// section
	$wp_customize->add_section( 'unlimited_custom_css', array(
		'title'      => __( 'Custom CSS', 'unlimited' ),
		'priority'   => 80,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'custom_css', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	) );
	// control
	$wp_customize->add_control( new unlimited_Textarea_Control(
		$wp_customize, 'custom_css', array(
			'label'          => __( 'Add Custom CSS Here:', 'unlimited' ),
			'section'        => 'unlimited_custom_css',
			'settings'       => 'custom_css',
		)
	) );

	/*
	 * PRO only sections
	 */

	/***** Header Image *****/

	// section
	$wp_customize->add_section( 'unlimited_header_image', array(
		'title'      => __( 'Header Image', 'unlimited' ),
		'priority'   => 35,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'header_image_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_header_image_control(
		$wp_customize, 'header_image_ad', array(
			'section'        => 'unlimited_header_image',
			'settings'       => 'header_image_ad'
		)
	) );

	/***** Colors *****/

	// section
	$wp_customize->add_section( 'unlimited_colors', array(
		'title'      => __( 'Colors', 'unlimited' ),
		'priority'   => 50,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'colors_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_color_control(
		$wp_customize, 'colors_ad', array(
			'section'        => 'unlimited_colors',
			'settings'       => 'colors_ad'
		)
	) );

	/***** Background *****/

	// section
	$wp_customize->add_section( 'unlimited_background', array(
		'title'      => __( 'Background', 'unlimited' ),
		'priority'   => 55,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'background_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_background_control(
		$wp_customize, 'background_ad', array(
			'section'        => 'unlimited_background',
			'settings'       => 'background_ad'
		)
	) );

	/***** Fonts *****/

	// section
	$wp_customize->add_section( 'unlimited_font', array(
		'title'      => __( 'Font', 'unlimited' ),
		'priority'   => 40,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'font_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_font_control(
		$wp_customize, 'font_ad', array(
			'section'        => 'unlimited_font',
			'settings'       => 'font_ad'
		)
	) );

	/***** Fixed Menu *****/

	// section
	$wp_customize->add_section( 'unlimited_fixed_menu', array(
		'title'      => __( 'Fixed Menu', 'unlimited' ),
		'priority'   => 15,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'fixed_menu_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_fixed_menu_control(
		$wp_customize, 'fixed_menu_ad', array(
			'section'        => 'unlimited_fixed_menu',
			'settings'       => 'fixed_menu_ad'
		)
	) );

	/***** Display Control *****/

	// section
	$wp_customize->add_section( 'unlimited_display_control', array(
		'title'      => __( 'Display Controls', 'unlimited' ),
		'priority'   => 70,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'display_control_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_display_control_control(
		$wp_customize, 'display_control_ad', array(
			'section'        => 'unlimited_display_control',
			'settings'       => 'display_control_ad'
		)
	) );

	/***** Footer Text *****/

	// section
	$wp_customize->add_section( 'unlimited_footer_text', array(
		'title'      => __( 'Footer Text', 'unlimited' ),
		'priority'   => 85,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'footer_text_ad', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'absint',
	) );
	// control
	$wp_customize->add_control( new unlimited_description_footer_text_control(
		$wp_customize, 'footer_text_ad', array(
			'section'        => 'unlimited_footer_text',
			'settings'       => 'footer_text_ad'
		)
	) );
}

/***** Custom Sanitization Functions *****/

/*
 * Sanitize settings with show/hide as options
 * Used in: search bar
 */
function unlimited_sanitize_all_show_hide_settings($input){
	// create array of valid values
	$valid = array(
		'show' => __('Show', 'unlimited'),
		'hide' => __('Hide', 'unlimited')
	);
	// if returned data is in array use it, else return nothing
	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function unlimited_sanitize_email( $input ) {

	return sanitize_email( $input );
}

// sanitize layout selection
function unlimited_sanitize_layout_settings($input){

	/*
	 * Also allow layouts only included in the premium plugin.
	 * Needs to be done this way b/c sanitize_callback cannot by updated
	 * via get_setting()
	 */
	$valid = array(
		'right'      => __( 'Right sidebar', 'unlimited' ),
		'left'       => __( 'Left sidebar', 'unlimited' ),
		'narrow'     => __( 'No sidebar - Narrow', 'unlimited' ),
		'wide'       => __( 'No sidebar - Wide', 'unlimited' ),
		'two-right'  => __( 'Two column - Right sidebar', 'unlimited' ),
		'two-left'   => __( 'Two column - Left sidebar', 'unlimited' ),
		'two-narrow' => __( 'Two column - No Sidebar - Narrow', 'unlimited' ),
		'two-wide'   => __( 'Two column - No Sidebar - Wide', 'unlimited' )
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

// sanitize yes/no settings
function unlimited_sanitize_yes_no_settings($input){

	$valid = array(
		'yes'   => __('Yes', 'unlimited'),
		'no'  => __('No', 'unlimited'),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

// sanitize comment display multi-check
function unlimited_sanitize_comments_setting($input){

	// valid data
	$valid = array(
		'post'   => __('Posts', 'unlimited'),
		'page'  => __('Pages', 'unlimited'),
		'attachment'  => __('Attachments', 'unlimited'),
		'none'  => __('Do not show', 'unlimited')
	);

	// loop through array
	foreach( $input as $selection ) {

		// if it's in the valid data, return it
		if ( array_key_exists( $selection, $valid ) ) {
			return $input;
		} else {
			return '';
		}
	}
}

/***** Helper Functions *****/

// ajax in search bar content when updated
function unlimited_update_search_bar_ajax(){

	// get the search bar content
	$response = get_template_part('content/search-bar');

	// return it
	echo $response;

	die();
}
add_action( 'wp_ajax_update_search_bar', 'unlimited_update_search_bar_ajax' );

// enable ajaxurl global variable on front-end / customizer
function unlimited_ajaxurl() { ?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
<?php
}
add_action('wp_head','unlimited_ajaxurl');

function unlimited_customize_preview_js() {

	$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"https://www.competethemes.com/unlimited-pro/\" target=\"_blank\">View the Unlimited Pro Plugin <span>&rarr;</span></a></div>');</script>";

	echo apply_filters('unlimited_customizer_ad', $content);
}
add_action('customize_controls_print_footer_scripts', 'unlimited_customize_preview_js');