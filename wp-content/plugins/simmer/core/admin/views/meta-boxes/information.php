<?php
/**
 * The ingredients meta box HTML.
 *
 * @package Simmer\Ingredients
 */
?>

<?php wp_nonce_field( 'simmer_save_recipe_meta', 'simmer_nonce' ); ?>

<?php // Build the formatted times.
$durations_api = new Simmer_Recipe_Durations;

$prep_time  = (int) $durations_api->get_duration( 'prep',  $recipe->ID );
$cook_time  = (int) $durations_api->get_duration( 'cook',  $recipe->ID );
$total_time = (int) $durations_api->get_duration( 'total', $recipe->ID );

if ( $prep_time ) {
	$prep_h = zeroise( floor( $prep_time / 60 ), 2 );
	$prep_m = zeroise( ( $prep_time % 60 ), 2 );
} else {
	$prep_h = '';
	$prep_m = '';
}

if ( $cook_time ) {
	$cook_h = zeroise( floor( $cook_time / 60 ), 2 );
	$cook_m = zeroise( ( $cook_time % 60 ), 2 );
} else {
	$cook_h = '';
	$cook_m = '';
}

if ( $total_time ) {
	$total_h = zeroise( floor( $total_time / 60 ), 2 );
	$total_m = zeroise( ( $total_time % 60 ), 2 );
} else {
	$total_h = '';
	$total_m = '';
} ?>

<p>
	<label for="simmer_prep"><?php _e( 'Prep Time', 'simmer' ); ?>:</label><br />
	<input class="simmer-time" name="simmer_times[prep][h]" type="number" min="0" value="<?php echo esc_html( $prep_h ); ?>" placeholder="hh" />
	:
	<input class="simmer-time" name="simmer_times[prep][m]" type="number" min="0" value="<?php echo esc_html( $prep_m ); ?>" placeholder="mm" />
</p>

<p>
	<label for="simmer_cook"><?php _e( 'Cook Time', 'simmer' ); ?>:</label><br />
	<input class="simmer-time" name="simmer_times[cook][h]" type="number" min="0" value="<?php echo esc_html( $cook_h ); ?>" placeholder="hh" />
	:
	<input class="simmer-time" name="simmer_times[cook][m]" type="number" min="0" value="<?php echo esc_html( $cook_m ); ?>" placeholder="mm" />
</p>

<p>
	<label for="simmer_total"><?php _e( 'Total Time', 'simmer' ); ?>:</label><br />
	<input class="simmer-time" name="simmer_times[total][h]" type="number" min="0" value="<?php echo esc_html( $total_h ); ?>" placeholder="hh" />
	:
	<input class="simmer-time" name="simmer_times[total][m]" type="number" min="0" value="<?php echo esc_html( $total_m ); ?>" placeholder="mm" />
</p>

<p>
	<label for="simmer_servings"><?php _e( 'Servings', 'simmer' ); ?>:</label><br />
	<input class="simmer-time" type="number" name="simmer_servings" value="<?php echo esc_attr( $servings ); ?>" placeholder="4" min="1" />
	<input type="text" name="simmer_servings_label" value="<?php echo esc_html( $servings_label ); ?>" placeholder="<?php _e( 'people', 'simmer' ); ?>" />
</p>

<p>
	<label for="simmer_yield"><?php _e( 'Yield', 'simmer' ); ?>:</label><br />
	<input type="text" name="simmer_yield" value="<?php echo esc_html( get_post_meta( $recipe->ID, '_recipe_yield', true ) ); ?>" placeholder="24 cookies" />
</p>

<?php $source_text = get_post_meta( $recipe->ID, '_recipe_source_text', true ); ?>
<?php $source_url  = get_post_meta( $recipe->ID, '_recipe_source_url',  true ); ?>

<?php // Update the deprecated meta key.
if ( ! $source_text && ( $source_text = get_post_meta( $recipe->ID, '_recipe_attribution_text', true ) ) ) {
	update_post_meta( $recipe->ID, '_recipe_source_text', $source_text );
	delete_post_meta( $recipe->ID, '_recipe_attribution_text' );
} ?>

<?php // Update the deprecated meta key.
if ( ! $source_url && ( $source_url = get_post_meta( $recipe->ID, '_recipe_attribution_url', true ) ) ) {
	update_post_meta( $recipe->ID, '_recipe_source_url', $source_url );
	delete_post_meta( $recipe->ID, '_recipe_attribution_url' );
} ?>

<p>
	<label for="simmer_source_text"><?php _e( 'Source', 'simmer' ); ?>:</label><br />
	<input id="simmer_source_text" name="simmer_source_text" type="text" value="<?php echo esc_html( $source_text ); ?>" placeholder="Source name" /><br />
	<label for="simmer_source_url"><?php _e( 'Source URL', 'simmer' ); ?>:</label><br />
	<input id="simmer_source_url" name="simmer_source_url" type="text" value="<?php echo esc_url( $source_url ); ?>" placeholder="http://somesource.com" />
</p>
