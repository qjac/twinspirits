<?php
/**
 * Define the support items functions
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items
 */

/**
 * Get items for a specific recipe.
 *
 * @since 1.3.0
 *
 * @param int   $recipe_id The recipe for which to get the items.
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
function simmer_get_recipe_items( $recipe_id, $args = array() ) {

	$items_api = new Simmer_Recipe_Items;

	$items = $items_api->get_items( $recipe_id, $args );

	return $items;
}

/**
 * Get a recipe item by its ID.
 *
 * @since 1.3.0
 *
 * @param  int         $item_id The ID of the desired item.
 * @return object|bool $item    The requested item or false on failure.
 */
function simmer_get_recipe_item( $item_id ) {

	$items_api = new Simmer_Recipe_Items;

	$item = $items_api->get_item( $item_id );

	return $item;
}

/**
 * Add a recipe item to the database.
 *
 * @since 1.3.0
 *
 * @param  int      $recipe_id The ID of the recipe for which the item will be added.
 * @param  string   $type      The item type.
 * @param  int      $order     Optional. The item order number. Default: 0.
 * @return int|bool $result    The ID of the newly added item or false on failure.
 */
function simmer_add_recipe_item( $recipe_id, $type, $order = 0 ) {

	$items_api = new Simmer_Recipe_Items;

	$item_id = $items_api->add_item( $recipe_id, $type, $order );

	return $item_id;
}

/**
 * Update an existing recipe item.
 *
 * @since 1.3.0
 *
 * @param int $item_id The ID of the item to update.
 * @param array $args {
 *     The values to update. All values default to their existing database values.
 *
 *     @type string $recipe_item_type  The item type.
 *     @type int    $recipe_id         The ID of the associated recipe.
 *     @type int    $recipe_item_order The item's order.
 * }
 * @return bool $result Whether the item was updated.
 */
function simmer_update_recipe_item( $item_id, $args ) {

	$items_api = new Simmer_Recipe_Items;

	$result = $items_api->update_item( $item_id, $args );

	return $result;
}

/**
 * Delete an existing recipe item from the database.
 *
 * @since 1.3.0
 *
 * @param  int  $item_id The ID of the item to delete.
 * @return bool $result  Whether the item was deleted.
 */
function simmer_delete_recipe_item( $item_id ) {

	$items_api = new Simmer_Recipe_Items;

	$result = $items_api->delete_item( $item_id );

	return $result;
}

/**
 * Get metadata for an item.
 *
 * @since 1.3.0
 *
 * @param  int                $item_id  The item ID.
 * @param  string             $meta_key Optional. The key of the metadata to retrieve. If left blank,
 *                                      all of the item's metadata will be returned in an array.
 * @param  bool               $single   Optional. Whether to return a single value or array of values. Default false.
 * @return array|string|false $metadata Array of metadata, a single metadata value, or false on failure.
 */
function simmer_get_recipe_item_meta( $item_id, $meta_key = '', $single = false ) {

	$item_meta_api = new Simmer_Recipe_Item_Meta;

	$metadata = $item_meta_api->get_item_meta( $item_id, $meta_key, $single );

	return $metadata;
}

/**
 * Add metadata to an item.
 *
 * @since 1.3.0
 *
 * @param  int      $item_id    The item ID.
 * @param  string   $meta_key   The meta key to add.
 * @param  string   $meta_value The meta value to add.
 * @param  bool     $unique     Optional. Whether the key should stay unique. When set to true,
 *                              the custom field will not be added if the given key already exists
 *                              among custom fields of the specified item.
 * @return int|bool $result     The new metadata's ID on success or false on failure.
 */
function simmer_add_recipe_item_meta( $item_id, $meta_key, $meta_value, $unique = false ) {

	$item_meta_api = new Simmer_Recipe_Item_Meta;

	$result = $item_meta_api->add_item_meta( $item_id, $meta_key, $meta_value, $unique );

	return $result;
}

/**
 * Update an item's metadata.
 *
 * @since 1.3.0
 *
 * @param  int      $item_id    The item ID.
 * @param  string   $meta_key   The key of the metadata to update.
 * @param  string   $meta_value The new metadata value.
 * @param  string   $prev_value Optional. The old value of the custom field you wish to change.
 *                              This is to differentiate between several fields with the same key.
 *                              If omitted, and there are multiple rows for this post and meta key,
 *                              all meta values will be updated.
 * @return int|bool $result     True on success or false on failure. If the metadata being updated
 *                              doesn't yet exist, it will be created and the new metadata's ID will
 *                              be returned. If the specified value already exists, then nothing will
 *                              be updated and false will be returned.
 */
function simmer_update_recipe_item_meta( $item_id, $meta_key, $meta_value, $prev_value = '' ) {

	$item_meta_api = new Simmer_Recipe_Item_Meta;

	$result = $item_meta_api->update_item_meta( $item_id, $meta_key, $meta_value, $prev_value );

	return $result;
}

/**
 * Delete metadata from an item.
 *
 * @since 1.3.0
 *
 * @param  int      $item_id    The item ID.
 * @param  string   $meta_key   The key of the metadata to delete.
 * @param  string   $meta_value Optional. The value of the metadata you wish to delete. This is used
 *                              to differentiate between several fields with the same key. If left blank,
 *                              all fields with the given key will be deleted.
 * @return bool     $result     True on success, false on failure.
 */
function simmer_delete_recipe_item_meta( $item_id, $meta_key, $meta_value = '' ) {

	$item_meta_api = new Simmer_Recipe_Item_Meta;

	$result = $item_meta_api->delete_item_meta( $item_id, $meta_key, $meta_value );

	return $result;
}
