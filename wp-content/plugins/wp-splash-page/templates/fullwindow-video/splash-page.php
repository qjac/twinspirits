<?php require_once( 'header.php' ); ?>

	<div id="wpsp-container">
	
			<?php if( ! empty( $this->settings['youtube_id'] ) ) : ?> 
			
				<!-- === YouTube Video === -->
				<iframe id="player" type="text/html" width="0" height="0" src="http://www.youtube.com/embed/<?php echo esc_attr( $this->settings['youtube_id'] ); ?>?wmode=transparent&enablejsapi=1&rel=0&showinfo=0&autohide=2&color=white&iv_load_policy=3&controls=0" allowfullscreen frameborder="0"></iframe>
			
			<?php endif; ?>
			
			<!-- === Continue Button === -->
			<a id="wpsp-continue" href="<?php echo esc_url( $this->current_url ); ?>" >
				<?php echo esc_html( $this->settings['continue_button_text'] ); ?>
			</a>
			
	</div> <!-- === END #wpsp-container === -->
			
<?php require_once( 'footer.php' ); ?>