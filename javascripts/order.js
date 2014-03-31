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
			//form.submit();
			//var d = {'formkey' : '', $(form).serialize()};
			var v = $('#order-form').jsonify({stringify:true});
			var d = $(form).serialize();
			console.log(d);
			var request = $.ajax({
				type: 'POST',
				dataType: 'text/html',
				crossDomain: true,
				url: "https://docs.google.com/forms/d/1i47I61djvq8eHTxNI-0cMOSpjO-R1yvjv9k5MMuupJ0/formResponse",
				data: d,
				statusCode: {
					0: function (){
						//form.resetForm();
						console.log('it happened');
					},
					200: function (){
						//form.resetForm();
						console.log("'it didn't happen");
					}
				}
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