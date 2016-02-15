<?php

global $wpdb;
define( 'WP_SPLASH_PAGE_VERSION', '1.2' );
define( 'WP_SPLASH_PAGE_ROOT_PATH', plugin_dir_path( __FILE__ ) );
define( 'WP_SPLASH_PAGE_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_SPLASH_PAGE_FORM_NONCE', 'wpsp-nonce' );
define( 'WP_SPLASH_PAGE_PREVIEW_NONCE', 'preview-nonce' );
define( 'WP_SPLASH_PAGE_TABLE_IPS',  $wpdb->prefix . 'wpsp_ips' );

?>