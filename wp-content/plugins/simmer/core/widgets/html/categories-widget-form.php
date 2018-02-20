<?php
/**
 * Display the Recipe Categories widget admin markup
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */
?>

<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'simmer' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
</p>

<p>
	<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>"<?php checked( $instance['show_count'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Show recipe counts', 'simmer' ); ?></label><br />

	<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>" <?php checked( $instance['hierarchical'] ); ?> />
	<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>"><?php _e( 'Show hierarchy', 'simmer' ); ?></label>
</p>
