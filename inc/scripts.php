<?php

/*
 * Front-end scripts
 */
function ct_unlimited_load_scripts_styles() {

	// main JS file
	wp_enqueue_script('ct-unlimited-js', get_template_directory_uri() . '/js/build/production.min.js#ct_unlimited_asyncload', array('jquery'),'', true);

	// Google Fonts (required to register outside scripts first)
	wp_register_style( 'ct-unlimited-google-fonts', '//fonts.googleapis.com/css?family=Open+Sans:400,300');
	wp_enqueue_style('ct-unlimited-google-fonts');

	// Font Awesome (named generically to allow overriding)
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css');

	// load stylesheet
	if( is_rtl() ) {
		wp_enqueue_style('style-rtl', get_template_directory_uri() . '/styles/rtl.min.css');
	} else {
		wp_enqueue_style('style', get_template_directory_uri() . 'style.min.css');
	}

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if( is_singular() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action('wp_enqueue_scripts', 'ct_unlimited_load_scripts_styles' );

/*
 * Back-end scripts
 */
function ct_unlimited_enqueue_admin_styles($hook){

	// if is user profile page
	if('profile.php' == $hook || 'user-edit.php' == $hook ){

		// Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs.
		wp_enqueue_media();

		// enqueue the JS needed to utilize media uploader on profile image upload
		wp_enqueue_script('ct-unlimited-profile-image-uploader', get_template_directory_uri() . '/js/build/profile-image-uploader.min.js#ct_unlimited_asyncload');
	}
	// if theme options page
	if( 'appearance_page_unlimited-options' == $hook ) {

		// Admin styles
		wp_enqueue_style('ct-unlimited-admin-styles', get_template_directory_uri() . '/styles/admin.min.css');
	}
}
add_action('admin_enqueue_scripts',	'ct_unlimited_enqueue_admin_styles' );

/*
 * Customizer scripts
 */
function ct_unlimited_enqueue_customizer_scripts(){

	// stylesheet for customizer
	wp_enqueue_style('ct-customizer-styles', get_template_directory_uri() . '/styles/customizer.min.css');

	// JS for all customizer screen modifications
	wp_enqueue_script('ct-unlimited-customizer-js', get_template_directory_uri() . '/js/build/customizer.min.js',array('jquery'),'',true);

}
add_action('customize_controls_enqueue_scripts','ct_unlimited_enqueue_customizer_scripts');

/*
 * Script for live updating with customizer options. Has to be loaded separately on customize_preview_init hook
 * transport => postMessage
 */
function ct_unlimited_enqueue_customizer_post_message_scripts(){

	// JS for live updating with customizer input
	wp_enqueue_script('ct-unlimited-customizer-post-message-js', get_template_directory_uri() . '/js/build/postMessage.min.js',array('jquery'),'',true);

}
add_action('customize_preview_init','ct_unlimited_enqueue_customizer_post_message_scripts');

// load scripts asynchronously
function ct_unlimited_add_async_script($url) {

	// if async parameter not present, do nothing
	if (strpos($url, '#ct_unlimited_asyncload') === false){
		return $url;
	}
	// if async parameter present, add async attribute
	return str_replace('#ct_unlimited_asyncload', '', $url)."' async='async";
}
add_filter('clean_url', 'ct_unlimited_add_async_script', 11, 1);

// add custom editor styles
function ct_unlimited_add_editor_styles() {
	add_editor_style( 'styles/custom-editor-style.min.css' );
}
add_action( 'admin_init', 'ct_unlimited_add_editor_styles' );