<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @since 1.0.0
 *
 * @package Simmer/Uninstall
*/

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	return;
}

require_once( plugin_dir_path( __FILE__ ) . 'core/class-simmer-installer.php' );

Simmer_Installer::uninstall();
