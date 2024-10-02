<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
?>
<script>
function ValidateForm() {
	jQuery(".error").hide();			
	var action = "submit-query";
	var action = "submit-query";
	var name = jQuery("#name").val();
	var email = jQuery("#email").val();
	var subject = jQuery("#subject").val();
	var message = jQuery("#message").val();
	
	//validation check
	if(name == "") {
		jQuery("#name").after('<p class="error alert alert-warning"><strong><?php echo esc_js($name_error_field); ?></strong></p>');
		jQuery("#name").focus();
		jQuery(".error").fadeOut(3000);
		return false;
	}
	
	if(email == "") {
		jQuery("#email").after('<p class="error alert alert-warning"><strong><?php echo esc_js($email_error_field); ?></strong></p>');
		jQuery("#email").focus();
		jQuery(".error").fadeOut(3000);
		return false;
	}
	
	if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) == false) {
		jQuery("#email").after('<p class="error alert alert-warning"><strong><?php echo esc_js($email_error_field_2); ?></strong></p>');
		jQuery("#email").focus();
		jQuery(".error").fadeOut(3000);
		return false;
	}
	
	if(subject == "") {
		jQuery("#subject").after('<p class="error alert alert-warning"><strong><?php echo esc_js($subject_error_field); ?></strong></p>');
		jQuery("#subject").focus();
		jQuery(".error").fadeOut(3000);
		return false;
	}
	
	if(message == "") {
		jQuery("#message").after('<p class="error alert alert-warning"><strong><?php echo esc_js($message_error_field); ?></strong></p>');
		jQuery("#message").focus();
		jQuery(".error").fadeOut(3000);
		return false;
	}
	
	jQuery("#user-contact-form").hide();
	jQuery("#awp-loading-icon").show();		
}
</script>