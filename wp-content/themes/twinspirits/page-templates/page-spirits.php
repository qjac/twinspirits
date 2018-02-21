<?php
/**
 * Template Name: Spirits
 *
 * All this does is add a class to #primary.
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
			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>

		<div class="spirits-nav">
			<?php $nav = twinspirits_get_sibling_pages(); ?>
			<?php echo $nav['previous']; ?>
			<?php echo $nav['next']; ?>
		</div>

	<?php
			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_footer(); ?>
