<?php
/**
 * Define the main ingredients class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items/Ingredients
 */

/**
 * The class that handles the specialty ingredients funcitonality.
 *
 * @since 1.3.0
 */
final class Simmer_Recipe_Ingredients {

	/**
	 * Get the ingredients list heading text.
	 *
	 * @since 1.3.0
	 *
	 * @return string $heading The ingredients list heading text.
	 */
	public function get_list_heading() {

		$heading = get_option( 'simmer_ingredients_list_heading', __( 'Ingredients', 'simmer' ) );

		/**
		 * Allow others to filter the ingredients list heading text.
		 *
		 * @since 1.0.0
		 *
		 * @param string $heading The ingredients list heading text.
		 */
		$heading = apply_filters( 'simmer_ingredients_list_heading', $heading );

		return $heading;
	}

	/**
	 * Get the ingredients list type.
	 *
	 * @since 1.3.0
	 *
	 * @return string $type The ingredients list type.
	 */
	public function get_list_type() {

		$type = get_option( 'simmer_ingredients_list_type', 'ul' );

		/**
		 * Allow others to filter the ingredients list type.
		 *
		 * @since 1.0.0
		 *
		 * @param string $type The ingredients list type.
		 */
		$type = apply_filters( 'simmer_ingredients_list_type', $type );

		return $type;
	}

	/**
	 * Get an existing ingredient.
	 *
	 * @since 1.3.0
	 *
	 * @param int          $ingredient_id The ingredient ID.
	 * @return object|bool $ingredient    The ingredient object on success, false on failure.
	 */
	public function get_ingredient( $ingredient_id ) {

		$ingredient = new Simmer_Recipe_Ingredient( $ingredient_id );

		if ( is_null( $ingredient->id ) ) {
			$ingredient = false;
		}

		return $ingredient;
	}

	/**
	 * Add a new ingredient.
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
	public function add_ingredient( $recipe_id, $description, $amount = null, $unit = '', $is_heading = false, $order = 0 ) {

		if ( ! absint( $recipe_id ) ) {
			return false;
		}

		// Try adding the item to the database.
		$item_id = simmer_add_recipe_item( $recipe_id, 'ingredient', $order );

		// If successful, add the metadata.
		if ( $item_id ) {

			simmer_add_recipe_item_meta( $item_id, 'description', $description );

			$amount = floatval( $amount );

			if ( ! empty( $amount ) ) {
				simmer_add_recipe_item_meta( $item_id, 'amount', $amount );
			}

			$unit = sanitize_text_field( $unit );

			if ( ! empty( $unit ) ) {
				simmer_add_recipe_item_meta( $item_id, 'unit', $unit );
			}

			simmer_add_recipe_item_meta( $item_id, 'is_heading', (bool) $is_heading );
		}

		return $item_id;
	}

	/**
	 * Update an existing ingredient.
	 *
	 * @since 1.3.0
	 *
	 * @param int   $ingredient_id The ID for the ingredient to update.
	 * @param array $args {
	 *     The updated ingredient values.
	 *
	 *     @type int    $recipe_id   The recipe ID.
	 *     @type float  $amount      The ingredient amount.
	 *     @type string $unit        Optional. The ingredient unit.
	 *     @type string $description Optional. The ingredient description.
	 *     @type bool   $is_heading  Whether the ingredient is a heading.
	 *     @type int    $order       Optional. The ingredient order number.
	 * }
	 * @return int|bool $result The ingredient ID or false on failure.
	 */
	public function update_ingredient( $ingredient_id, $args ) {

		$exists = simmer_get_recipe_ingredient( $ingredient_id );

		if ( ! $exists ) {
			return false;
		}

		$item_args = array();

		if ( isset( $args['recipe_id'] ) ) {

			$recipe_id = absint( $args['recipe_id'] );

			if ( $recipe_id ) {
				$item_args['recipe_id'] = $recipe_id;
			}
		}

		if ( isset( $args['order'] ) ) {

			if ( is_numeric( $args['order'] ) ) {
				$item_args['recipe_item_order'] = absint( $args['order'] );
			}
		}

		if ( ! empty( $item_args ) ) {
			simmer_update_recipe_item( $ingredient_id, $item_args );
		}

		if ( isset( $args['amount'] ) ) {

			$amount = floatval( $args['amount'] );

			if ( $amount ) {
				simmer_update_recipe_item_meta( $ingredient_id, 'amount', $amount );
			} else {
				simmer_delete_recipe_item_meta( $ingredient_id, 'amount' );
			}
		}

		if ( isset( $args['unit'] ) ) {

			$unit = sanitize_text_field( $args['unit'] );

			if ( ! empty( $unit ) ) {
				simmer_update_recipe_item_meta( $ingredient_id, 'unit', $unit );
			} else {
				simmer_delete_recipe_item_meta( $ingredient_id, 'unit' );
			}
		}

		if ( isset( $args['description'] ) ) {

			if ( ! empty( $args['description'] ) ) {
				simmer_update_recipe_item_meta( $ingredient_id, 'description', $args['description'] );
			} else {
				simmer_delete_recipe_item_meta( $ingredient_id, 'description' );
			}
		}

		if ( isset( $args['is_heading'] ) ) {

			simmer_update_recipe_item_meta( $ingredient_id, 'is_heading', (bool) $args['is_heading'] );
		}

		return $ingredient_id;
	}

