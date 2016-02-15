<?php require_once( 'header.php' ); ?>

	<div id="wpsp-container">
	
		<!-- === Title === -->
		
		<h1 id="wpsp-title"><?php echo esc_html( $this->settings['title'] ); ?></h1>
		
		<?php if ( $this->opt_in_rejected ):	?>	
		
			<!-- === Text when a user reject the opt-in === -->
			
			<div id="wpsp-reject">
				<?php echo esc_html( $this->settings['opt_in_reject_text'] ); ?>
			</div>
		
		<?php elseif( $this->minor ): ?>
		
			<!-- === Text when a user isn't of the required minimum age  === -->
			
			<div id="wpsp-reject">
				<?php echo esc_html( $this->settings['reject_text'] ); ?>
			</div>
			
		<?php else: ?>	
		
			<!-- === Text === -->
			
			<div id="wpsp-text">
				<?php echo $this->settings['text']; ?>
			</div>

			<?php if( ! empty( $this->settings['youtube_id'] ) ) : ?> 
			
			<!-- === YouTube Video === -->
			
			<div id="wpsp-video">
				<iframe id="player" type="text/html" width="<?php echo esc_attr( $this->settings['video_width'] ); ?>" height="<?php echo esc_attr( $this->settings['video_height'] ); ?>" src="http://www.youtube.com/embed/<?php echo esc_attr( $this->settings['youtube_id'] ); ?>?enablejsapi=1&rel=0&iv_load_policy=3&showinfo=0&controls=0&autohide=2&autoplay=<?php echo esc_attr( $this->settings['video_autoplay'] ); ?>" allowfullscreen frameborder="0"></iframe>
			</div>
			
			<?php endif; ?>
			
			<?php if( $this->settings['enable_age_confirmation'] || $this->settings['enable_opt_in'] ): ?>
			
				<form method="POST">
				
					<?php wp_nonce_field( WP_SPLASH_PAGE_FORM_NONCE, 'wpsp-nonce' ); ?>
					
					<?php if( $this->settings['enable_age_confirmation'] ): ?>
					
					<!-- === Age Verification Form === -->
					
						<fieldset id="wpsp-birthday-fieldset">
							<legend align="center"><?php esc_html_e( 'Enter your Age', 'wp-splash-page-domain' ); ?></legend>
							<label for="wpsp-month" class="wpsp-confirmation" ><?php esc_html_e( 'Month', 'wp-splash-page-domain' ); ?></label><input type="number" id="wpsp-month" name="wpsp-month" min="1" max="12" size="2" maxlength="2" value="<?php echo esc_attr( date('m') );?>" placeholder="mm" required="required" />
							<label for="wpsp-day" class="wpsp-confirmation" ><?php esc_html_e( 'Day', 'wp-splash-page-domain' ); ?></label><input type="number" id="wpsp-day" name="wpsp-day" min="1" max="31" size="2" maxlength="2" value="<?php echo esc_attr( date('d') );?>" placeholder="dd" required="required" />
							<label for="wpsp-year" class="wpsp-confirmation" ><?php esc_html_e( 'Year', 'wp-splash-page-domain' ); ?></label><input type="number" id="wpsp-year" name="wpsp-year" min="1" max="9999" size="4" maxlength="4" value="<?php echo esc_attr( date('Y') );?>" placeholder="yyyy" required="required" />
						</fieldset>

					<?php endif; ?>
					
					<?php if( $this->settings['enable_opt_in'] ): ?>
					
						<!-- === Opt-In === -->
						
						<span id="wpsp-opt-in">
							<input type="checkbox" id="opt-in-checkbox" name="opt-in-checkbox" value="1" /><label for="opt-in-checkbox"><?php echo esc_html( $this->settings['opt_in_text'] ); ?></label>
						</span>
						
					<?php endif; ?>
				
					<input type="submit" id="wpsp-continue" value="<?php echo esc_attr( $this->settings['continue_button_text'] ); ?>" />
				
				</form>
				
			<?php else: ?>	
			
			<!-- === Continue Button === -->
			
			<a id="wpsp-continue" href="<?php echo esc_url( $this->current_url ); ?>" ><?php echo esc_html( $this->settings['continue_button_text'] ); ?></a>
			
			<?php endif; ?>
				
		<?php endif; ?>
		
	</div> <!-- === END #wpsp-container === -->

<?php require_once( 'footer.php' ); ?>