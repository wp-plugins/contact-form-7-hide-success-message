/**
 * backend.js
 *
 * @author Rohil Mistry
 * @package CF7 Success Page Search with AJAX
 * @version 1.1.1
 */
 
//There is no use of request variable here, you can delete it 
var ajax_url		=	cf7_hide_msg_params.admin_ajax_url,
	nonce			=	cf7_hide_msg_params._nonce,
	loader_icon		=	cf7_hide_msg_params.loading;

var enable_plugin	=	jQuery("#enable_cf7_hide_success_msg").is(":checked");

jQuery(".ajax_loader").css('background', 'url('+loader_icon+')');
jQuery(".updated").hide();
jQuery(".ajax_loader").hide();
jQuery("#cf7Init").click(function(){
	
	jQuery(".cf7_init .form-table").css('opacity', '0.5');
	jQuery(".ajax_loader").show();
	var data	=	{
		action:	"save_selected_option",
		nonce: nonce,
		delay_time : jQuery("#cf7_hide_msg_input").val(),
		effect_	   : jQuery("#cf7_hide_msg_effect").val(),
		speed_effect_:jQuery("#cf7_hide_msg_speed_effect").val(),
		activate_plugin : jQuery("#enable_cf7_hide_success_msg").is(":checked")
	}
	
	jQuery.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function(mySuccess){
			jQuery(".cf7_init .form-table").css('opacity', '1');
			jQuery(".ajax_loader").css('display', 'none');
			jQuery(".updated").show().html("<p>Settings saved successfully.</p>").delay(2000).fadeOut('slow');
		}
		
	});
	
});