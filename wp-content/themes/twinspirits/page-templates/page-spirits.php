<?php
/**
 * Template Name: Spirits
 *
 * All this does is add a class to #primary and pull in modified content loop.
 *
 * @package WordPress
 * @subpackage twinspirits
 */

get_header(); ?>

<div id="primary" class="content-area spirits">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'template-parts/content', 'spirit' );

			?>

<?php
			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->

<?php get_footer(); ?>
