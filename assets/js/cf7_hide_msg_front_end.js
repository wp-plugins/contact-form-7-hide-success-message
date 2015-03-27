jQuery(document).ready(function(){
	
	var delay_time = cf7_hide_success_msg_params.delay_time;
	
	jQuery(document).on("ajaxComplete", function(){
		
		jQuery('.wpcf7-mail-sent-ok').delay(delay_time).fadeOut('slow');
		
	});
	
});

//jQuery('.wpcf7-mail-sent-ok').ajaxComplete(function(){jQuery(this).delay(2000).fadeOut('slow');});