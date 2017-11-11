<?php
/**
 * @package TSD Help
 * @version 1.0
 */

/*
Plugin Name: TSD Help
Plugin URI: https://twinspirits.us
Description: Adds a help widget for TSD admins.
Author: Sarah German
Author URI: https://www.sarahgerman.com
Version: 1.0
Text Domain: tsd-help
*/

if(!defined('ABSPATH')) {
  die('You are not allowed to call this page directly.');
}

add_action('wp_dashboard_setup', 'tsd_help_dashboard_widgets');

/**
 * Register the custom widget.
 */
function tsd_help_dashboard_widgets() {
  global $wp_meta_boxes;
  wp_add_dashboard_widget('tsd_help_widget', 'Twin Spirits Site Help', 'tsd_dashboard_help');
}

/**
 * Content for the TSD Help widget.
 * @return string Markup for the widget.
 */
function tsd_dashboard_help() {
  $output  = '<p>Click the links below to view a screencast showing how to do these things:</p>';
  $output .= '<ul>';

  $help_links = [
    'Update your password' => '1_changePassword.mp4',
    'Edit an existing page' => '2_editPage.mp4',
    'Add a new basic page' => '3_newPage.mp4',
    'Add a page to the menu' => '4_addToMenu.mp4',
    'Add images to a gallery' => '5_gallery.mp4'
  ];
  
  $items = [];
  $base_screencast_url = get_site_url() . '/wp-content/uploads/screencasts/';  
  foreach ($help_links as $text => $link) {
    $items[] = '<li><a href="'. $link .'">'. $text .'</a></li>'; 
  }

  $output .= implode(PHP_EOL, $items);
  $output .= '</ul>';
  return $output;
}

