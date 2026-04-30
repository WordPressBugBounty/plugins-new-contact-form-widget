<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function contact_form_shortcode_function( $atts ){
ob_start();
	// load saved setting from option table
	$all_setttings = get_option('contact_form_settings');
	//print_r($all_setttings);
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
			$show_query = 10;

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
		$show_query = 10;
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
            background-color: <?php echo esc_attr($bg_color); ?>;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: <?php echo esc_attr($contact_form_width); ?>% !important;
        }
		.cfw-container h2 {
            color: <?php echo esc_attr($title_color); ?> !important;
        }
		
		.cfw-form-align {
			display: flex;
			justify-content: <?php echo esc_attr($cfw_form_order); ?>;
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
		<div class="cfw-form-align">
			<div class="cfw-container">
				<form id="user-contact-form" name="user-contact-form" class="cfw-form">
					<h2 class="cwf-title"><?php echo esc_html($title_field); ?></h2>
					<p class="cwf-desc"><?php echo esc_html($description_field); ?></p>
					<div class="form-row">
						<div class="form-group">
							<label for="name"><?php esc_html_e( 'Name', 'new-contact-form-widget' ); ?></label>
							<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo esc_attr($name_field); ?>" maxlength="25">
							<p class="cfw-error name-error alert alert-warning"><strong><?php echo esc_html($name_error_field); ?></strong></p>
						</div>
						<div class="form-group">
							<label for="email"><?php esc_html_e( 'Email', 'new-contact-form-widget' ); ?></label>
							<input type="text" class="form-control" id="email" name="email" placeholder="<?php echo esc_attr($email_field); ?>">
							<p class="cfw-error email-error alert alert-warning"><strong><?php echo esc_html($email_error_field); ?></strong></p>
							<p class="cfw-error email-error-2 alert alert-warning"><strong><?php echo esc_html($email_error_field_2); ?></strong></p>
						</div>
					</div>
					<div class="form-group">
						<label for="subject"><?php esc_html_e( 'Subject', 'new-contact-form-widget' ); ?></label>
						<input type="text" class="form-control" id="subject" name="subject" placeholder="<?php echo esc_attr($subject_field); ?>" maxlength="50">
						<p class="cfw-error subject-error alert alert-warning"><strong><?php echo esc_html($subject_error_field); ?></strong></p>
					</div>
					<div class="form-group">
						<label for="message"><?php esc_html_e( 'Message', 'new-contact-form-widget' ); ?></label>
						<textarea class="form-control" id="message" name="message" placeholder="<?php echo esc_attr($message_field); ?>" maxlength="500"></textarea>
						<p class="cfw-error message-error alert alert-warning"><strong><?php echo esc_html($message_error_field); ?></strong></p>
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
		</div>
	<?php }  ?>
		<?php
		return ob_get_clean();
}
add_shortcode( 'CFW', 'contact_form_shortcode_function' );
?>