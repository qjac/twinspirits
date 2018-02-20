<?php
/**
 * Define the main class
 *
 * @since 1.0.0
 *
 * @package Simmer
 */

// If this file is called directly, get outa' town.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The main class.
 *
 * @since 1.0.0
 */
final class Simmer {

	/**
	 * The plugin version.
	 *
	 * @since 1.0.0
	 *
	 * @var string VERSION The plugin version.
	 */
	const VERSION = '1.3.9';

	/**
	 * The plugin slug.
	 *
	 * @since 1.0.0
	 *
	 * @var string SLUG The plugin slug.
	 */
	const SLUG = 'simmer';

	/**
	 * The plugin version.
	 *
	 * @since 1.3.0
	 *
	 * @var string $version
	 */
	public $version = self::VERSION;

	/**
	 * The plugin domain.
	 *
	 * @since 1.3.0
	 *
	 * @var string $domain
	 */
	public $domain = self::SLUG;

	/**
	 * The admin class.
	 *
	 * @since 1.3.0
	 *
	 * @var object $admin;
	 */
	public $admin;

	/**
	 * The front-end class.
	 *
	 * @since 1.3.0
	 *
	 * @var object $frontend;
	 */
	public $frontend;

	/** Singleton **/

	/**
	 * The only instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 *
	 * @var object $_instance The only instance of this class.
	 */
	protected static $_instance = null;

	/**
	 * Get the only instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return object $_instance The only instance of this class.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Construct the class.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Load the necessary supporting files.
		$this->require_files();

		if ( ! is_admin() ) {

			// Load the front-end.
			$this->frontend = new Simmer_Frontend;
		}

		// Add the essential action hooks.
		$this->add_actions();

		/**
		 * Fire after Simmer has been loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_loaded' );
	}

	/**
	 * Prevent this class from being cloned.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'simmer' ), $this->version );
	}

	/**
	 * Prevent this class from being unserialized.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'simmer' ), $this->version );
	}

	/** Private Methods **/

	/**
	 * Load the necessary supporting files.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function require_files() {

		/**
		 * The installer class.
		 */
		require( plugin_dir_path( __FILE__ ) . 'class-simmer-installer.php'  );

		/**
		 * Supporting functions.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'deprecated-functions.php' );

		/**
		 * The general recipes functionality.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/class-simmer-recipe.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/class-simmer-recipe-durations.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/class-simmer-recipe-shortcode.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/class-simmer-plugin-compatibility.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/functions.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/template-functions.php' );

		/**
		 * The recipe items functionality.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/class-simmer-recipe-items.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/class-simmer-recipe-item-meta.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/functions.php' );

		/**
		 * The recipe ingredients functionality.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/ingredients/class-simmer-recipe-ingredients.php'  );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/ingredients/class-simmer-recipe-ingredient.php'  );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/ingredients/functions.php'  );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/ingredients/template-functions.php'  );

		/**
		 * The recipe instructions functionality.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/instructions/class-simmer-recipe-instructions.php'  );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/instructions/class-simmer-recipe-instruction.php'  );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/instructions/functions.php'  );
		require_once( plugin_dir_path( __FILE__ ) . 'recipes/items/instructions/template-functions.php'  );

		/**
		 * The widgets class.
		 */
		require_once( plugin_dir_path( __FILE__ ) . 'widgets/class-simmer-widgets.php' );

