<?php
/**
 * Define the widgets class
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */

// Die if this file is called directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Simmer_Widgets {

	/**
	 * The only instance of this class.
	 *
	 * @since  1.1.0
	 * @access protected
	 * @var    object The only instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Get the main instance.
	 *
	 * @since 1.1.0
	 *
	 * @return The only instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->include_files();
			self::$instance->register_widgets();
		}

		return self::$instance;
	}

	/**
	 * Include the necessary widget class definitions.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function include_files() {

		/**
		 * Include the Recipe Categories widget class.
		 */
		include plugin_dir_path( __FILE__ ) . 'class-simmer-categories-widget.php';

		/**
		 * Include the Recipe Recipes widget class.
		 */
		include plugin_dir_path( __FILE__ ) . 'class-simmer-recent-recipes-widget.php';
	}

	/**
	 * Register the widgets.
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function register_widgets() {

		// Register the Recipe Categories widget.
		register_widget( 'Simmer_Categories_Widget' );

		// Register the Recent Recipes widget.
		register_widget( 'Simmer_Recent_Recipes_Widget' );

		/**
		 * Execute after Simmer's default widgets are registered.
		 *
		 * @since 1.1.0
		 */
		do_action( 'simmer_widgets_init' );
	}
}
