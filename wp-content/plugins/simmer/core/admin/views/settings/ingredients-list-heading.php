<?php
/**
 * The Ingredients List Heading setting field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<input id="simmer_ingredients_list_heading" class="regular-text code" name="simmer_ingredients_list_heading" type="text" value="<?php echo esc_html( get_option( 'simmer_ingredients_list_heading', 'Ingredients' ) ); ?>" />
<span class="description"><?php _e( 'The heading to be displayed before the list of ingredients.', 'simmer' ); ?></span>
