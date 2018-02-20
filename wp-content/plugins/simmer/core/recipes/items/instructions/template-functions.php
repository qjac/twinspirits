<?php
/**
 * Template tags for instructions.
 *
 * @since 1.0.0
 *
 * @package Simmer/Recipes/Items/Instructions
 */

/**
 * Get the instructions for a recipe.
 *
 * @since 1.0.0
 *
 * @param  array      $args         Optional. @see Simmer_Recipe::get_instructions().
 * @return array|bool $instructions The array of instructions or false if none set.
 */
function simmer_get_the_instructions( $args = array() ) {

	$recipe = simmer_get_recipe( get_the_ID() );

	$instructions = $recipe->get_instructions( $args );

	/**
	 * Filter the returned array of instructions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instructions The returned array of instructions or empty if none set.
	 */
	$instructions = apply_filters( 'simmer_get_the_instructions', $instructions );

	return $instructions;
}

/**
 * Get the instructions list heading text.
 *
 * @since 1.0.0
 *
 * @return string $heading The instructions list heading text.
 */
function simmer_get_instructions_list_heading() {

	$instructions = new Simmer_Recipe_Instructions;

	$heading = $instructions->get_list_heading();

	return $heading;
}

/**
 * Get the instructions list type.
 *
 * @since 1.0.0
 *
 * @return string $type The instructions list type.
 */
function simmer_get_instructions_list_type() {

	$instructions = new Simmer_Recipe_Instructions;

	$type = $instructions->get_list_type();

	return $type;
}

/**
 * Print or return an HTML list of instructions for the current recipe.
 *
 * @since 1.0.0
 *
 * @param array $args {
 *     The custom arguments. Optional.
 *
 *     $type bool   $show_heading Whether show the list heading. Default "true".
 *     $type string $heading      The list heading text. Default "Instructions".
 *     $type string $heading_type The heading tag. Default "h3".
 *     $type string $list_type    The list tag. Default "ul".
 *     $type string $list_class   The class(es) to apply to the list. Default "simmer-instructions".
 *     $type string $item_type    The list item tag. Default "li".
 *     $type string $item_class   The class(es) to apply to the list items. Default "simmer-instruction".
 *     $type string $none_message The message when there are no instructions. Default "This recipe has no instructions".
 *     $type string $none_class   The class to apply to the "none" message. Default "simmer-info".
 *     $type bool   $echo         Whether to echo or return the generated list. Default "true".
 * }
 * @return string $output The HTML list of instructions.
 */
function simmer_list_instructions( $args = array() ) {

	$defaults = array(
		'show_heading'      => true,
		'heading'	        => simmer_get_instructions_list_heading(),
		'heading_type'      => apply_filters( 'simmer_instructions_list_heading_type', 'h3' ),
		'list_type'	        => simmer_get_instructions_list_type(),
		'list_class'        => 'simmer-instructions',
		'item_type'         => apply_filters( 'simmer_instructions_list_item_type', 'li' ),
		'item_heading_type' => apply_filters( 'simmer_instructions_list_item_heading_type', 'h4' ),
		'item_class'        => 'simmer-instruction',
		'none_message'      => __( 'This recipe has no instructions', 'simmer' ),
		'none_class'        => 'simmer-message',
		'echo'              => true,
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Allow other to modify the args.
	 *
	 * @since 1.0.0
	 */
	$args = apply_filters( 'simmer_instructions_list_args', $args );

	// Get the array of instructions.
	$instructions = simmer_get_the_instructions();

	// Start the output!
	$output = '';

	if ( true == $args['show_heading'] ) {
		$output .= '<' . sanitize_html_class( $args['heading_type'] ) . '>';
			$output .= esc_html( $args['heading'] );
		$output .= '</' . sanitize_html_class( $args['heading_type'] ) . '>';
	}

	if ( ! empty( $instructions ) ) {

		/**
		 * Fire before listing the instructions.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_before_instructions_list' );

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
		$list_attributes = (array) apply_filters( 'simmer_instructions_list_attributes', $list_attributes );

		if ( $instructions[0]->is_heading() ) {

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

			// Loop through the instructions.
			foreach ( $instructions as $instruction ) {

				/**
				 * Fire before printing the current instruction.
				 *
				 * @since 1.0.0
				 *
				 * @param array $instruction The current instruction in the list.
				 */
				do_action( 'simmer_before_instructions_list_item', $instruction );

				// If this is an instruction heading, change the item tag.
				if ( $instruction->is_heading() ) {

					if ( true == $list_open ) {

						// Close the previous list.
						$output .= '</' . sanitize_html_class( $args['list_type'] ) . '>';

						$list_open = false;

					}

					// Build the heading.
					$output .= '<' . sanitize_html_class( $args['item_heading_type'] ) . '>';
						$output .= esc_html( $instruction->get_description() );
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
						'itemprop' => 'recipeInstructions',
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
					$item_attributes = (array) apply_filters( 'simmer_instructions_list_item_attributes', $item_attributes, $instruction );

					// Build the list item opening tag based on the attributes above.
					$output .= '<' . sanitize_html_class( $args['item_type'] );

						if ( ! empty( $item_attributes ) ) {

							foreach( $item_attributes as $attribute => $value ) {
								$output .= ' ' . sanitize_html_class( $attribute ) . '="' . esc_attr( $value ) . '"';
							}
						}

					$output .= '>';

						if ( $description = $instruction->get_description() ) {
							$output .= esc_html( $description );
						}

					// Close the list item.
					$output .= '</' . sanitize_html_class( $args['item_type'] ) . '>';

				}

				/**
				 * Fire after printing the current instruction.
				 *
				 * @since 1.0.0
				 *
				 * @param array $instruction The current instruction in the list.
				 */
				do_action( 'simmer_after_instructions_list_item', $instruction );

			}

		// Close the list.
		$output .= '</' . sanitize_html_class( $args['list_type'] ) . '>';

		/**
		 * Fire after listing the instructions.
		 *
		 * @since 1.0.0
		 */
		do_action( 'simmer_after_instructions_list' );

	} else {

		// No instructions to list!
		$output .= '<p class="' . sanitize_html_class( $args['none_class'] ) . '">';
			$output .= esc_html( $args['none_message'] );
		$output .= '</p>';

	}

	// Echo or return based on the $args.
	if ( true == $args['echo'] ) {
		echo $output;
	} else {
		return $output;
	}
}
