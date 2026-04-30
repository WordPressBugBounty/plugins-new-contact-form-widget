<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// All Query Page Code
add_action( 'admin_menu', 'cfw_menus' );
function cfw_menus() {
	add_menu_page( 'Contact Form Queries', __( 'Contact Form Queries', 'new-contact-form-widget' ),  'administrator', 'cfw-all-queries', 'cfw_all_queries', 'dashicons-email-alt', 65);
	add_submenu_page( 'cfw-all-queries', 'Settings', __( 'Settings', 'new-contact-form-widget' ), 'administrator','cfw-settings','cfw_settings');
	add_submenu_page( 'cfw-all-queries', 'Our Themes', __( 'Our Themes', 'new-contact-form-widget' ), 'administrator','cfw-our-themes','cfw_our_themes_page');
	add_submenu_page( 'cfw-all-queries', 'Our Plugins', __( 'Our Plugins', 'new-contact-form-widget' ), 'administrator','cfw-our-plugins','cfw_our_plugins_page');
}

//all contact queries page body function
function cfw_all_queries() {
	require_once('all-query-page.php');
}

// theme page
function cfw_our_themes_page() {
	require_once('our-themes.php');
}

// plugins page
function cfw_our_plugins_page() {
	require_once('our-plugins.php');
}

// setting page body
function cfw_settings() {
	require_once('settings-page.php');	
}

// enqueue admin assets
add_action( 'admin_enqueue_scripts', 'cfw_admin_assets' );
function cfw_admin_assets($hook) {
	if(strpos($hook, 'cfw-our-themes') !== false || strpos($hook, 'cfw-our-plugins') !== false) {
		wp_enqueue_style( 'cfw-our-plugins-style', plugin_dir_url( __FILE__ ) . 'css/our-plugins-style.css' );
	}
}
?>