<?php
/**
 * Define the plugin upgrade class
 *
 * @since 1.3.0
 *
 * @package Simmer/Upgrade
 */

/**
 * Handles upgrading from various legacy plugin versions.
 *
 * @since 1.3.0
 */
final class Simmer_Upgrade {

	/**
	 * Upgrade Simmer from 1.2.X to 1.3.X.
	 *
	 * @since 1.3.0
	 */
	public function from_1_2() {

		global $wpdb;

		// Get all recipe IDs.
		$recipe_ids = get_posts( array(
			'post_type'   => 'recipe',
			'post_status' => 'any',
			'numberposts' => -1,
			'fields'      => 'ids',
		) );

		if ( $recipe_ids ) {

			$items_api = new Simmer_Recipe_Items;

			foreach ( $recipe_ids as $recipe_id ) {

				$ingredients = get_post_meta( $recipe_id, '_recipe_ingredients', true );

				if ( $ingredients ) {

					$order = 0;

					foreach ( $ingredients as $ingredient ) {

						$item_id = $items_api->add_item( $recipe_id, 'ingredient', $order );

						if ( isset( $ingredient['amt'] ) ) {

							$wpdb->insert(
								$wpdb->prefix . 'simmer_recipe_itemmeta',
								array(
									'recipe_item_id' => $item_id,
									'meta_key'       => 'amount',
									'meta_value'     => $ingredient['amt'],
								),
								array(
									'%d',
									'%s',
									'%s',
								)
							);
						}

						if ( isset( $ingredient['unit'] ) ) {

							$wpdb->insert(
								$wpdb->prefix . 'simmer_recipe_itemmeta',
								array(
									'recipe_item_id' => $item_id,
									'meta_key'       => 'unit',
									'meta_value'     => $ingredient['unit'],
								),
								array(
									'%d',
									'%s',
									'%s',
								)
							);
						}

						if ( isset( $ingredient['desc'] ) ) {

							$wpdb->insert(
								$wpdb->prefix . 'simmer_recipe_itemmeta',
								array(
									'recipe_item_id' => $item_id,
									'meta_key'       => 'description',
									'meta_value'     => $ingredient['desc'],
								),
								array(
									'%d',
									'%s',
									'%s',
								)
							);
						}

						$order++;
					}
				}

				$instructions = get_post_meta( $recipe_id, '_recipe_instructions', true );

				if ( $instructions ) {

					$order = 0;

					foreach ( $instructions as $instruction ) {

						$item_id = $items_api->add_item( $recipe_id, 'instruction', $order );

						if ( isset( $instruction['desc'] ) ) {

							$wpdb->insert(
								$wpdb->prefix . 'simmer_recipe_itemmeta',
								array(
									'recipe_item_id' => $item_id,
									'meta_key'       => 'description',
									'meta_value'     => $instruction['desc'],
								),
								array(
									'%d',
									'%s',
									'%s',
								)
							);
						}

						if ( isset( $instruction['heading'] ) && 1 == $instruction['heading'] ) {

							$wpdb->insert(
								$wpdb->prefix . 'simmer_recipe_itemmeta',
								array(
									'recipe_item_id' => $item_id,
									'meta_key'       => 'is_heading',
									'meta_value'     => $instruction['heading'],
								),
								array(
									'%d',
									'%s',
									'%d',
								)
							);
						}

						$order++;
					}
				}
			}

		}
	}
}
