<?php

if( !defined( 'ABSPATH' ) ){

	exit(-1);
}


    if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
    
	delete_option('inazo_adv_ads_management_db_version');
	delete_option('widget_inazo_adv_ads_manager');
	
	global $wpdb;
    $wpdb->query('DROP TABLE IF EXISTS '.$wpdb->prefix.'inazo_wp_adv_ads_management_the_adds');      
?>