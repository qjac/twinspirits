<?php
/**
 * Define the recipe template functions
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes
 */

/**** Durations ****/

/**
 * Print the prep time of the current recipe in human-readable format.
 *
 * @since 1.0.0
 *
 * @return void
 */
function simmer_the_prep_time() {

	echo esc_html( simmer_get_the_prep_time() );
}

/**
 * Get the prep time for a recipe.
 *
 * @since 1.0.0
 *
 * @param  int    $recipe_id Optional. A recipe's ID.
 * @param  string $format    Optional. The duration format to return. Specify 'machine'
	 *                       for microdata-friendly format. Default: 'human'.
 * @return string $time      The recipe prep time.
 */
function simmer_get_the_prep_time( $recipe_id = null, $format = 'human' ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$recipe = simmer_get_recipe( $recipe_id );

	$prep_time = $recipe->get_prep_time( $format );

	return $prep_time;
}

/**
 * Print the total cook of the current recipe in human-readable format.
 *
 * @since 1.0.0
 *
 * @return void
 */
function simmer_the_cook_time() {

	echo esc_html( simmer_get_the_cook_time() );
}

/**
 * Get the cook time for a recipe.
 *
 * @since 1.0.0
 *
 * @param  int    $recipe_id Optional. A recipe's ID.
 * @param  string $format    Optional. The duration format to return. Specify 'machine'
	 *                       for microdata-friendly format. Default: 'human'.
 * @return string $time      The recipe cook time.
 */
function simmer_get_the_cook_time( $recipe_id = null, $format = 'human' ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$recipe = simmer_get_recipe( $recipe_id );

	$cook_time = $recipe->get_cook_time( $format );

	return $cook_time;
}

/**
 * Print the total time of the current recipe in human-readable format.
 *
 * @since 1.0.0
 *
 * @return void
 */
function simmer_the_total_time() {

	echo esc_html( simmer_get_the_total_time() );
}

/**
 * Get the total time for a recipe.
 *
 * @since 1.0.0
 *
 * @param  int    $recipe_id Optional. A recipe's ID.
 * @param  string $format    Optional. The duration format to return. Specify 'machine'
	 *                       for microdata-friendly format. Default: 'human'.
 * @return string $time      The recipe total time.
 */
function simmer_get_the_total_time( $recipe_id = null, $format = 'human' ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$recipe = simmer_get_recipe( $recipe_id );

	$total_time = $recipe->get_total_time( $format );

	return $total_time;
}


/**** General Recipe Information ****/

/**
 * Print the current recipe's servings.
 *
 * @since 1.0.0
 * @see simmer_get_the_servings()
 *
 * @param string $before Optional. To print before the servings string.
 * @param string $after  Optional. To print after the servings string.
 * @return void
 */
function simmer_the_servings( $before = '', $after = '' ) {

	$servings = simmer_get_the_servings();
	$servings_label = simmer_get_the_servings_label();

	if ( $servings || $servings_label ) {
		echo $before;
	}

	if ( $servings ) {
		echo esc_html( $servings );
	}

	if ( $servings_label ) {
		echo esc_html( $servings_label );
	}

	if ( $servings || $servings_label ) {
		echo $after;
	}
}

/**
 * Get the recipe's servings.
 *
 * @since 1.0.0
 *
 * @param  int    $recipe_id Optional. A recipe's ID.
 * @return string $servings  The recipe's servings
 */
function simmer_get_the_servings( $recipe_id = null ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$servings = get_post_meta( $recipe_id, '_recipe_servings', true );

	if ( ! is_numeric( $servings ) ) {
		$servings = false;
	}

	/**
	 * Filter the returned servings value.
	 *
	 * @since 1.0.0
	 *
	 * @param string|bool $servings  The returned servings or '' if none set.
	 * @param int         $recipe_id The recipe's ID.
	 */
	$servings = apply_filters( 'simmer_get_the_servings', $servings, $recipe_id );

	return $servings;
}

