<?php
/**
 * Supporting functions for instructions.
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items/Instructions
 */

/**
 * Get a specific instruction.
 *
 * @since 1.3.0
 *
 * @param  int    $instruction_id The instruction item ID.
 * @return object $instruction    The single instruction item.
 */
function simmer_get_recipe_instruction( $instruction_id ) {

	$instructions_api = new Simmer_Recipe_Instructions;

	$instruction = $instructions_api->get_instruction( $instruction_id );

	return $instruction;
}

/**
 * Add a new recipe instruction.
 *
 * @since 1.3.0
 *
 * @param  int      $recipe_id   The recipe ID.
 * @param  string   $description The instruction description.
 * @param  bool     $is_heading  Optional. Whether the instruction is a heading.
 * @param  int      $order       Optional. The instruction order number.
 * @return int|bool $result      The new instruction's ID or false on failure.
 */
function simmer_add_recipe_instruction( $recipe_id, $description, $is_heading = false, $order = 0 ) {

	$instructions_api = new Simmer_Recipe_Instructions;

	$instruction_id = $instructions_api->add_instruction( $recipe_id, $description, $is_heading, $order );

	return $instruction_id;
}

/**
 * Update an existing instruction.
 *
 * @since 1.3.0
 *
 * @param  int    $instruction_id The ID for the instruction to update.
 * @param array $args {
 *     The updated instruction values.
 *
 *     @type int    $recipe_id   The recipe ID.
 *     @type string $description The instruction description.
 *     @type bool   $is_heading  Whether the instruction is a heading.
 *     @type int    $order       The instruction order number.
 * }
 * @return int|bool $result The instruction ID or false on failure.
 */
function simmer_update_recipe_instruction( $instruction_id, $args ) {

	$instructions_api = new Simmer_Recipe_Instructions;

	$instruction_id = $instructions_api->update_instruction( $instruction_id, $args );

	return $instruction_id;
}

/**
 * Delete an existing instruction.
 *
 * @since 1.3.0
 *
 * @param  int  $instruction_id The ID for the instruction you want to delete.
 * @return bool $result        Whether the instruction was deleted.
 */
function simmer_delete_recipe_instruction( $instruction_id ) {

	$instructions_api = new Simmer_Recipe_Instructions;

	$result = $instructions_api->delete_instruction( $instruction_id );

	return $result;
}
