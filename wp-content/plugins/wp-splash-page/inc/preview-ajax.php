<?php
function wp_splash_page_show_preview() {

	check_admin_referer( WP_SPLASH_PAGE_PREVIEW_NONCE, 'nonce' );
	
	$options	= get_option( 'wp_splash_page_options' );
	
	$preview	= array(
		'template'						=> strip_tags( $_REQUEST['template'] ),
		'page_title'					=> ( empty ( $_REQUEST['page_title'] ) ) ? get_bloginfo('name') : strip_tags( stripslashes( $options['page_title'] ) ),
		'title'							=> balanceTags( stripslashes( $_REQUEST['title'] ) ),
		'text'							=> balanceTags( stripslashes( $_REQUEST['text'] ) ),
		'title_color'					=> ( !empty( $_REQUEST['title_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $_REQUEST['title_color'] ) ) ) ? str_replace( '#', '', $_REQUEST['title_color'] ): $options['title_color'],
		'text_color'					=> ( !empty( $_REQUEST['text_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $_REQUEST['text_color'] ) ) ) ? str_replace( '#', '', $_REQUEST['text_color'] ): $options['text_color'],
		'background_color'				=> ( !empty( $_REQUEST['background_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $_REQUEST['background_color'] ) ) ) ? str_replace( '#', '', $_REQUEST['background_color'] ): $options['background_color'],
		'continue_button_bg_color'		=> ( !empty( $_REQUEST['continue_button_bg_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $_REQUEST['continue_button_bg_color'] ) ) ) ? str_replace( '#', '', $_REQUEST['continue_button_bg_color'] ): $options['continue_button_bg_color'],
		'continue_button_text_color'	=> ( !empty( $_REQUEST['continue_button_text_color'] ) && preg_match( '|^([A-Fa-f0-9]{3}){1,2}$|', str_replace( '#', '' , $_REQUEST['continue_button_text_color'] ) ) ) ? str_replace( '#', '', $_REQUEST['continue_button_text_color'] ): $options['continue_button_text_color'],
		'image_url'						=> esc_url_raw( $_REQUEST['image_url'] ),
		'repeat_image'					=> strip_tags( $_REQUEST['repeat_image'] ),
		'center_image'					=> strip_tags( $_REQUEST['center_image'] ),
		'youtube_id'					=> strip_tags( $_REQUEST['youtube_id'] ),
		'video_autoplay'				=> ( $_REQUEST['video_autoplay'] == 'true') ? 1 : 0,
		'video_width'					=> ( $_REQUEST['video_width'] >= 1 && $_REQUEST['video_width'] <= 9999 && ctype_digit( $_REQUEST['video_width'] ) ) ? intval( $_REQUEST['video_width'] ): $options['video_width'],
		'video_height'					=> ( $_REQUEST['video_height'] >= 1 && $_REQUEST['video_height'] <= 9999 && ctype_digit( $_REQUEST['video_height'] ) ) ? intval( $_REQUEST['video_height'] ): $options['video_height'],
		'continue_button_text'			=> ( empty( $_REQUEST['continue_button_text'] ) ) ? strip_tags( 'Continue to Web Site' ) : strip_tags( stripslashes( $options['continue_button_text'] ) ),
		'enable_age_confirmation'		=> ( $_REQUEST['enable_age_confirmation'] == 'true') ? 1 : 0,
		'reject_text'					=> strip_tags( stripslashes( $_REQUEST['reject_text'] ) ),
		'enable_opt_in'					=> ( $_REQUEST['enable_opt_in'] == 'true') ? 1 : 0,
		'opt_in_reject_text'			=> strip_tags( stripslashes( $_REQUEST['opt_in_reject_text'] ) ),
		'opt_in_text'					=> balanceTags( stripslashes( $_REQUEST['opt_in_text'] ) ),
	);
	
	update_option( 'wp_splash_page_options_preview', $preview );
	
	$result	= array(
		'homeURL'	=> home_url()
	);
		
	header( 'content-type: application/json; charset=utf-8' );
	
	echo json_encode( $result );
	
	die();
	
}
?>