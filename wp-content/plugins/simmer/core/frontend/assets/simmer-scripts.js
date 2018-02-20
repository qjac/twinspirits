jQuery( document ).ready( function( $ ) {

	$( '.simmer-recipe-print' ).click( function( e ) {

		e.preventDefault();

		$( this ).closest( '.simmer-recipe' ).printThis( {
			printContainer: false,
			loadCSS: simmerStrings.printStylesURL
		} );

	} );

} );
