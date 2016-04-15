<?php
/**
 * @package TSD Team
 * @version 1.0
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
      <th><label for="show_on_team_page">Show on Team page?</label></th>
      <td><input type="checkbox" name="show_on_team_page" <?php if (get_the_author_meta( 'show_on_team_page', $user->ID) == 1 ) { ?>checked="checked"<?php }?>" /></td>
    </tr>
  </table>
  <?php
}

add_action( 'personal_options_update', 'save_extra_fields' );
add_action( 'edit_user_profile_update', 'save_extra_fields' );

function save_extra_fields( $user_id ) {
  update_user_meta( $user_id,'job_title', sanitize_text_field( $_POST['job_title'] ) );

  $checkbox = $_POST['show_on_team_page'] ? true : false;
  update_user_meta( $user_id,'show_on_team_page', $checkbox );
}