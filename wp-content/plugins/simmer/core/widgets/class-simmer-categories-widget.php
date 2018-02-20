<?php
/**
 * Define the Recipe Categories widget
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */

// Die if this file is called directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Simmer_Categories_Widget extends WP_Widget {

	/**
	 * Unique identifier for the widget.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $widget_slug = 'simmer-recipe-categories';

	/**
	 * Construct the widget.
	 *
	 * @since 1.1.0
	 * @see WP_Widget
	 */
	public function __construct() {

		parent::__construct(
			$this->widget_slug,
			__( 'Recipe Categories', 'simmer' ),
			array(
				'classname'   => $this->widget_slug . '-widget',
				'description' => __( 'A list of recipe categories', 'simmer' ),
			)
		);

	}

	/**
	 * Display the widget on the front end.
	 *
	 * @since 1.1.0
	 *
	 * @param array $args     The sidebar args for the instance.
	 * @param array $instance The instance and its settings.
	 */
	public function widget( $args, $instance ) {

		if ( ! isset( $args['widget_id'] ) ) {
			$widget_id = $this->id;
		} else {
			$widget_id = $args['widget_id'];
		}

		$sidebar_id = $args['id'];

		// Output the wrapper.
		echo $args['before_widget'];

		/**
		 * Filter the settings for the instance.
		 *
		 * @since 1.1.0
		 *
		 * @param array  $instance   The instance's settings.
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		$instance = apply_filters( 'simmer_categories_widget_settings', $instance, $widget_id, $sidebar_id );

		/**
		 * Filter the title for the instance.
		 *
		 * @since 1.1.0
		 *
		 * @param string $title      The instance's title.
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		$title = apply_filters( 'simmer_categories_widget_title', $instance['title'], $widget_id, $sidebar_id );

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// Define the wp_list_categories args based on this widget instance's settings.
		$list_args = array(
			'show_count'   => $instance['show_count'],
			'hierarchical' => $instance['hierarchical'],
			'title_li'     => false,
		);

		/**
		 * Filter the wp_list_categories args for the widget.
		 *
		 * @since 1.1.0
		 *
		 * @param array  $list_args  The arguments.
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		$list_args = apply_filters( 'simmer_categories_widget_list_args', $list_args, $widget_id, $sidebar_id );

		// Override the above filter to always set the taxonomy to display recipe categories.
		$list_args['taxonomy'] = simmer_get_category_taxonomy();

		/**
		 * Execute before displaying the widget.
		 *
		 * @since 1.1.0
		 *
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		do_action( 'simmer_before_categories_widget', $widget_id, $sidebar_id );

		// Output the main markup.
		include( plugin_dir_path( __FILE__ ) . 'html/categories-widget.php' );

		/**
		 * Execute after displaying the widget.
		 *
		 * @since 1.1.0
		 *
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		do_action( 'simmer_after_categories_widget', $widget_id, $sidebar_id );

		// Close the wrapper.
		echo $args['after_widget'];
	}

	/**
	 * Set the new settings for the instance.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $new_instance The new settings.
	 * @param  array $old_instance The old settings.
	 * @return array $instance     The updated settings.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		$instance['show_count']   = ! empty( $new_instance['show_count']   ) ? true : false;
		$instance['hierarchical'] = ! empty( $new_instance['hierarchical'] ) ? true : false;

		return $instance;

	}

	/**
	 * Display the settings fields for the widget.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $instance The instance's settings.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'        => '',
			'show_count'   => false,
			'hierarchical' => false,
		);

		// Check the settings (or lack thereof) against the defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Output the fields.
		include( plugin_dir_path( __FILE__ ) . 'html/categories-widget-form.php' );

	}
}
