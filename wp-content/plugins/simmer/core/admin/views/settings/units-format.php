<?php
/**
 * The Ingredients List Heading setting field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<?php $format = get_option( 'simmer_units_format', 'abbr' ); ?>

<select id="simmer_units_format" name="simmer_units_format">
	<option value="abbr" <?php selected( 'abbr', $format ); ?>><?php _e( 'abbreviations (i.e. "lbs.")', 'simmer' ); ?></option>
	<option value="full" <?php selected( 'full', $format ); ?>><?php _e( 'full names (i.e. "pounds")', 'simmer' ); ?></option>
</select>
