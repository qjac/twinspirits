<?php
/**
 * The Recipe Base settings field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<code><?php echo esc_url( trailingslashit( home_url( '/' ) . get_option( 'simmer_archive_base', simmer_get_archive_base() ) ) ); ?></code>
<input id="simmer_recipe_base" name="simmer_recipe_base" type="text" class="regular-text code" value="<?php echo sanitize_title( get_option( 'simmer_recipe_base', simmer_get_object_type() ) ); ?>" />
