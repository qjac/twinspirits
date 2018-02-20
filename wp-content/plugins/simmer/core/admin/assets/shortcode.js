var simmerShortcodeModal;

( function( $ ) {

	var inputs = {};

	simmerShortcodeModal = {

		init: function() {

			inputs.wrap     = $( '.simmer-shortcode-modal-wrap' );
			inputs.backdrop = $( '.simmer-shortcode-modal-background' );
			inputs.select   = $( '.simmer-shortcode-modal-content select' );
			inputs.submit   = $( '.simmer-shortcode-modal-wrap .simmer-submit-shortcode button' );
			inputs.close    = $( '.simmer-shortcode-modal-wrap .simmer-shortcode-modal-close' );
			inputs.cancel   = $( '.simmer-shortcode-modal-wrap .cancel' );

			// Enable Select2 for the dropdown.
			inputs.select.select2();

			inputs.trigger = $( '#simmer-add-recipe' );
			inputs.editor  = $( inputs.trigger ).data( 'editor' );

			$( inputs.trigger ).click( function( event ) {

				event.preventDefault();

				simmerShortcodeModal.open();

			} );

			inputs.select.on( 'change', function() {

				if ( 0 == $( this ).val() ) {
					inputs.submit.prop( 'disabled', true );
				} else {
					inputs.submit.prop( 'disabled', false );
				}

			} );

			inputs.submit.click( function( event ) {

				event.preventDefault();

				simmerShortcodeModal.submit();

			} );

			inputs.close.add( inputs.backdrop ).add( inputs.cancel ).click( function( event ) {
				event.preventDefault();
				simmerShortcodeModal.close();
			} );
		},

		open: function() {

			$( document.body ).addClass( 'modal-open' );

			inputs.submit.prop( 'disabled', true );

			inputs.wrap.show();
			inputs.backdrop.show();

			$( document ).trigger( 'simmer-shortcode-modal-open', inputs.wrap );
		},

		close: function() {

			inputs.backdrop.hide();
			inputs.wrap.hide();

			inputs.select.val( '' );

			$( document.body ).removeClass( 'modal-open' );

			$( document ).trigger( 'simmer-shortcode-modal-close', inputs.wrap );
		},

		submit: function() {

			var recipeID = inputs.select.val();

			if ( '0' !== recipeID ) {

				var shortcode = '[recipe id="' + recipeID + '"]';

				if ( ! tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden() ) {
					// TODO: Insert into text editor.
				} else {
					tinyMCE.execCommand( 'mceInsertContent', false, shortcode );
				}

			}

			simmerShortcodeModal.close();
		}
	};

	$( document ).ready( simmerShortcodeModal.init );

})( jQuery );
