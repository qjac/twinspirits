jQuery( function() {
	
	jQuery( '#remove_image' ).click(function( event ) {
		
		event.preventDefault();
		
		jQuery( '#image_url' ).val( '' );
		
	});
	
	jQuery( '#video_width' ).change(function( event ) {
		
		event.preventDefault();
		
		var text	= jQuery( '#span-number' ).text();
		jQuery( '#span-number' ).text( text.replace( /\(.*?\)/g, '( ' + Math.round( ( jQuery( '#video_width' ).val() / 16 ) * 9 ) + ' )' ) );
				
	});
	
	
	
	jQuery( '#wp-splash-page-preview' ).click( function( event ) {	
		
		event.preventDefault();
		
		var data = '',
		repeat = 'no-repeat',
		center = 'left top';
		
		if ( jQuery( '#repeat_image' ).is( ':checked' ) )
			repeat	= 'repeat';
			
		if ( jQuery( '#center_image' ).is( ':checked' ) )
			center	= 'center top';
		
		data += 'template=' + jQuery( '#template' ).val();			
		data += '&page_title=' +jQuery( '#page_title' ).val();
		data += '&title=' + jQuery( '#title' ).val();
		data += '&title_color=' + jQuery( '#title_color' ).val().replace('#', '');
		data += '&text=' + jQuery( '#text' ).val();
		data += '&text_color=' + jQuery( '#text_color' ).val().replace('#', '');
		data += '&continue_button_text=' + jQuery( '#continue_button_text' ).val();
		data += '&continue_button_bg_color=' + jQuery( '#continue_button_bg_color' ).val().replace('#', '');
		data += '&continue_button_text_color=' + jQuery( '#continue_button_text_color' ).val().replace('#', '');
		data += '&image_url=' + jQuery( '#image_url' ).val();
		data += '&repeat_image=' + repeat;
		data += '&center_image=' + center;	
		data += '&background_color=' + jQuery( '#background_color' ).val().replace('#', '');
		data += '&youtube_id=' + jQuery( '#youtube_id' ).val();
		data += '&video_autoplay=' + jQuery( '#video_autoplay' ).is( ':checked' );
		data += '&video_width=' + jQuery( '#video_width' ).val();
		data += '&video_height=' + jQuery( '#video_height' ).val();
		data += '&enable_age_confirmation=' + jQuery( '#enable_age_confirmation' ).is( ':checked' );
		data += '&reject_text=' + jQuery( '#reject_text' ).val();
		data += '&enable_opt_in=' + jQuery( '#enable_opt_in' ).is( ':checked' );
		data += '&opt_in_text=' + jQuery( '#opt_in_text' ).val();
		data += '&opt_in_reject_text=' + jQuery( '#opt_in_reject_text' ).val();
		data += '&nonce=' + wp_splash_page_data.previewSecurity;
		
		jQuery.ajax({
		
			url:		wp_splash_page_data.ajaxurl,
			data:		data + '&action=wp_splash_page_hook',
			type:		'POST',
			async:		false,
			dataType:	'json',
			
			success: function( result ) {
				
				window.open( result.homeURL + '?mode=wpsp_preview', '_newtab' );
	
			},
			
			error: function( xhr, textStatus, errorThrown ) {
				
				
			}
			
		});
		
	});
	
});