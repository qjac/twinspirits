<?php
/**
 * The "enqueue styles" settings field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<fieldset>
	<legend class="screen-reader-text">
		<span><?php _e( 'Default Styles', 'simmer' ); ?></span>
	</legend>
	<label for="simmer_enqueue_styles">
		<input id="simmer_enqueue_styles" name="simmer_enqueue_styles" type="checkbox" value="1" <?php checked( 1, get_option( 'simmer_enqueue_styles', 1 ) ); ?> />
		<?php _e( 'Enable built-in front end styles', 'simmer' ); ?>
	</label>
</fieldset>
