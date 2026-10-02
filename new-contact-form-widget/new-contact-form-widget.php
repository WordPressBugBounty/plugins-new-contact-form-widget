<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/*
Plugin Name:  New Contact Form Widget & Shortcode [Standard] 
Plugin URI: https://awplife.com/
Description: Add Contact Form Widget and Shortcode On WordPress
Version: 1.5.3
Author: A WP Life
Author URI: https://awplife.com/
Text Domain: new-contact-form-widget
Domain Path: /languages
*/

// create table when pluign activate
register_activation_hook( __FILE__, 'cfw_install_script' );
function cfw_install_script() {
	global $wpdb;
	$table_name = $wpdb->prefix . "awp_contact_form";
	$charset_collate = $wpdb->get_charset_collate();

	$create_contact_form_query = "CREATE TABLE $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		email varchar(255) NOT NULL,
		subject varchar(255) NOT NULL,
		message text NOT NULL,
		date_time datetime NOT NULL,
		status varchar(50) NOT NULL DEFAULT 'pending',
		PRIMARY KEY  (id),
		KEY date_time (date_time)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $create_contact_form_query );
}

// run when you de-activate this plugin
register_deactivation_hook( __FILE__, 'cfw_uninstall_script' );
function cfw_uninstall_script(){
	
}

//Plugin Text Domain
define("NCFWS_TXTDM","new-contact-form-widget");

add_action( 'plugins_loaded', '_load_textdomain_cf' );

function _load_textdomain_cf() {
		load_plugin_textdomain( NCFWS_TXTDM, false, dirname( plugin_basename(__FILE__) ) .'/languages' );			
}
// CFW Shortcode
require_once('shortcode.php');

// ajax action
add_action( 'wp_ajax_submit_user_query', 'submit_user_query_handle' );
add_action( 'wp_ajax_nopriv_submit_user_query', 'submit_user_query_handle' ); // need this to serve non logged in users

function submit_user_query_handle(){
	if(isset($_POST['action']) && isset($_POST['formsdata'])) {
		$cfw_query_nonce_value = isset($_POST['security']) ? sanitize_text_field($_POST['security']) : '';
		$nonce_verified = wp_verify_nonce( $cfw_query_nonce_value, 'cfw_query_nonce' );
		
		$action = sanitize_text_field($_POST['action']);
		// Convert serialized forms data into array
		$cfw_data = array();
		parse_str($_POST['formsdata'], $cfw_data);
		global $wpdb;

		if($action == "submit_user_query") {
			// Load saved messages
			$all_setttings = get_option('contact_form_settings');
			$qsm = (isset($all_setttings['qsm']) && ! empty($all_setttings['qsm'])) ? $all_setttings['qsm'] : "Thank you for submitting query. We will be back to you shortly.";
			$qfm = (isset($all_setttings['qfm']) && ! empty($all_setttings['qfm'])) ? $all_setttings['qfm'] : "Sorry! contact form not working properly. Please directly contact site admin using this email: " . get_option( 'admin_email' );

			// Anti-spam Honeypot Check: silently discard spam bots
			if ( ! empty( $cfw_data['cfw_hp_check'] ) ) {
				echo esc_html( $qsm );
				wp_die();
			}

			// Nonce verification: strictly enforce for logged-in users, allow graceful validation for guest submissions if page was cached
			if ( is_user_logged_in() && ! $nonce_verified ) {
				echo esc_html__( 'Security check failed. Please refresh the page and try again.', 'new-contact-form-widget' );
				wp_die();
			}

			$name = isset($cfw_data['name']) ? sanitize_text_field($cfw_data['name']) : '';
			$email = isset($cfw_data['email']) ? sanitize_email($cfw_data['email']) : '';
			$subject = isset($cfw_data['subject']) ? sanitize_text_field($cfw_data['subject']) : '';
			$message = isset($cfw_data['message']) ? sanitize_textarea_field($cfw_data['message']) : '';

			// Verify required fields
			if ( empty($name) || empty($email) || ! is_email($email) || empty($subject) || empty($message) ) {
				echo esc_html( $qfm );
				wp_die();
			}
			
			// Table name
			$cfw_table_name = $wpdb->prefix . 'awp_contact_form';

			// Data array
			$cfw_columns_data = array(
				'name'      => $name,
				'email'     => $email,
				'subject'   => $subject,
				'message'   => $message,
				'date_time' => current_time( 'mysql' ),
				'status'    => 'pending'
			);

			// Format array
			$cfw_data_format = array('%s', '%s', '%s', '%s', '%s', '%s');

			if($wpdb->insert( $cfw_table_name, $cfw_columns_data, $cfw_data_format)) {
				// Send email notification to site administrator
				$admin_email = get_option( 'admin_email' );
				if ( is_email( $admin_email ) ) {
					$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
					$email_subject = sprintf( '[%s] New Contact Query: %s', $site_name, $subject );
					$email_body = sprintf(
						"You have received a new contact inquiry from your website:\n\n" .
						"Name: %s\n" .
						"Email: %s\n" .
						"Subject: %s\n" .
						"Date: %s\n\n" .
						"Message:\n%s\n\n" .
						"--\nSent from: %s",
						$name,
						$email,
						$subject,
						current_time( 'mysql' ),
						$message,
						home_url()
					);
					$headers = array(
						'Content-Type: text/plain; charset=UTF-8',
						sprintf( 'Reply-To: %s <%s>', $name, $email )
					);
					@wp_mail( $admin_email, $email_subject, $email_body, $headers );
				}

				echo esc_html($qsm);
			} else {
				echo esc_html($qfm);
			}
		}
	}
	wp_die();
}

