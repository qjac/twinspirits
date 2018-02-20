<div class="simmer-recipe-meta">

	<meta itemprop="datePublished" content="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
	<meta itemprop="url" content="<?php the_permalink(); ?>">

	<p class="simmer-recipe-byline">
		<?php printf(
			__( 'Created by %1s on %2s', 'simmer' ),
			'<span itemprop="author">' . esc_html( get_the_author() ) . '</span>',
			get_the_date()
		); ?>
	</p>

</div><!-- .simmer-recipe-date -->

<div class="simmer-recipe-details">

	<ul class="simmer-recipe-timing">

		<?php if ( $prep_time = simmer_get_the_prep_time() ) : ?>

			<li>
				<strong><?php _e( 'Prep Time', 'simmer' ); ?>:</strong>
				<meta itemprop="prepTime" content="<?php echo esc_attr( simmer_get_the_prep_time( get_the_ID(), 'machine' ) ); ?>"><?php echo esc_html( $prep_time ); ?>
			</li>

		<?php endif; ?>

		<?php if ( $cook_time = simmer_get_the_cook_time() ) : ?>

			<li>
				<strong><?php _e( 'Cook Time', 'simmer' ); ?>:</strong>
				<meta itemprop="cookTime" content="<?php echo esc_attr( simmer_get_the_cook_time( get_the_ID(), 'machine' ) ); ?>"><?php echo esc_html( $cook_time ); ?>
			</li>

		<?php endif; ?>

		<?php if ( $total_time = simmer_get_the_total_time() ) : ?>

			<li>
				<strong><?php _e( 'Total Time', 'simmer' ); ?>:</strong>
				<meta itemprop="totalTime" content="<?php echo esc_attr( simmer_get_the_total_time( get_the_ID(), 'machine' ) ); ?>"><?php echo esc_html( $total_time ); ?>
			</li>

		<?php endif; ?>

	</ul><!-- .simmer-recipe-timing -->

	<ul class="simmer-recipe-extras">

		<?php $servings = simmer_get_the_servings(); ?>
		<?php $servings_label = simmer_get_the_servings_label(); ?>

		<?php if ( $servings || $servings_label ) : ?>

			<li>
				<strong><?php _e( 'Serves', 'simmer' ); ?>:</strong>
				<span itemprop="recipeYield">
					<?php echo esc_html( $servings ); ?>
					<?php echo esc_html( $servings_label ); ?>
				</span>
			</li>

		<?php endif; ?>

		<?php if ( $yield = simmer_get_the_yield() ) : ?>

			<li>
				<strong><?php _e( 'Yield', 'simmer' ); ?>:</strong>
				<span itemprop="recipeYield"><?php echo esc_html( $yield ); ?></span>
			</li>

		<?php endif; ?>

		<?php if ( $categories = get_the_term_list( get_the_ID(), simmer_get_category_taxonomy(), '', ', ' ) ) : ?>

			<li>
				<strong><?php _e( 'Category', 'simmer' ); ?>:</strong>
				<span itemprop="recipeCategory"><?php echo $categories; ?></span>
			</li>

		<?php endif; ?>

	</ul><!-- .simmer-recipe-extras -->

</div><!-- .simmer-recipe-details -->

<div class="simmer-recipe-ingredients">
	<?php simmer_list_ingredients(); ?>
</div><!-- .simmer-recipe-ingredients -->

<div class="simmer-recipe-instructions">
	<?php simmer_list_instructions(); ?>
</div><!-- .simmer-recipe-instructions -->

<div class="simmer-recipe-footer">

	<div class="simmer-recipe-source">
		<?php simmer_the_source(); ?>
	</div>

	<div class="simmer-recipe-tools">

		<ul>
			<li class="simmer-recipe-print simmer-icon-print">
				<a href="#"><?php _e( 'Print', 'simmer' ); ?></a>
			</li>

			<?php /**
			 * Trigger to add more tools to the recipe footer.
			 *
			 * @since 1.2.1
			 *
			 * @param int $recipe_id The current recipe ID.
			 */
			do_action( 'simmer_recipe_tools', get_the_ID() );

			/**
			 * Trigger to add more tools to the single recipe footer.
			 *
			 * @since 1.2.1
			 *
			 * @param int $recipe_id The current recipe ID.
			 */
			do_action( 'simmer_recipe_tools_single', get_the_ID() ); ?>

		</ul>

	</div><!-- .simmer-recipe-tools -->

</div><!-- .simmer-recipe-footer -->
