<?php
/**
 * The instructions meta box HTML.
 *
 * @package Simmer\Instructions
 */
?>

<?php wp_nonce_field( 'simmer_save_recipe_meta', 'simmer_nonce' ); ?>

<table width="100%" cellspacing="5" class="simmer-list-table instructions">

	<thead>
		<tr>
			<th class="simmer-sort">
				<span class="hide-if-js">Order</span>
				<div class="dashicons dashicons-sort hide-if-no-js"></div>
			</th>
			<th><?php _e( 'Description', 'simmer' ); ?></th>
			<th></th>
		</tr>
	</thead>

	<tbody>

		<tr class="simmer-heading simmer-row-hidden simmer-row">
			<td class="simmer-sort">
				<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_instructions[0][order]" value="0" />
				<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
			</td>
			<td class="simmer-desc">
				<input type="text" name="simmer_instructions[0][description]" value="" /> <span class="simmer-heading-label"><?php _e( 'Heading', 'simmer' ); ?></span>
				<input class="simmer-heading-input" type="hidden" name="simmer_instructions[0][heading]" value="true" />
			</td>
			<td class="simmer-remove">
				<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="heading" title="Remove"></a>
			</td>
		</tr>

		<?php $instructions = simmer_get_the_instructions(); ?>

		<?php if ( ! empty( $instructions ) ) : ?>

			<?php foreach ( $instructions as $key => $instruction ) : ?>

				<?php if ( $instruction->is_heading() ) : ?>

					<tr class="simmer-heading simmer-row">
						<td class="simmer-sort">
							<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_instructions[<?php echo absint( $key ); ?>][order]" value="<?php echo absint( $instruction->order ); ?>" />
							<input class="simmer-id" name="simmer_instructions[<?php echo absint( $key ); ?>][id]" type="hidden" value="<?php echo absint( $instruction->id ); ?>" />
							<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
						</td>
						<td class="simmer-desc">
							<input type="text" name="simmer_instructions[<?php echo absint( $key ); ?>][description]" value="<?php echo esc_html( $instruction->description ); ?>" /> <span class="simmer-heading-label"><?php _e( 'Heading', 'simmer' ); ?></span>
							<input class="simmer-heading-input" type="hidden" name="simmer_instructions[<?php echo absint( $key ); ?>][heading]" value="1" />
						</td>
						<td class="simmer-remove">
							<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="heading" title="Remove"></a>
						</td>
					</tr>

				<?php else : ?>

					<tr class="simmer-instruction simmer-row">

						<td class="simmer-sort">
							<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_instructions[<?php echo absint( $key ); ?>][order]" value="<?php echo absint( $instruction->order ); ?>" />
							<input class="simmer-id" name="simmer_instructions[<?php echo absint( $key ); ?>][id]" type="hidden" value="<?php echo absint( $instruction->id ); ?>" />
							<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
						</td>
						<td class="simmer-desc">
							<textarea style="width:100%;" name="simmer_instructions[<?php echo absint( $key ); ?>][description]" placeholder="Preheat oven to 450 degrees. In a large bowl, mix stuff."><?php echo esc_textarea( $instruction->description ); ?></textarea>
							<input type="hidden" name="simmer_instructions[<?php echo absint( $key ); ?>][heading]" value="0" />
						</td>
						<td class="simmer-remove">
							<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="instruction" title="Remove"></a>
						</td>

					</tr>

				<?php endif; ?>

			<?php endforeach; ?>

		<?php else : ?>

			<tr class="simmer-instruction simmer-row">

				<td class="simmer-sort">
					<input class="simmer-order hide-if-js" style="width:100%;" type="text" name="simmer_instructions[0][order]" value="0" />
					<span class="simmer-sort-handle dashicons dashicons-menu hide-if-no-js"></span>
				</td>
				<td class="simmer-desc">
					<textarea style="width:100%;" name="simmer_instructions[0][description]" placeholder="Preheat oven to 450 degrees. In a large bowl, mix stuff."></textarea>
					<input type="hidden" name="simmer_instructions[0][heading]" value="0" />
				</td>
				<td class="simmer-remove">
					<a href="#" class="simmer-remove-row dashicons dashicons-no" data-type="instruction" title="Remove"></a>
				</td>

			</tr>

		<?php endif; ?>

	</tbody>

	<tfoot class="hide-if-no-js">
		<tr class="simmer-actions">
			<td colspan="5">

				<a class="simmer-bulk-add-link hide-if-no-js" href="#" data-type="instruction"><?php _e( '+ Add in Bulk', 'simmer' ); ?></a>

				<a class="simmer-add-row button" data-type="instruction" href="#">
					<span class="dashicons dashicons-plus"></span>
					<?php _e( 'Add an Instruction', 'simmer' ); ?>
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
				do_action( 'simmer_instructions_admin_actions' ); ?>

			</td>
		</tr>
	</tfoot>

</table>
