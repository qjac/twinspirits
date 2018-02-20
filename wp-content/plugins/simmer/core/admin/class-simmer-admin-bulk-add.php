<?php
/**
 * Define the bulk-add class
 *
 * @since 1.2.0
 *
 * @package Simmer/Admin/Bulk
 */

/**
 * Set up the bulk-add functionality.
 *
 * @since 1.2.0
 */
final class Simmer_Admin_Bulk_Add {

	/** Singleton **/

	/**
	 * The singleton instance of the class.
	 *
	 * @since  1.3.3
	 * @access private
	 * @var    object $instance.
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance of the class.
	 *
	 * @since 1.3.3
	 *
	 * @return object self::$instance The single instance of the class.
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prevent the class from being cloned.
	 *
	 * @since 1.3.3
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Bulk_Add class can not be cloned', 'simmer' ), Simmer()->version );
	}

	/**
	 * Prevent the class from being unserialized.
	 *
	 * @since 1.3.3
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'The Simmer_Admin_Bulk_Add class can not be unserialized', 'simmer' ), Simmer()->version );
	}

	/**
	 * Add the modal markup to the admin footer.
	 *
	 * @since 1.2.0
	 */
	public function add_modal() {

		$current_screen = get_current_screen();

		// Only load the modal when editing or creating a new recipe.
		if ( 'post' == $current_screen->base && $current_screen->post_type == simmer_get_object_type() ) {

			include_once( plugin_dir_path( __FILE__ ) . 'views/meta-boxes/bulk-add.php' );
		}
	}

