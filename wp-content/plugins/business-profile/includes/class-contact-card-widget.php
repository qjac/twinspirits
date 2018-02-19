<?php
/**
 * The contact card widget class.
 *
 * @package   BusinessProfile
 * @copyright Copyright (c) 2016, Theme of the Crop
 * @license   GPL-2.0+
 * @since     0.0.1
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_Widget', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/widgets.php';
}

if ( ! class_exists( 'bpfwpContactCardWidget', false ) ) :

	/**
	 * Contact card widget
	 *
	 * Extends WP_Widget to display a contact card in a widget.
	 *
	 * @since 0.0.1
	 */
	class bpfwpContactCardWidget extends WP_Widget {

		/**
		 * Register widget with WordPress.
		 *
		 * @since  0.0.1
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Display toggles.
			$this->toggles = apply_filters( 'bpfwp_widget_display_toggles', array(
					'show_name'                => __( 'Show Name', 'business-profile' ),
					'show_address'             => __( 'Show Address', 'business-profile' ),
					'show_get_directions'      => __( 'Show link to get directions on Google Maps', 'business-profile' ),
					'show_phone'               => __( 'Show Phone number', 'business-profile' ),
					'show_contact'             => __( 'Show contact details', 'business-profile' ),
					'show_opening_hours'       => __( 'Show Opening Hours', 'business-profile' ),
					'show_opening_hours_brief' => __( 'Show brief opening hours on one line', 'business-profile' ),
					'show_map'                 => __( 'Show Google Map', 'business-profile' ),
				)
			);

			parent::__construct(
				'bpfwp_contact_card_widget',
				__( 'Contact Card', 'business-profile' ),
				array( 'description' => __( 'Display a contact card with your name, address, phone number, opening hours and map.', 'business-profile' ) )
			);

		}

		/**
		 * Print the widget content
		 *
		 * @since  0.0.1
		 * @access public
		 * @param  array $args Display arguments including before_title, after_title, before_widget, and after_widget.
		 * @param  array $instance The settings for the particular instance of the widget.
		 * @return void
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( isset( $instance['title'] ) ) {
				$title = apply_filters( 'widget_title', $instance['title'] );
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo bpwfwp_print_contact_card( $instance );
			echo $args['after_widget'];
		}

		/**
		 * Print the form to configure this widget in the admin panel.
		 *
		 * @since  1.0
		 * @access public
		 * @global object $bpfwp_controller BPFWP controller class instance.
		 * @param  array $instance Current widget settings.
		 * @return void
		 */
		public function form( $instance ) {
			global $bpfwp_controller;
			?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"> <?php _e( 'Title' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"<?php if ( isset( $instance['title'] ) ) : ?> value="<?php echo esc_attr( $instance['title'] ); ?>"<?php endif; ?>>
			</p>

			<?php
			if ( $bpfwp_controller->settings->get_setting( 'multiple-locations' ) ) :

				// Get an array of all locations with sane limits.
				$locations = array();
				$query = new WP_Query( array(
					'post_type'              => array( $bpfwp_controller->cpts->location_cpt_slug ),
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'posts_per_page'         => 500,
				) );
				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->next_post();
						$locations[ $query->post->ID ] = $query->post->post_title;
					}
				}
				wp_reset_postdata();
				?>

				<p>
					<label for="<?php echo $this->get_field_id( 'location' ); ?>"> <?php _e( 'Location' ); ?></label>
					<select name="<?php echo $this->get_field_name( 'location' ); ?>" id="<?php echo $this->get_field_id( 'location' ); ?>" class="widefat">
						<option><?php esc_html_e( 'Use Primary Business Profile' ); ?></option>
						<?php foreach ( $locations as $id => $title ) : ?>
							<option value="<?php echo absint( $id ); ?>"<?php if ( isset( $instance['location'] ) && $instance['location'] === $id ) : ?> selected<?php endif; ?>>
								<?php esc_attr_e( $title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>

			<?php endif; // Locations. ?>

			<?php foreach ( $this->toggles as $id => $label ) : ?>

			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id( $id ); ?>" name="<?php echo $this->get_field_name( $id ); ?>" value="1"<?php if ( ! empty( $instance[ $id ] ) ) : ?> checked="checked"<?php endif; ?>>
				<label for="<?php echo $this->get_field_id( $id ); ?>"> <?php echo $label; ?></label>
			</p>

			<?php endforeach;
		}

		/**
		 * Sanitize and save the widget form values.
		 *
		 * @since  1.0
		 * @access public
		 * @param  array $new_instance New settings for this instance as input by the user via form().
		 * @param  array $old_instance Old settings for this instance.
		 * @return array $instance Settings to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = array();
			if ( ! empty( $new_instance['title'] ) ) {
				$instance['title'] = strip_tags( $new_instance['title'] );
			}

			if ( ! empty( $new_instance['location'] ) ) {
				$instance['location'] = absint( $new_instance['location'] );
			}

			foreach ( $this->toggles as $id => $label ) {
				$instance[ $id ] = empty( $new_instance[ $id ] ) ? false : true;
			}

			return $instance;
		}
	}
endif;
