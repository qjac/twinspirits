<?php
/**
 * Define the recipe Items API class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items
 */

final class Simmer_Recipe_Items {

	/**
	 * Get items for a specific recipe.
	 *
	 * @since 1.3.0
	 *
	 * @global object $wpdb The WordPress database class.
	 *
	 * @param int $recipe_id The recipe for which to get the items.
	 * @param array $args {
	 *     Optional. The query parameters.
	 *
	 *     @type string       $type   The item type to retrieve. Default: 'all'.
	 *     @type bool|int     $limit  The number of items to get or false for no limit. Default: 'false'.
	 *     @type bool|int     $offset The number of items by which to offset the query or false for no
	 *                                offset. Default: 'false'.
	 *     @type string       $order  The order in which to return the items. Default: 'ASC'. Accepts 'ASC' or 'DESC'.
	 * }
	 * @return array $items The retrieved items.
	 */
	public function get_items( $recipe_id, $args = array() ) {

		global $wpdb;

		$recipe_id = (int) $recipe_id;

		if ( ! $recipe_id ) {
			return false;
		}

		$defaults = array(
			'type'      => 'all',
			'limit'     => false,
			'offset'    => false,
			'order'     => 'ASC',
		);

		$args = wp_parse_args( $args, $defaults );

		// Start building the query.
		$query = "
			SELECT   *
			FROM     {$wpdb->prefix}simmer_recipe_items
			WHERE    recipe_id = %d
		";

		$values = array(
			$recipe_id,
		);

		// Parse the item type.
		if ( 'all' !== $args['type'] ) {

			$type = esc_attr( $args['type'] );

			$query .= " AND recipe_item_type = '%s'";

			$values[] = $type;
		}

		$query .= " ORDER BY recipe_item_type, recipe_item_order";

		// Parse the order param.
		if ( 'DESC' == $args['order'] ) {
			$query .= " DESC";
		}

		// Parse the limit/offset params.
		$limit = (int) $args['limit'];
		$offset = (int) $args['offset'];

		if ( $limit || $offset ) {

			$limit_clause = " LIMIT ";

			if ( $limit && $offset ) {

				$limit_clause .= '%d,%d';

				$values[] = $offset;
				$values[] = $limit;

			} else if ( $limit ) {

				$limit_clause .= '%d';

				$values[] = $limit;

			} else if ( $offset ) {

				$limit_clause .= '%d,999999999';

				$values[] = $offset;
			}

			$query .= $limit_clause;
		}

		$items = $wpdb->get_results( $wpdb->prepare( $query, $values ) );

		/**
		 * Filter the retrieved items for a recipe.
		 *
		 * @since 1.3.0
		 *
		 * @param array $items     The recipe items.
		 * @param int   $recipe_id The recipe ID.
		 * @param array $args      The passed query arguments.
		 */
		$items = apply_filters( 'simmer_get_recipe_items', $items, $recipe_id, $args );

		return $items;
	}

	/**
	 * Get an item by its ID.
	 *
	 * @since 1.3.0
	 *
	 * @global object $wpdb The WordPress database class.
	 *
	 * @param  int         $item_id The ID of the desired item.
	 * @return object|bool $item    The requested item or false on failure.
	 */
	public function get_item( $item_id ) {

		global $wpdb;

		$item_id = (int) $item_id;

		$query = "
			SELECT *
			FROM {$wpdb->prefix}simmer_recipe_items
			WHERE recipe_item_id = %d
		";

		$values = array(
			$item_id,
		);

		$item = $wpdb->get_row( $wpdb->prepare( $query, $values ) );

		if ( is_null( $item ) ) {
			$item = false;
		}

		$item = apply_filters( 'simmer_get_recipe_item', $item );

		return $item;
	}

	/**
	 * Add a new item to the database.
	 *
	 * @since 1.3.0
	 *
	 * @global object $wpdb The WordPress database class.
	 *
	 * @param  int      $recipe_id The ID of the recipe for which the item will be added.
	 * @param  string   $type      The item type.
	 * @param  int      $order     Optional. The item order number. Default: 0.
	 * @return int|bool $result    The ID of the newly added item or false on failure.
	 */
	public function add_item( $recipe_id, $type, $order = 0 ) {

		global $wpdb;

		$recipe_id = (int) $recipe_id;

		if ( ! $recipe_id ) {
			return false;
		}

		$result = $wpdb->insert(
			$wpdb->prefix . 'simmer_recipe_items',
			array(
				'recipe_item_type'  => esc_attr( $type ),
				'recipe_id'         => $recipe_id,
				'recipe_item_order' => (int) $order,
			),
			array(
				'%s',
				'%d',
				'%d',
			)
		);

		// If successful, set to return the new item ID.
		if ( $result ) {
			$result = $wpdb->insert_id;
		}

		return $result;
	}

	/**
	 * Update an existing item.
	 *
	 * @since 1.3.0
	 *
	 * @global object $wpdb The WordPress database class.
	 *
	 * @param int $item_id The ID of the item to update.
	 * @param array $args {
	 *     The values to update. All values default to their existing database values.
	 *
	 *     @type string $recipe_item_type  The item type.
	 *     @type int    $recipe_id         The ID of the associated recipe.
	 *     @type int    $recipe_item_order The item's order.
	 * }
	 * @return bool Whether the item was updated.
	 */
	public function update_item( $item_id, $args ) {

		global $wpdb;

		$existing_item = $this->get_item( $item_id );

		if ( ! $existing_item ) {
			return false;
		}

		$existing_item = (array) $existing_item;

		unset( $existing_item['recipe_item_id'] );

		// Check for invalid keys and remove them from the args.
		if ( $invalid_keys = array_diff_key( $args, $existing_item ) ) {

			foreach ( $invalid_keys as $key => $value ) {
				unset( $args[ $key ] );
			}
		}

		$updated_item = wp_parse_args( $args, $existing_item );

		$result = $wpdb->update(
			$wpdb->prefix . 'simmer_recipe_items',
			$updated_item,
			array(
				'recipe_item_id' => $item_id,
			),
			array(
				'%s',
				'%d',
				'%d',
			),
			array(
				'%d',
			)
		);

		// If successful, set to return the new item ID.
		if ( $result ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Delete an existing item from the database.
	 *
	 * @since 1.3.0
	 *
	 * @global object $wpdb The WordPress database class.
	 *
	 * @param  int  $item_id The ID of the item to delete.
	 * @return bool $result  Whether the item was deleted.
	 */
	public function delete_item( $item_id ) {

		global $wpdb;

		$item = $this->get_item( $item_id );

		$result = false;

		if ( $item ) {

			$result = $wpdb->delete(
				$wpdb->prefix . 'simmer_recipe_items',
				array(
					'recipe_item_id' => $item->recipe_item_id,
				),
				array(
					'%d',
				)
			);

			// If the item was deleted, delete its metadata too.
			if ( $result ) {

				$wpdb->delete(
					$wpdb->prefix . 'simmer_recipe_itemmeta',
					array(
						'recipe_item_id' => $item->recipe_item_id,
					),
					array(
						'%d',
					)
				);
			}
		}

		return $result;
	}
}
