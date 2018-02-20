<?php
/**
 * Define the recipe functions
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes
 */

/**
 * Get a specific recipe.
 *
 * @since 1.3.0
 *
 * @param  int    $recipe_id A recipe ID.
 * @return object Simmer_Recipe
 */
function simmer_get_recipe( $recipe_id ) {

	if ( ! is_numeric( $recipe_id ) ) {
		return false;
	}

	return new Simmer_Recipe( $recipe_id );
}

/**
 * Get a recipe's source text.
 *
 * @since 1.1.0
 *
 * @param  int    $recipe_id A recipe's ID.
 * @return string $text      The recipe's source text.
 */
function simmer_get_source_text( $recipe_id ) {

	$text = get_post_meta( $recipe_id, '_recipe_source_text', true );

	// For back compat, check for the old "attribution" key.
	if ( ! $text ) {
		$text = get_post_meta( $recipe_id, '_recipe_attribution_text', true );
	}

	/**
	 * Filter the returned source text.
	 *
	 * @since 1.1.0
	 *
	 * @param string $url       The returned source text or '' on failure.
	 * @param int    $recipe_id The recipe's ID.
	 */
	$text = apply_filters( 'simmer_get_source_text', $text, $recipe_id );

	return $text;
}

/**
 * Get a recipe's source URL.
 *
 * @since 1.1.0
 *
 * @param  int    $recipe_id A recipe's ID.
 * @return string $url       The recipe's source URL.
 */
function simmer_get_source_url( $recipe_id ) {

	$url = get_post_meta( $recipe_id, '_recipe_source_url', true );

	// For back compat, check for the old "attribution" key.
	if ( ! $url ) {
		$url = get_post_meta( $recipe_id, '_recipe_attribution_url', true );
	}

	/**
	 * Filter the returned source URL.
	 *
	 * @since 1.1.0
	 *
	 * @param string $url       The returned source URL or '' on failure.
	 * @param int    $recipe_id The recipe's ID.
	 */
	$url = apply_filters( 'simmer_get_source_url', $url, $recipe_id );

	return $url;
}
