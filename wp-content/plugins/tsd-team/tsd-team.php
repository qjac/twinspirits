<?php
/**
 * @package TSD Team
 * @version 1.0
 * @see https://paulund.co.uk/add-custom-user-profile-fields
 */

/*
Plugin Name: TSD Team
Plugin URI: http://twinspirits.us
Description: Adds custom fields to Twin Spirits user pages.
Author: Sarah German
Author URI: http://www.sarahgerman.com
Version: 1.0
Text Domain: tsd-team
*/

if(!defined('ABSPATH')) {
  die('You are not allowed to call this page directly.');
}

/**
 * Add fields to User profiles.
 */
add_action( 'show_user_profile', 'add_extra_fields' );
add_action( 'edit_user_profile', 'add_extra_fields' );

function add_extra_fields( $user ) {
  ?>
  <h3>TSD Team Info</h3>
  <table class="form-table">
    <tr>
      <th><label for="job_title">Job Title</label></th>
      <td><input type="text" name="job_title" value="<?php echo esc_attr(get_the_author_meta( 'job_title', $user->ID )); ?>" class="regular-text" /></td>
    </tr>
    <tr>
      <th><label for="list_position">List position</label></th>
      <td><input type="text" name="list_position" value="<?php echo esc_attr(get_the_author_meta( 'list_position', $user->ID )); ?>" class="regular-text" /></td>
    </tr>
  </table>
  <?php
}

/**
 * Save the custom fields defined above.
 */
add_action( 'personal_options_update', 'save_extra_fields' );
add_action( 'edit_user_profile_update', 'save_extra_fields' );

function save_extra_fields( $user_id ) {
  update_user_meta( $user_id,'job_title', sanitize_text_field( $_POST['job_title'] ) );
  update_user_meta( $user_id,'list_position', sanitize_text_field( $_POST['list_position'] ) );
}