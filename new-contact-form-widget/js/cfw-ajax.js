function ValidateForm(query_nonce_value) {	
	jQuery(".cfw-error").hide();			
	var action = "submit-query";
	var name = jQuery("#name").val();
	var email = jQuery("#email").val();
	var subject = jQuery("#subject").val();
	var message = jQuery("#message").val();
	
	//validation check
	if(name == "") {
		jQuery(".name-error").show();
		jQuery("#name").focus();
		jQuery(".cfw-error").fadeOut(4000);
		return false;
	}
	
	if(email == "") {
		jQuery(".email-error").show();
		jQuery("#email").focus();
		jQuery(".cfw-error").fadeOut(4000);
		return false;
	}
	
	if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) == false) {
		jQuery(".email-error-2").show();
		jQuery("#email").focus();
		jQuery(".cfw-error").fadeOut(4000);
		return false;
	}
	
	if(subject == "") {
		jQuery(".subject-error").show();
		jQuery("#subject").focus();
		jQuery(".cfw-error").fadeOut(4000);
		return false;
	}
	
	if(message == "") {
		jQuery(".message-error").show();
		jQuery("#message").focus();
		jQuery(".cfw-error").fadeOut(4000);
		return false;
	}
	
	jQuery("#user-contact-form").hide();
	jQuery("#awp-loading-icon").show();
	
	var alldata = {
		'action': 'submit_user_query',
		'formsdata': jQuery("#user-contact-form").serialize() + '&security=' + query_nonce_value,
	};

	jQuery.post(cfw_ajax.ajaxurl, alldata, function(response) {
		jQuery("#awp-loading-icon").hide();
		jQuery("#contact-result").show();
		jQuery("#contact-result").text(response.substring(0, response.indexOf('0')));		
	});
}