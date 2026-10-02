<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function contact_form_shortcode_function( $atts ){
ob_start();
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
            max-width: <?php echo esc_attr($contact_form_width); ?>% !important;
        }
		.cfw-container h2 {
            color: <?php echo esc_attr($title_color); ?> !important;
        }
		
		.cfw-form-align {
			display: flex;
			justify-content: <?php echo esc_attr($cfw_form_order); ?>;
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
		</div>
	<?php }  ?>
		<?php
		return ob_get_clean();
}
add_shortcode( 'CFW', 'contact_form_shortcode_function' );
?>