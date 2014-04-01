// When the browser is ready...
$(document).ready(function(){

	var loader = $('.load').hide();
	// Setup form validation on the #register-form element
	$("#order-form").validate(
	{

		// Specify the validation rules
		rules:
		{
			product : "required",
			quantity : "required",
			name : "required",
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
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},

		// Specify the validation error messages
		messages:
		{
			product: "Please enter the product you want to order",
			quantity: "Please enter or select the quantity you want",
			email: "Please enter a valid email address, eg. buy@sellingdomain.com"
		},

		// Submit the form
		submitHandler: function(form)
		{
			//console.log(JSON.stringify(form.quantity.));
			var d = $(form).serialize().replace('&send=','')+'&task=order';
			console.log(d);
			var request = $.ajax({
				type: 'POST',
				contentType: "application/json",
				url : 'http://mizudori.jp/international/api/',
				data: d,

			}).done(function(data) {
				form.resetForm();
				$("div.error span").html('Your order has successfully been submitted for processing');
				$("div.error").toggleClass('alert-success');
				$("div.error").show();

			}).fail(function(data){
				console.log('failed '+JSON.stringify(data));
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