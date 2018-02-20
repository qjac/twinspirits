<?php
/**
 * The Ingredients List Type setting field.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<?php $on_uninstall = get_option( 'simmer_on_uninstall', 'delete_settings' ); ?>

<fieldset>
	<label for="simmer_on_uninstall_delete_settings" title="<?php _e( 'Delete settings data', 'simmer' ); ?>">
		<input id="simmer_on_uninstall_delete_settings" name="simmer_on_uninstall" type="radio" value="delete_settings" <?php checked( 'delete_settings', $on_uninstall ); ?> />
		<span><?php _e( 'Delete settings data but keep saved recipes (recommended).', 'simmer' ); ?></span>
	</label><br>
	<label for="simmer_on_uninstall_keep_all" title="<?php _e( 'Keep all data', 'simmer' ); ?>">
		<input id="simmer_on_uninstall_keep_all" name="simmer_on_uninstall" type="radio" value="keep_all" <?php checked( 'keep_all', $on_uninstall ); ?> />
		<span><?php _e( 'Keep all data.', 'simmer' ); ?></span>
	</label><br>
	<label for="simmer_on_uninstall_delete_all" title="<?php _e( 'Delete all data', 'simmer' ); ?>">
		<input id="simmer_on_uninstall_delete_all" name="simmer_on_uninstall" type="radio" value="delete_all" <?php checked( 'delete_all', $on_uninstall ); ?> />
		<span><?php _e( 'Delete all data.', 'simmer' ); ?></span>
	</label>
</fieldset>
