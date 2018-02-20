<?php
/**
 * Define the installer class
 *
 * @since 1.3.0
 *
 * @package Simmer/Install
 */

/**
 * A class to handle the installing and uninstalling of Simmer.
 *
 * @since 1.3.0
 */
final class Simmer_Installer {

	/**
	 * Install Simmer in all its glory.
	 *
	 * @since 1.3.0
	 */
	public static function install() {

		// Shout it from the rooftops: "Simmer is installing!"
		if ( ! defined( 'SIMMER_INSTALLING' ) ) {
			define( 'SIMMER_INSTALLING', true );
		}

		// Create the custom database tables.
		self::create_db_tables();

		$current_version = get_option( 'simmer_version', '1.0.0' );

		// Upgrade older versions of Simmer.
		if ( version_compare( $current_version, Simmer()->version, '<' ) ) {
			self::upgrade( $current_version );
		}

		// Reset the version number.
		delete_option( 'simmer_version' );
		add_option( 'simmer_version', Simmer()->version, '', 'no' );

		self::register_types();
		flush_rewrite_rules();

		/**
		 * Fires after Simmer has been installed.
		 *
		 * @since 0.1.0
		 */
		do_action( 'simmer_installed' );
	}

	/**
	 * Run plugin deactivation procedures.
	 *
	 * @since  1.3.9
	 * @access public
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Uninstall Simmer. Sad face.
	 *
	 * @since 1.3.0
	 */
	public static function uninstall() {

		global $wpdb;

		$on_uninstall = get_option( 'simmer_on_uninstall', 'delete_settings' );

		// Check that the user wants everything deleted with the plugin.
		if ( 'keep_all' == $on_uninstall ) {
			return;
		}

		if ( 'delete_settings' == $on_uninstall || 'delete_all' == $on_uninstall ) {

			// Delete options
			$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'simmer_%';" );

		}

		if ( 'delete_all' == $on_uninstall ) {

			// Delete all recipes.
			$recipe_ids = get_posts( array(
				'post_type'   => 'recipe',
				'post_status' => 'any',
				'numberposts' => -1,
				'fields'      => 'ids',
			) );

			if ( $recipe_ids ) {

				foreach ( $recipe_ids as $recipe_id ) {
					wp_delete_post( $recipe_id, true );
				}

			}

			// Delete all categories.
			$category_ids = get_terms( 'recipe_category', array(
				'hide_empty' => false,
				'fields'     => 'ids',
			) );

			if ( ! is_wp_error( $category_ids ) && ! empty( $category_ids ) ) {

				foreach ( $category_ids as $category_id ) {
					wp_delete_term( $category_id, 'recipe_category' );
				}

			}

			// Remove the custom DB tables.
			$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "simmer_recipe_items" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "simmer_recipe_itemmeta" );
		}
	}

	/**
	 * Upgrade legacy versions of Simmer.
	 *
	 * @since  1.3.0
	 * @access private
	 *
	 * @param string $current_version The current Simmer version.
	 */
	private static function upgrade( $current_version ) {

		global $wpdb;

		require( plugin_dir_path( __FILE__ ) . 'class-simmer-upgrade.php' );

		$upgrader = new Simmer_Upgrade;

		$db_version = get_option( 'simmer_db_version', '1.0.0' );

		// Upgrade Simmer from 1.2.X to 1.3.0.
		if ( version_compare( $db_version, '1.1.0', '<' ) ) {

			if ( ! $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "simmer_recipe_items" ) ) {
				$upgrader->from_1_2();
			}

			// Reset the db version number.
			delete_option( 'simmer_db_version' );
			add_option( 'simmer_db_version', '1.1.0', '', 'no' );
		}
	}

	/**
	 * Create the custom database tables.
	 *
	 * @since  1.3.0
	 * @access private
	 *
	 * @global $wpdb The WordPress database object.
	 */
	private static function create_db_tables() {

		global $wpdb;

		$items_table_name     = $wpdb->prefix . 'simmer_recipe_items';
		$item_meta_table_name = $wpdb->prefix . 'simmer_recipe_itemmeta';

		$query = '';

		$charset_collate = $wpdb->get_charset_collate();

		if ( $items_table_name != $wpdb->get_var( "SHOW TABLES LIKE '$items_table_name'" ) ) {

			// The recipe items table.
			$query .= "CREATE TABLE $items_table_name (
				recipe_item_id bigint(20) NOT NULL auto_increment,
				recipe_item_type varchar(200) NOT NULL DEFAULT '',
				recipe_id bigint(20) NOT NULL,
				recipe_item_order int(11) NOT NULL DEFAULT '0',
				PRIMARY KEY  (recipe_item_id),
				KEY recipe_id (recipe_id)
				) $charset_collate;";
		}

		if ( $item_meta_table_name != $wpdb->get_var( "SHOW TABLES LIKE '$item_meta_table_name'" ) ) {

			// The recipe item meta table.
			$query .= "CREATE TABLE $item_meta_table_name (
				meta_id bigint(20) NOT NULL auto_increment,
				recipe_item_id bigint(20) NOT NULL,
				meta_key varchar(255) NULL,
				meta_value longtext NULL,
				PRIMARY KEY  (meta_id),
				KEY recipe_item_id (recipe_item_id),
				KEY meta_key (meta_key)
				) $charset_collate;";
		}

		if ( '' != $query ) {

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			dbDelta( $query );
		}
	}

	/**
	 * Register the recipe CPT and category taxonomy.
	 *
	 * @since  1.3.9
	 * @access protected
	 * @return void
	 */
	protected static function register_types() {
		Simmer()->register_object_type();
		Simmer()->register_category_taxonomy();
	}
}
