<?php
/**
 * Define the main recipe class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes
 */

/**
 * The single recipe object.
 *
 * @since 1.3.0
 */
final class Simmer_Recipe {

	/**
	 * The recipe ID.
	 *
	 * @since 1.3.0
	 *
	 * @var int $id
	 */
	public $id = 0;

	/**
	 * The standard WordPress post object.
	 *
	 * @since 1.3.0
	 *
	 * @var object $post
	 */
	public $post = null;

	/**
	 * Construct the recipe.
	 *
	 * @since 1.3.0
	 */
	public function __construct( $recipe ) {

		if ( is_numeric( $recipe ) ) {
			$this->id   = absint( $recipe );
			$this->post = get_post( $recipe );
		} elseif ( $recipe instanceof Simmer_Recipe ) {
			$this->id   = absint( $recipe->id );
			$this->post = $recipe->post;
		} elseif ( isset( $recipe->ID ) ) {
			$this->id   = absint( $recipe->ID );
			$this->post = $recipe;
		}
	}

	/**
	 * Get the items that belong to the recipe.
	 *
	 * @since 1.3.0
	 *
	 * @param  string $type  Optional. The type of items to get. If blank, all items
	 *                       will be returned. Default: all items.
	 * @return array  $items The attached items.
	 */
	public function get_items( $type = '' ) {

		$args = array();

		if ( $type ) {
			$args['type'] = esc_attr( $type );
		}

		$items_api = new Simmer_Recipe_Items;

		$items = (array) $items_api->get_items( $this->id, $args );

		/**
		 * Filter a recipe's retrieved items.
		 *
		 * @since 1.3.0
		 *
		 * @param array $items     The retrieved items.
		 * @param int   $recipe_id The recipe ID.
		 */
		$items = apply_filters( 'simmer_get_recipe_items', $items, $this->id );

		return $items;
	}