	/**
	 * Process the request from the modal via AJAX.
	 *
	 * @since 1.2.0
	 */
	public function process_ajax() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'simmer_process_bulk' ) ) {

			echo json_encode( array(
				'error' => 'no-nonce',
				'message'  => __( 'No don\'t have permission to do this.', 'simmer' ),
			) );

			die();
		}

		if ( ! isset( $_POST['text'] ) || empty( $_POST['text'] ) ) {

			echo json_encode( array(
				'error' => 'no-input',
				'message'  => __( 'Please add some text.', 'simmer' ),
			) );

			die();
		}

		$type = ( isset( $_POST['type'] ) ) ? $_POST['type'] : 'ingredient';
		$text = $_POST['text'];

		if ( $items = $this->parse_input( $text, $type ) ) {

			echo json_encode( $items );

			die();

		} else {

			echo json_encode( array(
				'error' => 'parse-error',
				'message'  => __( 'Could not parse input.', 'simmer' ),
			) );

			die();

		}

		die();
	}

	/**
	 * Parse a textarea value for individual items, line by line.
	 *
	 * @since 1.2.0
	 *
	 * @param  string $input The input to parse.
	 * @param  string $type  The type of items to parse. 'ingredient' or 'instruction'.
	 * @return array  $items The parsed items.
	 */
	public function parse_input( $input, $type ) {

		// Split the input into individual lines and trim any whitespace.
		$lines = preg_split( '/\r\n|[\r\n]/', $input );
		$lines = array_map( 'trim', $lines );

		$items = false;

		if ( ! empty( $lines ) ) {

			$items = array();

			foreach ( $lines as $line ) {

				// Discard blank lines.
				if ( '' == $line ) {
					continue;
				}

				if ( 'ingredient' == $type ) {

					$amount = '';
					$unit   = '';
					$description = $line;

					$parsed_amount = $this->parse_amount( $line );

					if ( $parsed_amount ) {

						$amount = $parsed_amount['result'];

						// Remove the amount and format the description.
						$description = substr( $description, $parsed_amount['end'] );
						$description = trim( $description );

						$parsed_unit = $this->parse_unit( $description );

						if ( $parsed_unit ) {

							$description = substr( $description, $parsed_unit['end'] );

							$unit = $parsed_unit['result'];
						}
					}

					$description = trim( $description );

					$items[] = array(
						'amount'      => $amount,
						'unit'        => $unit,
						'description' => $description,
					);

				} else {

					$description = trim( $line );

					$items[] = array(
						'description' => $description,
					);
				}
			}
		}

		return $items;
	}

	/**
	 * Parse out amount values from the start of a given string.
	 *
	 * This method checks the first two words in a string for possible
	 * numerical values. It will automatically recognize integers, floats,
	 * and fractions or combination of any two. If any exist in the first
	 * two words of a string, a total value will be determined by adding
	 * them together.
	 *
	 * @since  1.2.0
	 * @access private
	 *
	 * @param  string      $string The string from user input.
	 * @return array|false $result The resulting amount information or false if none exists.
	 */
	private function parse_amount( $string ) {

		// Isolate the first word.
		$first_word = strtok( $string, ' ' );

		// Get amount string if it meets our criteria.
		$amount_length = strspn( $first_word, '0123456789/.' );
		$amount_string = substr( $string, 0, $amount_length );

		if ( $amount_string ) {

			// Format the amount.
			$amount = trim( $amount_string );
			$amount = Simmer_Recipe_Ingredient::convert_amount_to_float( $amount );

			// Isolate the second word to check for fractions or floats.
			$string = substr( $string, $amount_length );
			$string = trim( $string );
			$second_word = strtok( $string, ' ' );

			// Filter the second word against allowed characters.
			$fraction_length = strspn( $second_word, '0123456789/.' );
			$fraction_string = substr( $string, 0, $fraction_length );

			// If a fraction or float does indeed exist, parse it.
			if ( $fraction_string ) {

				// Update the final amount string to include the fraction or float.
				$amount_string = $amount_string . ' ' . $fraction_string;
				$amount_length = strlen( $amount_string );

				// Format the fraction to a float.
				$fraction_amount = trim( $fraction_string );
				$fraction_amount = Simmer_Recipe_Ingredient::convert_amount_to_float( $fraction_amount );

				// Add the two together for one final value.
				$amount = $amount + $fraction_amount;
			}

			$result = array(
				'start'  => 0,
				'end'    => $amount_length,
				'result' => $amount,
			);

		} else {

			$result = false;

		}

		return $result;
	}

	/**
	 * Parse out a unit value from the start of a given string.
	 *
	 * This method checks the first word in a string for possible
	 * unit label matches as defined by the core measurement units.
	 * It will account for capitalization, abbreviation, & other oddities.
	 *
	 * @since  1.2.0
	 * @access private
	 *
	 * @param  string      $string The string from user input.
	 * @return array|false $result The resulting unit information or no match was found.
	 */
	private function parse_unit( $string ) {

		$_unit  = '';
		$end = 0;

		// Isolate the first word.
		$first_word = strtok( $string, ' ' );

		// Get the available measurement units.
		$units = Simmer_Recipe_Ingredients::get_units();
		$_units = array();

		// Remove unit types (volume, weight, etc...) from array.
		foreach ( $units as $unit ) {
			$_units = array_merge( $unit, $_units );
		}

		// Loop through each unit & its labels for a match.
		foreach ( $_units as $unit => $labels ) {

			foreach ( $labels as $label ) {

				// Check that no match has been found yet.
				if ( '' != $_unit && 0 == $end ) {
					continue;
				}

				// Force lowercase and remove . from input unit.
				$_first_word = strtolower( $first_word );
				$_first_word = str_replace( '.', '', $_first_word );

				// Force lowercase and remove . from looped label.
				$_label = strtolower( $label );
				$_label = str_replace( '.', '', $_label );

				// If we have a match, set the values.
				if ( $_first_word === $_label ) {

					$_unit = $unit;
					$end  = strlen( $first_word );
				}
			}
		}

		if ( $_unit && $end ) {

			$result = array(
				'result' => $_unit,
				'start'  => 0,
				'end'    => $end,
			);

		} else {
			$result = false;
		}

		return $result;
	}
}
