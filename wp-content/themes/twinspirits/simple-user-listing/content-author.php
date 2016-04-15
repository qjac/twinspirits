<?php
/**
 * The Template for displaying Author listings
 *
 * Override this template by copying it to yourtheme/authors/content-author.php
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $user;
$user_info = get_userdata( $user->ID );
?>


<div id="user-<?php echo $user->ID; ?>" class="six columns">
  <?php echo get_avatar( $user->ID, 365 ); ?>

  <h2><?php echo $user_info->display_name; ?></h2>

  <?php
  $title = get_the_author_meta( 'job_title', $user->ID );
  if ($title) { echo '<h3>'; the_author_meta( 'job_title', $user->ID ); echo '</h3>'; }
  ?>

  <p><?php echo $user_info->description; ?></p>

</div>