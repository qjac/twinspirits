<?php
/**
 * Template Name: Home Page
 *
 * @package WordPress
 * @subpackage twinspirits
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="home-title">
			<h1><?php bloginfo( 'name' ); ?></h1>
		</div> 
		<div class="home-block-one" style="background-image: url(<?php the_field('first_image'); ?>);">
				<div class="home-text-block">
					<h2><?php the_field('first_title'); ?></h2>
					<div class="home-text-body"><?php the_field('first_text_block'); ?></div>
				</div> <!-- end .home-text-block -->
			<!-- </div>  end .home-image -->

		</div>
		<div class="home-block-two" style="background-image: url(<?php the_field('second_image'); ?>);">
				<div class="home-text-block">
					<h2><?php the_field('second_title'); ?></h2>
					<div class="home-text-body"><?php the_field('second_text_block'); ?></div>
				</div> <!-- end .home-text-block -->
			<!-- </div> end .home-image -->
		</div>

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'template-parts/content', 'page' );

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

</div><!-- .content-area -->
<?php get_footer(); ?>
