<?php

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

$option_delay_time = 'cf7_hide_success_msg_delay_time';
$option_activated =	'cf7_hide_success_msg_activated';
$effect_	=	'cf7_hide_success_msg_effect';
$speed_effect_=	'cf7_hide_success_msg_speed_effect';


delete_option( $option_delay_time );
delete_option( $option_activated );
delete_option( $effect_ );
delete_option( $speed_effect_ );
