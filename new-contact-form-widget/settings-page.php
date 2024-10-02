<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	//toogle-button
	wp_enqueue_style('awl-cfw-button-css', plugin_dir_url( __FILE__ ).'css/toogle-button.css');
	wp_enqueue_style( 'cfw-bootstrap-css', plugin_dir_url( __FILE__ ).'css/cfw-bootstrap.css' );
	wp_enqueue_style( 'cfw-font-awesome-css', plugin_dir_url( __FILE__ ).'css/font-awesome.min.css' );
	wp_enqueue_style( 'cfw-metabox-css', plugin_dir_url( __FILE__ ).'css/metabox.css' );
	wp_enqueue_script( 'cfw-boostrap-js', plugin_dir_url( __FILE__ ).'js/bootstrap.js', array('jquery'), '3.3.6', true );
	wp_enqueue_style( 'wp-color-picker' ); 
	
	
	// js
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'cfw-color-picker-js',  plugin_dir_url( __FILE__ ).'js/cfw-color-picker.js', array( 'jquery', 'wp-color-picker' ), '', true  );
	wp_enqueue_script( 'jquery-ui-sortable' );		
	wp_localize_script( 'cfw-ajax', 'cfw_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
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
			$title_color = "#000000";
		
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
					<h1><?php esc_html_e( 'Select Template Design', 'abc-pricing-table' ); ?></h1>
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
					<h2><?php esc_html_e( 'Upgrade To Pro', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<!--Grid-->
					<div class="" style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Upgrade To Premium For Unloack More Features & Settings', 'new-contact-form-widget' ); ?></p>
					</div>

					<div class="">
						<h2><strong><?php esc_html_e( 'Offer:', 'new-contact-form-widget' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price ', 'modal-popup-box' ); ?><strike><?php esc_html_e( '$19.99', 'new-contact-form-widget' ); ?></strike> <strong><?php esc_html_e( '$ 12.99', 'new-contact-form-widget' ); ?></strong></h2>
						<br>
						<a href="https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'modal-popup-box' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/how-to-test-premium-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Try Pro Version', 'new-contact-form-widget' ); ?></a>
					</div>
				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Upgrade To Pro', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<!--Grid-->
					<div class="" style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Upgrade To Premium For Unloack More Features & Settings', 'modal-popup-box' ); ?></p>
					</div>

					<div class="">
						<h2><strong><?php esc_html_e( 'Offer:', 'new-contact-form-widget' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price ', 'new-contact-form-widget' ); ?><strike><?php esc_html_e( '$19.99', 'new-contact-form-widget' ); ?></strike> <strong><?php esc_html_e( '$ 12.99', 'new-contact-form-widget' ); ?></strong></h2>
						<br>
						<a href="https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'modal-popup-box' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/how-to-test-premium-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Try Pro Version', 'new-contact-form-widget' ); ?></a>
					</div>

				</div>
				<div class="bhoechie-tab-content">
					<h2><?php esc_html_e( 'Upgrade To Pro', 'new-contact-form-widget' ); ?></h2>
					<hr>
					<!--Grid-->
					<div class="" style="padding-left: 10px;">
						<p class="ms-title"><?php esc_html_e( 'Upgrade To Premium For Unloack More Features & Settings', 'new-contact-form-widget' ); ?></p>
					</div>

					<div class="">
						<h2><strong><?php esc_html_e( 'Offer:', 'new-contact-form-widget' ); ?></strong> <?php esc_html_e( 'Upgrade To Premium Just In Half Price ', 'new-contact-form-widget' ); ?><strike><?php esc_html_e( '$19.99', 'new-contact-form-widget' ); ?></strike> <strong><?php esc_html_e( '$ 12.99', 'new-contact-form-widget' ); ?></strong></h2>
						<br>
						<a href="https://awplife.com/wordpress-plugins/contact-form-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Premium Version Details', 'new-contact-form-widget' ); ?></a>
						<a href="https://awplife.com/demo/contact-form-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize"><?php esc_html_e( 'Check Live Demo', 'modal-popup-box' ); ?></a>
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
		
		<div class="p-4" style="text-align:center">
			<button id="cfw-save-settings" name="cfw-save-settings" type="button" href="#" class="btn btn-primary btn-lg" onclick="return SaveSettings();"><i class="fa fa-save" aria-hidden="true"></i> <?php esc_html_e('Save', 'new-contact-form-widget'); ?></button>
		</div>
	</form>
	<!-- settings ajax post code -->
	<script>

	function SaveSettings() {		
		jQuery(".error").hide();
		var action = 'cfw-save-setting';
		var qsm = jQuery("#qsm").val();
		var qfm = jQuery("#qfm").val();
		
		var contact_form_template = jQuery('input[name=contact_form_template]:checked', '#cfw-settings-form').val()
		var title_field = jQuery("#title_field").val();
		var title_color = jQuery("#title_color").val();
		var bg_color = jQuery("#bg_color").val();
		var contact_form_width = jQuery("#contact_form_width").val();
		var cfw_form_order = jQuery('input[name=cfw_form_order]:checked', '#cfw-settings-form').val();
		var description_field = jQuery("#description_field").val();	
		var name_field = jQuery("#name_field").val();
		var email_field = jQuery("#email_field").val();
		var subject_field = jQuery("#subject_field").val();
		var message_field = jQuery("#message_field").val();
		var name_error_field = jQuery("#name_error_field").val();
		var email_error_field = jQuery("#email_error_field").val();
		var email_error_field_2 = jQuery("#email_error_field_2").val();
		var subject_error_field = jQuery("#subject_error_field").val();
		var message_error_field = jQuery("#message_error_field").val();
		var sb_button_text = jQuery("#sb_button_text").val();
		var show_query = jQuery("#show_query").val();
		var cus_css = jQuery("#cus_css").val();
		
	
		var CFWAjax = new XMLHttpRequest();
		
		// hide saving button
		jQuery("#cfw-save-settings").hide();

		//show loading icon
		jQuery("#loading-msg").show();
	
		//check object request
		CFWAjax.onreadystatechange = function() {
			jQuery("#loading-msg").show();
			
			if (CFWAjax.readyState == 4 && CFWAjax.status == 200) {
				if(CFWAjax.responseText.indexOf("setting-successfully-saved") > 0) {
					//hide loading icon
					jQuery("#loading-msg").hide();
					
					// show saving button
					jQuery("#cfw-save-settings").show();
					
					//show setting saved successfully message
					jQuery("#success-msg").show();
					jQuery("#success-msg").fadeOut(3000);
				}
			}
			
			if(CFWAjax.status == 404) {
				alert('File not found & Object not responding.');
				return false;
			}
		};
		CFWAjax.open("POST", location.href, true);
		CFWAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		CFWAjax.send('action=' + action + '&security=' + '<?php echo esc_js(wp_create_nonce( "cfw_save_nonce" )); ?>' + '&qsm=' + qsm + '&qfm=' + qfm + '&contact_form_template=' + contact_form_template + '&title_field=' + title_field  +  '&name_error_field=' + name_error_field  +  '&email_error_field=' + email_error_field +  '&email_error_field_2=' + email_error_field_2  + '&subject_error_field=' + subject_error_field  +  '&message_error_field=' + message_error_field  +  '&title_color=' + title_color + '&bg_color=' + bg_color + '&contact_form_width=' + contact_form_width + '&cfw_form_order=' + cfw_form_order + '&description_field=' + description_field  + '&name_field=' + name_field + '&email_field=' + email_field + '&subject_field=' + subject_field + '&message_field=' + message_field + '&sb_button_text=' + sb_button_text  + '&show_query=' + show_query +  '&cus_css=' + cus_css );
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
	// php save settings
	if(isset($_POST['action'])) {
		$cfw_nonce_value = $_POST['security'];
		if(wp_verify_nonce( $cfw_nonce_value, 'cfw_save_nonce' )) {
			$action = $_POST['action'];
			if($action == "cfw-save-setting") {
				$qsm = sanitize_text_field($_POST['qsm']);
				$qfm = sanitize_text_field($_POST['qfm']);	
				$title_field = sanitize_text_field ($_POST['title_field']);
				$contact_form_template = sanitize_text_field ($_POST['contact_form_template']);
				$title_color = sanitize_text_field($_POST['title_color']);
				$bg_color = sanitize_text_field($_POST['bg_color']);
				$contact_form_width = sanitize_text_field($_POST['contact_form_width']);
				$cfw_form_order = sanitize_text_field($_POST['cfw_form_order']);
				$description_field = sanitize_text_field($_POST['description_field']);
				$name_field = sanitize_text_field ($_POST['name_field']);
				$email_field = sanitize_text_field ($_POST['email_field']);
				$subject_field = sanitize_text_field ($_POST['subject_field']);
				$message_field = sanitize_text_field ($_POST['message_field']);
				$name_error_field = sanitize_text_field ($_POST['name_error_field']);
				$email_error_field = sanitize_text_field ($_POST['email_error_field']);
				$email_error_field_2 = sanitize_text_field( $_POST['email_error_field_2']);
				$subject_error_field = sanitize_text_field ($_POST['subject_error_field']);
				$message_error_field = sanitize_text_field ($_POST['message_error_field']);
				$sb_button_text = sanitize_text_field ($_POST['sb_button_text']);
				$show_query = sanitize_text_field($_POST['show_query']);
				$cus_css = sanitize_text_field($_POST['cus_css']);			
				
				$all_settings = array(
					'qsm' => $qsm,
					'qfm' => $qfm,
					//Design settings
					'title_field' => $title_field,
					'contact_form_template' => $contact_form_template,
					'title_color' => $title_color,	
					'bg_color' => $bg_color,	
					'contact_form_width' => $contact_form_width,	
					'cfw_form_order' => $cfw_form_order,	
					'description_field' => $description_field,
					'name_field' => $name_field,
					'email_field' => $email_field,
					'subject_field' => $subject_field,
					'message_field' => $message_field,
					'name_error_field' => $name_error_field,
					'email_error_field' => $email_error_field,
					'email_error_field_2' => $email_error_field_2,
					'subject_error_field' => $subject_error_field,
					'message_error_field' => $message_error_field,
					'sb_button_text' => $sb_button_text,
					'show_query' => $show_query,
					'cus_css' => $cus_css,
				);
				
				if(update_option('contact_form_settings', $all_settings)) {
					echo "<p id='setting-saved'>setting-successfully-saved</p>";
				}
			}
		} // end of nonce check
	} // end if isset
?>