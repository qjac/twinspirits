<?php
/**
 * Define the admin shortcode functionality
 *
 * @since 1.2.0
 *
 * @package Simmer\Admin
 */

class Simmer_Admin_Shortcode_UI {

	/**
	 * Post types that allow the shortcode UI.
	 *
	 * @since 1.2.0
	 *
	 * @var array $supported_post_types
	 */
	public $supported_post_types;

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

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Shortcode_UI class can not be cloned', 'simmer' ), Simmer()->version );
	}

	/**
	 * Prevent the class from being unserialized.
	 *
	 * @since 1.3.3
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Shortcode_UI class can not be unserialized', 'simmer' ), Simmer()->version );
	}

	/**
	 * Construct the class.
	 *
	 * @since 1.2.0
	 */
	public function __construct() {

		// Set the default supported post types.
		$this->supported_post_types = array(
			'post',
			'page',
		);
	}

	/**
	 * Enqueue the modal script.
	 *
	 * @since 1.2.0
	 *
	 * @param string $hook The current admin screen.
	 */
	public function enqueue_script( $hook ) {

		if ( ( 'post.php' == $hook || 'post-new.php' == $hook ) && ! in_array( get_post_type(), $this->get_supported_post_types() ) ) {
			return;
		}

		wp_register_script( 'select2-script', plugin_dir_url( __FILE__ ) . 'assets/plugins/select2.min.js', array(
			'jquery',
		), '4.0.0', true );

		wp_enqueue_script( 'simmer-admin-shortcode-script', plugin_dir_url( __FILE__ ) . 'assets/shortcode.js', array(
			'jquery',
			'select2-script',
		), Simmer()->version, true );

		wp_enqueue_style( 'select2-style', plugin_dir_url( __FILE__ ) . 'assets/plugins/select2.min.css', array(), '4.0.0' );
	}

	/**
	 * Add the 'Add Recipe' button above the main content editor.
	 *
	 * @since 1.2.0
	 *
	 * @param string $editor_id The TinyMCE editor ID.
	 */
	public function add_media_button( $editor_id = 'content' ) {

		if ( ! in_array( get_post_type(), $this->get_supported_post_types() ) ) {
			return;
		}

		printf( '<a href="#" id="simmer-add-recipe" class="simmer-icon-fork button" data-editor="%s" title="%s">%s</a>',
			esc_attr( $editor_id ),
			esc_attr__( 'Add Recipe', 'simmer' ),
			esc_html__( 'Add Recipe', 'simmer' )
		);
	}

	/**
	 * Add the modal markup to the admin footer.
	 *
	 * @since 1.2.0
	 */
	public function add_modal() {

		$current_screen = get_current_screen();

		// Only load the modal when editing or creating a new recipe.
		if ( 'post' == $current_screen->base && in_array( $current_screen->post_type, $this->get_supported_post_types() ) ) {

			include_once( plugin_dir_path( __FILE__ ) . 'views/shortcode-modal.php' );
		}
	}

	/**
	 * Get the post types supported by the shortcode UI.
	 *
	 * @since  1.2.0
	 * @access private
	 *
	 * @return array $supported_post_types The filtered post types array.
	 */
	private function get_supported_post_types() {

		/**
		 * Filter the list of supported post types.
		 *
		 * @since 1.2.0
		 */
		$supported_post_types = apply_filters( 'simmer_admin_shortcode_ui_post_types', $this->supported_post_types );

		return (array) $supported_post_types;
	}
}
