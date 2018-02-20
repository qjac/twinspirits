<?php
/**
 * Define the single ingredient class
 *
 * @since 1.3.0
 *
 * @package Simmer/Recipes/Items/Ingredients
 */

/**
 * The class for gathering and formatting information about a single recipe ingredient.
 *
 * @since 1.3.0
 */
final class Simmer_Recipe_Ingredient {

	/**
	 * The ingredient's item ID.
	 *
	 * @since 1.3.0
	 *
	 * @var int $id
	 */
	public $id;

	/**
	 * The ingredient amount.
	 *
	 * @since 1.0.0
	 *
	 * @var string $amount
	 */
	public $amount = '';

	/**
	 * The ingredient unit of measure.
	 *
	 * @since 1.0.0
	 *
	 * @var string $unit
	 */
	public $unit = '';

	/**
	 * The ingredient description.
	 *
	 * @since 1.0.0
	 *
	 * @var string $description
	 */
	public $description = '';

	/**
	 * The ingredient order number.
	 *
	 * @since 1.3.0
	 *
	 * @var int $order
	 */
	public $order = 0;

	/**
	 * Construct the ingredient object.
	 *
	 * @since 1.0.0
	 *
	 * @param int|object $ingredient The ingredient item or item ID.
	 */
	public function __construct( $ingredient ) {

		if ( is_numeric( $ingredient ) ) {

			$items_api = new Simmer_Recipe_Items;

			$ingredient = $items_api->get_item( $ingredient );

			if ( ! $ingredient ) {
				return false;
			}
		}

		$this->id    = $ingredient->recipe_item_id;
		$this->order = $ingredient->recipe_item_order;

		$this->amount      = $this->get_amount( true );
		$this->unit        = $this->get_unit( true );
		$this->description = $this->get_description( true );
		$this->is_heading  = $this->is_heading( true );

	}

	/**
	 * Get the amount.
	 *
	 * @since 1.3.0
	 *
	 * @param  bool   $raw    Optional. Whether to get the unaltered amount. Default: false.
	 * @return string $amount The ingredient amount.
	 */
	public function get_amount( $raw = false ) {

		$amount = simmer_get_recipe_item_meta( $this->id, 'amount', true );

		// Allow bypassing of the formatting and filter.
		if ( $raw ) {
			return $amount;
		}

		$amount = $this->convert_amount_to_string( $amount );

		/**
		 * Filter the ingredient amount.
		 *
		 * @since 1.3.0
		 *
		 * @param string $amount The ingredient amount.
		 * @param int    $id     The recipe ID.
		 */
		$amount = apply_filters( 'simmer_recipe_ingredient_amount', $amount, $this->id );

		return $amount;
	}

	/**
	 * Get the unit of measure.
	 *
	 * @since 1.3.0
	 *
	 * @param  bool   $raw  Optional. Whether to get the unaltered unit. Default: false.
	 * @return string $unit The ingredient unit.
	 */
	public function get_unit( $raw = false ) {

		$unit = simmer_get_recipe_item_meta( $this->id, 'unit', true );
		$count = simmer_get_recipe_item_meta( $this->id, 'amount', true );

		// Allow bypassing of the formatting and filter.
		if ( $raw ) {
			return $unit;
		}

		$unit = $this->get_unit_label( $unit , $count );

		/**
		 * Filter the ingredient unit of measure.
		 *
		 * @since 1.3.0
		 *
		 * @param string $unit The ingredient unit of measure.
		 * @param int    $id   The recipe ID.
		 */
		$unit = apply_filters( 'simmer_recipe_ingredient_unit', $unit, $this->id );

		return $unit;
	}

	/**
	 * Get the description.
	 *
	 * @since 1.3.0
	 *
	 * @param  bool   $raw         Optional. Whether to get the unaltered description. Default: false.
	 * @return string $description The ingredient description.
	 */
	public function get_description( $raw = false ) {

		$description = simmer_get_recipe_item_meta( $this->id, 'description', true );

		// Allow bypassing of the filter.
		if ( $raw ) {
			return $description;
		}

		/**
		 * Filter the ingredient description.
		 *
		 * @since 1.3.0
		 *
		 * @param string $description The ingredient description.
		 * @param int    $id          The recipe ID.
		 */
		$description = apply_filters( 'simmer_recipe_ingredient_description', $description, $this->id );

		return $description;
	}

