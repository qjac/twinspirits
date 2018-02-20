<?php
/**
 * Define the class that enables the [recipe] shortcode
 *
 * @since 1.0.0
 *
 * @package Simmer/Recipes/Shortcode
 */

/**
 * Set up the [recipe] shortcode.
 *
 * @since 1.0.0
 */
final class Simmer_Recipe_Shortcode {

	/**
	 * The shortcode's name/slug.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @var string $shortcode_slug The shortcode's name/slug.
	 */
	private $shortcode_slug;

	/**
	 * Build the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->shortcode_slug = 'recipe';
	}

	/**
	 * Add the hooks that do all of the work.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		add_action( 'init', array( $this, 'add_shortcode' ), 10 );
	}

	/**
	 * Add the shortcode and display when called.
	 *
	 * @since 1.0.0
	 */
	public function add_shortcode() {

		add_shortcode( $this->shortcode_slug, array( $this, 'display_shortcode' ) );
	}

	/**
	 * Build the [recipe] shortcode.
	 *
	 * This shortcode allows users to embed recipes
	 * in their posts & pages.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The available args.
	 *        'id' => A recipe ID.
	 * @return object The recipe HTML.
	 */
	public function display_shortcode( $args ) {

		global $post;

		// Set the default args.
		$defaults = array(
			'id' => 0,
		);

		// Parse the user args, if any.
		$args = shortcode_atts( $defaults, $args );

		// Setup the $post object for setup_postdata().
		$post = get_post( $args['id'] );

		// If no recipe exists with the passed ID, bail.
		if ( is_null( $post ) ) {
			return;
		}

		ob_start();

		setup_postdata( $post );

		/**
		 * Allow others to execute code before including the template file.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_recipe_shortcode', $post );

		// Include the template file.
		simmer_get_template_part( 'recipe', 'shortcode' );

		/**
		 * Allow others to execute code after including the template file.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_recipe_shortcode', $post );

		// Reset the $post global.
		wp_reset_postdata();

		return ob_get_clean();
	}
}

// Get things running!
$recipe_shortcode = new Simmer_Recipe_Shortcode();
$recipe_shortcode->init();
