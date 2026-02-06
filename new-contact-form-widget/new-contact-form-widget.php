<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/*
Plugin Name:  New Contact Form Widget & Shortcode [Standard] 
Plugin URI: https://awplife.com/wordpress-plugins/contact-form-premium/
Description: Add Contact Form Widget and Shortcode On WordPress
Version: 1.5.1
Author: A WP Life
Author URI: https://awplife.com/wordpress-plugins/contact-form-premium/
Text Domain: new-contact-form-widget
Domain Path: /languages
*/

// create table when pluign activate
register_activation_hook( __FILE__, 'cfw_install_script' );
function cfw_install_script() {
	//load create table file here
	global $wpdb;
	$table_name = $wpdb->prefix . "awp_contact_form";
	$create_contact_form_query = "CREATE TABLE IF NOT EXISTS `$table_name` (
	`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` varchar(256) NOT NULL,
	`email` varchar(256) NOT NULL,
	`subject` varchar(256) NOT NULL,
	`message` text NOT NULL,
	`date_time` datetime NOT NULL,
	`status` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$wpdb->query($create_contact_form_query);
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
	if(isset($_POST['action']) && $_POST['formsdata']) {
		$cfw_query_nonce_value = $_POST['security'];
		if(!wp_verify_nonce( $cfw_query_nonce_value, 'cfw_query_nonce' )) {
			$action = $_POST['action'];
			//convert sterilise forms data into array
			$cfw_data = array();
			parse_str($_POST['formsdata'], $cfw_data);
			global $wpdb;
			if($action == "submit_user_query") {
				$name = sanitize_text_field($cfw_data['name']);
				$email = sanitize_email($cfw_data['email']);
				$subject = sanitize_text_field($cfw_data['subject']);
				$message = sanitize_text_field($cfw_data['message']);
				
				// table name
				$cfw_table_name = $wpdb->prefix . 'awp_contact_form';

				//data array
				$cfw_columns_data = array(
					//column_name => field_value
					'id' => NULL,
					'name' => $name,
					'email' => $email,
					'subject' => $subject,
					'message' => $message,
					'date_time' => date("Y-m-d h:i:s"),
					'status' => 'pending'
				);

				//format array
				$cfw_data_format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s');
				
				// load saved message
				$all_setttings = get_option('contact_form_settings');
				if(isset($all_setttings)){
					$qsm = $all_setttings['qsm'];
					$qfm = $all_setttings['qfm'];
				}
				
				
				
				if($wpdb->insert( $cfw_table_name, $cfw_columns_data, $cfw_data_format)) {
					if($qsm == "") echo "Thank you for submitting query. We will be back to you shortly."; else echo esc_html($qsm);
				} else {
					if($qfm == "") echo "Sorry! contact from not working properly. Please directly contact to site admin using this email: ".get_option( 'admin_email' ); else echo esc_html($qfm);
				}
			}
		}// verify query nonce value
	}// end of isset
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
		
		//css
		wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/cfw-bootstrap.css' );
		wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
		
		//js
		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'cfw-bootstrap-js', plugin_dir_url( __FILE__ ) . 'js/bootstrap.js', array('jquery'), '3.3.6', false );
		wp_enqueue_script( 'cfw-ajax', plugin_dir_url( __FILE__ ) . 'js/cfw-ajax.js', array( 'jquery' ), '', true );
		wp_localize_script( 'cfw-ajax', 'cfw_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );		
		
		echo $args['before_widget'];
		// widget title
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		// load saved setting from option table
		$all_setttings = get_option('contact_form_settings');
		if(isset($all_setttings)){
			// Design Setting	
			
			if($all_setttings['contact_form_template']) 
				$contact_form_template = $all_setttings['contact_form_template'];
			else 
				$contact_form_template = "template1";
			
			if($all_setttings['title_field']) 
				$title_field = $all_setttings['title_field'];
			else 
				$title_field = "Contact Form";
			
			if($all_setttings['title_color']) 
				$title_color = $all_setttings['title_color'];
			else 
				$title_color = "#FAFAFA";
			
			$contact_form_width = isset($all_setttings['contact_form_width']) ? $all_setttings['contact_form_width'] : "35";
			$cfw_form_order = isset($all_setttings['cfw_form_order']) ? $all_setttings['cfw_form_order'] : "center";
			$bg_color = isset($all_setttings['bg_color']) ? $all_setttings['bg_color'] : "#FFFFFF";

			if($all_setttings['description_field']) 
				$description_field = $all_setttings['description_field'];
			else 
				$description_field = "Please fill below form if you have any query with us.";
			
			if($all_setttings['name_field']) 
				$name_field = $all_setttings['name_field'];
			else 
				$name_field = "Type Your Name Here";
			
			if($all_setttings['email_field']) 
				$email_field = $all_setttings['email_field'];
			else 
				$email_field = "Type Your Email Here";
			
			if($all_setttings['subject_field']) 
				$subject_field = $all_setttings['subject_field'];
			else 
				$subject_field = "Type Your Query Subject Here";
			
			if($all_setttings['message_field']) 
				$message_field = $all_setttings['message_field'];
			else 
				$message_field = "Type Your Query Message Here";
		
		
			if($all_setttings['name_error_field']) 
				$name_error_field = $all_setttings['name_error_field'];
			else 
				$name_error_field = "Name cannot be blank.";
		
			if($all_setttings['email_error_field']) 
					$email_error_field = $all_setttings['email_error_field'];
				else 
					$email_error_field = "Email cannot be blank.";
			
			if($all_setttings['email_error_field_2']) 
					$email_error_field_2 = $all_setttings['email_error_field_2'];
				else 
					$email_error_field_2 = "Email is invalid.";
			
			if($all_setttings['subject_error_field']) 
					$subject_error_field = $all_setttings['subject_error_field'];
				else 
					$subject_error_field = "Subject cannot be blank.";
			
			if($all_setttings['message_error_field']) 
					$message_error_field = $all_setttings['message_error_field'];
				else 
					$message_error_field = "Message cannot be blank.";
			
			if($all_setttings['show_query']) 
				$show_query = $all_setttings['show_query'];
			else 
				$show_query = "";
			
			if($all_setttings['sb_button_text']) 
				$sb_button_text = $all_setttings['sb_button_text'];
			else 
				$sb_button_text = "Submit";
			
			if($all_setttings['cus_css']) 
				$cus_css = $all_setttings['cus_css'];
			else 
				$cus_css = "";
		
			// Message Setting
			if($all_setttings['qsm']) 
				$qsm = $all_setttings['qsm'];
			else 
				$qsm = "Thank you for submitting query. We will be back to you shortly.";
			
			if($all_setttings['qfm'])
				$qfm = $all_setttings['qfm'];
			else
				$qfm = "Sorry! contact from not working properly. Please directly contact to site admin using this email: ".get_option( 'admin_email' );
		} else {
			
			$contact_form_template = "template1";
			$title_field = "Contact Form";
			$title_color = "#FAFAFA";
			$bg_color = "#ffffff";
			$contact_form_width = 35;
			$cfw_form_order = "center";
			$description_field = "Please fill below form if you have any query with us.";
			$name_field = "Type Your Name Here";
			$email_field = "Type Your Email Here";
			$subject_field = "Type Your Query Subject Here";
			$message_field = "Type Your Query Message Here";
			$name_error_field = "Name cannot be blank.";
			$email_error_field = "Email cannot be blank.";
			$email_error_field_2 = "Email is invalid.";
			$subject_error_field = "Subject cannot be blank.";
			$message_error_field = "Message cannot be blank.";
			$show_query = "";
			$sb_button_text = "Submit";
			$cus_css= "";
			
			$qsm = "Thank you for submitting query. We will be back to you shortly.";
			$qfm = "Sorry! contact from not working properly. Please directly contact to site admin using this email: ".get_option( 'admin_email' );
		}
		?>
		<style>	
		.cwf-title {
			color:<?php echo esc_attr($title_color); ?> !important;
		}
		.cwf-desc {
			color:<?php echo esc_attr($title_color); ?> !important;
		}
		.cfw-form {
            padding: 10px;
            border-radius: 5px;
        }
		.cfw-container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
		.cfw-container h2 {
            color: <?php echo $title_color; ?> !important;
        }

		.form-group {
			padding-top:15px;
			padding-bottom:15px;		
		}
		.cfw-error {
			display: none;
			padding: 7px !important;
		}
		 button {
            width: 100%;
            font-size: 20px!important;
        }
			<?php echo $cus_css; ?>
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
					
					<div class="form-group">
						<button type="button" class="btn btn-primary"  onclick="return ValidateForm('<?php echo esc_js(wp_create_nonce( "cfw_query_nonce" )); ?>');"><?php echo esc_html($sb_button_text); ?></button>
					</div>
				</form>
			
			<!--loading icon-->
			<div id="awp-loading-icon" class="text-center" style="display: none;">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br>
				<?php esc_html_e('Please wait submitting your query.', 'new-contact-form-widget'); ?>
			</div>
			
			<!--Ajax result-->
			<div id="contact-result" style="display: none;">
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
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( esc_html( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
		echo "<p><a href='admin.php?page=cfw-settings'>Configure Widget Settings</a></p>";
		echo "<p><strong>Important Note:</strong></p>";
		echo "<p>Don't use multiple shortcode on same Widget / Sidebar Area.</p>";
		echo "<p>Also, don't activate multiple Contact Form Widget into multiple Widget / Sidebar Area like (Sidebar Widgets / Footer Widgets / Header Widgets)</p>";
	}

	/**
	 * Processing widget options on save
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}

// Contact Form Widget Menu Page For Administrator
// For mange all contact queries & contact form widget settings
require_once('cfw-menu-pages.php');
?>