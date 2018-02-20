<?php
/**
 * Display the shortcode modal
 *
 * @since 1.2.0
 *
 * @package Simmer\Admin
 */
?>

<div class="simmer-shortcode-modal-background"></div>
<div class="simmer-shortcode-modal-wrap">

	<form id="simmer-shortcode-add-form" tabindex="-1">

		<div class="simmer-shortcode-modal-header">
			<span class="simmer-shortcode-modal-title"><?php echo _e( 'Insert Recipe', 'simmer' ); ?></span>
			<button class="simmer-shortcode-modal-close">
				<span class="screen-reader-text"><?php _e( 'Close', 'simmer' ); ?></span>
			</button>
		</div>

		<div class="simmer-shortcode-modal-content">

			<p class="simmer-shortcode-help"><?php _e( 'Select or search for a recipe name.', 'simmer' ); ?></p>

			<?php $recipes = get_posts( array(
				'post_type'              => simmer_get_object_type(),
				'posts_per_page'         => -1,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			) ); ?>

			<?php if ( $recipes ) : ?>

				<select id="simmer_shortcode_recipe" name="simmer_shortcode_recipe" style="width: 100%">

					<option value="0"><?php _e( 'Choose a recipe...', 'simmer' ); ?></option>

					<?php foreach ( $recipes as $recipe ) : ?>

						<option value="<?php echo esc_attr( $recipe->ID ); ?>"><?php echo esc_html( $recipe->post_title ); ?></option>

					<?php endforeach; ?>

				</select>

			<?php endif; ?>

		</div>

		<div class="simmer-shortcode-modal-footer submitbox">
			<div class="cancel">
				<a class="submitdelete" href="#"><?php _e( 'Cancel', 'simmer' ); ?></a>
			</div>
			<div class="simmer-submit-shortcode">
				<span class="spinner"></span>
				<button class="button button-primary" disabled="disabled"><?php _e( 'Insert into post', 'simmer' ); ?></button>
			</div>
		</div>

	</form>

</div>
