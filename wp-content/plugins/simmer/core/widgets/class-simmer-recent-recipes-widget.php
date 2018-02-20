<?php
/**
 * Define the Recent Recipes widget
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */

// Die if this file is called directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Simmer_Recent_Recipes_Widget extends WP_Widget {

	/**
	 * Unique identifier for the widget.
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected $widget_slug = 'simmer-recent-recipes';

	/**
	 * Construct the widget.
	 *
	 * @since 1.1.0
	 * @see WP_Widget
	 */
	public function __construct() {

		parent::__construct(
			$this->widget_slug,
			__( 'Recent Recipes', 'simmer' ),
			array(
				'classname'   => $this->widget_slug . '-widget',
				'description' => __( "Your site's most recent recipes", 'simmer' ),
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
		$instance = apply_filters( 'simmer_recent_recipes_widget_settings', $instance, $widget_id, $sidebar_id );

		/**
		 * Filter the title for the instance.
		 *
		 * @since 1.1.0
		 *
		 * @param string $title      The instance's title.
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		$title = apply_filters( 'simmer_recent_recipes_widget_title', $instance['title'], $widget_id, $sidebar_id );

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		// Set the recipe query args.
		$query_args = array(
			'posts_per_page'         => (int) $instance['number'],
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		/**
		 * Filter the recipe query args for the instance.
		 *
		 * @since 1.1.0
		 *
		 * @param array  $query_args The instance's recipe query args.
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		$query_args = apply_filters( 'simmer_recent_recipes_widget_query_args', $query_args, $widget_id, $sidebar_id );

		// Override the above filter to ensure recipes are always queried.
		$query_args['post_type'] = simmer_get_object_type();

		// Try to get the recipes.
		$recent_recipes = new WP_Query( $query_args );

		/**
		 * Execute before displaying the widget.
		 *
		 * @since 1.1.0
		 *
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		do_action( 'simmer_before_recent_recipes_widget', $widget_id, $sidebar_id );

		// Output the main markup.
		include( plugin_dir_path( __FILE__ ) . 'html/recent-recipes-widget.php' );

		/**
		 * Execute after displaying the widget.
		 *
		 * @since 1.1.0
		 *
		 * @param string $widget_id  The instance's ID.
		 * @param string $sidebar_id The ID of the sidebar in which the instance is located.
		 */
		do_action( 'simmer_after_recent_recipes_widget', $widget_id, $sidebar_id );

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

		$instance['title']  = wp_strip_all_tags( $new_instance['title'] );
		$instance['number'] = absint( $new_instance['number'] );

		$instance['show_dates'] = ! empty( $new_instance['show_dates'] ) ? true : false;

		return $instance;

	}

	/**
	 * Display the settings fields for the widget.
	 *
	 * @since 1.1.0
	 *
	 * @param  array $instance The current instance's settings.
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'      => '',
			'number'     => 5,
			'show_dates' => false,
		);

		// Check the settings (or lack thereof) against the defaults.
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Output the fields.
		include( plugin_dir_path( __FILE__ ) . 'html/recent-recipes-widget-form.php' );

	}
}
