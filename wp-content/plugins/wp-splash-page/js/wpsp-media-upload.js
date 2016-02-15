jQuery( function() {

	jQuery( '#wp-select-image-button' ).click(function( event ) {
		
		event.preventDefault();
		
        tb_show( 'Select a Image', 'media-upload.php?referer=wp-splash-page-settings&post_id=0&type=image&TB_iframe=true', false );
        return false;
		
    });
	
	window.send_to_editor = function( html ) {
	
		var image_url = jQuery( 'img', html ).attr( 'src' );
		jQuery( '#image_url' ).val( image_url );
		tb_remove();
		
	}
	
});