// Settings Save Ajax Action
add_action( 'wp_ajax_cfw_save_settings', 'cfw_save_settings_ajax_handler' );
function cfw_save_settings_ajax_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You do not have sufficient permissions.', 'new-contact-form-widget' ) ) );
	}

	check_ajax_referer( 'cfw_save_nonce', 'security' );

	$qsm                   = isset($_POST['qsm']) ? sanitize_text_field( wp_unslash( $_POST['qsm'] ) ) : '';
	$qfm                   = isset($_POST['qfm']) ? sanitize_text_field( wp_unslash( $_POST['qfm'] ) ) : '';
	$title_field           = isset($_POST['title_field']) ? sanitize_text_field( wp_unslash( $_POST['title_field'] ) ) : '';
	$contact_form_template = isset($_POST['contact_form_template']) ? sanitize_text_field( wp_unslash( $_POST['contact_form_template'] ) ) : 'template1';
	$title_color           = isset($_POST['title_color']) ? sanitize_hex_color( wp_unslash( $_POST['title_color'] ) ) : '#000000';
	if ( empty( $title_color ) ) $title_color = '#000000';
	$bg_color              = isset($_POST['bg_color']) ? sanitize_hex_color( wp_unslash( $_POST['bg_color'] ) ) : '#FFFFFF';
	if ( empty( $bg_color ) ) $bg_color = '#FFFFFF';
	$contact_form_width    = isset($_POST['contact_form_width']) ? absint( $_POST['contact_form_width'] ) : 35;
	$cfw_form_order        = isset($_POST['cfw_form_order']) ? sanitize_text_field( wp_unslash( $_POST['cfw_form_order'] ) ) : 'center';
	$description_field     = isset($_POST['description_field']) ? sanitize_text_field( wp_unslash( $_POST['description_field'] ) ) : '';
	$name_field            = isset($_POST['name_field']) ? sanitize_text_field( wp_unslash( $_POST['name_field'] ) ) : '';
	$email_field           = isset($_POST['email_field']) ? sanitize_text_field( wp_unslash( $_POST['email_field'] ) ) : '';
	$subject_field         = isset($_POST['subject_field']) ? sanitize_text_field( wp_unslash( $_POST['subject_field'] ) ) : '';
	$message_field         = isset($_POST['message_field']) ? sanitize_text_field( wp_unslash( $_POST['message_field'] ) ) : '';
	$name_error_field      = isset($_POST['name_error_field']) ? sanitize_text_field( wp_unslash( $_POST['name_error_field'] ) ) : '';
	$email_error_field     = isset($_POST['email_error_field']) ? sanitize_text_field( wp_unslash( $_POST['email_error_field'] ) ) : '';
	$email_error_field_2   = isset($_POST['email_error_field_2']) ? sanitize_text_field( wp_unslash( $_POST['email_error_field_2'] ) ) : '';
	$subject_error_field   = isset($_POST['subject_error_field']) ? sanitize_text_field( wp_unslash( $_POST['subject_error_field'] ) ) : '';
	$message_error_field   = isset($_POST['message_error_field']) ? sanitize_text_field( wp_unslash( $_POST['message_error_field'] ) ) : '';
	$sb_button_text        = isset($_POST['sb_button_text']) ? sanitize_text_field( wp_unslash( $_POST['sb_button_text'] ) ) : 'Submit';
	$show_query            = isset($_POST['show_query']) ? absint( $_POST['show_query'] ) : 10;
	$cus_css               = isset($_POST['cus_css']) ? wp_strip_all_tags( wp_unslash( $_POST['cus_css'] ) ) : '';

	$all_settings = array(
		'qsm'                   => $qsm,
		'qfm'                   => $qfm,
		'title_field'           => $title_field,
		'contact_form_template' => $contact_form_template,
		'title_color'           => $title_color,
		'bg_color'              => $bg_color,
		'contact_form_width'    => $contact_form_width,
		'cfw_form_order'        => $cfw_form_order,
		'description_field'     => $description_field,
		'name_field'            => $name_field,
		'email_field'           => $email_field,
		'subject_field'         => $subject_field,
		'message_field'         => $message_field,
		'name_error_field'      => $name_error_field,
		'email_error_field'     => $email_error_field,
		'email_error_field_2'   => $email_error_field_2,
		'subject_error_field'   => $subject_error_field,
		'message_error_field'   => $message_error_field,
		'sb_button_text'        => $sb_button_text,
		'show_query'            => $show_query,
		'cus_css'               => $cus_css,
	);

	update_option( 'contact_form_settings', $all_settings );

	wp_send_json_success( array(
		'message' => esc_html__( 'Settings saved successfully.', 'new-contact-form-widget' )
	) );
}

