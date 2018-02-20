<?php
/**
 * The ingredients meta box HTML.
 *
 * @since 1.0.0
 *
 * @package Simmer\Ingredients
 */
?>

<?php wp_nonce_field( 'simmer_save_recipe_meta', 'simmer_nonce' ); ?>

<table width="100%" cellspacing="5" class="simmer-list-table ingredients">

	<thead>
		<tr>
			<th class="simmer-sort">
				<span class="hide-if-js">Order</span>
				<div class="dashicons dashicons-sort hide-if-no-js"></div>
			</th>
			<th><?php _e( 'Amount', 'simmer' ); ?></th>
			<th><?php _e( 'Unit', 'simmer' ); ?></th>
			<th><?php _e( 'Description', 'simmer' ); ?></th>
			<th></th>
		</tr>
	</thead>

	<tbody>

		<tr class="simmer-heading simmer-row-hidden simmer-row">
			<td class="simmer-sort">
				<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_ingredients[0][order]" value="0" />
				<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
			</td>
			<td class="simmer-desc" colspan="3">
				<input type="text" name="simmer_ingredients[0][description]" value="" /> <span class="simmer-heading-label"><?php _e( 'Heading', 'simmer' ); ?></span>
				<input class="simmer-heading-input" type="hidden" name="simmer_ingredients[0][heading]" value="true" />
			</td>
			<td class="simmer-remove">
				<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="heading" title="Remove"></a>
			</td>
		</tr>

		<?php // Get the recipe's ingredients.
		$ingredients = simmer_get_the_ingredients(); ?>

		<?php if ( ! empty( $ingredients ) ) : ?>

			<?php foreach ( $ingredients as $key => $ingredient ) : ?>

				<?php if ( $ingredient->is_heading() ) : ?>

					<tr class="simmer-heading simmer-row">
						<td class="simmer-sort">
							<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_ingredients[<?php echo absint( $key ); ?>][order]" value="<?php echo absint( $ingredient->order ); ?>" />
							<input class="simmer-id" name="simmer_ingredients[<?php echo absint( $key ); ?>][id]" type="hidden" value="<?php echo absint( $ingredient->id ); ?>" />
							<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
						</td>
						<td class="simmer-desc" colspan="3">
							<input type="text" name="simmer_ingredients[<?php echo absint( $key ); ?>][description]" value="<?php echo esc_html( $ingredient->description ); ?>" /> <span class="simmer-heading-label"><?php _e( 'Heading', 'simmer' ); ?></span>
							<input class="simmer-heading-input" type="hidden" name="simmer_ingredients[<?php echo absint( $key ); ?>][heading]" value="1" />
						</td>
						<td class="simmer-remove">
							<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="heading" title="Remove"></a>
						</td>
					</tr>

				<?php else : ?>

					<tr class="simmer-ingredient simmer-row">

						<td class="simmer-sort">
							<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_ingredients[<?php echo absint( $key ); ?>][order]" value="<?php echo absint( $ingredient->order ); ?>" />
							<input class="simmer-id" name="simmer_ingredients[<?php echo absint( $key ); ?>][id]" type="hidden" value="<?php echo absint( $ingredient->id ); ?>" />
							<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
						</td>
						<td class="simmer-amt">

							<?php // Filter whether ingredient amounts should be raw (floats) in the admin.
							$amount = ( apply_filters( 'simmer_admin_raw_ingredient_amounts', false ) ) ? $ingredient->amount : $ingredient->convert_amount_to_string( $ingredient->amount ); ?>

							<input type="text" style="width:100%;" name="simmer_ingredients[<?php echo absint( $key ); ?>][amount]" value="<?php echo esc_html( $amount ); ?>" placeholder="2" />
						</td>
						<td class="simmer-unit">
							<?php simmer_units_select_field( array(
								'name'     => 'simmer_ingredients[' . absint( $key ) . '][unit]',
								'selected' => $ingredient->unit,
							), $ingredient->amount ); ?>
						</td>
						<td class="simmer-desc">
							<input type="text" style="width:100%;" name="simmer_ingredients[<?php echo absint( $key ); ?>][description]" value="<?php echo esc_html( $ingredient->description ); ?>" placeholder="onions, diced" />
							<input class="simmer-heading-input" type="hidden" name="simmer_ingredients[<?php echo absint( $key ); ?>][heading]" value="0" />
						</td>
						<td class="simmer-remove">
							<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="ingredient" title="Remove"></a>
						</td>

					</tr>

				<?php endif; ?>

			<?php endforeach; ?>

		<?php else : ?>

			<tr class="simmer-ingredient simmer-row">

				<td class="simmer-sort">
					<input class="hide-if-js" style="width:100%;" type="text" name="simmer_ingredients[0][order]" value="0" />
					<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
				</td>
				<td class="simmer-amt">
					<input type="text" style="width:100%;" name="simmer_ingredients[0][amount]" value="" placeholder="2" />
				</td>
				<td class="simmer-unit">
					<?php simmer_units_select_field( array(
						'name' => 'simmer_ingredients[0][unit]',
					) ); ?>
				</td>
				<td class="simmer-desc">
					<input type="text" style="width:100%;" name="simmer_ingredients[0][description]" value="" placeholder="onions, diced" />
					<input class="simmer-heading-input" type="hidden" name="simmer_ingredients[0][heading]" value="0" />
				</td>
				<td class="simmer-remove">
					<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="ingredient" title="Remove"></a>
				</td>

			</tr>

		<?php endif; ?>

	</tbody>

	<tfoot class="hide-if-no-js">
		<tr class="simmer-actions">
			<td colspan="5">

				<a class="simmer-bulk-add-link hide-if-no-js" href="#" data-type="ingredient"><?php _e( '+ Add in Bulk', 'simmer' ); ?></a>

				<a class="simmer-add-row button" data-type="ingredient" href="#">
					<span class="dashicons dashicons-plus"></span>
					<?php _e( 'Add an Ingredient', 'simmer' ); ?>
				</a>
				<a class="simmer-add-row button" data-type="heading" href="#">
					<span class="dashicons dashicons-plus"></span>
					<?php _e( 'Add a Heading', 'simmer' ); ?>
				</a>

				<?php /**
				* Execute after the core action buttons have been rendered.
				*
				* @since 1.2.0
				*/
				do_action( 'simmer_ingredients_admin_actions' ); ?>

			</td>
		</tr>
	</tfoot>

</table>
