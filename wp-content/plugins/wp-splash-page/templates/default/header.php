<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!-- === Page Title === -->
	<title><?php echo esc_html( $this->settings['page_title'] ); ?></title>
	
	<!-- === Embedding style.css === -->
	<link rel="stylesheet" href="<?php echo esc_url( $this->template_url . 'style.css' ); ?>" type="text/css" media="all"/>
	
	<!-- === Embedding script for YouTube Video === -->
	<?php if ( ! empty( $this->settings['youtube_id'] ) ) : ?>
		<script type="text/javascript" src="<?php echo esc_url( $this->template_url . 'video.js' ); ?>"></script>
	<?php endif; ?>
	
	<!-- === Dynamic Style === -->
	<style type="text/css">
		
		body {
			background-color: #<?php echo $this->settings['background_color']; ?>;
			background-image: <?php echo ( ! empty( $this->settings['image_url'] ) ) ? 'url(' . $this->settings['image_url'] . ')' : 'none'; ?>;
			background-repeat: <?php echo $this->settings['repeat_image']; ?>;
			background-position: <?php echo $this->settings['center_image']; ?>;
			color: #<?php echo $this->settings['text_color']; ?>;
		}
		
		/* === Highlight === */
		::selection {
			color: #<?php echo $this->settings['background_color']; ?>;
			background-color: #<?php echo $this->settings['title_color']; ?>;
		}

		#wpsp-title {
			color: #<?php echo $this->settings['title_color']; ?>;
		}

		#wpsp-birthday-fieldset, #wpsp-birthday-fieldset input {
			border-color: #<?php echo $this->settings['title_color']; ?>;
			color: #<?php echo $this->settings['text_color']; ?>;
		}
		
		#wpsp-continue {
			background-color: #<?php echo $this->settings['continue_button_bg_color']; ?>;
			color: #<?php echo $this->settings['continue_button_text_color']; ?>;
		}
		
		#wpsp-continue:hover {
			background-color: #<?php echo $this->settings['continue_button_text_color']; ?>;
			color: #<?php echo $this->settings['continue_button_bg_color']; ?>;
		}
		
	</style>
	
</head>
<body>