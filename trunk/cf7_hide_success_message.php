<?php 

/**
 * Plugin Name: Contact Form-7 : Hide Success Message
 * Plugin URI: http://stackoverflow.com/users/2778929/rohil-phpbeginner
 * Description: Contact Form-7 : Hide Success Message will hide the Success Message after defined time by the user only if message sent successfully
 * Version: 1.0.0
 * Author: rohilmistry
 * Author URI: http://stackoverflow.com/users/2778929/rohil-phpbeginner
 * License: GPLv3
 * 
 * 
 * 
 */

/*
 * Check whether plugin is active or not
 *
 * @since 1.0.0
 *
 */
function cf7_is_plugin_active( $plugin ) {
	return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
}
 
/*
 * Add option to the database at the time of activation if option is not added
 *
 * @since 1.0.0
 *
 */
register_activation_hook ( __FILE__, 'cf7_hide_success_msg_activate' );

function cf7_hide_success_msg_activate(){
	
    if ( !get_option( 'cf7_hide_success_msg_activated' ) ) {
        add_option( 'cf7_hide_success_msg_activated', 'false' );
    }
	elseif( !get_option( 'cf7_hide_success_msg_delay_time' ) ){
		add_option( 'cf7_hide_success_msg_delay_time', '2000' );
	}
	elseif( !get_option( 'cf7_hide_success_msg_effect' ) ){
		add_option( 'cf7_hide_success_msg_effect', 'fadeOut' );
	}
	elseif( !get_option( 'cf7_hide_success_msg_speed_effect' ) ){
		add_option('cf7_hide_success_msg_speed_effect', 'slow');
	}
	
}

/*
 * @since 1.0.0
 */ 
 
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
	$effect_	=	get_option('cf7_hide_success_msg_effect');
	$speed_effect_=	get_option('cf7_hide_success_msg_speed_effect');	
	
	//If plugin is activated then only enqueue script on front end
	if($plugin_activated === "true"):
		
		wp_enqueue_script('cf7_hide_msg', plugin_dir_url(__FILE__)  . 'assets/js/cf7_hide_msg_front_end.js', array('jquery'), '1.0', true);

		//Localize script to use parameter in cf7_hide_msg_front_end.js
		wp_localize_script( 'cf7_hide_msg', 'cf7_hide_success_msg_params', array(
			'delay_time'=> 	$delay_time,
			'effect'	=>	$effect_,
			'speed'		=>	$speed_effect_
		));
		
	endif;
}

/*
 * @since 1.0.0
 */

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_setting_links' );

function add_setting_links ( $links ) {
	
	$setting = array(
		'<a href="' . admin_url( 'options-general.php?page=cf7_hide_msg' ) . '">Settings</a>',
	);
	
	return array_merge( $links, $setting );
	
}

/*
 * AJAX call function - Update settings
 *
 * @since 1.0.0
 *
 */
 
function save_options(){
	
	if( isset($_POST['activate_plugin']) && isset($_POST['nonce']) ){
		if( $_POST['activate_plugin'] != '' && $_POST['nonce'] != '' ){
			//Update settings
			update_option('cf7_hide_success_msg_activated', $_POST['activate_plugin']);
			update_option('cf7_hide_success_msg_delay_time', intval($_POST['delay_time']));
			update_option('cf7_hide_success_msg_effect', esc_attr($_POST['effect_']));
			update_option('cf7_hide_success_msg_speed_effect', esc_attr($_POST['speed_effect_']));
		}
	}
	die();
}

/*
 * @since 1.0.0
 */

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
					$effect_	=	get_option('cf7_hide_success_msg_effect');
					$speed_effect_=	get_option('cf7_hide_success_msg_speed_effect');
					
					echo "<div class='wrap'>
							<h2> Contact Form-7 : Hide Success Message </h2>";
					
					echo '<div class="updated"></div>
							<div class="welcome-panel" id="welcome-panel">
								<div class="welcome-panel-content">
									<div class="welcome-panel-column-container">
										<div class="welcome-panel-column">
											<h4>Welcome to Contact Form 7:Hide Success Message</h4>
											<p>As many people want to hide the message after email has been sent<br> successfully, but somehow they do not manage it with the Additional<br> Settings that Contact Form 7 Provides.So this plugin is to help those<br> people.You just need to enable the plugin from below and you can<br> configure it as per your needs.<br><a href="https://wordpress.org/support/plugin/contact-form-7-hide-success-message" target="_blank">Give suggestion/Feature Request!</a><br><h4><strong>Bingo !!</strong></h4> Now go and test it!</p>
										</div>
										<div class="welcome-panel-column">
											<h4>Want help?</h4>
											<p>We are always ready to help you.</p>
											<a href="https://wordpress.org/support/plugin/contact-form-7-hide-success-message" class="button button-primary" target="_blank">Ask for Support!</a>										
										</div>
										<div class="welcome-panel-column">
											<h4>Like it ???</h4>
											<p>Like that plugin ??? then please leave us <a href="https://wordpress.org/support/view/plugin-reviews/contact-form-7-hide-success-message?filter=5#postform" target="_blank" class="wc-rating-link" data-rated="Thanks :)">&#9733;&#9733;&#9733;&#9733;&#9733;</a> ratings to keep us motivated! Thanks in advance from Contact Form 7:Hide Success Message.</p>
											<p>Also a <strong>Big Thanks</strong> to <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a> plugin.
										</div>
									</div>
								</div>
							</div>
							<form class="cf7_init" action="options.php" >
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
											Effect :
										</th>
										<td class="forminp forminp-checkbox">
											<fieldset>
												<select name="effect_" id="cf7_hide_msg_effect">';
	?>											
													<option value="fadeOut" <?php if($effect_ == 'fadeOut'){echo "selected";} ?> >Fade Out</option>
													<option value="slideUp" <?php if($effect_ == 'slideUp'){echo "selected";} ?> >Slide Up</option>
	<?php												
											echo '</select>												
											</fieldset>
										</td>
									</tr>
									<tr valign="top" class="">
										<th scope="row" class="titledesc">
											Speed of Effect :
										</th>
										<td class="forminp forminp-checkbox">
											<fieldset>
												<select name="speed_effect_" id="cf7_hide_msg_speed_effect">';
	?>									
													<option value="1000" <?php if($speed_effect_ == '1000'){echo "selected";} ?> >1000 MilliSecond</option>
													<option value="1500" <?php if($speed_effect_ == '1500'){echo "selected";} ?> >1500 MilliSecond</option>
													<option value="slow" <?php if($speed_effect_ == 'slow'){echo "selected";} ?> >Slow</option>
													<option value="fast" <?php if($speed_effect_ == 'fast'){echo "selected";} ?> >Fast</option>
	<?php												
											echo '</select>
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
						'delay_time' => $delay_time,
						'effect_'	=>	$effect_,
						'speed_effect_'=> $speed_effect_
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