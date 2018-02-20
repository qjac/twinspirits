<?php
/**
 * Define the settings admin class
 *
 * @since 1.0.0
 *
 * @package Simmer/Admin/Settings
 */

/**
 * Set up the setting admin.
 *
 * @since 1.0.0
 */
final class Simmer_Admin_Settings {

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

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Settings class can not be cloned', 'simmer' ), Simmer()->version );
	}

	/**
	 * Prevent the class from being unserialized.
	 *
	 * @since 1.3.3
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Settings class can not be unserialized', 'simmer' ), Simmer()->version );
	}

	/**
	 * Add the Recipes settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_options_page() {

		add_submenu_page(
			'edit.php?post_type=recipe',
			__( 'Extend', 'simmer' ),
			__( 'Extend', 'simmer' ),
			'manage_options',
			'simmer-extend',
			array( $this, 'extend_page_callback' )
		);

		add_options_page(
			__( 'Simmer Settings', 'simmer' ),
			__( 'Simmer', 'simmer' ),
			'manage_options',
			'simmer-settings',
			array( $this, 'settings_page_callback' )
		);
	}

	/**
	 * Register the settings.
	 *
	 * @since 1.0.0
	 *
	 * @global array $simmer_extensions A list of registered Simmer extensions.
	 */
	public function register_settings() {

		/* Display */

		// Add the ingredients display settings section.
		add_settings_section(
			'simmer_display_ingredients',
			__( 'Ingredients', 'simmer' ),
			'__return_false',
			'simmer_display'
		);

		// Register the ingredients display settings.
		register_setting( 'simmer_display', 'simmer_ingredients_list_heading', 'esc_html' );
		register_setting( 'simmer_display', 'simmer_ingredients_list_type',    'esc_attr' );
		register_setting( 'simmer_display', 'simmer_units_format',             'esc_attr' );

		// Add the ingredients display settings fields.
		add_settings_field(
			'simmer_ingredients_list_heading',
			__( 'List Heading', 'simmer' ),
			array( $this, 'ingredients_list_heading_callback' ),
			'simmer_display',
			'simmer_display_ingredients'
		);
		add_settings_field(
			'simmer_ingredients_list_type',
			__( 'List Type', 'simmer' ),
			array( $this, 'ingredients_list_type_callback' ),
			'simmer_display',
			'simmer_display_ingredients'
		);
		add_settings_field(
			'simmer_units_format',
			__( 'Show Units As', 'simmer' ),
			array( $this, 'units_format_callback' ),
			'simmer_display',
			'simmer_display_ingredients'
		);

		// Add the instructions display settings section.
		add_settings_section(
			'simmer_display_instructions',
			__( 'Instructions', 'simmer' ),
			'__return_false',
			'simmer_display'
		);

		// Register the instructions display settings.
		register_setting( 'simmer_display', 'simmer_instructions_list_heading', 'esc_html' );
		register_setting( 'simmer_display', 'simmer_instructions_list_type',    'esc_attr' );

		// Add the instructions display settings fields.
		add_settings_field(
			'simmer_instructions_list_heading',
			__( 'List Heading', 'simmer' ),
			array( $this, 'instructions_list_heading_callback' ),
			'simmer_display',
			'simmer_display_instructions'
		);
		add_settings_field(
			'simmer_instructions_list_type',
			__( 'List Type', 'simmer' ),
			array( $this, 'instructions_list_type_callback' ),
			'simmer_display',
			'simmer_display_instructions'
		);

	 	// Add the styles display settings section.
		add_settings_section(
			'simmer_display_styles',
			__( 'Styles', 'simmer' ),
			'__return_false',
			'simmer_display'
		);

		// Register the general display settings.
		register_setting( 'simmer_display', 'simmer_enqueue_styles', 'absint' );
		register_setting( 'simmer_display', 'simmer_recipe_accent_color', array( $this, 'validate_hex_color' ) );
		register_setting( 'simmer_display', 'simmer_recipe_text_color', array( $this, 'validate_hex_color' ) );

		// Add the general display settings fields.
		add_settings_field(
			'simmer_enqueue_styles',
			__( 'Enable Styles', 'simmer' ),
			array( $this, 'enqueue_styles_callback' ),
			'simmer_display',
			'simmer_display_styles'
		);
		add_settings_field(
			'simmer_recipe_accent_color',
			__( 'Accent Color', 'simmer' ),
			array( $this, 'recipe_accent_color_callback' ),
			'simmer_display',
			'simmer_display_styles'
		);
		add_settings_field(
			'simmer_recipe_text_color',
			__( 'Text Color', 'simmer' ),
			array( $this, 'recipe_text_color_callback' ),
			'simmer_display',
			'simmer_display_styles'
		);

		/** Licenses **/

		global $simmer_extensions;

		if ( ! empty( $simmer_extensions ) ) {

			foreach ( $simmer_extensions as $simmer_extension ) {

				if ( isset( $simmer_extension['license'] ) && true === $simmer_extension['license'] ) {

					// Add the extensions license settings section.
					add_settings_section(
						'simmer_license_extensions',
						__( 'Extension Licenses', 'simmer' ),
						array( $this, 'license_extensions_section_callback' ),
						'simmer_license'
					);

					break;
				}
			}
		}

		/** Advanced **/

		/** Permalinks **/

	 	// Add the "Recipes" section to the WordPress permalink options page.
		add_settings_section(
			'simmer_permalinks',
			__( 'Permalinks', 'simmer' ),
			'__return_false',
			'simmer_advanced'
		);

	 	// Register the permalink settings.
	 	register_setting( 'simmer_advanced', 'simmer_archive_base',  'esc_attr' );
	 	register_setting( 'simmer_advanced', 'simmer_recipe_base',   'esc_attr' );
	 	register_setting( 'simmer_advanced', 'simmer_category_base', 'esc_attr' );

	 	// Define the fields.
	 	add_settings_field(
	 		'simmer_archive_base',
	 		__( 'Archive base', 'simmer' ),
	 		array( $this, 'archive_base_callback' ),
	 		'simmer_advanced',
	 		'simmer_permalinks'
	 	);
		add_settings_field(
			'simmer_recipe_base',
			__( 'Single recipe base', 'simmer' ),
			array( $this, 'recipe_base_callback' ),
			'simmer_advanced',
			'simmer_permalinks'
		);
		add_settings_field(
			'simmer_category_base',
			__( 'Category base', 'simmer' ),
			array( $this, 'category_base_callback' ),
			'simmer_advanced',
			'simmer_permalinks'
		);

		// Add the uninstall settings section.
		add_settings_section(
			'simmer_advanced_uninstall',
			__( 'Uninstall Settings', 'simmer' ),
			'__return_false',
			'simmer_advanced'
		);

		register_setting( 'simmer_advanced', 'simmer_on_uninstall', 'esc_attr' );

		// Add the on uninstall settings field.
		add_settings_field(
			'simmer_on_uninstall',
			__( 'On Uninstall', 'simmer' ),
			array( $this, 'on_uninstall_callback' ),
			'simmer_advanced',
			'simmer_advanced_uninstall'
		);

		/**
		 * Allow others to register additional Simmer settings.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_register_settings' );

	}

	/**
	 * Enqueue the admin styles.
	 *
	 * @since x.x.x
	 */
	public function enqueue_styles() {

		wp_enqueue_style(  'wp-color-picker' );
	}

	/**
	 * Enqueue the admin scripts.
	 *
	 * @since x.x.x
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'simmer-plugin-settings-scripts', plugin_dir_url( __FILE__ ) . 'assets/settings.js', array(
			'jquery',
			'wp-color-picker'
		), Simmer()->version );
	}

	/**
	 * Display the extend page markup.
	 *
	 * @since 1.0.0
	 */
	public function extend_page_callback() {

		/**
		 * Include the markup.
		 */
		include_once( 'views/extend-page.php' );
	}

	/**
	 * Display the settings page markup.
	 *
	 * @since 1.0.0
	 *
	 * @global array $simmer_extensions A list of registered Simmer extensions.
	 */
	public function settings_page_callback() {

		global $simmer_extensions;

		// Define the settings tabs.
		$tabs = array(
			'display'  => __( 'Display',  'simmer' ),
		);

		/**
		 * Filter the settings page tabs.
		 *
		 * @since 1.0.0
		 *
		 * @param array $tabs The default settings page tabs.
		 */
		$tabs = apply_filters( 'simmer_settings_tabs', $tabs );

		// Append the "Advanced" tab to the end.
		$tabs['advanced'] = __( 'Advanced',  'simmer' );

		// If any licensed extensions are registered, display the "Licenses" tab.
		if ( ! empty( $simmer_extensions ) ) {

			foreach ( $simmer_extensions as $simmer_extension ) {

				if ( isset( $simmer_extension['license'] ) && true === $simmer_extension['license'] ) {
					$tabs['license'] = __( 'Licenses',  'simmer' );
					break;
				}
			}
		}

		// Get current tab.
		$current_tab = ( empty( $_GET['tab'] ) || ! array_key_exists( $_GET['tab'], $tabs ) ) ? 'display' : sanitize_title( $_GET['tab'] );

		// Flush the rewrite rules.
		if ( 'advanced' == $current_tab ) {
			flush_rewrite_rules();
		}

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/settings-page.php' );
	}

	/**
	 * Display the Ingredients List Heading setting markup.
	 *
	 * @since 1.0.0
	 */
	public function ingredients_list_heading_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_ingredients_list_heading_setting_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/ingredients-list-heading.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_ingredients_list_heading_setting_field' );
	}

	/**
	 * Display the Ingredients List Type setting markup.
	 *
	 * @since 1.0.0
	 */
	public function ingredients_list_type_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_ingredients_list_type_setting_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/ingredients-list-type.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_ingredients_list_type_setting_field' );
	}

	/**
	 * Display the units format settings field markup.
	 *
	 * @since 1.0.0
	 */
	public function units_format_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_units_format_settings_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/units-format.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_units_format_settings_field' );
	}

	/**
	 * Display the Instructions List Heading setting markup.
	 *
	 * @since 1.0.0
	 */
	public function instructions_list_heading_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_instructions_list_heading_setting_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/instructions-list-heading.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_ingredients_list_heading_setting_field' );
	}

	/**
	 * Display the Instructions List Type setting markup.
	 *
	 * @since 1.0.0
	 */
	public function instructions_list_type_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_instructions_list_type_setting_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/instructions-list-type.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_instructions_list_type_setting_field' );
	}

	/**
	 * Display the "enqueue styles" setting markup.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_enqueue_styles_setting_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/enqueue-styles.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_enqueue_styles_setting_field' );
	}

	/**
	 * Display the "recipe accent color" setting markup.
	 *
	 * @since 1.0.0
	 */
	public function recipe_accent_color_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_recipe_accent_color_settings_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/recipe-accent-color.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_recipe_accent_color_settings_field' );
	}

	/**
	 * Display the "recipe text color" setting markup.
	 *
	 * @since 1.0.0
	 */
	public function recipe_text_color_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_recipe_text_color_settings_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/recipe-text-color.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_recipe_text_color_settings_field' );
	}

	/**
	 * Display the "extensions license" section markup.
	 *
	 * @since 1.0.0
	 */
	public function license_extensions_section_callback() {

		do_action( 'simmer_before_license_extensions_section' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/license-extensions-section.php' );

		do_action( 'simmer_after_license_extensions_section' );

	}

	/**
	 * Display the "on uninstall" setting markup.
	 *
	 * @since 1.0.0
	 */
	public function on_uninstall_callback() {

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_on_uninstall_settings_field' );

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/advanced/on-uninstall.php' );

		/**
		 * Allow others to add to this field.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_on_uninstall_settings_field' );
	}

	/**
	 * Display the Archive Base setting markup.
	 *
	 * @since 1.0.0
	 */
	public function archive_base_callback() {

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/permalink-archive-base.php' );
	}

	/**
	 * Display the Recipe Base setting markup.
	 *
	 * @since 1.0.0
	 */
	public function recipe_base_callback() {

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/permalink-recipe-base.php' );
	}

	/**
	 * Display the Category Base setting markup.
	 *
	 * @since 1.0.0
	 */
	public function category_base_callback() {

		/**
		 * Include the markup.
		 */
		include_once( 'views/settings/permalink-category-base.php' );
	}

	/**
	 * Validate a given hex color.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $color A color in hex format.
	 * @return string $color A color in hex format or empty on failure.
	 */
	public function validate_hex_color( $color ) {

		$color = preg_replace( '/[^0-9a-fA-F]/', '', $color );

		if ( strlen( $color ) == 6 || strlen( $color ) == 3 ) {
			return $color;
		} else {
			return '';
		}
	}
}