// Enqueue Assets
add_action( 'wp_enqueue_scripts', 'cfw_frontend_assets' );
function cfw_frontend_assets() {
	wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/cfw-bootstrap.css' );
	wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cfw-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array('jquery'), '3.3.6', false );
	wp_enqueue_script( 'cfw-ajax', plugin_dir_url( __FILE__ ) . 'js/cfw-ajax.js', array( 'jquery' ), '', true );
	wp_localize_script( 'cfw-ajax', 'cfw_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}


/**
 * Helper to sanitize CSV cells against Formula Injection
 */
if ( ! function_exists( 'cfw_sanitize_csv_cell' ) ) {
	function cfw_sanitize_csv_cell( $value ) {
		$value = (string) $value;
		if ( preg_match( '/^[\=\+\-\@\t\r]/', $value ) ) {
			return "'" . $value;
		}
		return $value;
	}
}

/**
 * Handle CSV Download early to avoid "Headers already sent" error
 */
add_action( 'admin_init', 'cfw_handle_csv_download' );
function cfw_handle_csv_download() {
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'cfw-all-queries' && isset( $_GET['action'] ) && $_GET['action'] === 'download-user-list' ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'download_user_list_action' ) ) {
			wp_die( esc_html__( 'Nonce verification failed.', 'new-contact-form-widget' ) );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'awp_contact_form';
		$user_search_query_result = $wpdb->get_results( "SELECT * FROM `$table_name` ORDER BY date_time DESC" );

		if ( ! empty( $user_search_query_result ) ) {
			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=contact-queries-' . date( 'Y-m-d' ) . '.csv' );
			$output = fopen( 'php://output', 'w' );
			// Write UTF-8 BOM for proper Excel display
			fprintf( $output, chr(0xEF) . chr(0xBB) . chr(0xBF) );
			fputcsv( $output, array( '#', 'Name', 'Email', 'Subject', 'Message', 'Date' ) );

			$no = 1;
			foreach ( $user_search_query_result as $single_row ) {
				fputcsv( $output, array(
					$no,
					cfw_sanitize_csv_cell( $single_row->name ),
					cfw_sanitize_csv_cell( $single_row->email ),
					cfw_sanitize_csv_cell( $single_row->subject ),
					cfw_sanitize_csv_cell( $single_row->message ),
					cfw_sanitize_csv_cell( $single_row->date_time )
				) );
				$no++;
			}
			fclose( $output );
			exit;
		} else {
			wp_die( esc_html__( 'No queries found to export.', 'new-contact-form-widget' ) );
		}
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'cfw_Widget' );
});