	/**
	 * Get the ingredients.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args {
	 *     Optional. An array of arguments.
	 *
	 *     @type bool   $exclude_headings Whether returned ingredients should exclude headings. Default 'false'.
	 *     @type string $orderby          What to order the ingredients by. Default 'order' for their set order.
	 *                                    Accepts 'order', 'amount', 'unit', or 'random'. Requires $exclude_headings
	 *                                    be set to 'true'.
	 * }
	 * @return array $ingredients An array of the recipe's ingredients. An empty array is returned when there
	 *                            are no ingredients for the recipe.
	 */
	public function get_ingredients( $args = array() ) {

		$defaults = array(
			'exclude_headings' => false,
			'orderby'          => 'order',
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Filter the recipe's ingredients query args.
		 *
		 * @since 1.3.3
		 *
		 * @param array $args      The recipe's ingredients query args. @see Simmer_Recipe::get_ingredients().
		 * @param int   $recipe_id The recipe ID.
		 */
		$args = apply_filters( 'simmer_get_recipe_ingredients_args', $args, $this->id );

		$items = $this->get_items( 'ingredient' );

		$ingredients = array();

		foreach ( $items as $item ) {

			// Exclude headings if set to do so in the args.
			if ( $args['exclude_headings'] && simmer_get_recipe_item_meta( $item->recipe_item_id, 'is_heading', true ) ) {
				continue;
			}

			$ingredients[] = new Simmer_Recipe_Ingredient( $item );
		}

		// Maybe reorder the ingredients.
		if ( $args['exclude_headings'] && 'order' !== $args['orderby'] ) {

			if ( 'random' == $args['orderby'] ) {
				shuffle( $ingredients );
			} else if ( method_exists( $this, 'sort_ingredients_by_' . $args['orderby'] ) ) {
				usort( $ingredients, array( $this, 'sort_ingredients_by_' . $args['orderby'] ) );
			}
		}

		/**
		 * Filter a recipe's retrieved ingredients.
		 *
		 * @since 1.3.0
		 *
		 * @param array $ingredients The retrieved ingredients.
		 * @param int   $recipe_id   The recipe ID.
		 */
		$ingredients = apply_filters( 'simmer_get_recipe_ingredients', $ingredients, $this->id );

		return $ingredients;
	}

	/**
	 * Sort two ingredients by their amounts.
	 *
	 * @since  1.3.3
	 * @access private
	 *
	 * @see usort()
	 *
	 * @param  object $a The first ingredient object.
	 * @param  object $b The second ingredient object.
	 * @return int       Whether first amount is greater than, less than, or equal to the second.
	 */
	private function sort_ingredients_by_amount( $a, $b ) {

		// Get the filtered amounts.
		$a = $a->get_amount();
		$b = $b->get_amount();

		// Convert the amounts to floats.
		$a = Simmer_Recipe_Ingredient::convert_amount_to_float( $a );
		$b = Simmer_Recipe_Ingredient::convert_amount_to_float( $b );

		if( $a == $b ) {
			return 0;
		}

		return ( $a < $b ) ? -1 : 1;
	}

	/**
	 * Sort two ingredients by their units (alphabetical).
	 *
	 * @since  1.3.3
	 * @access private
	 *
	 * @see usort()
	 *
	 * @param  object $a The first ingredient object.
	 * @param  object $b The second ingredient object.
	 * @return int       Which unit is alphabetically superior.
	 */
	private function sort_ingredients_by_unit( $a, $b ) {

		return strcmp( $a->get_unit(), $b->get_unit() );
	}

	/**
	 * Get the instructions.
	 *
	 * @since 1.3.0
	 *
	 * @param array $args {
	 *     Optional. An array of arguments.
	 *
	 *     @type bool $exclude_headings Whether returned instructions should exclude headings. Default 'false'.
	 * }
	 * @return array $instructions An array of the recipe's instructions. An empty array is returned when there
	 *                             are no instructions for the recipe.
	 */
	public function get_instructions( $args = array() ) {

		$defaults = array(
			'exclude_headings' => false,
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * Filter the recipe's instructions query args.
		 *
		 * @since 1.3.3
		 *
		 * @param array $args      The recipe's instructions query args. @see Simmer_Recipe::get_instructions().
		 * @param int   $recipe_id The recipe ID.
		 */
		$args = apply_filters( 'simmer_get_recipe_instructions_args', $args, $this->id );

		$items = $this->get_items( 'instruction' );

		$instructions = array();

		foreach ( $items as $item ) {

			// Exclude headings if set to do so in the args.
			if ( $args['exclude_headings'] && simmer_get_recipe_item_meta( $item->recipe_item_id, 'is_heading', true ) ) {
				continue;
			}

			$instructions[] = new Simmer_Recipe_Instruction( $item );
		}

		/**
		 * Filter a recipe's retrieved instructions.
		 *
		 * @since 1.3.0
		 *
		 * @param array $instructions The retrieved instructions.
		 * @param int   $recipe_id    The recipe ID.
		 */
		$instructions = apply_filters( 'simmer_get_recipe_instructions', $instructions, $this->id );

		return $instructions;
	}

	/**
	 * Get the prep time.
	 *
	 * @since 1.3.0
	 *
	 * @param  string      $format    Optional. The duration format to return. Specify 'machine'
	 *                                for microdata-friendly format. Default: 'human'.
	 * @return string|bool $prep_time The formatted prep time or false on failure.
	 */
	public function get_prep_time( $format = 'human' ) {

		$durations_api = new Simmer_Recipe_Durations;

		$prep_time = $durations_api->get_duration( 'prep', $this->id );

		if ( $prep_time ) {

			if ( 'machine' == $format ) {
				$prep_time = $durations_api->format_machine_duration( $prep_time );
			} else {
				$prep_time = $durations_api->format_human_duration( $prep_time );
			}
		}

		/**
		 * Filter the prep time.
		 *
		 * @since 1.3.0
		 *
		 * @param string|bool $prep_time The returned time string or false if none set.
		 * @param int         $recipe_id The recipe ID.
		 */
		$prep_time = apply_filters( 'simmer_get_recipe_prep_time', $prep_time, $this->id );

		return $prep_time;
	}

	/**
	 * Get the cook time.
	 *
	 * @since 1.3.0
	 *
	 * @param  string      $format    Optional. The duration format to return. Specify 'machine'
	 *                                for microdata-friendly format. Default: 'human'.
	 * @return string|bool $cook_time The formatted cook time or false on failure.
	 */
	public function get_cook_time( $format = 'human' ) {

		$durations_api = new Simmer_Recipe_Durations;

		$cook_time = $durations_api->get_duration( 'cook', $this->id );

		if ( $cook_time ) {

			if ( 'machine' == $format ) {
				$cook_time = $durations_api->format_machine_duration( $cook_time );
			} else {
				$cook_time = $durations_api->format_human_duration( $cook_time );
			}
		}

		/**
		 * Filter the cook time.
		 *
		 * @since 1.3.0
		 *
		 * @param string|bool $cook_time The returned time string or false if none set.
		 * @param int         $recipe_id The recipe ID.
		 */
		$cook_time = apply_filters( 'simmer_get_recipe_cook_time', $cook_time, $this->id );

		return $cook_time;
	}

	/**
	 * Get the total time.
	 *
	 * @since 1.3.0
	 *
	 * @param  string      $format     Optional. The duration format to return. Specify 'machine'
	 *                                 for microdata-friendly format. Default: 'human'.
	 * @return string|bool $total_time The formatted total time or false on failure.
	 */
	public function get_total_time( $format = 'human' ) {

		$durations_api = new Simmer_Recipe_Durations;

		$total_time = $durations_api->get_duration( 'total', $this->id );

		if ( $total_time ) {

			if ( 'machine' == $format ) {
				$total_time = $durations_api->format_machine_duration( $total_time );
			} else {
				$total_time = $durations_api->format_human_duration( $total_time );
			}
		}

		/**
		 * Filter the total time.
		 *
		 * @since 1.3.0
		 *
		 * @param string|bool $total_time The returned time string or false if none set.
		 * @param int         $recipe_id  The recipe ID.
		 */
		$total_time = apply_filters( 'simmer_get_recipe_total_time', $total_time, $this->id );

		return $total_time;
	}
}
