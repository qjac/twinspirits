<?php
/**
 * Display the Recipe Categories widget markup
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */
?>

<ul class="simmer-recipe-categories">

	<?php wp_list_categories( $list_args ); ?>

</ul><!-- .simmer-recipe-categories -->
