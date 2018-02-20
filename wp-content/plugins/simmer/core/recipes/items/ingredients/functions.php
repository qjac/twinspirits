<?php
/**
 * Supporting functions for ingredients.
 *
 * @since 1.0.0
 *
 * @package Simmer/Recipes/Items/Ingredients
 */

/**
 * Get a specific ingredient.
 *
 * @since 1.0.0
 *
 * @param int          $ingredient_id The ingredient ID.
 * @return object|bool $ingredient    The ingredient object on success, false on failure.
 */
function simmer_get_recipe_ingredient( $ingredient_id ) {

	$ingredients_api = new Simmer_Recipe_Ingredients;

	$ingredient = $ingredients_api->get_ingredient( $ingredient_id );

	return $ingredient;
}

/**
 * Add a new recipe ingredient.
 *
 * @since 1.3.0
 *
 * @param  int      $recipe_id   The recipe ID.
 * @param  string   $description The ingredient description.
 * @param  float    $amount      Optional. The ingredient amount.
 * @param  string   $unit        Optional. The ingredient unit.
 * @param  bool     $is_heading  Optional. Whether the ingredient is a heading.
 * @param  int      $order       Optional. The ingredient order number.
 * @return int|bool $result      The new ingredient's ID or false on failure.
 */
function simmer_add_recipe_ingredient( $recipe_id, $description, $amount = null, $unit = '', $is_heading = false, $order = 0 ) {

	$ingredients_api = new Simmer_Recipe_Ingredients;

	$ingredient_id = $ingredients_api->add_ingredient( $recipe_id, $description, $amount, $unit, $is_heading, $order );

	return $ingredient_id;
}

/**
 * Update an existing ingredient.
 *
 * @since 1.3.0
 *
 * @param  int    $ingredient_id The ID for the ingredient to update.
 * @param array $args {
 *     The updated ingredient values.
 *
 *     @type int    $recipe_id   The recipe ID.
 *     @type float  $amount      The ingredient amount.
 *     @type string $unit        The ingredient unit.
 *     @type string $description The ingredient description.
 *     @type int    $order       The ingredient order number.
 * }
 * @return int|bool $result The ingredient ID or false on failure.
 */
function simmer_update_recipe_ingredient( $ingredient_id, $args ) {

	$ingredients_api = new Simmer_Recipe_Ingredients;

	$result = $ingredients_api->update_ingredient( $ingredient_id, $args );

	return $result;
}

/**
 * Delete an existing ingredient.
 *
 * @since 1.3.0
 *
 * @param  int  $ingredient_id The ID for the ingredient you want to delete.
 * @return bool $result        Whether the ingredient was deleted.
 */
function simmer_delete_recipe_ingredient( $ingredient_id ) {

	$ingredients_api = new Simmer_Recipe_Ingredients;

	$result = $ingredients_api->delete_ingredient( $ingredient_id );

	return $result;
}
