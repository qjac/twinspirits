<?php
/**
 * The extend page markup.
 *
 * @since 1.0.0
 *
 * @package Simmer\Extend
 */
?>

<div class="wrap">

	<?php
	/**
	 * Allow others to add to the top of the extend page.
	 *
	 * @since 1.0.0
	 */
	do_action( 'simmer_before_extend_page' ); ?>

	<h2><?php _e( 'Recipe Extensions', 'simmer' ); ?></h2>

	<p><?php _e( 'Extend your business to whatever length you desire with Simmer. Sign up for a membership, or pick and choose add-ons. Questions? Use our <a href="https://simmer.zendesk.com/hc/en-us/categories/200299795-Start-Guide">Start Guide</a> or visit <a href="http://docs.simmerwp.com/">docs.simmerwp.com</a> for directions and how-to\'s for using recipes in WordPress.', 'simmer' ); ?></p>

	<div class="wp-list-table widefat simmer-extensions-list">

		<div class="simmer-extensions">

			<div class="simmer-extension-card">

				<div class="simmer-extension-card-top">

					<a href="https://wordpress.org/plugins/simmer-private/" class="extension-icon" target="_blank">
						<img src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/assets/extensions/recipe-connector.png' ); ?>" width="128" height="128" />
					</a>

					<h4 class="extension-name">
						<a href="https://wordpress.org/plugins/simmer-private/" target="_blank"><?php _e( 'Simmer Private', 'simmer' ); ?></a>
					</h4>

					<a class="extension-get button button-primary" href="https://wordpress.org/plugins/simmer-private/" target="_blank"><?php _e( 'Free Download', 'simmer' ); ?></a>

					<p class="extension-description"><?php _e( 'Once Simmer Private is enabled on your blog, all Simmer recipe archives and slug options are disabled. This means the only way to display a Simmer recipe is to embed it directly into a post, page, or another custom post type. If you\'re used to using recipe plugins like Ziplist, Easy Recipe, ReciPress, Recipe Card and others this will make Simmer behave in a way that\'s similar to what you\'re used to.', 'simmer' ); ?></p>

				</div><!-- .simmer-extension-card-top -->
				<?php /*
				<div class="simmer-extension-card-bottom">
					<div class="extension-rating">
						<div class="star-rating" title="4.0 rating based on 1,503 ratings">
							<span class="screen-reader-text">4.0 rating based on 1,503 ratings</span>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-empty"></div>
						</div><!-- .start-rating -->
						<span class="num-ratings">(1,503)</span>
					</div><!-- .extension-rating -->
					<div class="extension-meta">
						<div class="extension-updated">
							<strong>Last Updated:</strong>
							<span title="2014-11-14 4:17pm GMT">6 days ago</span>
						</div>
						<div class="extension-compatibility">
							<strong>Compatible</strong> with your version of Simmer
						</div>
					</div><!-- .extension-meta -->
				</div><!-- .simmer-extension-card-bottom -->
				*/ ?>
			</div><!-- .simmer-extension-card -->

			<div class="simmer-extension-card">

				<div class="simmer-extension-card-top">

					<a href="http://simmerwp.com/product/tinypass-for-simmer/" class="extension-icon" target="_blank">
						<img src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/assets/extensions/simmer-tinypass.png' ); ?>" width="128" height="128" />
					</a>

					<h4 class="extension-name">
						<a href="http://simmerwp.com/product/tinypass-for-simmer/" target="_blank"><?php _e( 'Tinypass for Simmer', 'simmer' ); ?></a>
					</h4>

					<a class="extension-get button button-primary" href="http://simmerwp.com/product/tinypass-for-simmer/" target="_blank"><?php _e( 'Buy', 'simmer' ); ?></a>

					<p class="extension-description"><?php _e( 'Tinypass for Simmer is an extension that allows WordPress websites to connect the power of micro e-commerce to monetize your food, drink, or recipe content instantly with Simmer.', 'simmer' ); ?></p>

				</div><!-- .simmer-extension-card-top -->
				<?php /*
				<div class="simmer-extension-card-bottom">
					<div class="extension-rating">
						<div class="star-rating" title="4.5 rating based on 427 ratings">
							<span class="screen-reader-text">4.5 rating based on 427 ratings</span>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-half"></div>
						</div><!-- .start-rating -->
						<span class="num-ratings">(427)</span>
					</div><!-- .extension-rating -->
					<div class="extension-meta">
						<div class="extension-updated">
							<strong>Last Updated:</strong>
							<span title="2014-11-14 4:17pm GMT">2 hours ago</span>
						</div>
						<div class="extension-compatibility">
							<strong>Compatible</strong> with your version of Simmer
						</div>
					</div><!-- .extension-meta -->
				</div><!-- .simmer-extension-card-bottom -->
				*/ ?>
			</div><!-- .simmer-extension-card -->

			<div class="simmer-extension-card">

				<div class="simmer-extension-card-top">

					<a href="http://simmerwp.com/membership/" class="extension-icon" target="_blank">
						<img src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/assets/extensions/membership.png' ); ?>" width="128" height="128" />
					</a>

					<h4 class="extension-name">
						<a href="http://simmerwp.com/membership/" target="_blank"><?php _e( 'Membership', 'simmer' ); ?></a>
					</h4>

					<a class="extension-get button button-primary" href="http://simmerwp.com/membership/" target="_blank"><?php _e( 'Buy', 'simmer' ); ?></a>

					<p class="extension-description"><?php _e( 'When you sign up for a Simmer for Recipes account, you\'ll receive access to a variety of members-only benefits that are really helpful for food-related businesses using WordPress.', 'simmer' ); ?></p>

				</div><!-- .simmer-extension-card-top -->
				<?php /*
				<div class="simmer-extension-card-bottom">
					<div class="extension-rating">
						<div class="star-rating" title="4.5 rating based on 427 ratings">
							<span class="screen-reader-text">4.5 rating based on 427 ratings</span>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-half"></div>
						</div><!-- .start-rating -->
						<span class="num-ratings">(427)</span>
					</div><!-- .extension-rating -->
					<div class="extension-meta">
						<div class="extension-updated">
							<strong>Last Updated:</strong>
							<span title="2014-11-14 4:17pm GMT">2 hours ago</span>
						</div>
						<div class="extension-compatibility">
							<strong>Compatible</strong> with your version of Simmer
						</div>
					</div><!-- .extension-meta -->
				</div><!-- .simmer-extension-card-bottom -->
				*/ ?>
			</div><!-- .simmer-extension-card -->

			<div class="simmer-extension-card">

				<div class="simmer-extension-card-top">

					<a href="http://develop.simmerwp.com/" class="extension-icon" target="_blank">
						<img src="<?php echo esc_url( dirname( plugin_dir_url( __FILE__ ) ) . '/assets/extensions/codex.png' ); ?>" width="128" height="128" />
					</a>

					<h4 class="extension-name">
						<a href="http://develop.simmerwp.com/" target="_blank"><?php _e( 'Codex and APIs', 'simmer' ); ?></a>
					</h4>

					<a class="extension-get button button-primary" href="http://develop.simmerwp.com/" target="_blank"><?php _e( 'View', 'simmer' ); ?></a>

					<p class="extension-description"><?php _e( 'Those looking to extend and customize this plugin can utilize the ever-growing list of action and filter hooks, as well as customizable template files to help tailor recipe display and functionality for any project.', 'simmer' ); ?></p>

				</div><!-- .simmer-extension-card-top -->
				<?php /*
				<div class="simmer-extension-card-bottom">
					<div class="extension-rating">
						<div class="star-rating" title="4.5 rating based on 427 ratings">
							<span class="screen-reader-text">4.5 rating based on 427 ratings</span>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-full"></div>
							<div class="star star-half"></div>
						</div><!-- .start-rating -->
						<span class="num-ratings">(427)</span>
					</div><!-- .extension-rating -->
					<div class="extension-meta">
						<div class="extension-updated">
							<strong>Last Updated:</strong>
							<span title="2014-11-14 4:17pm GMT">2 hours ago</span>
						</div>
						<div class="extension-compatibility">
							<strong>Compatible</strong> with your version of Simmer
						</div>
					</div><!-- .extension-meta -->
				</div><!-- .simmer-extension-card-bottom -->
				*/ ?>
			</div><!-- .simmer-extension-card -->

		</div><!-- .simmer-extensions -->

	</div><!-- .simmer-extensions-list -->

	<?php
	/**
	 * Allow others to add to the bottom of the extend page.
	 *
	 * @since 1.0.0
	 */
	do_action( 'simmer_after_extend_page' ); ?>

</div>
