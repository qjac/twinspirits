( function( $ ) {

	$( document ).ready( function() {

		// If "enqueue styles" is unchecked, hide the style options.
		if ( ! $( '#simmer_enqueue_styles' ).is( ':checked' ) ) {

			var styleSettings = $( '#simmer_enqueue_styles' ).parents( 'tr' ).nextAll();

			$( styleSettings ).hide();
		}

		// Hide/show the style options when "enqueue styles" is checked/unchecked.
		$( '#simmer_enqueue_styles' ).click( function() {
			$( styleSettings ).toggle();
		} );

		// Init the WordPress color picker.
		$( '#simmer_recipe_accent_color' ).wpColorPicker();
		$( '#simmer_recipe_text_color' ).wpColorPicker();

	} );

} )( jQuery );
