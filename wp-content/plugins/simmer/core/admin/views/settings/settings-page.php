<?php
/**
 * The settings page markup.
 *
 * @since 1.0.0
 *
 * @package Simmer\Settings
 */
?>

<div class="wrap">

	<?php
	/**
	 * Allow others to add to the top of the settings page.
	 *
	 * @since 1.0.0
	 */
	do_action( 'simmer_before_settings_page' ); ?>

	<h2><?php esc_html_e( 'Simmer Settings', 'simmer' ) ?></h2>

	<h2 class="simmer-nav-tab-wrapper nav-tab-wrapper">

		<?php foreach( $tabs as $name => $label ) : ?>

			<a href="<?php echo esc_url( admin_url( 'options-general.php?page=simmer-settings&tab=' . $name ) ); ?>" class="nav-tab <?php echo ( $current_tab == $name ? 'nav-tab-active' : '' ); ?>">
				<?php esc_html_e( $label ); ?>
			</a>

		<?php endforeach; ?>

	</h2>

	<form method="post" action="options.php">

		<?php settings_fields( 'simmer_' . $current_tab ); ?>

		<?php do_settings_sections( 'simmer_' . $current_tab ); ?>

		<?php submit_button( __( 'Save Changes', 'simmer' ) ); ?>

	</form>

	<?php
	/**
	 * Allow others to add to the bottom of the settings page.
	 *
	 * @since 1.0.0
	 */
	do_action( 'simmer_after_settings_page' ); ?>

</div>
