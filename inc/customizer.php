<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_unlimited_add_customizer_content' );

function ct_unlimited_add_customizer_content( $wp_customize ) {

	/***** Add PostMessage Support *****/
	
	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	
	/***** Add Custom Controls *****/

	// create url input control
	class ct_unlimited_url_input_control extends WP_Customize_Control {
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
	class ct_unlimited_number_input_control extends WP_Customize_Control {
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
	class ct_unlimited_Textarea_Control extends WP_Customize_Control {
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
	class ct_unlimited_Multi_Checkbox_Control extends WP_Customize_Control {
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

	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'ct_unlimited_logo_upload', array(
		'title'      => __( 'Logo Upload', 'unlimited' ),
		'priority'   => 30,
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
			'section'  => 'ct_unlimited_logo_upload',
			'settings' => 'logo_upload',
		)
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_unlimited_social_site_list();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_unlimited_social_media_icons', array(
		'title'          => __('Social Media Icons', 'unlimited'),
		'priority'       => 35,
	) );

	// create a setting and control for each social site
	foreach( $social_sites as $social_site ) {
		// if email icon
		if( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( "$social_site", array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'ct_unlimited_sanitize_email',
				'transport'         => 'postMessage'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'   => $social_site . ' ' . __('address:', 'unlimited' ),
				'section' => 'ct_unlimited_social_media_icons',
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
			$wp_customize->add_control( new ct_unlimited_url_input_control(
				$wp_customize, $social_site, array(
					'label'   => $social_site . ' ' . __('url:', 'unlimited' ),
					'section' => 'ct_unlimited_social_media_icons',
					'priority'=> $priority,
				)
			) );
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	/***** Search Bar *****/

	// section
	$wp_customize->add_section( 'ct_unlimited_search_bar', array(
		'title'      => __( 'Search Bar', 'unlimited' ),
		'priority'   => 40,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'search_bar', array(
		'default'           => 'show',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_unlimited_sanitize_all_show_hide_settings'
	) );
	// control
	$wp_customize->add_control( 'search_bar', array(
		'type' => 'radio',
		'label' => __('Show search bar at top of site?', 'unlimited'),
		'section' => 'ct_unlimited_search_bar',
		'setting' => 'search_bar',
		'choices' => array(
			'show' => __('Show', 'unlimited'),
			'hide' => __('Hide', 'unlimited')
		),
	) );

	/***** Layout *****/

	// section
	$wp_customize->add_section( 'ct_unlimited_layout', array(
		'title'      => __( 'Layout', 'unlimited' ),
		'priority'   => 50,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'layout', array(
		'default'           => 'right',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_unlimited_sanitize_layout_settings',
	) );
	// control
	$wp_customize->add_control( 'layout', array(
		'label'          => __( 'Choose Your Layout:', 'unlimited' ),
		'section'        => 'ct_unlimited_layout',
		'settings'       => 'layout',
		'type'           => 'radio',
		'choices'        => array(
			'right'   => __('Right sidebar', 'unlimited'),
			'left'  => __('Left sidebar', 'unlimited'),
		)
	) );

	/***** Blog *****/

	// section
	$wp_customize->add_section( 'ct_unlimited_blog', array(
		'title'      => __( 'Blog', 'unlimited' ),
		'priority'   => 60,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_unlimited_sanitize_yes_no_settings',
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'          => __( 'Show full posts on blog?', 'unlimited' ),
		'section'        => 'ct_unlimited_blog',
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
	$wp_customize->add_control( new ct_unlimited_number_input_control(
		$wp_customize, 'excerpt_length', array(
			'label'          => __( 'Excerpt length', 'unlimited' ),
			'section'        => 'ct_unlimited_blog',
			'settings'       => 'excerpt_length',
			'type'           => 'number',
		)
	) );

	/***** Comment Display *****/

	// section
	$wp_customize->add_section( 'ct_unlimited_comments_display', array(
		'title'      => __( 'Comments', 'unlimited' ),
		'priority'   => 70,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'comments_display', array(
		'default'           => 'none',
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'ct_unlimited_sanitize_comments_setting',
	) );
	// control
	$wp_customize->add_control( new ct_unlimited_Multi_Checkbox_Control(
		$wp_customize, 'comments_display', array(
			'label'          => __( 'Show comments on:', 'unlimited' ),
			'section'        => 'ct_unlimited_comments_display',
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
	$wp_customize->add_section( 'ct_unlimited_custom_css', array(
		'title'      => __( 'Custom CSS', 'unlimited' ),
		'priority'   => 90,
		'capability' => 'edit_theme_options'
	) );
	// setting
	$wp_customize->add_setting( 'custom_css', array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_textarea',
	) );
	// control
	$wp_customize->add_control( new ct_unlimited_Textarea_Control(
		$wp_customize, 'custom_css', array(
			'label'          => __( 'Add Custom CSS Here:', 'unlimited' ),
			'section'        => 'ct_unlimited_custom_css',
			'settings'       => 'custom_css',
		)
	) );
}

/***** Custom Sanitization Functions *****/

/*
 * Sanitize settings with show/hide as options
 * Used in: search bar
 */
function ct_unlimited_sanitize_all_show_hide_settings($input){
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
function ct_unlimited_sanitize_email( $input ) {

	return sanitize_email( $input );
}

// sanitize layout selection
function ct_unlimited_sanitize_layout_settings($input){
	$valid = array(
		'right'   => __('Right sidebar', 'unlimited'),
		'left'  => __('Left sidebar', 'unlimited'),
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

// sanitize yes/no settings
function ct_unlimited_sanitize_yes_no_settings($input){

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
function ct_unlimited_sanitize_comments_setting($input){

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

/*
 * Sets default values in customizer.
 * Otherwise, they will be empty until a visitor visits and saves changed in the Customizer
 */
function ct_unlimited_set_customizer_values() {

	// search bar
	if( ! get_theme_mod('search_bar') ) {
		set_theme_mod( 'search_bar', 'show' );
	}
	// layout
	if( ! get_theme_mod('layout') ) {
		set_theme_mod( 'layout', 'right' );
	}
	// full post
	if( ! get_theme_mod('full_post') ) {
		set_theme_mod( 'full_post', 'no' );
	}
	// excerpt length
	if( ! get_theme_mod('excerpt_length') ) {
		set_theme_mod( 'excerpt_length', '25' );
	}
	// comments display
	if( ! get_theme_mod( 'comments_display' ) ) {
		set_theme_mod( 'comments_display', array( 'post', 'page', 'attachment', 'none' ) );
	}
}
add_action( 'init', 'ct_unlimited_set_customizer_values' );

// add the ad for Unlimited Pro
function ct_unlimited_customize_preview_js() { ?>
	<script>
		jQuery('#customize-info').append('<div class="upgrades-ad"><a href="<?php echo esc_url('https://www.competethemes.com/unlimited-pro/');?>" target="_blank"><?php _e('Premium Upgrade Available!','unlimited');?> <span>&rarr;</span></a></div>');
	</script>
<?php }

add_action('customize_controls_print_footer_scripts', 'ct_unlimited_customize_preview_js');