<?php
/**
 * Define the recipe item meta class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items
 */

/**
 * Handles the recipe item meta
 *
 * @since 1.3.0
 */
final class Simmer_Recipe_Item_Meta {

	/**
	 * The database meta type to use when getting meta values.
	 *
	 * @since 1.3.0
	 *
	 * @var string $meta_type.
	 */
	public $meta_type;

	/**
	 * Construct the class.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->meta_type = 'recipe_item';
	}

	/**
	 * Add the custom table names to the database object.
	 *
	 * This is necessary for the *_item_meta methods to work with
	 * WordPress' built-in *_metadata core functions.
	 *
	 * @since 1.3.0
	 *
	 * @global object $wpdb The WordPress database class.
	 */
	public static function add_meta_table_names() {

		global $wpdb;

		$table_name = 'simmer_recipe_itemmeta';

		$wpdb->recipe_itemmeta = $wpdb->prefix . $table_name;

		$wpdb->tables[] = $table_name;
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
	public function get_item_meta( $item_id, $meta_key = '', $single = false ) {

		$metadata = get_metadata( $this->meta_type, $item_id, $meta_key, $single );

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
	public function add_item_meta( $item_id, $meta_key, $meta_value, $unique = false ) {

		$result = add_metadata( $this->meta_type, $item_id, $meta_key, $meta_value, $unique );

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
	public function update_item_meta( $item_id, $meta_key, $meta_value, $prev_value = '' ) {

		$result = update_metadata( $this->meta_type, $item_id, $meta_key, $meta_value, $prev_value );

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
	public function delete_item_meta( $item_id, $meta_key, $meta_value = '' ) {

		$result = delete_metadata( $this->meta_type, $item_id, $meta_key, $meta_value );

		return $result;
	}
}
