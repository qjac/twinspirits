<?php
/**
 * Define the main front-end class
 *
 * @since 1.3.0
 *
 * @package Simmer/Frontend
 */

/**
 * Set up the front-end.
 *
 * @since 1.3.0
 */
final class Simmer_Frontend {

	/**
	 * The styles class.
	 *
	 * @since 1.3.0
	 *
	 * @var object $styles
	 */
	public $styles;

	/**
	 * The scripts class.
	 *
	 * @since 1.3.0
	 *
	 * @var object $scripts
	 */
	public $scripts;

	/**
	 * The HTML classes class.
	 *
	 * @since 1.3.0
	 *
	 * @var object $html_classes
	 */
	public $html_classes;

	/**
	 * Whether the recipe schema wrapper has been opened.
	 *
	 * @since  1.2.1
	 * @access private
	 *
	 * @var bool $schema_wrap_open
	 */
	private $schema_wrap_open = false;

	/**
	 * Slug for the Simmer custom post type object.
	 *
	 * @since  1.3.9
	 * @access private
	 *
	 * @var string simmer_get_object_type()
	 */
	private $cpt = null;

	/**
	 * Get the front-end running.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		// Load the necessary files.
		$this->load_files();

		/**
		 * Load the styles class.
		 */
		$this->styles = new Simmer_Frontend_Styles;

		/**
		 * Load the scripts class.
		 */
		$this->scripts = new Simmer_Frontend_Scripts;

		/**
		 * Load the HTML classes class.
		 */
		$this->html_classes = new Simmer_Frontend_HTML_Classes;

		// Add the necessary actions.
		$this->add_actions();

		// Add the necessary filters.
		$this->add_filters();

		// Store the simmer object type for reuse.
		$this->cpt = simmer_get_object_type();
	}

	/**
	 * Load the necessary files.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function load_files() {

		/**
		 * The all-important template loader.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'class-simmer-template-loader.php' );

		/**
		 * The HTML classes class.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'class-simmer-frontend-html-classes.php' );

		/**
		 * The CSS styles class.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'class-simmer-frontend-styles.php' );

		/**
		 * The JS scripts class.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'class-simmer-frontend-scripts.php' );

		/**
		 * The supporting functions.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

		/**
		 * The supporting template functions.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'template-functions.php' );
	}

	/**
	 * Add the necessary actions.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function add_actions() {

		// Check if front-end styles should be enqueued.
		if ( $this->styles->enable_styles() ) {
			add_action( 'wp_enqueue_scripts', array( $this->styles, 'enqueue_styles' ) );
			add_action( 'wp_head', array( $this->styles, 'add_custom_styles' ) );
		}

		// Check if front-end scripts should be enqueued.
		if ( $this->scripts->enable_scripts() ) {
			add_action( 'wp_enqueue_scripts', array( $this->scripts, 'enqueue_scripts' ) );
		}

		// Add the opening schema markup before outputting the recipe.
		add_action( 'loop_start', array( $this, 'open_schema_wrap' ) );

		// Add the closing schema markup after outputting the recipe.
		add_action( 'loop_end', array( $this, 'close_schema_wrap' ) );

	}

	/**
	 * Add the necessary filters.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function add_filters() {

		add_filter( 'body_class', array( $this->html_classes, 'add_body_classes' ), 20, 1 );
		add_filter( 'post_class', array( $this->html_classes, 'add_recipe_classes' ), 20, 3 );

		// Wrap the title with the proper schema markup.
		add_filter( 'the_title', array( $this, 'add_title_schema' ), 10, 2 );

		// Add schema.org property to a single recipe's featured image.
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_featured_image_schema' ), 20, 2 );

		// Add the recipe display to the bottom of a singular recipe's content.
		add_filter( 'the_content', array( $this, 'append_recipe' ), 99, 1 );
	}

	/**
	 * Add the opening schema markup before outputting the recipe.
	 *
	 * @since 1.3.0
	 *
	 * @param object $query The currently looped WP_Query.
	 */
	public function open_schema_wrap( $query ) {

		if ( true === $this->schema_wrap_open || ! $query instanceof WP_Query ) {
			return;
		}

		if ( $query->is_singular( $this->cpt ) && $query->is_main_query() ) {

			$this->schema_wrap_open = true;

			echo '<span itemscope itemtype="http://schema.org/Recipe">';
		}
	}

	/**
	 * Add the closing schema markup after outputting the recipe.
	 *
	 * @since 1.3.0
	 *
	 * @param object $query The currently looped WP_Query.
	 */
	public function close_schema_wrap( $query ) {

		if ( false === $this->schema_wrap_open || ! $query instanceof WP_Query ) {
			return;
		}

		if ( $query->is_singular( $this->cpt ) && $query->is_main_query() ) {

			$this->schema_wrap_open = false;

			echo '</span>';
		}
	}

	/**
	 * Wrap the title with the proper schema markup.
	 *
	 * @since 1.3.0
	 *
	 * @param  string $title The recipe's title.
	 * @param  int    $id    The recipe's ID.
	 * @return string $title The recipe's title with schema markup added.
	 */
	public function add_title_schema( $title, $id = 0 ) {

		$wrapped_title = $title;

		if ( $id == get_the_ID() && is_singular( $this->cpt ) && is_main_query() ) {

			$wrapped_title = '<span itemprop="name">';
				$wrapped_title .= $title;
			$wrapped_title .= '</span>';
		}

		return $wrapped_title;
	}

	/**
	 * Add schema.org property to a single recipe's featured image.
	 *
	 * @since 1.2.1
	 *
	 * @param  array  $attributes The existing image attributes.
	 * @param  object $image      The image's post object.
	 * @return array  $attributes The image attributes, possibly with the schema.org property added.
	 */
	public function add_featured_image_schema( $attributes, $image ) {

		if ( $image->ID == get_post_thumbnail_id( get_the_ID() ) && is_singular( $this->cpt ) && is_main_query() ) {

			$attributes['itemprop'] = 'image';
		}

		return $attributes;
	}

	/**
	 * Determine whether it's safe to append a recipe to the content.
	 *
	 * Checks to make sure we're on a single view of Simmer's recipe post type,
	 * we're not currently displaying an excerpt, and the recipe markup hasn't
	 * already been output.
	 *
	 * @since 1.3.9
	 *
	 * @return bool True if it's safe to append recipe markup, false otherwise.
	 */
	protected function can_append_recipe() {
		global $wp_current_filter;

		$filters = (array) $wp_current_filter;

		if ( ! is_singular( $this->cpt ) ) {
			return false;
		}

		if ( get_post_type() !== $this->cpt ) {
			return false;
		}

		if ( in_array( 'get_the_excerpt', $filters, true ) ) {
			return false;
		}

		$already_appended = false;

		foreach ( $filters as $filter ) {
			if ( 'the_content' !== $filter ) {
				continue;
			}

			if ( $already_appended ) {
				return false;
			} else {
				$already_appended = true;
			}
		}

		return $already_appended;
	}

	/**
	 * Add the recipe display to the bottom of a singular recipe's content.
	 *
	 * @since 1.2.0
	 *
	 * @param string $content The TinyMCE content.
	 */
	public function append_recipe( $content ) {
		if ( ! $this->can_append_recipe() ) {
			return $content;
		}

		ob_start();

		echo '<div class="simmer-recipe-description" itemprop="description">';

			echo $content;

		echo '</div><!-- .simmer-recipe-description -->';

		do_action( 'simmer_before_recipe', get_the_ID() );

		simmer_get_template_part( 'recipe' );

		do_action( 'simmer_after_recipe', get_the_ID() );

		return ob_get_clean();
	}
}
