<?php
/**
 * Display the Recent Recipes widget admin markup
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */
?>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'simmer' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" />
</p>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of recipes to show:', 'simmer' ); ?></label>
	<input type="number" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo absint( $instance['number'] ); ?>" min="0" style="width:50px;"/>
</p>

<p>
	<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_dates' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_dates' ) ); ?>" <?php checked( $instance['show_dates'] ); ?> />
	<label for="<?php echo esc_attr( $this->get_field_id( 'show_dates' ) ); ?>"><?php esc_attr_e( 'Display relative dates', 'simmer' ); ?></label>
</p>
