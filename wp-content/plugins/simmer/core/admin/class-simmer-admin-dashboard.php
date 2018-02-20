<?php
/**
 * Define the admin dashboard customizations
 *
 * @since 1.3.3
 *
 * @package Simmer/Admin
 */

/**
 * Customize the general WordPress admin dashboard.
 *
 * This class makes basic, general customizations to the WordPress
 * admin. The methods in this class generally apply to all admin screens,
 * regardless of the component being called.
 *
 * @since 1.3.3
 */
final class Simmer_Admin_Dashboard {

	/** Singleton **/

	/**
	 * The singleton instance of the class.
	 *
	 * @since  1.3.3
	 * @access private
	 * @var    object $instance.
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance of the class.
	 *
	 * @since 1.3.3
	 *
	 * @return object self::$instance The single instance of the class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent the class from being cloned.
	 *
	 * @since 1.3.3
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Dashboard class can not be cloned', 'simmer' ), Simmer()->version );
	}

	/**
	 * Prevent the class from being unserialized.
	 *
	 * @since 1.3.3
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Dashboard class can not be unserialized', 'simmer' ), Simmer()->version );
	}

	/**
	 * Enqueue the custom scripts & styles.
	 *
	 * @since 1.3.3
	 */
	public function enqueue_scripts( $hook ) {

		wp_enqueue_style( 'simmer-admin-styles', plugin_dir_url( __FILE__ ) . 'assets/admin.css', array( 'dashicons' ), Simmer()->version );

		// Only enqueue the script when dealing with our main object type (recipe).
		if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && get_post_type() == simmer_get_object_type() ) {

			wp_enqueue_script( 'simmer-admin-scripts', plugin_dir_url( __FILE__ ) . 'assets/admin.js', array( 'jquery' ), Simmer()->version, true );

			wp_localize_script( 'simmer-admin-scripts', 'simmer_vars', array(
				'remove_ingredient_min'  => __( 'You must have at least one ingredient!',     'simmer' ),
				'remove_instruction_min' => __( 'You must have at least one instruction!',    'simmer' ),
				'remove_ays'             => __( 'Are you sure you want to remove this item?', 'simmer' ),
			) );

			wp_enqueue_script( 'simmer-admin-bulk-script', plugin_dir_url( __FILE__ ) . 'assets/bulk-add.js', array( 'jquery' ), Simmer()->version, true );

			wp_localize_script( 'simmer-admin-bulk-script', 'simmer_bulk_add_vars', array(

				// Ingredients text.
				'ingredients_title'       => __( 'Add Bulk Ingredients', 'simmer' ),
				'ingredients_help'        => __( 'Type or paste the list of ingredients below, one ingredient per line.', 'simmer' ),
				'ingredients_placeholder' => __( 'e.g. 1 cup flour, sifted', 'simmer' ),
				'ingredients_button'      => __( 'Add Ingredients',      'simmer' ),

				// Instructions text.
				'instructions_title'       => __( 'Add Bulk Instructions', 'simmer' ),
				'instructions_help'        => __( 'Type or paste the list of instructions below, one instruction per line.', 'simmer' ),
				'instructions_placeholder' => __( 'e.g. Preheat your oven to 450 degrees.', 'simmer' ),
				'instructions_button'      => __( 'Add Instructions',      'simmer' ),

				// Misc. text.
				'error_message'       => __( 'Something went wrong. Please try again.', 'simmer' ),
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
			) );
		}
	}

	/**
	 * Add the published recipe count to the "At a Glance" dashboard widget.
	 *
	 * @since 1.3.3
	 *
	 * @param  array $elements The current at-a-glance items.
	 * @return array $elements The new at-a-glance items.
	 */
	public function add_glance_recipe_count( $elements ) {

		$post_type = simmer_get_object_type();

		// Get the number of recipes.
		$num_posts = wp_count_posts( $post_type );

		if ( $num_posts && $num_posts->publish ) {

			$text = _n( '%s Recipe', '%s Recipes', $num_posts->publish, 'simmer' );

			$text = sprintf( $text, number_format_i18n( $num_posts->publish ) );

			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object && current_user_can( $post_type_object->cap->edit_posts ) ) {
				$text = sprintf( '<a class="simmer-recipe-count" href="edit.php?post_type=%1$s">%2$s</a>', $post_type, $text );
			} else {
				$text = sprintf( '<span class="simmer-recipe-count">%2$s</span>', $post_type, $text );
			}

			$elements[] = $text;
		}

		return $elements;
	}

	/**
	 * Add a "Settings" link to the list of plugin row actions.
	 *
	 * @since 1.3.3
	 *
	 * @param  array $actions The default plugin row actions.
	 * @return array $actions The new plugin row actions.
	 */
	public function add_settings_link( $actions ) {

		$new_action = sprintf(
			'<a href="%s">%s</a>',
			esc_url( get_admin_url( null, 'options-general.php?page=simmer-settings' ) ),
			__( 'Settings', 'simmer' )
		);

		// Add the new action to the front of the array.
		array_unshift( $actions, $new_action );

		return $actions;
	}

	/**
	 * Add a Simmer thanks link to the admin footer.
	 *
	 * @since 1.3.3
	 *
	 * @param  string $text The default admin footer text.
	 * @return string $text The new admin footer text with the Simmer link appended.
	 */
	public function add_footer_text( $text ) {

		$text = '<span id="footer-thankyou">';

			$text .= sprintf(
				__( 'Thank you for creating with %sWordPress%s and cooking with %sSimmer%s.', 'simmer' ),
				'<a href="http://wordpress.org/">',
				'</a>',
				'<a href="https://simmerwp.com/">',
				'</a>'
			);

		$text .= '</span>';

		return $text;
	}
}
