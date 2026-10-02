<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

// Load saved settings from options table with safe PHP 8 fallbacks
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
	
	<div style="text-align:center">
		<h1><?php esc_html_e( 'How to show Contact Form on page ?', 'new-contact-form-widget' ); ?></h1>
		<hr>
		<p class="input-text-wrap">
			<p><?php esc_html_e( 'Copy & Embed shortcode into any Page/ Post / Text to display Contact Form on site.', 'new-contact-form-widget' ); ?><br></p>
			<p><?php esc_html_e( 'Note:  Don t use multiple shortcode on same Page / Post.', 'new-contact-form-widget' ); ?><br></p>
			<input type="text" name="shortcode" id="shortcode" value="[CFW]" readonly style="height: 60px; text-align: center; font-size: 24px; width: 15%; border: 2px dashed;">
		</p>
		<hr>
	</div>

	<form id="cfw-settings-form" name="cfw-settings-form">
		<div class="row setting-css">
		<div class="col-lg-12 bhoechie-tab-container">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
				<div class="list-group">
					<a href="#" class="list-group-item active text-center">
						<span class="dashicons dashicons-feedback"></span><br />
						<?php esc_html_e('Contact Form Template', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-feedback"></span><br />
						<?php esc_html_e('Contact Form Header', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-generic"></span><br />
						<?php esc_html_e('Contact Form Lable', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-welcome-write-blog"></span><br />
						<?php esc_html_e('Message', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-button"></span><br />
						<?php esc_html_e('Submit Button', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-media-code"></span><br />
						<?php esc_html_e('Custom Css', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-controls-repeat"></span><br />
						<?php esc_html_e('Auto Responder Setting', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-email-alt"></span><br />
						<?php esc_html_e('Email Setting', 'new-contact-form-widget'); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-google"></span><br />
						<?php esc_html_e('Google reCAPTCHA', 'new-contact-form-widget'); ?>
					</a>
				</div>
			</div>
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
				<div class="bhoechie-tab-content active">
					<h1><?php esc_html_e( 'Select Template Design', 'new-contact-form-widget' ); ?></h1>
					<hr>
					<div id="contact_form_template">
						<div class="row">
							<div class="col-md-3">
								<input type="radio" name="contact_form_template" id="contact_form_template_one" value="template1" <?php if($contact_form_template == "template1") echo "checked" ; ?>>
								<label for="contact_form_template_one" class="contact_layout_one"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'image/1.png'); ?>" style="width: 100%;  box-shadow: 3px 2px 11px 0px #999;"></label> 
							</div>
							<div class="col-md-3">
								<input type="radio" name="contact_form_template" id="contact_form_template_two" value="template2" <?php if($contact_form_template == "template2") echo "checked" ; ?>>
								<label for="contact_form_template_two" class="contact_layout_two" ><img src="<?php echo esc_url(plugin_dir_url( __FILE__ ).'image/2.webp'); ?>" style="width: 100%;  box-shadow: 3px 2px 11px 0px #999;"></label> 
								</label>
							</div>
						</div>
						<hr>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Form Header', 'new-contact-form-widget' ); ?></h2>
					<hr>	
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Contact Form Title', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="title_field" name="title_field" placeholder="<?php esc_html_e('Type your Title', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($title_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Contact Form Description', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="description_field" name="description_field" placeholder="<?php esc_html_e('Type your description', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($description_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Title Color', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="title_color" name="title_color" placeholder="<?php esc_html_e('chose form color', 'new-contact-form-widget'); ?>" value="<?php echo esc_attr($title_color); ?>" default-color="<?php echo esc_attr($title_color); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Contact Form Width', 'new-contact-form-widget' ); ?></h5>
							</div> 
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="range" class="custom-range" id="contact_form_width" name="contact_form_width" min="10" max="100" value="<?php echo esc_attr($contact_form_width); ?>" onchange="return display_range_value(this.id, this.value);">
								<span id="contact_form_width-value" class="badge badge-info pt-2 pb-2 pr-2 pl-2"><?php echo esc_attr($contact_form_width); ?></span>							
							</div>
						</div>
					</div>
					<div class="row">
							<div class="col-md-3">
								<div class="ma_field_discription">
									<h5><?php esc_html_e( 'Form Background Color', 'new-contact-form-widget' ); ?></h5>
								</div>
							</div>
							<div class="col-md-4">
								<div class="ma_field p-4">
									<input type="text" class="form-control" id="bg_color" name="bg_color" placeholder="" value="<?php echo esc_attr($bg_color); ?>">
								</div>
							</div>
						</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Contact Form Position', 'new-contact-form-widget' ); ?></h5>
							</div> 
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<p class="switch-field em_size_field">
									<input type="radio" name="cfw_form_order" id="cfw_form_order1" value="flex-start" <?php if ($cfw_form_order == "flex-start") echo "checked=checked"; ?>>
									<label for="cfw_form_order1">
										<?php esc_html_e('Left', 'new-contact-form-widget'); ?>
									</label>
									<input type="radio" name="cfw_form_order" id="cfw_form_order2" value="center" <?php if ($cfw_form_order == "center") echo "checked=checked"; ?>>
									<label for="cfw_form_order2">
										<?php esc_html_e('Center', 'new-contact-form-widget'); ?>
									</label>
									<input type="radio" name="cfw_form_order" id="cfw_form_order3" value="flex-end" <?php if ($cfw_form_order == "flex-end") echo "checked=checked"; ?>>
									<label for="cfw_form_order3">
										<?php esc_html_e('Right', 'new-contact-form-widget'); ?>
									</label>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Form Label Setting', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Name Field Place Holder Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="name_field" name="name_field" placeholder="<?php esc_html_e('Type your Name', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($name_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Email Field Place Holder Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="email_field" name="email_field" placeholder="<?php esc_html_e('Type your Email', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($email_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Subject Field Place Holder Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="subject_field" name="subject_field" placeholder="<?php esc_html_e('Type your Subject', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($subject_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Message Field Place Holder Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="message_field" name="message_field" placeholder="<?php esc_html_e('Type your Message', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($message_field); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Message Settings', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Blank Name Error Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<input type="text" class="form-control" id="name_error_field" name="name_error_field" placeholder="<?php esc_html_e('Type your Name Error Text', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($name_error_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Blank Email Error Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<input type="text" class="form-control" id="email_error_field" name="email_error_field" placeholder="<?php esc_html_e('Type your Email Error Text', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($email_error_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Invalid Email Error Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<input type="text" class="form-control" id="email_error_field_2" name="email_error_field_2" placeholder="<?php esc_html_e('Type your Email Error Text', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($email_error_field_2); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Blank Subject Error Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<input type="text" class="form-control" id="subject_error_field" name="subject_error_field" placeholder="<?php esc_html_e('Type your Subject Error Text', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($subject_error_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Blank Message Error Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<input type="text" class="form-control" id="message_error_field" name="message_error_field" placeholder="<?php esc_html_e('Type your Message Error Text', 'new-contact-form-widget'); ?>" value="<?php echo esc_html($message_error_field); ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Message To User / Visitor After Successful Query Submission', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<textarea class="form-control" id="qsm" name="qsm" placeholder="<?php esc_html_e('Type your message Here', 'new-contact-form-widget'); ?>"><?php echo esc_html($qsm); ?></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Message To User / Visitor When Query Submission Failed', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field">
								<textarea class="form-control" id="qfm" name="qfm" placeholder="<?php esc_html_e('Type your message Here', 'new-contact-form-widget'); ?>" style="height: 110px;"><?php echo esc_html($qfm); ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">		
					<h2><?php esc_html_e( 'Form Submit Button Settings', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Show Query Per Page', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<select id="show_query" name="show_query">
									<option><?php esc_html_e('Select Number of Rows', 'new-contact-form-widget'); ?></option>
									<option value="5" <?php if($show_query == "5") echo "selected=selected"; ?>><?php esc_html_e('5', 'new-contact-form-widget'); ?></option>
									<option value="10" <?php if($show_query == "10") echo "selected=selected"; ?>><?php esc_html_e('10', 'new-contact-form-widget'); ?></option>
									<option value="20" <?php if($show_query == "20") echo "selected=selected"; ?>><?php esc_html_e('20', 'new-contact-form-widget'); ?></option>
									<option value="25" <?php if($show_query == "25") echo "selected=selected"; ?>><?php esc_html_e('25', 'new-contact-form-widget'); ?></option>
									<option value="50" <?php if($show_query == "50") echo "selected=selected"; ?>><?php esc_html_e('50', 'new-contact-form-widget'); ?></option>
									<option value="100" <?php if($show_query == "100") echo "selected=selected"; ?>><?php esc_html_e('100', 'new-contact-form-widget'); ?></option>
									<option value="200" <?php if($show_query == "200") echo "selected=selected"; ?>><?php esc_html_e('200', 'new-contact-form-widget'); ?></option>
									<option value="250" <?php if($show_query == "250") echo "selected=selected"; ?>><?php esc_html_e('250', 'new-contact-form-widget'); ?></option>
								</select>						
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Form Submit Button Text', 'new-contact-form-widget' ); ?></h5>
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<input type="text" class="form-control" id="sb_button_text" name="sb_button_text" placeholder="" value="<?php echo esc_html($sb_button_text); ?>">
							</div>
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Custom CSS Setting', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div class="row">
						<div class="col-md-3">
							<div class="ma_field_discription">
								<h5><?php esc_html_e( 'Custom CSS', 'new-contact-form-widget' ); ?></h5>
								<p><?php esc_html_e( 'Add Custom CSS', 'new-contact-form-widget' ); ?></p> 
							</div>
						</div>
						<div class="col-md-4">
							<div class="ma_field p-4">
								<textarea rows="7" class="form-control" id="cus_css" name="cus_css" placeholder="<?php esc_html_e('Type your CSS without <style>...</style> tag ', 'new-contact-form-widget'); ?>"><?php echo esc_html($cus_css); ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Auto Responder Setting (Pro Feature)', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Automatically send customized confirmation emails to visitors when they submit an inquiry.', 'new-contact-form-widget' ); ?></p>
					</div>

					<div>
						<h2><strong><?php esc_html_e( 'Offer:', 'new-contact-form-widget' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price ', 'new-contact-form-widget' ); ?><strike><?php esc_html_e( '$19.99', 'new-contact-form-widget' ); ?></strike> <strong><?php esc_html_e( '$ 12.99', 'new-contact-form-widget' ); ?></strong></h2>
						<br>
						<a href="https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/how-to-test-premium-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Try Pro Version', 'new-contact-form-widget' ); ?></a>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'SMTP & Custom Email Setting (Pro Feature)', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Deliver notifications reliably via custom SMTP servers and design personalized HTML email templates.', 'new-contact-form-widget' ); ?></p>
					</div>

					<div>
						<h2><strong><?php esc_html_e( 'Offer:', 'new-contact-form-widget' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price ', 'new-contact-form-widget' ); ?><strike><?php esc_html_e( '$19.99', 'new-contact-form-widget' ); ?></strike> <strong><?php esc_html_e( '$ 12.99', 'new-contact-form-widget' ); ?></strong></h2>
						<br>
						<a href="https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/how-to-test-premium-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Try Pro Version', 'new-contact-form-widget' ); ?></a>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Google reCAPTCHA v2 / v3 (Pro Feature)', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<div style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Block automated spambots and abuse with built-in Google reCAPTCHA integration.', 'new-contact-form-widget' ); ?></p>
					</div>

					<div>
						<h2><strong><?php esc_html_e( 'Offer:', 'new-contact-form-widget' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price ', 'new-contact-form-widget' ); ?><strike><?php esc_html_e( '$19.99', 'new-contact-form-widget' ); ?></strike> <strong><?php esc_html_e( '$ 12.99', 'new-contact-form-widget' ); ?></strong></h2>
						<br>
						<a href="https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/how-to-test-premium-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Try Pro Version', 'new-contact-form-widget' ); ?></a>
					</div>
				</div>
			</div>
		</div>		
		</div>		
		<div id="loading-msg" class="alert alert-warning" style="display:none; text-align: center"> 
			<i class='fa fa-cog fa-spin fa-5x fa-fw margin-bottom'></i>
			<p><?php esc_html_e('Saving setting is under processing...', 'new-contact-form-widget'); ?></p>
		</div>

		<div id="success-msg" class="alert alert-success" style="display:none; text-align: center; margin-top: 15px;"> 
			<i class='fa fa-check-circle fa-2x' style="vertical-align: middle; margin-right: 8px;"></i>
			<strong><?php esc_html_e('Settings saved successfully!', 'new-contact-form-widget'); ?></strong>
		</div>
		
		<div class="p-4" style="text-align:center">
			<button id="cfw-save-settings" name="cfw-save-settings" type="button" href="#" class="btn btn-primary btn-lg" onclick="return SaveSettings();"><i class="fa fa-save" aria-hidden="true"></i> <?php esc_html_e('Save', 'new-contact-form-widget'); ?></button>
		</div>
	</form>
	<!-- settings ajax post code -->
	<script>

	function SaveSettings() {		
		jQuery(".error").hide();
		var $btn = jQuery("#cfw-save-settings");
		var $loading = jQuery("#loading-msg");
		var $success = jQuery("#success-msg");

		$btn.hide();
		$loading.show();
		$success.hide();

		var formData = jQuery("#cfw-settings-form").serializeArray();
		var postData = {
			action: 'cfw_save_settings',
			security: '<?php echo esc_js( wp_create_nonce( "cfw_save_nonce" ) ); ?>'
		};

		jQuery.each(formData, function(i, field) {
			postData[field.name] = field.value;
		});

		jQuery.post(ajaxurl, postData, function(response) {
			$loading.hide();
			$btn.show();
			if (response && response.success) {
				$success.fadeIn().delay(3000).fadeOut();
			} else {
				alert((response && response.data && response.data.message) ? response.data.message : 'Error saving settings.');
			}
		}).fail(function() {
			$loading.hide();
			$btn.show();
			alert('Error saving settings. Server request failed.');
		});
	}
	
	//color-picker
	(function( jQuery ) {
		jQuery(function() {
			// Add Color Picker to all inputs that have 'color-field' class
			jQuery('#title_color').wpColorPicker();
			jQuery('#bg_color').wpColorPicker();
		});
	})( jQuery );
	
	jQuery(document).ajaxComplete(function() {
		jQuery('#title_color').wpColorPicker();
		jQuery('#bg_color').wpColorPicker();
	});	
	
	// range bar value display
	function display_range_value(id, value) {
		var slider = document.getElementById(id);
		var output = document.getElementById(id+"-value");
		output.innerHTML = slider.value; // display the default value

		// Update the current slider value (each time you drag the slider handle)
		slider.oninput = function() {
			output.innerHTML = this.value;
		}
	}
	
	jQuery(document).ready(function() {
		function updateLayout(layout) {
			jQuery('.contact_layout_one, .contact_layout_two').removeClass('contact_layout');
			if (layout === 'template1') {
				jQuery('#contact_form_width').val(40);
				jQuery('.contact_layout_one').addClass('contact_layout');
			} else if (layout === 'template2') {
				jQuery('#contact_form_width').val(60);
				jQuery('.contact_layout_two').addClass('contact_layout');
			}
		}

		var layout = jQuery('[name=contact_form_template]:checked').val();
		updateLayout(layout);

		jQuery('input[name=contact_form_template]').change(function() {
			updateLayout(jQuery(this).val());
		});
	});


	
	// tab
	jQuery("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
		e.preventDefault();
		jQuery(this).siblings('a.active').removeClass("active");
		jQuery(this).addClass("active");
		var index = jQuery(this).index();
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
	});
	
	</script>
<?php