<?php
/**
 * Define the recipe durations class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes
 */

/**
 * Various recipe durations functionality.
 *
 * @since 1.3.0
 */
final class Simmer_Recipe_Durations {

	/**
	 * Get a specific recipe duration.
	 *
	 * @since 1.3.0
	 *
	 * @param  string $type      The type of duration to retrieve.
	 * @param  int    $recipe_id The recipe ID.
	 * @return int    $duration  The duration, in minutes.
	 */
	public function get_duration( $type, $recipe_id ) {

		$duration = get_post_meta( $recipe_id, "_recipe_{$type}_time", true );

		return $duration;
	}

	/**
	 * Format a given duration to a human-readable format.
	 *
	 * @since 1.3.0
	 *
	 * @param  int         $time     A duration, in minutes.
	 * @return string|bool $duration The human-readable duration or false on failure.
	 */
	public function format_human_duration( $time ) {

		if ( ! is_numeric( $time ) ) {
			return false;
		}

		$hours   = floor( $time / 60 );
		$minutes = ( $time % 60 );

		if ( ! $hours && ! $minutes ) {
			return false;
		}

		$duration = '';

		if ( $hours ) {
			$duration .= $hours . 'h';
		}

		if ( $hours && $minutes ) {
			$duration .= ' ';
		}

		if ( $minutes ) {
			$duration .= $minutes . 'm';
		}

		return $duration;
	}

	/**
	 * Format a given duration to a machine-readable format.
	 *
	 * @since 1.3.0
	 *
	 * @param  int         $time     A duration, in minutes.
	 * @return string|bool $duration The machine-readable duration or false on failure.
	 */
	public function format_machine_duration( $time ) {

		if ( ! is_numeric( $time ) ) {
			return false;
		}

		$hours   = floor( $time / 60 );
		$minutes = ( $time % 60 );

		if ( $hours || $minutes ) {
			$duration = 'PT';
		} else {
			return false;
		}

		if ( $hours ) {
			$duration .= $hours . 'H';
		}

		if ( $minutes ) {
			$duration .= $minutes . 'M';
		}

		return $duration;
	}
}
