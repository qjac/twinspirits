<?php
/*
Plugin Name: WP Splash Page
Plugin URI: https://wordpress.org/plugins/wp-splash-page/
Description: A splash page for WordPress, simple and easy to use.
Version: 1.2
Author: OptimalDevs
Author URI: https://profiles.wordpress.org/optimaldevs/
*/

require_once( 'wpsp-config.php' );

load_plugin_textdomain( 'wp-splash-page-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

require_once( 'inc/activation.php' );

add_action( 'init', 'wp_splash_page_init' );

function wp_splash_page_init() {	
	
	if ( ! is_admin() )	
		add_action( 'template_redirect', 'wp_splash_page_generate' );
	
	if ( is_admin() ) {		
		
		wpsp_import_class( 'WP_Splash_Page_Admin', 'inc/admin/class-wp-splash-page-admin.php' );
		
		require_once( 'inc/preview-ajax.php' );
			
		add_action( 'wp_ajax_wp_splash_page_hook', 'wp_splash_page_show_preview' );
			
	}
	
}

function wp_splash_page_generate() {

	wpsp_import_class( 'WP_Splash_Page', 'inc/class-wp-splash-page.php' );
		
	$wpsp	= new WP_Splash_Page();

	if ( $wpsp->is_active() )
		$wpsp->splash_page();

}

function wpsp_import_class( $class, $file ) {
	
	if ( ! class_exists( $class ) )
		require_once( $file );
	
}

?>