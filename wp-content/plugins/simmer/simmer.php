<?php
/**
 * Load the plugin
 *
 * @since 1.0.0
 *
 * @package Simmer
 */

/**
 * Plugin Name: Recipes by Simmer
 * Plugin URI:  https://simmerwp.com
 * Description: A recipe publishing tool for WordPress.
 * Version:     1.3.11
 * Author:      Simmer
 * Author URI:  https://simmerwp.com/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: simmer
 * Domain Path: /languages
 */

// If this file is called directly, bail.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The base plugin file path (this file).
 *
 * @since 1.0.3
 * @var string SIMMER_PLUGIN_FILE The base plugin file path.
 */
define( 'SIMMER_PLUGIN_FILE', plugin_basename( __FILE__ ) );

/**
 * Load the main Simmer class.
 */
require_once( plugin_dir_path( __FILE__ ) . 'core/class-simmer.php' );

// Instantiate Simmer.
Simmer::get_instance();
