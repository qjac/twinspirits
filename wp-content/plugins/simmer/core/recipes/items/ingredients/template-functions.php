<?php
/**
 * Template tags for ingredients.
 *
 * @since 1.0.0
 *
 * @package Simmer/Recipes/Items/Ingredients
 */

/**
 * Get the ingredients for a recipe.
 *
 * @since 1.0.0
 *
 * @param  array      $args        Optional. @see Simmer_Recipe::get_ingredients().
 * @return array|bool $ingredients The array of ingredients or false if none found.
 */
function simmer_get_the_ingredients( $args = array() ) {

	$recipe = simmer_get_recipe( get_the_ID() );

	$ingredients = $recipe->get_ingredients( $args );

	/**
	 * Filter the returned array of ingredients.
	 *
	 * @since 1.0.0
	 *
	 * @param array $ingredients The returned array of ingredients or empty if none set.
	 */
	$ingredients = apply_filters( 'simmer_get_the_ingredients', $ingredients );

	return $ingredients;
}

/**
 * Get the ingredients list heading text.
 *
 * @since 1.0.0
 *
 * @return string $heading The ingredients list heading text.
 */
function simmer_get_ingredients_list_heading() {

	$ingredients = new Simmer_Recipe_Ingredients;

	$heading = $ingredients->get_list_heading();

	return $heading;
}

/**
 * Get the ingredients list type.
 *
 * @since 1.0.0
 *
 * @return string $type The ingredients list type.
 */
function simmer_get_ingredients_list_type() {

	$ingredients = new Simmer_Recipe_Ingredients;

	$type = $ingredients->get_list_type();

	return $type;
}

/**
 * Print or return an HTML list of ingredients for the current recipe.
 *
 * @since 1.0.0
 *
 * @param array $args {
 *     The custom arguments. Optional.
 *
 *     $type bool   $show_heading Whether show the list heading. Default "true".
 *     $type string $heading      The list heading text. Default "Ingredients".
 *     $type string $heading_type The heading tag. Default "h3".
 *     $type string $list_type    The list tag. Default "ul".
 *     $type string $list_class   The class(es) to apply to the list. Default "simmer-ingredients".
 *     $type string $item_type    The list item tag. Default "li".
 *     $type string $item_class   The class(es) to apply to the list items. Default "simmer-ingredient".
 *     $type string $none_message The message when there are no ingredients. Default "This recipe has no ingredients".
 *     $type string $none_class   The class to apply to the "none" message. Default "simmer-info".
 *     $type bool   $echo         Whether to echo or return the generated list. Default "true".
 * }
 * @return string $output The HTML list of ingredients.
 */
