<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (!is_front_page()) : ?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php twentysixteen_post_thumbnail(); ?>
	</header><!-- .entry-header -->
		<?php endif; ?>

	<div class="entry-content">
		
		<!-- page-id-20 is 'Where To Buy' page-->
		<a href="<?php echo get_page_link('20'); ?>" class="btn">Purchase</a>
		<div>
		<!-- duplcate name on spirits pack to hack the placement. -->
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="proof">
			<p><?php the_field('proof'); ?></p>
		</div>
		<?php
		the_content();

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		?>
		</div>


<!-- spirits nav -->
		<div class="spirits-nav">
		<?php $nav = twinspirits_get_sibling_pages(); ?>
		<?php echo $nav['previous']; ?>
		<span></span> 
		<!-- empty element to force proper flexboxing when only one arrow is showing. definitely a hack. -->
		<?php echo $nav['next']; ?>
	</div>
	</div><!-- .entry-content -->

	


	<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
				get_the_title()
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
	?>
</article><!-- #post-## -->
