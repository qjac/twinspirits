<?php
/**
 * Display the Recipe Categories widget markup
 *
 * @since 1.1.0
 *
 * @package Simmer\Widgets
 */
?>

<?php if ( $recent_recipes->have_posts() ) : ?>

	<ul class="simmer-recent-recipes">

		<?php while ( $recent_recipes->have_posts() ) : ?>

			<?php $recent_recipes->the_post(); ?>

			<li>
				<a class="title" href="<?php the_permalink(); ?>" role="link"><?php the_title(); ?></a>
				<?php if ( $instance['show_dates'] ) : ?>
					<span class="divider">-</span>
					<span class="date"><?php printf( __( '%s ago', 'simmer' ), human_time_diff( get_the_time( 'U' ) ) ); ?></span>
				<?php endif; ?>
			</li>

		<?php endwhile; ?>

		<?php wp_reset_postdata(); ?>

	</ul><!-- .simmer-recent-recipes -->

<?php else : ?>

	<p class="simmer-recent-recipes-none"><?php _e( 'No recent recipes.', 'simmer' ); ?></p>

<?php endif; ?>