class cfw_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
		public function __construct() {
			$widget_ops = array( 
				'classname' => 'contact_form',
				'description' => 'Display contact form to your visitors.',
			);
			parent::__construct( 'contact_form', 'Contact Form Widget', $widget_ops );
		}

	/**
	 * Outputs of the widget
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		// widget title
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		// load saved setting from option table with safe PHP 8 fallbacks
		$all_setttings = get_option('contact_form_settings');
		if ( ! is_array( $all_setttings ) ) {
			$all_setttings = array();
		}

		$contact_form_template = ! empty( $all_setttings['contact_form_template'] ) ? $all_setttings['contact_form_template'] : "template1";
		$title_field           = ! empty( $all_setttings['title_field'] ) ? $all_setttings['title_field'] : "Contact Form";
		$title_color           = ! empty( $all_setttings['title_color'] ) ? $all_setttings['title_color'] : "#000000";
		$contact_form_width    = ! empty( $all_setttings['contact_form_width'] ) ? $all_setttings['contact_form_width'] : "35";
		$cfw_form_order        = ! empty( $all_setttings['cfw_form_order'] ) ? $all_setttings['cfw_form_order'] : "center";
		$bg_color              = ! empty( $all_setttings['bg_color'] ) ? $all_setttings['bg_color'] : "#FFFFFF";
		$description_field     = ! empty( $all_setttings['description_field'] ) ? $all_setttings['description_field'] : "Please fill below form if you have any query with us.";
		$name_field            = ! empty( $all_setttings['name_field'] ) ? $all_setttings['name_field'] : "Type Your Name Here";
		$email_field           = ! empty( $all_setttings['email_field'] ) ? $all_setttings['email_field'] : "Type Your Email Here";
		$subject_field         = ! empty( $all_setttings['subject_field'] ) ? $all_setttings['subject_field'] : "Type Your Query Subject Here";
		$message_field         = ! empty( $all_setttings['message_field'] ) ? $all_setttings['message_field'] : "Type Your Query Message Here";
		$name_error_field      = ! empty( $all_setttings['name_error_field'] ) ? $all_setttings['name_error_field'] : "Name cannot be blank.";
		$email_error_field     = ! empty( $all_setttings['email_error_field'] ) ? $all_setttings['email_error_field'] : "Email cannot be blank.";
		$email_error_field_2   = ! empty( $all_setttings['email_error_field_2'] ) ? $all_setttings['email_error_field_2'] : "Email is invalid.";
		$subject_error_field   = ! empty( $all_setttings['subject_error_field'] ) ? $all_setttings['subject_error_field'] : "Subject cannot be blank.";
		$message_error_field   = ! empty( $all_setttings['message_error_field'] ) ? $all_setttings['message_error_field'] : "Message cannot be blank.";
		$show_query            = ! empty( $all_setttings['show_query'] ) ? $all_setttings['show_query'] : 10;
		$sb_button_text        = ! empty( $all_setttings['sb_button_text'] ) ? $all_setttings['sb_button_text'] : "Submit";
		$cus_css               = ! empty( $all_setttings['cus_css'] ) ? $all_setttings['cus_css'] : "";
		$qsm                   = ! empty( $all_setttings['qsm'] ) ? $all_setttings['qsm'] : "Thank you for submitting query. We will be back to you shortly.";
		$qfm                   = ! empty( $all_setttings['qfm'] ) ? $all_setttings['qfm'] : "Sorry! contact from not working properly. Please directly contact site admin using this email: " . get_option( 'admin_email' );
		?>
		<style>	
		.cfw-container .cwf-title {
			color:<?php echo esc_attr($title_color); ?> !important;
		}
		.cfw-container .cwf-desc {
			color:<?php echo esc_attr($title_color); ?> !important;
		}
		.cfw-form {
            padding: 10px;
            border-radius: 5px;
        }
		.cfw-container {
            background-color: <?php echo esc_attr($bg_color); ?>;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
		.cfw-container h2 {
            color: <?php echo esc_attr($title_color); ?> !important;
        }

		.cfw-container .form-group {
			padding-top:15px;
			padding-bottom:15px;		
		}
		.cfw-container .cfw-error {
			display: none;
			padding: 7px !important;
		}
		.cfw-container button.cfw-submit-btn,
		.cfw-container .btn-primary {
            width: 100%;
            font-size: 20px!important;
        }
		<?php echo wp_strip_all_tags($cus_css); ?>
		</style>
		<?php 
			if ($contact_form_template == 'template1') {
				include 'css/template/form-one.php';
			} elseif ($contact_form_template == 'template2') {
				include 'css/template/form-two.php';
			}
			?>
		<!--gogle captcha script-->
		<?php if ($contact_form_template) { ?>
			<div class="cfw-container">
				<form id="user-contact-form" name="user-contact-form" class="cfw-form">
					<h2 class="cwf-title"><?php echo esc_html($title_field); ?></h2>
					<p class="cwf-desc"><?php echo esc_html($description_field); ?></p>
					<div class="form-row">
						<div class="form-group">
							<label for="name"> Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo esc_html($name_field); ?>" maxlength="25">
							<p class="cfw-error name-error alert alert-warning"><strong><?php echo $name_error_field; ?></strong></p>
						</div>
						<div class="form-group">
							<label for="email"> Email</label>
							<input type="text" class="form-control" id="email" name="email" placeholder="<?php echo $email_field; ?>">
							<p class="cfw-error email-error alert alert-warning"><strong><?php echo $email_error_field; ?></strong></p>
							<p class="cfw-error email-error-2 alert alert-warning"><strong><?php echo $email_error_field_2; ?></strong></p>
						</div>
					</div>
					<div class="form-group">
						<label for="subject"> Subject</label>
						<input type="text" class="form-control" id="subject" name="subject" placeholder="<?php echo $subject_field; ?>" maxlength="50">
						<p class="cfw-error subject-error alert alert-warning"><strong><?php echo $subject_error_field; ?></strong></p>
					</div>
					<div class="form-group">
						<label for="message"> Message</label>
						<textarea class="form-control" id="message" name="message" placeholder="<?php echo $message_field; ?>" maxlength="500"></textarea>
						<p class="cfw-error message-error alert alert-warning"><strong><?php echo $message_error_field; ?></strong></p>
					</div>
					
					<!-- Honeypot anti-spam field -->
					<div class="cfw-hp-wrap" style="display:none !important; visibility:hidden !important; position:absolute !important; left:-9999px !important;">
						<label for="cfw_hp_check"><?php esc_html_e( 'Leave this field blank', 'new-contact-form-widget' ); ?></label>
						<input type="text" name="cfw_hp_check" id="cfw_hp_check" value="" autocomplete="off" tabindex="-1">
					</div>

					<div class="form-group">
						<button type="button" class="btn btn-primary cfw-submit-btn" onclick="return ValidateForm('<?php echo esc_js(wp_create_nonce( "cfw_query_nonce" )); ?>', this);"><?php echo esc_html($sb_button_text); ?></button>
					</div>
				</form>
			
			<!--loading icon-->
			<div id="awp-loading-icon" class="awp-loading-icon text-center" style="display: none;">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br>
				<?php esc_html_e('Please wait submitting your query.', 'new-contact-form-widget'); ?>
			</div>
			
			<!--Ajax result-->
			<div id="contact-result" class="contact-result" style="display: none;">
			</div>
		</div>
		<?php }  ?>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs Form For Admin
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'new-contact-form-widget' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=cfw-settings' ) ) . '">' . esc_html__( 'Configure Form & Design Settings', 'new-contact-form-widget' ) . '</a></p>';
	}

	/**
	 * Processing widget options on save
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		return $instance;
	}
}

// Contact Form Widget Menu Page For Administrator
// For mange all contact queries & contact form widget settings
require_once('cfw-menu-pages.php');
?>