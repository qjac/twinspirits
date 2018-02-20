<?php
/**
 * The Instructions List Heading setting field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<input id="simmer_instructions_list_heading" class="regular-text code" name="simmer_instructions_list_heading" type="text" value="<?php echo esc_html( get_option( 'simmer_instructions_list_heading', 'Instructions' ) ); ?>" />
<span class="description"><?php _e( 'The heading to be displayed before the list of instructions.', 'simmer' ); ?></span>
