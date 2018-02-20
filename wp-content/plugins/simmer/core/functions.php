<?php
/**
 * Define the general supporting functions
 *
 * @since 1.0.0
 *
 * @package Simmer
 */

/**
 * Load the main plugin object.
 *
 * @since 1.3.0
 *
 * @return object $simmer The singleton instance of Simmer.
 */
function Simmer() {

	$simmer = Simmer::get_instance();

	return $simmer;
}

/**
 * Return the main recipe object type.
 *
 * We have this function in case, in the future, we want to
 * make the object type's slug filterable.
 *
 * @since 1.0.0
 *
 * @return string The object type slug.
 */
function simmer_get_object_type() {

	return 'recipe';
}

/**
 * Return the category taxonomy slug.
 *
 * We have this function in case, in the future, we want to
 * make this taxonomy's slug filterable.
 *
 * @since 1.0.0
 *
 * @return string The category taxonomy slug.
 */
function simmer_get_category_taxonomy() {

	return 'recipe_category';
}

/**
 * Return the base permalink slug.
 *
 * @since 1.0.0
 *
 * @return string $slug The base permalink slug.
 */
function simmer_get_archive_base() {

	$slug = get_option( 'simmer_archive_base', 'recipes' );

	/**
	 * Allow others to filter the base permalink slug.
	 *
	 * since 1.0.0
	 *
	 * @param string $slug The base permalink slug.
	 */
	$slug = apply_filters( 'simmer_archive_base', $slug );

	return $slug;
}

/**
 * Convert a hex color to RGB values.
 *
 * @since 1.0.0
 *
 * @param string $hex The color in hex format. The # is optional.
 * @return array $rgb The RGB values.
 */
function simmer_hex_to_rgb( $hex ) {

	$hex = str_replace( '#', '', $hex );

	if ( strlen( $hex ) == 3 ) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}

	$rgb = array(
		$r,
		$g,
		$b,
	);

	return $rgb;
}
