<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// All Query Page Code
add_action( 'admin_menu', 'cfw_menus' );
function cfw_menus() {
	add_menu_page( 'Contact Form Queries', __( 'Contact Form Queries', 'new-contact-form-widget' ),  'administrator', 'cfw-all-queries', 'cfw_all_queries', 'dashicons-email-alt', 65);
	add_submenu_page( 'cfw-all-queries', 'Settings', __( 'Settings', 'new-contact-form-widget' ), 'administrator','cfw-settings','cfw_settings');   
	add_submenu_page( 'cfw-all-queries','Our Theme', __( 'Our Theme', 'new-contact-form-widget' ),  'administrator', 'sr-theme-page', 'cfw_theme_page' );
}

//all contact queries page body function
function cfw_all_queries() {
	require_once('all-query-page.php');
}

// theme page
function cfw_theme_page() {
	require_once('our-theme/awp-theme.php');
}

// setting page body
function cfw_settings() {
	require_once('settings-page.php');	
}
?>