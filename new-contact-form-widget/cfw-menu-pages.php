<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register Admin Menu Pages with manage_options capability
add_action( 'admin_menu', 'cfw_menus' );
function cfw_menus() {
	add_menu_page( 'Contact Form Queries', __( 'Contact Form Queries', 'new-contact-form-widget' ), 'manage_options', 'cfw-all-queries', 'cfw_all_queries', 'dashicons-email-alt', 65 );
	add_submenu_page( 'cfw-all-queries', __( 'Settings', 'new-contact-form-widget' ), __( 'Settings', 'new-contact-form-widget' ), 'manage_options', 'cfw-settings', 'cfw_settings' );
	add_submenu_page( 'cfw-all-queries', __( 'Our Themes', 'new-contact-form-widget' ), __( 'Our Themes', 'new-contact-form-widget' ), 'manage_options', 'cfw-our-themes', 'cfw_our_themes_page' );
	add_submenu_page( 'cfw-all-queries', __( 'Our Plugins', 'new-contact-form-widget' ), __( 'Our Plugins', 'new-contact-form-widget' ), 'manage_options', 'cfw-our-plugins', 'cfw_our_plugins_page' );
}

// All contact queries page callback
function cfw_all_queries() {
	require_once('all-query-page.php');
}

// Themes page callback
function cfw_our_themes_page() {
	require_once('our-themes.php');
}

// Plugins page callback
function cfw_our_plugins_page() {
	require_once('our-plugins.php');
}

// Settings page callback
function cfw_settings() {
	require_once('settings-page.php');	
}

// Enqueue admin assets properly on screen hooks
add_action( 'admin_enqueue_scripts', 'cfw_admin_assets' );
function cfw_admin_assets( $hook ) {
	if ( strpos( $hook, 'cfw-all-queries' ) !== false ) {
		wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/bootstrap.css' );
		wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css' );
		wp_enqueue_style( 'cfw-metabox-css', plugin_dir_url( __FILE__ ) . 'css/metabox.css' );
		wp_enqueue_script( 'cfw-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array( 'jquery' ), '3.3.6', true );
	}

	if ( strpos( $hook, 'cfw-settings' ) !== false ) {
		wp_enqueue_style( 'awl-cfw-button-css', plugin_dir_url( __FILE__ ) . 'css/toogle-button.css' );
		wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ) . 'css/cfw-bootstrap.css' );
		wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css' );
		wp_enqueue_style( 'cfw-metabox-css', plugin_dir_url( __FILE__ ) . 'css/metabox.css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'cfw-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array( 'jquery' ), '3.3.6', true );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	if ( strpos( $hook, 'cfw-our-themes' ) !== false || strpos( $hook, 'cfw-our-plugins' ) !== false ) {
		wp_enqueue_style( 'cfw-our-plugins-style', plugin_dir_url( __FILE__ ) . 'css/our-plugins-style.css' );
	}
}