	/**
	 * Determine if the ingredient is a heading.
	 *
	 * @since 1.3.3
	 *
	 * @param  bool   $raw        Whether to get the heading status unaltered from the database.
	 * @return string $is_heading Whether the ingredient is a heading..
	 */
	public function is_heading( $raw = false ) {

		$is_heading = simmer_get_recipe_item_meta( $this->id, 'is_heading', true );

		if ( $raw ) {
			return $is_heading;
		}

		$is_heading = apply_filters( 'simmer_recipe_ingredient_is_heading', $is_heading, $this->id );

		return $is_heading;
	}

	/**
	 * Convert an ingredient amount float to a string.
	 *
	 * @since  1.0.0

	 * @param  float      $amount The amount to convert.
	 * @return string|int $amount The converted amount.
	 */
	public static function convert_amount_to_string( $amount ) {

		if ( ! is_numeric( $amount ) ) {
			return false;
		}

		$whole = floor( $amount );

		$decimal = $amount - $whole;

		$least_common_denominator = 48; // 16 * 3;

		$denominators = array( 2, 3, 4, 8, 16, 24, 48 );

		$decimal = round( $decimal * $least_common_denominator ) / $least_common_denominator;

		if ( 0 == $decimal ) {

			$amount = $whole;

		} else if ( 1 == $decimal ) {

			$amount = $whole + 1;

		} else {

			foreach ( $denominators as $denominator ) {

				if ( $decimal * $denominator == floor ( $decimal * $denominator ) ) {
					break;
				}
			}

			$amount = ( 0 == $whole ? '' : $whole . ' ' ) . ( $decimal * $denominator ) . '/' . $denominator;

		}

		return $amount;
	}

	/**
	 * Convert the amount string to a float.
	 *
	 * @since 1.0.0
	 *
	 * @param  string|int $amount The amount to convert.
	 * @return float      $amount The converted amount.
	 */
	public static function convert_amount_to_float( $amount ) {

		// Assume there is no whole number.
		$has_whole = false;

		// Remove whitespace.
		$amount = trim( $amount );

		// Check for a space, signifying a whole number with a fraction.
		if ( strstr( $amount, ' ' ) ) {

			$reversed     = strrev( $amount );
			$whole_number = strrev( strstr( $reversed, ' ' ) );

			$has_whole = true;
		}

		// Now check the fraction part.
		if ( strstr( $amount, '/' ) ) {

			// Isolate the fraction.
			if ( true == $has_whole ) {
				$amount = strstr( $amount, ' ' );
			}

			$divisor = str_replace( '/', '', strstr( $amount, '/' ) );

			// Isolate the numerator.
			$numerator = strrev( $amount );
			$numerator = strstr( $numerator, '/' );
			$numerator = strrev( $numerator );
			$numerator = str_replace( '/', '', $numerator );

			if ( true == $has_whole ) {
				$numerator = $numerator + ( $whole_number * $divisor );
			}

			$amount = $numerator / $divisor;
		}

		return $amount;
	}

	/**
	 * Get the approprate label for a given unit based on count.
	 *
	 * @since 1.0.0
	 *
	 * @param  array  $unit  The given unit & its labels.
	 * @param  int    $count Optional. The ingredient count.
	 * @return string $label The appropriate label.
	 */
	public static function get_unit_label( $unit, $count = 1 ) {

		if ( ! is_array( $unit ) ) {

			$units = Simmer_Recipe_Ingredients::get_units();

			foreach( $units as $type => $units ) {
				if ( isset( $units[ $unit ] ) ) {

					$unit = $units[ $unit ];
					break;
				}
			}
		}

		// If an abbreviation is set, use that.
		if ( 'abbr' == get_option( 'simmer_units_format' ) && isset( $unit['abbr'] ) && ! empty( $unit['abbr'] ) ) {

			$label = $unit['abbr'];

		// Otherwise, choose either plural or single based on the count.
		} else if ( isset( $unit['plural'] ) && 1 < (float) $count ) {

			$label = $unit['plural'];

		} else if ( isset( $unit['single'] ) ) {

			$label = $unit['single'];

		// Finally, if none of the appropriate labels are set then bail.
		} else {
			return false;
		}

		/**
		 * Filter a single unit of measure's label.
		 *
		 * @since 1.0.0
		 *
		 * @param string $label The generated label.
		 * @param string $unit  The raw unit slug.
		 * @param int    $count The ingredient count. Used to determine singular vs. plural.
		 */
		$label = apply_filters( 'simmer_get_unit_label', $label, $unit, $count );

		return $label;
	}
}
