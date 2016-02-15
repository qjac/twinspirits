jQuery( function() {
	
	jQuery( '.wpsp-input-color' )
		.click(function () {
		
			jQuery( '.wpsp-colorpicker' ).hide(); 
			jQuery( '#wpsp-colorpicker-' + this.id ).show();
			jQuery.farbtastic( '#wpsp-colorpicker-' + this.id ).linkTo( this );
			
		})
		.each(function () { 
		
			jQuery.farbtastic( '#wpsp-colorpicker-' + this.id ).linkTo( this ); 
			
		})
		
	jQuery(document).mousedown(function() {
        
		jQuery( '.wpsp-colorpicker' ).each(function() {
		
            var display = jQuery( this ).css( 'display' );
            if ( display == 'block' ) jQuery( this ).fadeOut();
			
        });
		
    });
});