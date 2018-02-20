<?php
/**
 * Provide compatibility with other 3rd party plugins.
 *
 * @since 1.3.7
 *
 * @package Simmer/Recipes/PluginCompatibility
 */

final class Simmer_Recipe_Plugin_Compatibility {

	/**
	 * The singleton instance of the class.
	 *
	 * @since  1.3.7
	 * @access private
	 * @var    object $instance.
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance of the class.
	 *
	 * @since 1.3.7
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
	 * Add the hooks that do all of the work.
	 *
	 * @since  1.3.7
	 * @return void
	 */
	public function init() {

		add_filter( 'jetpack_shortcodes_to_include', array( $this, 'disable_jetpack_recipes' ) );
	}

	/**
	 * Rebuild the Jetpack shortcodes includes array without the path to the
	 * recipes shortcode.
	 *
	 * @since  1.3.7
	 * @param  array $shortcode_includes a list of paths to Jetpack shortcode modules
	 * @return array $paths an updated list of paths to Jetpack shortcode modules
	 */
	public function disable_jetpack_recipes( $shortcode_includes ) {

		$paths = array();
		foreach ( $shortcode_includes as $key => $path ) {
			if ( false === stripos( $path, 'recipe' ) ) {
				$paths[] = $path;
			}
		}
		return $paths;
	}

}

// Get things running!
Simmer_Recipe_Plugin_Compatibility::get_instance()->init();
