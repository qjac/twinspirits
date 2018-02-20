<?php
/**
 * Template for the advanced settings sub page.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/admin/settings
 */

?>

<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="wprm_settings_advanced">
	<?php wp_nonce_field( 'wprm_settings', 'wprm_settings', false ); ?>
	<h2 class="title"><?php esc_html_e( 'Metadata', 'wp-recipe-maker' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Opt out of Rich Pins', 'wp-recipe-maker' ); ?>
				</th>
				<td>
					<label for="metadata_pinterest_optout">
						<?php $checked = WPRM_Settings::get( 'metadata_pinterest_optout' ) ? ' checked="checked"' : ''; ?>
						<input name="metadata_pinterest_optout" type="checkbox" id="metadata_pinterest_optout"<?php echo esc_html( $checked ); ?> />
						<?php esc_html_e( 'Tell Pinterest NOT to display my pins as rich pins', 'wp-recipe-maker' ); ?>
					</label>
				</td>
			</tr>
		</tbody>
	</table>
	<h2 class="title"><?php esc_html_e( 'Custom Styling', 'wp-recipe-maker' ); ?></h2>
	<p>
		<?php esc_html_e( 'Add your own CSS for styling the plugin.', 'wp-recipe-maker' ); ?>
	</p>
	<table class="form-table">
		<tbody>
<?php if ( WPRM_Settings::get( 'features_custom_style' ) ) : ?>
			<tr>
				<th scope="row">
					<label for="recipe_css"><?php esc_html_e( 'Recipe CSS', 'wp-recipe-maker' ); ?></label>
				</th>
				<td>
					<textarea name="recipe_css" rows="8" cols="50" id="recipe_css" class="large-text code"><?php echo esc_html( WPRM_Settings::get( 'recipe_css' ) ); ?></textarea>
				</td>
			</tr>
<?php endif; ?>
			<tr>
				<th scope="row">
					<label for="print_css"><?php esc_html_e( 'Recipe Print CSS', 'wp-recipe-maker' ); ?></label>
				</th>
				<td>
					<textarea name="print_css" rows="8" cols="50" id="print_css" class="large-text code"><?php echo esc_html( WPRM_Settings::get( 'print_css' ) ); ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	<h2 class="title"><?php esc_html_e( 'Tools', 'wp-recipe-maker' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Find Parent Posts', 'wp-recipe-maker' ); ?>
				</th>
				<td>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wprm_finding_parents' ) ); ?>" class="button" id="tools_finding_parents"><?php esc_html_e( 'Find Parent Posts', 'wp-recipe-maker' ); ?></a>
					<p class="description" id="tagline-tools_finding_parents">
						<?php esc_html_e( 'Go through all posts and pages on your website to find and link recipes to their parent.', 'wp-recipe-maker-premium' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Reset to defaults', 'wp-recipe-maker' ); ?>
				</th>
				<td>
					<label for="reset_settings_to_default">
						<input name="reset_settings_to_default" type="checkbox" id="reset_settings_to_default" />
						<?php esc_html_e( 'Reset settings to default upon save', 'wp-recipe-maker' ); ?>
					</label>
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button( __( 'Save Changes', 'wp-recipe-maker' ) ); ?>
</form>
