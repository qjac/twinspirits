var simmerBulkModal;

( function( $ ) {

	var inputs = {};

	simmerBulkModal = {

		init: function() {

			inputs.wrap     = $( '.simmer-bulk-modal-wrap' );
			inputs.backdrop = $( '.simmer-bulk-modal-background' );
			inputs.text     = $( '.simmer-bulk-modal-content textarea' );
			inputs.nonce    = $( '#simmer_process_bulk_nonce' );
			inputs.submit   = $( '.simmer-bulk-modal-wrap .simmer-submit-bulk button' );
			inputs.close    = $( '.simmer-bulk-modal-wrap .simmer-bulk-modal-close' );
			inputs.cancel   = $( '.simmer-bulk-modal-wrap .cancel' );

			inputs.trigger = $( '.simmer-list-table .simmer-actions .simmer-bulk-add-link' );

			$( inputs.trigger ).click( function( event ) {

				event.preventDefault();

				inputs.type = $( this ).data( 'type' );

				simmerBulkModal.open();

			} );

			inputs.submit.click( function( event ) {

				event.preventDefault();

				simmerBulkModal.submit();

			} );

			inputs.close.add( inputs.backdrop ).add( inputs.cancel ).click( function( event ) {
				event.preventDefault();
				simmerBulkModal.close();
			} );
		},

		open: function() {

			$( document.body ).addClass( 'modal-open' );

			inputs.wrap.show();
			inputs.backdrop.show();

			if ( 'ingredient' == inputs.type ) {
				var titleText        = simmer_bulk_add_vars.ingredients_title;
				var helpText         = simmer_bulk_add_vars.ingredients_help;
				var placeholderText  = simmer_bulk_add_vars.ingredients_placeholder;
				var buttonText       = simmer_bulk_add_vars.ingredients_button;
			} else if ( 'instruction' == inputs.type ) {
				var titleText        = simmer_bulk_add_vars.instructions_title;
				var helpText         = simmer_bulk_add_vars.instructions_help;
				var placeholderText  = simmer_bulk_add_vars.instructions_placeholder;
				var buttonText       = simmer_bulk_add_vars.instructions_button;
			}

			inputs.wrap.find( '.simmer-bulk-modal-title' ).text( titleText );
			inputs.wrap.find( '.simmer-bulk-help' ).text( helpText );
			inputs.text.attr( 'placeholder', placeholderText );
			inputs.submit.text( buttonText );

			$( document ).trigger( 'simmer-bulk-modal-open', inputs.wrap );
		},

		close: function() {

			inputs.backdrop.hide();
			inputs.wrap.hide();

			inputs.text.val( '' );

			$( document.body ).removeClass( 'modal-open' );

			$( document ).trigger( 'simmer-bulk-modal-close', inputs.wrap );
		},

		submit: function() {

			inputs.wrap.find( '.spinner' ).show();

			$.ajax( {
				url: simmer_bulk_add_vars.ajax_url,
				dataType: 'json',
				type: 'post',
				data: {
					action: 'simmer_process_bulk',
					type: inputs.type,
					text: inputs.text.val(),
					nonce: inputs.nonce.val()
				},
				success: function( response ) {

					inputs.wrap.find( '.spinner' ).hide();

					if ( ! response.error ) {

						$.each( response,  function( index, item ) {
							simmerBulkModal.addRow( inputs.type, item );
						} );

						simmerBulkModal.close();

					} else {

						$( '.simmer-bulk-modal-content' ).find( '.error' ).remove();

						if ( response.message ) {
							var message = response.message;
						} else {
							var message = simmer_bulk_add_vars.error_message;
						}

						$( '.simmer-bulk-modal-content' ).prepend( '<div class="error"><p>' + message + '</p></div>' );
					}
				}
			} );
		},

		addRow: function( type, item ) {

			row = $( 'tr.simmer-' + type ).last();

			clone = row.clone();

			var count = row.parent().find( 'tr' ).length;

			if ( 'ingredient' === type ) {
				clone.find( '.simmer-amt input:not([type="hidden"])' ).val( item.amount );
				clone.find( '.simmer-unit select:not([type="hidden"])' ).val( item.unit );
				clone.find( '.simmer-desc input:not([type="hidden"])' ).val( item.description );
			} else if ( 'instruction' === type ) {
				clone.find( '.simmer-desc textarea' ).val( item.description );
			}

			clone.removeClass( 'simmer-row-hidden' );
			clone.addClass( 'new-row' );

			clone.find( 'input, select, textarea' ).each( function() {

				name = $( this ).attr( 'name' );

				name = name.replace( /\[(\d+)\]/, '[' + parseInt( count ) + ']');

				$( this ).attr( 'name', name ).attr( 'id', name );

			} );

			clone.find( '.simmer-sort input.simmer-order' ).attr( 'value', parseInt( count ) );
			clone.find( '.simmer-sort input.simmer-id' ).attr( 'value', '' );

			clone.insertAfter( row );

			setTimeout( function() {
				$( '.simmer-list-table .new-row' ).removeClass( 'new-row' );
			}, 100 );
		}
	};

	$( document ).ready( simmerBulkModal.init );

})( jQuery );