/**
 * Get the recipe's servings label.
 *
 * @since 1.3.3
 *
 * @param  int    $recipe_id      Optional. A recipe's ID.
 * @return string $servings_label The recipe's servings label
 */
function simmer_get_the_servings_label( $recipe_id = null ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$servings_label = get_post_meta( $recipe_id, '_recipe_servings_label', true );

	/**
	 * Filter the returned servings label value.
	 *
	 * @since 1.3.3
	 *
	 * @param string|bool $servings_label The returned servings label or '' if none set.
	 * @param int         $recipe_id      The recipe's ID.
	 */
	$servings_label = apply_filters( 'simmer_get_the_servings_label', $servings_label, $recipe_id );

	return $servings_label;
}

/**
 * Print the current recipe's yield.
 *
 * @since 1.0.0
 * @see simmer_get_the_yield()
 *
 * @param string $before Optional. To print before the yield string.
 * @param string $after  Optional. To print after the yield string.
 * @return void
 */
function simmer_the_yield( $before = '', $after = '' ) {

	if ( $yield = simmer_get_the_yield() ) {
		echo $before . esc_html( $yield ) . $after;
	}
}

/**
 * Get the recipe's yield.
 *
 * @since 1.0.0
 *
 * @param  int    $recipe_id Optional. A recipe's ID.
 * @return string $yield     The recipe's yield
 */
function simmer_get_the_yield( $recipe_id = null ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$yield = get_post_meta( $recipe_id, '_recipe_yield', true );

	/**
	 * Allow others to modify the returned yield value.
	 *
	 * @since 1.0.0
	 *
	 * @param string|bool $yield     The returned yield or false if none set.
	 * @param int         $recipe_id The recipe's ID.
	 */
	$yield = apply_filters( 'simmer_get_the_yield', $yield, $recipe_id );

	return $yield;
}

/**
 * Print the current recipe's source information.
 *
 * @since 1.1.0
 * @see simmer_get_the_source()
 *
 * @return void
 */
function simmer_the_source() {

	echo simmer_get_the_source();
}

/**
 * Get a recipe's source information.
 *
 * @since 1.1.0
 *
 * @param  int    $recipe_id Optional. A recipe's ID. Defaults to current.
 * @param  string $label     Optional. A label to place before the source info.
 * @param  bool   $anchor    Optional. Whether to wrap the source in an anchor.
 * @return string $source    The recipe's source.
 */
function simmer_get_the_source( $recipe_id = null, $label = null, $anchor = true ) {

	if ( ! is_numeric( $recipe_id ) ) {
		$recipe_id = get_the_ID();
	}

	$text = simmer_get_source_text( $recipe_id );
	$url  = simmer_get_source_url(  $recipe_id );

	// If no text is set, use the URL.
	if ( ! $text ) {

		// If there is no URL AND no text, bail.
		if ( $url ) {
			$text = $url;
		} else {
			return false;
		}
	}

	$source = $text;

	if ( $anchor && $url ) {
		$source = '<a class="simmer-recipe-source-link" href="' . esc_url( $url ) . '" target="_blank">';
			$source .= esc_html( $text );
		$source .= '</a>';
	}

	/**
	 * Filter the source anchor.
	 *
	 * @since 1.1.0
	 *
	 * @param string|bool $source    The source.
	 * @param int         $recipe_id The recipe's ID.
	 */
	$source = apply_filters( 'simmer_get_the_source', $source, $recipe_id );

	// If no label is specifically set via this function, add the default.
	if ( is_null( $label ) ) {

		$label = __( 'Source: ', 'simmer' );

		/**
		 * Filter the source label text.
		 *
		 * @since 1.1.0
		 *
		 * @param string $label     The label text.
		 * @param int    $recipe_id The recipe's ID.
		 */
		$label = apply_filters( 'simmer_source_label', $label, $recipe_id );
	}

	// Append the label text.
	if ( $label ) {
		$source = $label . $source;
	}

	return $source;
}