	/**
	 * Delete an existing ingredient.
	 *
	 * @since 1.3.0
	 *
	 * @param  int  $ingredient_id The ID for the ingredient you want to delete.
	 * @return bool $result        Whether the ingredient was deleted.
	 */
	public function delete_ingredient( $ingredient_id ) {

		$result = simmer_delete_recipe_item( $ingredient_id );

		if ( $result ) {

			simmer_delete_recipe_item_meta( $ingredient_id, 'amount' );
			simmer_delete_recipe_item_meta( $ingredient_id, 'unit' );
			simmer_delete_recipe_item_meta( $ingredient_id, 'description' );
		}

		return $result;
	}

	/**
	 * Get the available units of measure.
	 *
	 * @since 1.3.0
	 *
	 * @return array $units The filtered units.
	 */
	public static function get_units() {

		$units = array(
			'volume' => array(
				'tsp' => array(
					'single' => _x( 'teaspoon', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'teaspoons', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'tsp.', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'tbsp' => array(
					'single' => _x( 'tablespoon', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'tablespoons', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'tbsp.', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'floz' => array(
					'single' => _x( 'fluid ounce', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'fluid ounces', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'fl oz', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'cup' => array(
					'single' => _x( 'cup', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'cups', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'c', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'pint' => array(
					'single' => _x( 'pint', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'pints', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'pt', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'quart' => array(
					'single' => _x( 'quart', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'quarts', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'qt', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'gal' => array(
					'single' => _x( 'gallon', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'gallons', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'gal', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'ml' => array(
					'single' => _x( 'milliliter', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'milliliters', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'mL', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'liter' => array(
					'single' => _x( 'liter', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'liters', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'L', 'unit of measurement: abbreviation', 'simmer' ),
				),
			),
			'weight' => array(
				'lb' => array(
					'single' => _x( 'pound', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'pounds', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'lb', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'oz' => array(
					'single' => _x( 'ounce', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'ounces', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'oz', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'mg' => array(
					'single' => _x( 'milligram', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'milligrams', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'mg', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'gram' => array(
					'single' => _x( 'gram', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'grams', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'g', 'unit of measurement: abbreviation', 'simmer' ),
				),
				'kg' => array(
					'single' => _x( 'kilogram', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'kilograms', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'kg', 'unit of measurement: abbreviation', 'simmer' ),
				),
			),
			'misc' => array(
				'pinch' => array(
					'single' => _x( 'pinch', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'pinches', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => false,
				),
				'dash' => array(
					'single' => _x( 'dash', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'dashes', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => false,
				),
				'package' => array(
					'single' => _x( 'package', 'unit of measurement: singular', 'simmer' ),
					'plural' => _x( 'packages', 'unit of measurement: plural', 'simmer' ),
					'abbr'   => _x( 'pkg', 'unit of measurement: abbreviation', 'simmer' ),
				),
			),
		);

		/**
		 * Filter the available units.
		 *
		 * @since 1.0.0
		 *
		 * @param array $units The available units of measure.
		 */
		$units = apply_filters( 'simmer_get_units', $units );

		return $units;
	}
}
