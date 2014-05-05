jQuery(document).ready(function ($) {
	jQuery('.totop').click(function(){
		jQuery('html, body').animate({ scrollTop: 0 }, "slow");
	});

	// full year
	jQuery(".year").text( (new Date).getFullYear() );
});