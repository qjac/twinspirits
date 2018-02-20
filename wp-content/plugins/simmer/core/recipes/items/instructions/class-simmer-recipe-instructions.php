<?php
/**
 * Define the main instructions class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items/Instructions
 */

/**
 * The class that handles the specialty instructions funcitonality.
 *
 * @since 1.3.0
 */
final class Simmer_Recipe_Instructions {

	/**
	 * Get the instructions list heading text.
	 *
	 * @since 1.3.0
	 *
	 * @return string $heading The instructions list heading text.
	 */
	public function get_list_heading() {

		$heading = get_option( 'simmer_instructions_list_heading', __( 'Instructions', 'simmer' ) );

		/**
		 * Filter the instructions list heading text.
		 *
		 * @since 1.0.0
		 *
		 * @param string $heading The instructions list heading text.
		 */
		$heading = apply_filters( 'simmer_instructions_list_heading', $heading );

		return $heading;
	}

	/**
	 * Get the instructions list type.
	 *
	 * @since 1.3.0
	 *
	 * @return string $type The instructions list type.
	 */
	public function get_list_type() {

		$type = get_option( 'simmer_instructions_list_type', 'ol' );

		/**
		 * Filter the instructions list type.
		 *
		 * @since 1.0.0
		 *
		 * @param string $type The instructions list type.
		 */
		$type = apply_filters( 'simmer_instructions_list_type', $type );

		return $type;
	}

	/**
	 * Get an existing instruction.
	 *
	 * @since 1.3.0
	 *
	 * @param int          $instruction_id The instruction ID.
	 * @return object|bool $instruction    The instruction object on success, false on failure.
	 */
	public function get_instruction( $instruction_id ) {

		$instruction = new Simmer_Recipe_Instruction( $instruction_id );

		if ( is_null( $instruction->id ) ) {
			$instruction = false;
		}

		return $instruction;
	}

	/**
	 * Add a new instruction.
	 *
	 * @since 1.3.0
	 *
	 * @param  int      $recipe_id   The recipe ID.
	 * @param  string   $description The instruction description.
	 * @param  bool     $is_heading  Optional. Whether the instruction is a heading.
	 * @param  int      $order       Optional. The instruction order number.
	 * @return int|bool $result      The new instruction's ID or false on failure.
	 */
	public function add_instruction( $recipe_id, $description, $is_heading = false, $order = 0 ) {

		if ( ! absint( $recipe_id ) ) {
			return false;
		}

		// Try adding the item to the database.
		$item_id = simmer_add_recipe_item( $recipe_id, 'instruction', $order );

		// If successful, add the metadata.
		if ( $item_id ) {

			simmer_add_recipe_item_meta( $item_id, 'description', $description );

			simmer_add_recipe_item_meta( $item_id, 'is_heading', (bool) $is_heading );
		}

		return $item_id;
	}

	/**
	 * Update an existing instruction.
	 *
	 * @since 1.3.0
	 *
	 * @param int   $instruction_id The ID for the instruction to update.
	 * @param array $args {
	 *     The updated instruction values.
	 *
	 *     @type int    $recipe_id   The recipe ID.
	 *     @type string $description The instruction description.
	 *     @type bool   $is_heading' Whether the instruction is a heading.
	 *     @type int    $order       The instruction order number.
	 * }
	 * @return int|bool $result The instruction ID or false on failure.
	 */
	public function update_instruction( $instruction_id, $args ) {

		$exists = simmer_get_recipe_instruction( $instruction_id );

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
			simmer_update_recipe_item( $instruction_id, $item_args );
		}

		if ( isset( $args['description'] ) ) {

			if ( ! empty( $args['description'] ) ) {
				simmer_update_recipe_item_meta( $instruction_id, 'description', $args['description'] );
			} else {
				simmer_delete_recipe_item_meta( $instruction_id, 'description' );
			}
		}

		if ( isset( $args['is_heading'] ) ) {

			simmer_update_recipe_item_meta( $instruction_id, 'is_heading', (bool) $args['is_heading'] );
		}

		return $instruction_id;
	}

	/**
	 * Delete an existing instruction.
	 *
	 * @since 1.3.0
	 *
	 * @param  int  $instruction_id The ID for the instruction you want to delete.
	 * @return bool $result         Whether the instruction was deleted.
	 */
	public function delete_instruction( $instruction_id ) {

		$result = simmer_delete_recipe_item( $instruction_id );

		if ( $result ) {

			simmer_delete_recipe_item_meta( $instruction_id, 'description' );
			simmer_delete_recipe_item_meta( $instruction_id, 'is_heading' );
		}

		return $result;
	}
}
