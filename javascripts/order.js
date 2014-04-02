// When the browser is ready...
$(document).ready(function(){

	var loader = $('.load').hide();
	// Setup form validation on the #register-form element
	var valid = $("#order-form").validate(
	{

		// Specify the validation rules
		rules:
		{
			product : 'required',
			quantity : 'required',
			name : 'required',
			email : {
				required : true,
				email : true
			},
			phone : 'required',
			address : 'required',
			city : 'required',
			country : 'required'
		},

		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'You missed 1 field. It has been highlighted below'
					: 'You missed ' + errors + ' fields.  They have been highlighted below';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},

		// Specify the validation error messages
		messages:
		{
			product : 'Please enter the product you want to order',
			quantity : 'Please enter or select the quantity you want',
			email : 'Please enter a valid email address, eg. buy@sellingdomain.com',
			phone : 'Please enter your phone number',
			address : 'Please enter a shipping address',
			city : 'Please enter your city',
			country : 'Please select a country for your order to be shipped to'
		},

		// Submit the form
		submitHandler: function(form)
		{

			var d = $(form).serialize();
			$.post('http://mizudori.jp/mizudori-international/api/', d, function(data) {
				$("div.error span").html('Your order has successfully been submitted for processing');
				$("div.error").toggleClass('alert-success');
				$("div.error").show();
				valid.resetForm();
				form.reset();
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