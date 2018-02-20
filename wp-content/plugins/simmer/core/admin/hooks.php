<?php
/**
 * Add the admin action and filter hooks
 *
 * @since 1.3.3
 *
 * @package Simmer/Admin/Hooks
 */

// If this file is called directly, get outa' town.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**** General Dashboard ****/

// Load the dashboard customizing class.
$simmer_dashboard = Simmer_Admin_Dashboard::get_instance();

// Enqueue the custom scripts.
add_action( 'admin_enqueue_scripts', array( $simmer_dashboard, 'enqueue_scripts' ) );

// Add the published recipe count to the "At a Glance" dashboard widget.
add_filter( 'dashboard_glance_items', array( $simmer_dashboard, 'add_glance_recipe_count' ) );

// Add the plugin list table row "Settings" link.
add_action( 'plugin_action_links_' . SIMMER_PLUGIN_FILE, array( $simmer_dashboard, 'add_settings_link' ) );

//Add a Simmer "thank you" link to the admin footer.
add_action( 'admin_footer_text', array( $simmer_dashboard, 'add_footer_text' ), 20 );

// Unload the dashboard customizing class from memory.
unset( $simmer_dashboard );


/**** Recipes ****/

// Load the recipes admin class.
$simmer_admin_recipes = Simmer_Admin_Recipes::get_instance();

// Add the recipe metaboxes.
add_action( 'add_meta_boxes', array( $simmer_admin_recipes, 'add_metaboxes' ) );

// Save the recipe meta.
add_action( 'save_post_recipe', array( $simmer_admin_recipes, 'save_recipe_meta' ) );

// Add custom "updated" messages.
add_filter( 'post_updated_messages', array( $simmer_admin_recipes, 'updated_messages' ) );

// Add custom bulk 'recipe updated' messages.
add_filter( 'bulk_post_updated_messages', array( $simmer_admin_recipes, 'bulk_updated_messages' ), 10, 2 );

// Remove the recipe items on recipe deletion.
add_action( 'delete_post', array( $simmer_admin_recipes, 'delete_recipe_items' ) );

// Unload the recipes admin class from memory.
unset( $simmer_admin_recipes );


/**** Bulk-Add ****/

// Load the ingredients/instructions bulk-add class.
$simmer_admin_bulk_add = Simmer_Admin_Bulk_Add::get_instance();

add_action( 'admin_footer', array( $simmer_admin_bulk_add, 'add_modal' ) );

add_action( 'wp_ajax_simmer_process_bulk', array( $simmer_admin_bulk_add, 'process_ajax' ) );

// Unload the ingredients/instructions bulk-add class from memory.
unset( $simmer_admin_bulk_add );


/**** Shortcode UI ****/

// Load the shortcode UI class.
$simmer_shortcode_ui = Simmer_Admin_Shortcode_UI::get_instance();

// Enqueue the modal script.
add_action( 'admin_enqueue_scripts', array( $simmer_shortcode_ui, 'enqueue_script' ) );

// Add the 'Add Recipe' button above the main content editor.
add_action( 'media_buttons', array( $simmer_shortcode_ui, 'add_media_button' ), 99 );

add_action( 'admin_footer', array( $simmer_shortcode_ui, 'add_modal' ) );

// Unoad the shortcode UI class from memory.
unset( $simmer_shortcode_ui );


/**** Settings ****/

// Load the admin settings class.
$simmer_admin_settings = Simmer_Admin_Settings::get_instance();

// Add the settings submenu item.
add_action( 'admin_menu', array( $simmer_admin_settings, 'add_options_page' ) );

// Register the available settings with the Settings API.
add_action( 'admin_init', array( $simmer_admin_settings, 'register_settings' ) );

// Enqueue the admin styles.
add_action( 'admin_enqueue_scripts', array( $simmer_admin_settings, 'enqueue_styles' ) );

// Enqueue the admin scripts.
add_action( 'admin_enqueue_scripts', array( $simmer_admin_settings, 'enqueue_scripts' ) );

// Unload the admin settings class from memory.
unset( $simmer_admin_settings );
