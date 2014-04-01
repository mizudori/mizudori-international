// When the browser is ready...
$(document).ready(function(){

	var loader = $('.load').hide();
	// Setup form validation on the #register-form element
	$("#contactform").validate(
	{

		// Specify the validation rules
		rules:
		{
			name : "required",
			comment : "required",
			email : {
				required : true,
				email : true
			},
		},

		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'You missed 1 field. It has been highlighted below'
					: 'You missed ' + errors + ' fields.  They have been highlighted below';
				$("div.error-msg span").html(message);
				$("div.error-msg").show();
			} else {
				$("div.error-msg").hide();
			}
		},

		// Specify the validation error messages
		messages:
		{
			name: "Please  enter your full name",
			email: "Please enter a valid email address, eg. buy@sellingdomain.com",
			comment: "Please provide a comment"
		},

		// Submit the form
		submitHandler: function(form)
		{
			//console.log(JSON.stringify(form.quantity.));
			var d = $(form).serialize();

			$.post('http://mizudori.jp/mizudori-international/api/', d, function(data){
				$("div.error-msg span").html('Your message has been successfully submitted');
				$("div.error-msg").toggleClass('alert-success');
				$("div.error-msg").show();
			});
			return false;
		}
	});

	$(document).ajaxStart(function() {
		loader.show();
	}).ajaxStop(function() {
		loader.hide();
	}).ajaxError(function(a, b, e) {
		//throw e;
	});
});