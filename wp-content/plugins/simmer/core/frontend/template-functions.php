<?php
/**
 * Define the front-end template functions
 *
 * @package    Simmer
 * @subpackage Simmer/Frontend
 * @author     Team Simmer
 * @copyright  Copyright (c) 2015, Team Simmer
 * @since      1.2.0
 */

/**
 * Determine if a recipe's featured image has been set.
 *
 * @since 1.2.0
 *
 * @param  int $recipe_id Optional. The ID of the recipe to check. Defaults to the currently looped recipe.
 * @return bool $has_featured_image Whether the recipe's featured image has been set.
 */
function simmer_recipe_has_featured_image( $recipe_id = null ) {

	if ( is_null( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	// Check if the featured image has been set.
	$has_featured_image = has_post_thumbnail( $recipe_id );

	/**
	 * Filter the recipe's featured image status.
	 *
	 * @since 1.2.0
	 *
	 * @param int $recipe_id The ID of the recipe to check.
	 */
	$has_featured_image = (bool) apply_filters( 'simmer_recipe_has_featured_image', $has_featured_image, $recipe_id );

	return $has_featured_image;
}

/**
 * Return the title for a recipe.
 *
 * @since  1.3.9
 *
 * @param  int $recipe_id The ID of the recipe to output the title for.
 * @return string The recipe's title with a wrapping permalink.
 */
function simmer_get_recipe_title( $recipe_id = null ) {
	if ( null === $recipe_id ) {
		$recipe_id = get_the_ID();
	}

	$title = sprintf( '<a href="%s">%s</a>',
		esc_url( get_permalink( $recipe_id ) ),
		get_the_title( $recipe_id )
	);

	/**
	 * Filter the recipe title's markup.
	 *
	 * @since 1.3.9
	 *
	 * @param int $recipe_id The ID of the recipe to output the title for.
	 */
	return apply_filters( 'simmer_recipe_title', $title, $recipe_id );
}

/**
 * Output the title for a recipe.
 *
 * @since  1.3.9
 *
 * @param  int $recipe_id The ID of the recipe to output the title for.
 * @return void
 */
function simmer_recipe_title( $recipe_id = null ) {
	if ( null === $recipe_id ) {
		$recipe_id = get_the_ID();
	}
	echo simmer_get_recipe_title( $recipe_id );
}
