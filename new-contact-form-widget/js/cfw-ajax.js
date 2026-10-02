function ValidateForm(query_nonce_value, btn) {
	var $form = btn ? jQuery(btn).closest('form') : jQuery("#user-contact-form");
	if ($form.length === 0) {
		$form = jQuery("#user-contact-form");
	}
	var $container = $form.closest('.cfw-container');
	if ($container.length === 0) {
		$container = $form.parent();
	}

	$form.find(".cfw-error").hide();

	var nameField = $form.find('[name="name"]');
	var emailField = $form.find('[name="email"]');
	var subjectField = $form.find('[name="subject"]');
	var messageField = $form.find('[name="message"]');

	var name = jQuery.trim(nameField.val());
	var email = jQuery.trim(emailField.val());
	var subject = jQuery.trim(subjectField.val());
	var message = jQuery.trim(messageField.val());

	// Validation check
	if (name === "") {
		$form.find(".name-error").show();
		nameField.focus();
		$form.find(".cfw-error").fadeOut(4000);
		return false;
	}

	if (email === "") {
		$form.find(".email-error").show();
		emailField.focus();
		$form.find(".cfw-error").fadeOut(4000);
		return false;
	}

	// Modern RFC-compliant email regex allowing any valid TLD and subaddressing
	var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
	if (!emailPattern.test(email)) {
		$form.find(".email-error-2").show();
		emailField.focus();
		$form.find(".cfw-error").fadeOut(4000);
		return false;
	}

	if (subject === "") {
		$form.find(".subject-error").show();
		subjectField.focus();
		$form.find(".cfw-error").fadeOut(4000);
		return false;
	}

	if (message === "") {
		$form.find(".message-error").show();
		messageField.focus();
		$form.find(".cfw-error").fadeOut(4000);
		return false;
	}

	$form.hide();
	var $loading = $container.find("#awp-loading-icon, .awp-loading-icon");
	var $result = $container.find("#contact-result, .contact-result");
	$loading.show();

	var alldata = {
		'action': 'submit_user_query',
		'formsdata': $form.serialize(),
		'security': query_nonce_value
	};

	jQuery.post(cfw_ajax.ajaxurl, alldata, function(response) {
		$loading.hide();
		$result.show();
		$result.text(response);
	});

	return false;
}