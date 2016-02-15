jQuery(document).ready(function(jQuery){

	var file_frame;
 
	jQuery( '#wp-splash-page-options-form' ).on( 'click', '#wp-select-image-button', function( event ){

		event.preventDefault();

		if ( file_frame ) {
		
		  file_frame.open();
		  return;
		  
		}

		file_frame = wp.media.frames.file_frame = wp.media({
		
		  title: 'Choose a File',
		  button: {text: jQuery( this ).data( 'uploader_button_text' )},
		  multiple: false
		  
		});

		file_frame.on( 'select', function() {

			attachment = file_frame.state().get( 'selection' ).first().toJSON();

			jQuery( '#image_url' ).val( attachment.url );
			
		});

		file_frame.open();
		
	});
	
});