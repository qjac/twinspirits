<?php
/**
 * Define the single instruction class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items/Instructions
 */

/**
 * The class for gathering and formatting information about a single recipe instruction.
 *
 * @since 1.3.0
 */
final class Simmer_Recipe_Instruction {

	/**
	 * The instruction's item ID.
	 *
	 * @since 1.3.0
	 *
	 * @var int $id
	 */
	public $id;

	/**
	 * The instruction description.
	 *
	 * @since 1.3.0
	 *
	 * @var string $description
	 */
	public $description = '';

	/**
	 * The instruction order.
	 *
	 * @since 1.3.0
	 *
	 * @var int $order
	 */
	public $order = 0;

	/**
	 * Construct the instruction object.
	 *
	 * @since 1.3.0
	 *
	 * @param int|object instruction The instruction item or item ID.
	 */
	public function __construct( $instruction ) {

		if ( is_numeric( $instruction ) ) {

			$items_api = new Simmer_Recipe_Items;

			$instruction = $items_api->get_item( $instruction );

			if ( ! $instruction ) {
				return false;
			}
		}

		$this->id    = $instruction->recipe_item_id;
		$this->order = $instruction->recipe_item_order;

		$this->description = $this->get_description( true );
		$this->is_heading  = $this->is_heading( true );
	}

	/**
	 * Get the instruction description.
	 *
	 * @since 1.3.0
	 *
	 * @param  bool   $raw         Whether to get the description unaltered from the database.
	 * @return string $description The instruction description.
	 */
	public function get_description( $raw = false ) {

		$description = simmer_get_recipe_item_meta( $this->id, 'description', true );

		if ( $raw ) {
			return $description;
		}

		$description = apply_filters( 'simmer_recipe_instruction_description', $description, $this->id );

		return $description;
	}

	/**
	 * Determine if the instruction is a heading.
	 *
	 * @since 1.3.0
	 *
	 * @param  bool   $raw        Whether to get the heading status unaltered from the database.
	 * @return string $is_heading Whether the instruction is a heading..
	 */
	public function is_heading( $raw = false ) {

		$is_heading = simmer_get_recipe_item_meta( $this->id, 'is_heading', true );

		if ( $raw ) {
			return $is_heading;
		}

		$is_heading = apply_filters( 'simmer_recipe_instruction_is_heading', $is_heading, $this->id );

		return $is_heading;
	}
}
