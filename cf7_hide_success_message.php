<?php 

/**
 * Plugin Name: Contact Form-7 : Hide Success Message
 * Plugin URI: http://stackoverflow.com/users/2778929/rohil-phpbeginner
 * Description: Contact Form-7 : Hide Success Message will hide the Success Message after defined time by the user only if message sent successfully
 * Version: 0.5
 * Author: rohilmistry
 * Author URI: http://stackoverflow.com/users/2778929/rohil-phpbeginner
 * License: GPLv3
 * 
 * 
 * 
 */

 function cf7_is_plugin_active( $plugin ) {
    return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}
 
//Plugin Activation to add default option
register_activation_hook ( __FILE__, 'cf7_hide_success_msg_activate' );

function cf7_hide_success_msg_activate(){
	
    if ( !get_option( 'cf7_hide_success_msg_activated' ) && !get_option( 'cf7_hide_success_msg_delay_time' ) ) {
        add_option( 'cf7_hide_success_msg_activated', 'false' );
		add_option( 'cf7_hide_success_msg_delay_time', '2000' );
    }
	
}
 
 
add_action('init', 'cf7_init'); 

function cf7_init(){
	//Enqueue CSS for the admin section
	wp_enqueue_style( 'cf7_admin_style',  plugin_dir_url(__FILE__) . 'assets/css/admin_style.css' );
	
	//AJAX call
	add_action('wp_ajax_save_selected_option', 'save_options');
	add_action('wp_ajax_nopriv_save_selected_option', 'save_options');
	
	//Retrieve Plugin settings
	$plugin_activated	=	get_option('cf7_hide_success_msg_activated');
	$delay_time	=	get_option('cf7_hide_success_msg_delay_time');
	
	//If plugin is activated then only enqueue script on front end
	if($plugin_activated === "true"):
		
		wp_enqueue_script('cf7_hide_msg', plugin_dir_url(__FILE__)  . 'assets/js/cf7_hide_msg_front_end.js', array('jquery'), '1.0', true);

		//Localize script to use parameter in cf7_hide_msg_front_end.js
		wp_localize_script( 'cf7_hide_msg', 'cf7_hide_success_msg_params', array(
			'delay_time' => $delay_time
		));
		
	endif;
}

//AJAX call function to save options
function save_options(){
	
	if( isset($_POST['activate_plugin']) && isset($_POST['nonce']) ){
		if( $_POST['activate_plugin'] != '' && $_POST['nonce'] != '' ){
			//Update settings
			update_option('cf7_hide_success_msg_activated', $_POST['activate_plugin']);
			update_option('cf7_hide_success_msg_delay_time', $_POST['delay_time']);

		}
	}
	die();
}


if(cf7_is_plugin_active('contact-form-7/wp-contact-form-7.php')){
	
	if ( ! class_exists( 'cf7_hide_success_msg' ) ) {
	
		class cf7_hide_success_msg {
			
			function __construct() {
				add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			}
			
			function admin_menu () {
				add_options_page( 'Hide Success Message','Hide Success Message','manage_options','cf7_hide_msg', array( $this, 'cf7_hide_success_msg_settings_page' ) );
			}
			
			function  cf7_hide_success_msg_settings_page () {
				
				if(is_plugin_active('contact-form-7/wp-contact-form-7.php')){
					
					$delay_time	=	get_option('cf7_hide_success_msg_delay_time');
					
					echo "<h1 style='margin-left:2%;'> Contact Form-7 : Hide Success Message </h1>";
					
					echo '<div class="updated"></div><form class="cf7_init" action="options.php" >
							<table class="form-table">
								<tr valign="top" class="">
									<th scope="row" class="titledesc">Enable Contact Form-7 : Hide Success Message</th>
									<td class="forminp forminp-checkbox">
										<fieldset>';
?>
											<input name="enable_cf7_hide_success_msg" id="enable_cf7_hide_success_msg" type="checkbox" value="true" <?php if(get_option('cf7_hide_success_msg_activated') == "true" ){echo "checked";} ?> /> Enable all plugin features.
<?php					echo	'			</fieldset>
								</td>
								</tr>
								<tr valign="top" class="">
									<th scope="row" class="titledesc">
										Delay time :
									</th>
									<td class="forminp forminp-checkbox">
										<fieldset>
											<input type="text" name="delay_time_" id="cf7_hide_msg_input" class="cf7_hide_msg_input" value="'.$delay_time.'"/>(Default is 2000 in milli second)
										</fieldset>
									</td>
								</tr>								
								<tr valign="top" class="">
									<th scope="row" class="titledesc">
										<input type="button" class="button-primary" value="Save Changes" id="cf7Init" />
										<span class="ajax_loader" ></span>
									</th>
								</tr>
							</table>
						</form>';
						
					//Enqueue backend script	
					wp_enqueue_script('cf7_save_all_options', plugin_dir_url(__FILE__)  . 'assets/js/cf7_hide_msg_back_end.js', array('jquery'), '1.0', true);

					//Localize script to use parameter in cf7_hide_msg_back_end.js
					wp_localize_script( 'cf7_save_all_options', 'cf7_hide_msg_params', array(
						'loading' => plugin_dir_url(__FILE__)  .'assets/images/ajax-loader.gif',
						'admin_ajax_url'=>admin_url( 'admin-ajax.php' ),
						'_nonce' => wp_create_nonce( 'cf7_hide_success_msg_nonce' ),
						'delay_time' => $delay_time
					));					

				}
				else{
					echo '<div class="error"><p>Please activate Contact form-7 plugin to use this setting page.</p></div>';
				}
			}
		}
		new cf7_hide_success_msg;
	}
}
else{
	
	add_action('admin_notices', 'active_cf7' );
	
	function active_cf7(){
	
		echo '<div class="error"><p><strong>Warning: </strong>Contact Form-7 Hide Success Message requires Contact Form-7 plugin to be installed and activated.</p></div>';
	
	}
	
}
?>