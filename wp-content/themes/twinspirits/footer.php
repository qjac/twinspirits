<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage twinspirits
 * @since Twenty Sixteen 1.0
 */
?>

		</div><!-- .site-content -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="footer-top">
				<!-- <div class="footer-widgets"> -->
					<div class="footer1">
						<?php if ( function_exists('dynamic_sidebar')) { dynamic_sidebar('footer-one'); } ?>
					</div>
					<div class="footer2">
						<?php if ( function_exists('dynamic_sidebar')) { dynamic_sidebar('footer-two'); } ?>
					</div>
				<!-- </div> -->
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'twentysixteen' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'footer',
							'menu_class'     => 'footer-menu',
						 ) );
					?>
				</nav><!-- .footer-navigation -->
			<?php endif; ?>

			</div>
			
			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'twinspirits' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'social',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						) );
					?>
				</nav><!-- .social-navigation -->
			<?php endif; ?>
			<!-- <div class="address">2931 Central Ave NE<br>Minneapolis, MN 55418</div> -->
			<p class="copyright">&copy; <?php echo date('Y'); ?> Twin Spirits Distillery. All rights reserved.</p>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
