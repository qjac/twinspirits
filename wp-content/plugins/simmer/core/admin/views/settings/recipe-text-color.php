<?php
/**
 * The "recipe text color" settings field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<fieldset>
	<legend class="screen-reader-text">
		<span><?php _e( 'Text Color', 'simmer' ); ?></span>
	</legend>
	<input type="text" name="simmer_recipe_text_color" id="simmer_recipe_text_color" value="#<?php echo esc_attr( get_option( 'simmer_recipe_text_color', '000' ) ); ?>" data-default-color="#000" />
</fieldset>
