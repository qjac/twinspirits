<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

require_once( 'wpsp-config.php' );

delete_option( 'wp_splash_page_config' );

delete_option( 'wp_splash_page_options' );

delete_option( 'wp_splash_page_options_preview' );

$wpdb->query( 'DROP TABLE IF EXISTS ' . WP_SPLASH_PAGE_TABLE_IPS . ';' );

?>