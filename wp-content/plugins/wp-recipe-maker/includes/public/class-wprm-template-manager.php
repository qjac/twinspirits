<?php
/**
 * Responsible for the recipe template.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 */

/**
 * Responsible for the recipe template.
 *
 * @since      1.0.0
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/includes/public
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPRM_Template_Manager {
	/**
	 * Cached version of all the available templates.
	 *
	 * @since    1.2.0
	 * @access   private
	 * @var      array    $templates    Array containing all templates that have been loaded.
	 */
	private static $templates = array();

	/**
	 * Register actions and filters.
	 *
	 * @since    1.0.0
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'amp_post_template_css', array( __CLASS__, 'amp_style' ) );
	}

	/**
	 * Enqueue stylesheets and scripts.
	 *
	 * @since    1.0.0
	 */
	public static function enqueue() {
		$template = self::get_template_by_type( 'single' );

		wp_enqueue_style( 'wprm-template', $template['url'] . '/' . $template['slug'] . '.min.css', array(), WPRM_VERSION, 'all' );
	}

	/**
	 * Enqueue template style on AMP pages.
	 *
	 * @since    2.1.0
	 */
	public static function amp_style() {
		$template = self::get_template_by_type( 'single' );

		ob_start();
		include( $template['dir'] . '/' . $template['slug'] . '.min.css' );
		$css = ob_get_contents();
		ob_end_clean();

		// Get rid of !important flags.
		$css = str_ireplace( ' !important', '', $css );
		$css = str_ireplace( '!important', '', $css );

		echo $css;
	}

	/**
	 * Get template for a specific recipe.
	 *
	 * @since    1.0.0
	 * @param		 object $recipe Recipe object to get the template for.
	 * @param		 mixed  $type 	Type of template we want to get, defaults to single.
	 * @param		 mixed  $slug 	Slug of the specific template we want.
	 */
	public static function get_template( $recipe, $type = 'single', $slug = false ) {
		if ( $slug ) {
			$template = self::get_template_by_slug( $slug );
		}

		if ( ! $slug || ! $template ) {
			$template = self::get_template_by_type( $type );
		}

		ob_start();
		require( $template['dir'] . '/' . $template['slug'] . '.php' );
		$template = ob_get_contents();
		ob_end_clean();

		// Prevent infinite shortcode loop.
		$template = preg_replace( "/\[wprm-recipe\s+id=\"?'?" . $recipe->id() . "\"?'?\]/im", '', $template );

		// Replace nutrition label shortcode without ID in the print version.
		if ( 'print' === $type ) {
			$label_shortcodes = array();
			$pattern = get_shortcode_regex( array( 'wprm-nutrition-label' ) );

			if ( preg_match_all( '/' . $pattern . '/s', $template, $matches ) && array_key_exists( 2, $matches ) ) {
				foreach ( $matches[2] as $key => $value ) {
					if ( 'wprm-nutrition-label' === $value ) {
						$label_shortcodes[ $matches[0][ $key ] ] = shortcode_parse_atts( stripslashes( $matches[3][ $key ] ) );
					}
				}
			}

			foreach ( $label_shortcodes as $shortcode => $shortcode_options ) {
				$recipe_id = isset( $shortcode_options['id'] ) ? intval( $shortcode_options['id'] ) : 0;

				if ( ! $recipe_id ) {
					$shortcode_options['id'] = $recipe->id();

					$new_shortcode = '[wprm-nutrition-label';
					foreach ( $shortcode_options as $attr => $val ) {
						$new_shortcode .= ' ' . $attr . '="' . $val . '"';
					}
					$new_shortcode .= ']';

					$template = str_replace( $shortcode, $new_shortcode, $template );
				}
			}
		}

		$template = do_shortcode( $template );

		return apply_filters( 'wprm_get_template', $template, $recipe, $type, $slug );
	}

	/**
	 * Get template styles for a specific recipe.
	 *
	 * @since    1.0.0
	 * @param		 object $recipe Recipe object to get the template for.
	 * @param		 mixed  $type 	Type of template we want to get, defaults to single.
	 */
	public static function get_template_styles( $recipe, $type = 'single' ) {
		$template = self::get_template_by_type( $type );

		ob_start();
		require( $template['dir'] . '/' . $template['slug'] . '.min.css' );
		$css = ob_get_contents();
		ob_end_clean();

		$style = '<style type="text/css">' . $css . '</style>';

		return $style;
	}

	/**
	 * Get template by name.
	 *
	 * @since    1.2.0
	 * @param		 mixed $slug Slug of the template we want to get.
	 */
	public static function get_template_by_slug( $slug ) {
		$templates = self::get_templates();
		$template = isset( $templates[ $slug ] ) ? $templates[ $slug ] : false;

		return $template;
	}

	/**
	 * Get template by type.
	 *
	 * @since    1.7.0
	 * @param	 mixed $type Type of template we want to get, defaults to single.
	 */
	public static function get_template_by_type( $type = 'single' ) {
		if ( 'print' === $type ) {
			$template_slug = WPRM_Settings::get( 'default_print_template' );
		} else {
			$template_slug = WPRM_Settings::get( 'default_recipe_template' );
		}

		$template = self::get_template_by_slug( $template_slug );

		// Get default template if the template in the settings doesn't exist anymore.
		if ( ! $template ) {
			if ( 'print' === $type ) {
				$template_slug = WPRM_Settings::get_default( 'default_print_template' );
			} else {
				$template_slug = WPRM_Settings::get_default( 'default_recipe_template' );
			}

			$template = self::get_template_by_slug( $template_slug );
		}

		return $template;
	}

	/**
	 * Get all available templates.
	 *
	 * @since    1.2.0
	 */
	public static function get_templates() {
		if ( empty( self::$templates ) ) {
			self::load_templates();
		}

		return self::$templates;
	}

	/**
	 * Load all available templates.
	 *
	 * @since    1.2.0
	 */
	private static function load_templates() {
		$templates = array();

		// Load included templates.
		$dirs = array_filter( glob( WPRM_DIR . 'templates/recipe/*' ), 'is_dir' );
		$url = WPRM_URL . 'templates/recipe/';

		foreach ( $dirs as $dir ) {
			$template = self::load_template( $dir, $url, false );
			$templates[ $template['slug'] ] = $template;
		}

		// Load premium templates.
		if ( WPRM_Addons::is_active( 'premium' ) ) {
			$dirs = array_filter( glob( WPRMP_DIR . 'templates/recipe/*' ), 'is_dir' );
			$url = WPRMP_URL . 'templates/recipe/';

			foreach ( $dirs as $dir ) {
				$template = self::load_template( $dir, $url, false );
				$templates[ $template['slug'] ] = $template;
			}
		}

		// Load custom templates from parent theme.
		$theme_dir = get_template_directory();

		if ( file_exists( $theme_dir . '/wprm-templates' ) && file_exists( $theme_dir . '/wprm-templates/recipe' ) ) {
			$url = get_template_directory_uri() . '/wprm-templates/recipe/';

			$dirs = array_filter( glob( $theme_dir . '/wprm-templates/recipe/*' ), 'is_dir' );

			foreach ( $dirs as $dir ) {
				$template = self::load_template( $dir, $url, true );
				$templates[ $template['slug'] ] = $template;
			}
		}

		// Load custom templates from child theme (if present).
		if ( get_stylesheet_directory() !== $theme_dir ) {
			$theme_dir = get_stylesheet_directory();

			if ( file_exists( $theme_dir . '/wprm-templates' ) && file_exists( $theme_dir . '/wprm-templates/recipe' ) ) {
				$url = get_stylesheet_directory_uri() . '/wprm-templates/recipe/';

				$dirs = array_filter( glob( $theme_dir . '/wprm-templates/recipe/*' ), 'is_dir' );

				foreach ( $dirs as $dir ) {
					$template = self::load_template( $dir, $url, true );
					$templates[ $template['slug'] ] = $template;
				}
			}
		}

		self::$templates = $templates;
	}

	/**
	 * Load template from directory.
	 *
	 * @since    1.2.0
	 * @param		 mixed 	 $dir 	 Directory to load the template from.
	 * @param		 mixed 	 $url 	 URL to load the template from.
	 * @param		 boolean $custom Wether or not this is a custom template included by the user.
	 */
	private static function load_template( $dir, $url, $custom = false ) {
		$slug = basename( $dir );
		$name = ucwords( str_replace( '-', ' ', $slug ) );

		$screenshot = false;
		$screenshots = glob( $dir . '/' . $slug . '.{jpg,jpeg,png,gif}', GLOB_BRACE );
		if ( ! empty( $screenshots ) ) {
			$info = pathinfo( $screenshots[0] );
			$screenshot = $info['extension'];
		}

		return array(
			'custom' => $custom,
			'name' => $name,
			'slug' => $slug,
			'dir' => $dir,
			'url' => $url . $slug,
			'screenshot' => $screenshot,
		);
	}
}

WPRM_Template_Manager::init();