		if ( is_admin() ) {

			/**
			 * The admin dashboard class.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/class-simmer-admin-dashboard.php' );

			/**
			 * The recipes admin class.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/class-simmer-admin-recipes.php' );

			/**
			 * The admin bulk-add class.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/class-simmer-admin-bulk-add.php' );

			/**
			 * The shortcode UI class.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/class-simmer-admin-shortcode-ui.php' );

			/**
			 * The admin settings class.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/class-simmer-admin-settings.php' );

			/**
			 * The admin functions.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/functions.php' );

			/**
			 * The admin hooks.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'admin/hooks.php' );

		} else {

			/**
			 * The front-end class.
			 */
			require_once( plugin_dir_path( __FILE__ ) . 'frontend/class-simmer-frontend.php' );
		}
	}

	/**
	 * Add the essential action hooks.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function add_actions() {

		// Perform on plugin activation.
		register_activation_hook( SIMMER_PLUGIN_FILE, 'Simmer_Installer::install' );
		register_deactivation_hook( SIMMER_PLUGIN_FILE, 'Simmer_Installer::deactivate' );

		// Add the custom table names to the database object for later use.
		add_action( 'plugins_loaded', array( 'Simmer_Recipe_Item_Meta', 'add_meta_table_names' ), 0 );
		add_action( 'switch_blog',    array( 'Simmer_Recipe_Item_Meta', 'add_meta_table_names' ), 0 );

		// Load the text domain for i18n.
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// Register the 'recipe' object type.
		add_action( 'init', array( $this, 'register_object_type' ) );

		// Register the category taxonomy.
		add_action( 'init', array( $this, 'register_category_taxonomy' ) );

		// Load the widgets.
		add_action( 'widgets_init', array( 'Simmer_Widgets', 'get_instance' ) );
	}

	/** Public Methods **/

	/**
	 * Load the text domain.
	 *
	 * Based on the bbPress implementation.
	 *
	 * @since 1.0.0
	 *
	 * @return The textdomain or false on failure.
	 */
	public function load_textdomain() {

		$locale = get_locale();
		$locale = apply_filters( 'plugin_locale',  $locale, 'simmer' );

		$mofile        = sprintf( 'simmer' . '-%s.mo', $locale );
		$mofile_local  = plugin_dir_path( dirname( __FILE__ ) ) . 'languages/' . $mofile;
		$mofile_global = WP_LANG_DIR . '/' . 'simmer' . '/' . $mofile;

		if ( file_exists( $mofile_local ) ) {
			return load_textdomain( 'simmer', $mofile_local );
		}

		if ( file_exists( $mofile_global ) ) {
			return load_textdomain( 'simmer', $mofile_global );
		}

		load_plugin_textdomain( 'simmer' );

		return false;
	}

	/**
	 * Register the 'recipe' object type.
	 *
	 * @since 1.0.0
	 */
	public function register_object_type() {

		// The arguments that define the object type's labels and functionality.
		$args = array(
			'labels'  => array(
				'name'               => __( 'Recipes',                   'simmer' ),
				'singular_name'      => __( 'Recipe',                    'simmer' ),
				'all_items'          => __( 'All Recipes',               'simmer' ),
				'add_new_item'       => __( 'Add New Recipe',            'simmer' ),
				'edit_item'          => __( 'Edit Recipe',               'simmer' ),
				'new_item'           => __( 'New Recipe',                'simmer' ),
				'view_item'          => __( 'View Recipe',               'simmer' ),
				'search_items'       => __( 'Search Recipes',            'simmer' ),
				'not_found'          => __( 'No recipes found',          'simmer' ),
				'not_found_in_trash' => __( 'No recipes found in Trash', 'simmer' ),
			),
			'public'  => true,
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'comments',
				'author',
			),
			'taxonomies' => array(
				simmer_get_category_taxonomy(),
			),
			'has_archive' => simmer_get_archive_base(),
			'rewrite' => array(
				'slug' => trailingslashit( simmer_get_archive_base() ) . get_option( 'simmer_recipe_base', simmer_get_object_type() ),
				'with_front' => false,
			),
		);

		/**
		 * Filter the recipe object type arguments.
		 *
		 * @since 1.0.0
		 * @see register_post_type() for the available args.
		 *
		 * @param array $args {
		 * 		The arguments that define the object type's
		 * 		labels and functionality.
		 * }
		 */
		$args = apply_filters( 'simmer_register_recipe_args', $args );

		// Finally register the object type.
		register_post_type( simmer_get_object_type(), $args );
	}

	/**
	 * Register the category taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function register_category_taxonomy() {

		$args = array(
			'show_tagcloud'     => false,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite' => array(
				'slug'       => trailingslashit( simmer_get_archive_base() ) . get_option( 'simmer_category_base', 'category' ),
				'with_front' => false,
			),
		);

		/**
		 * Filter the taxonomy args.
		 *
		 * @since 1.0.0
		 * @see register_taxonomy() for the available args.
		 *
		 * @param array $args {
		 * 		The arguments that define the taxonomy's
		 * 		labels and functionality.
		 * }
		 */
		$args = apply_filters( 'simmer_register_category_args', $args );

		// Finally register the taxonomy.
		register_taxonomy(
			simmer_get_category_taxonomy(),
			simmer_get_object_type(),
			$args
		);

		/*
		 * Better safe than sorry.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy#Usage
		 */
		register_taxonomy_for_object_type(
			simmer_get_category_taxonomy(),
			simmer_get_object_type()
		);
	}
}
