<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<header class="entry-header">
			<span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?></span>
	</header><!-- .entry-header -->
	<?php endif; ?>

	
	<?php twentysixteen_post_thumbnail(); ?>


	<div class="entry-content">

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<?php twentysixteen_excerpt(); ?>
		<?php
		// for grabbing staff title on team page
			if(get_field('title'))
			{ echo '<h3 class="team-title">' . get_field('title') . '</h3>'; }
			?>

			<?php
			// for grabbing recipe ingredients on recipe pages
			if(get_field('ingredients'))
			{ echo '<div class="recipe-ingredients">' . get_field('ingredients') . '</div>'; }

			// for grabbing recipe instructions on recipe pages
			if(get_field('instructions'))
			{ echo '<div class="recipe-instructions">' . get_field('instructions') . '</div>'; }
			?>
		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php twentysixteen_entry_meta(); ?>
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