function simmer_list_ingredients( $args = array() ) {

	$defaults = array(
		'show_heading' => true,
		'heading'	   => simmer_get_ingredients_list_heading(),
		'heading_type' => apply_filters( 'simmer_ingredients_list_heading_type', 'h3' ),
		'list_type'	   => simmer_get_ingredients_list_type(),
		'list_class'   => 'simmer-ingredients',
		'item_type'    => apply_filters( 'simmer_ingredients_list_item_type', 'li' ),
		'item_heading_type' => apply_filters( 'simmer_ingredients_list_item_heading_type', 'h4' ),
		'item_class'   => 'simmer-ingredient',
		'none_message' => __( 'This recipe has no ingredients', 'simmer' ),
		'none_class'   => 'simmer-message',
		'echo'         => true,
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Allow other to modify the args.
	 *
	 * @since 1.0.0
	 */
	$args = apply_filters( 'simmer_ingredients_list_args', $args );

	// Get the array of ingredients.
	$ingredients = simmer_get_the_ingredients();

	// Start the output!
	$output = '';

	if ( true == $args['show_heading'] ) {
		$output .= '<' . sanitize_html_class( $args['heading_type'] ) . '>';
			$output .= esc_html( $args['heading'] );
		$output .= '</' . sanitize_html_class( $args['heading_type'] ) . '>';
	}

	if ( ! empty( $ingredients ) ) {

		/**
		 * Fire before listing the ingredients.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_ingredients_list' );

		/**
		 * Create an array of attributes for the list element.
		 *
		 * Instead of hardcoding these into the tag itself,
		 * we use an associative array so folks can easily add
		 * custom attributes like data-*="" for JavaScript.
		 */
		$list_attributes = array();

		if ( ! empty( $args['list_class'] ) ) {
			$list_attributes['class'] = $args['list_class'];
		}

		/**
		 * Allow others to filter the list attributes.
		 *
		 * @since 1.0.0
		 *
		 * @param array $list_attributes {
		 *     The attributes in format $attribute => $value.
		 * }
		 */
		$list_attributes = (array) apply_filters( 'simmer_ingredients_list_attributes', $list_attributes );

		if ( $ingredients[0]->is_heading() ) {

			$list_open = false;

		} else {

			// Build the list's opening tag based on the attributes above.
			$output .= '<' . sanitize_html_class( $args['list_type'] );

				if ( ! empty( $list_attributes ) ) {

					foreach( $list_attributes as $attribute => $value ) {
						$output .= ' ' . sanitize_html_class( $attribute ) . '="' . esc_attr( $value ) . '"';
					}
				}

			$output .= '>';

			$list_open = true;

		}
			// Loop through the ingredients.
			foreach ( $ingredients as $ingredient ) {

				/**
				 * Fire before printing the current ingredient.
				 *
				 * @since 1.0.0
				 *
				 * @param array $ingredient The current ingredient in the list.
				 */
				do_action( 'simmer_before_ingredients_list_item', $ingredient );

				// If this is an ingredient heading, change the item tag.
				if ( $ingredient->is_heading() ) {

					if ( true == $list_open ) {

						// Close the previous list.
						$output .= '</' . sanitize_html_class( $args['list_type'] ) . '>';

						$list_open = false;

					}

					// Build the heading.
					$output .= '<' . sanitize_html_class( $args['item_heading_type'] ) . '>';
						$output .= esc_html( $ingredient->get_description() );
					$output .= '</' . sanitize_html_class( $args['item_heading_type'] ) . '>';

					// Build the new list's opening tag based on the attributes above.
					$output .= '<' . sanitize_html_class( $args['list_type'] );

						if ( ! empty( $list_attributes ) ) {

							foreach( $list_attributes as $attribute => $value ) {
								$output .= ' ' . sanitize_html_class( $attribute ) . '="' . esc_attr( $value ) . '"';
							}
						}

					$output .= '>';

					$list_open = true;

				} else {

					/**
					 * Create an array of attributes for the list element.
					 *
					 * Instead of hardcoding these into the tag itself,
					 * we use an associative array so folks can easily add
					 * custom attributes like data-*="" for JavaScript.
					 */
					$item_attributes = array(
						'itemprop' => 'ingredients',
					);

					if ( ! empty( $args['item_class'] ) ) {
						$item_attributes['class'] = $args['item_class'];
					}

					/**
					 * Allow others to filter the list item attributes.
					 *
					 * @since 1.0.0
					 *
					 * @param array $item_attributes {
					 *     The attributes in format $attribute => $value.
					 * }
					 */
					$item_attributes = (array) apply_filters( 'simmer_ingredients_list_item_attributes', $item_attributes, $ingredient );

					// Build the list item opening tag based on the attributes above.
					$output .= '<' . sanitize_html_class( $args['item_type'] );

						if ( ! empty( $item_attributes ) ) {

							foreach( $item_attributes as $attribute => $value ) {
								$output .= ' ' . sanitize_html_class( $attribute ) . '="' . esc_attr( $value ) . '"';
							}
						}

					$output .= '>';

						if ( $amount = $ingredient->get_amount() ) {
							$output .= '<span class="simmer-ingredient-amount">' . esc_html( $amount ) . '</span> ';
						}

						if ( $unit = $ingredient->get_unit() ) {
							$output .= '<span class="simmer-ingredient-unit">' . esc_html( $unit ) . '</span> ';
						}

						if ( $description = $ingredient->get_description() ) {
							$output .= '<span class="simmer-ingredient-description">' . esc_html( $description ) . '</span>';
						}

					// Close the list item.
					$output .= '</' . sanitize_html_class( $args['item_type'] ) . '>';

				}

				/**
				 * Fire after printing the current ingredient.
				 *
				 * @since 1.0.0
				 *
				 * @param array $ingredient The current ingredient in the list.
				 */
				do_action( 'simmer_after_ingredients_list_item', $ingredient );

			}

		// Close the list.
		$output .= '</' . sanitize_html_class( $args['list_type'] ) . '>';

		/**
		 * Fire after listing the ingredients.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_ingredients_list' );

	} else {

		// No ingredients to list!
		$output .= '<p class="' . sanitize_html_class( $args['none_class'] ) . '">' . esc_html( $args['none_message'] ) . '</p>';

	}

	// Echo or return based on the $args.
	if ( true == $args['echo'] ) {
		echo $output;
	} else {
		return $output;
	}
}
