jQuery(document).ready(function(){
	
	var delay_time	=	cf7_hide_success_msg_params.delay_time,
		effect		=	cf7_hide_success_msg_params.effect,
		speed		=	cf7_hide_success_msg_params.speed;
	console.log(delay_time);
	console.log(effect);
	console.log(speed);
	jQuery(document).on("ajaxComplete", function(){
		
		jQuery('.wpcf7-mail-sent-ok').delay(delay_time)[effect](speed);
		
	});
	
});

//jQuery('.wpcf7-mail-sent-ok').ajaxComplete(function(){jQuery(this).delay(2000).fadeOut('slow');});