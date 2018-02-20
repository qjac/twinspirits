<?php
/**
 * Define the template loader class
 *
 * @since 1.0.0
 *
 * @package Simmer/Frontend
 */

/**
 * The template loader.
 *
 * Originally based on the Gamajo_Template_Loader class by Gary Jones.
 *
 * @since 1.0.0
 * @link https://github.com/GaryJones/Gamajo-Template-Loader
 */
class Simmer_Template_Loader {

	/**
	 * Retrieve a requested template part.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug The slug of the template part.
	 * @param string $name Optional. The name of the template part.
	 * @param bool   $load Optional. Whether to load the template part.
	 *
	 * @return string
	 */
	public function get_template_part( $slug, $name = null, $load = true ) {

		/**
		 * Trigger when a template part is requested.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug The requested slug.
		 * @param string $name The requested name.
		 */
		do_action( 'simmer_get_template_part_' . $slug, $slug, $name );

		// Get file names of templates for the requested slug and name.
		$templates_names = $this->get_template_file_names( $slug, $name );

		// Return the part that is found.
		return $this->locate_template( $templates_names, $load, false );
	}

	/**
	 * Given a slug and optional name, build the file names of templates.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $slug The requested slug.
	 * @param string $name The requested name.
	 *
	 * @return array
	 */
	private function get_template_file_names( $slug, $name ) {

		$templates = array();

		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '.php';
		}

		$templates[] = $slug . '.php';

		/**
		 * Filter the template filenames.
		 *
		 * The resulting array should be in the order of most specific first, to least specific last.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $templates Names of template files that should be looked for the requested slug and name.
		 * @param string $slug      The requested slug.
		 * @param string $name      The requested name.
		 */
		$templates = apply_filters( 'simmer_get_template_part', $templates, $slug, $name );

		return $templates;
	}

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
	 * inherit from a parent theme can just overload one file. If the template is
	 * not found in either of those, it looks in the theme-compat folder last.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param  string|array $template_names Template file(s) to search for, in order.
	 * @param  bool         $load           Optional. If true the template file will be loaded if it is found.
	 * @param  bool         $require_once   Optional. Whether to require_once or require.
	 * @return string       $located        The template filename if one is located.
	 */
	private function locate_template( $template_names, $load = false, $require_once = true ) {

		// No file found yet.
		$located = false;

		// Remove empty entries.
		$template_names = array_filter( (array) $template_names );

		$template_paths = $this->get_template_paths();

		// Try to find a template file.
		foreach ( $template_names as $template_name ) {

			// Trim off any slashes from the template name
			$template_name = ltrim( $template_name, '/' );

			// Try locating this template file by looping through the template paths.
			foreach ( $template_paths as $template_path ) {

				if ( file_exists( $template_path . $template_name ) ) {

					$located = $template_path . $template_name;

					break 2;
				}
			}
		}

		if ( $load && $located ) {
			load_template( $located, $require_once );
		}

		return $located;
	}

	/**
	 * Return a list of paths to check for template locations.
	 *
	 * Default is to check in a child theme (if relevant) before a parent theme, so that themes which inherit from a
	 * parent theme can just overload one file. If the template is not found in either of those, it looks in the
	 * theme-compat folder last.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return array $template_paths Possible template paths.
	 */
	private function get_template_paths() {

		$theme_directory = 'simmer';

		$template_paths = array(
			10  => trailingslashit( get_template_directory() ) . $theme_directory,
			100 => $this->get_templates_dir(),
		);

		// Only add this conditionally, so non-child themes don't redundantly check active theme twice.
		if ( is_child_theme() ) {
			$template_paths[1] = trailingslashit( get_stylesheet_directory() ) . $theme_directory;
		}

		/**
		 * Allow others to modify the list of template paths.
		 *
		 * @since 1.0.0
		 *
		 * @param array $template_paths Default is directory in child theme at index 1, parent theme at 10, and plugin at 100.
		 */
		$template_paths = apply_filters( 'simmer_template_paths', $template_paths );

		// Sort the file paths based on priority.
		ksort( $template_paths, SORT_NUMERIC );

		// Trailing slash all of the template paths.
		$template_paths = array_map( 'trailingslashit', $template_paths );

		return $template_paths;
	}

	/**
	 * Return the path to the templates directory.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return string
	 */
	private function get_templates_dir() {

		return trailingslashit( plugin_dir_path( __FILE__ ) . 'templates' );
	}
}
