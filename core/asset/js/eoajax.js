
function create_notification( notification ) {
	if ( notification.data.message ) {
		var dashicons = 'yes';
		var message = notification.data.message;
		var type = 'info';

		if ( notification.success == false ) {
			dashicons = 'no';
			type = 'error';
		}
		var data = {
			action: "wpeo-load-notification",
			type: type,
			message: message,
			dashicons: dashicons,
			_wpnonce: jQuery( '.wpeo-container-notification' ).data( 'nonce' ),
		}

		jQuery('.wpeo-container-notification').append('<div></div>');

		var my_div = jQuery('.wpeo-container-notification div:last');
		my_div.load(ajaxurl, data, function( response ) {
			setTimeout(function() {
				my_div.fadeOut(200);
			}, 3000);
		});
	}
}

jQuery( document ).ready( function() {
	jQuery.eoajax = function( url, data, callback ) {
		if ( typeof callback == 'function' ) {
			jQuery.post( url, data, function( response ) {
				create_notification( response );

				if( response && !response.success ) {
				}
				else {
					callback.call( response.data );
				}
			} );
	  }
	}


	/** Si ajaxForm est activé */
	if( jQuery.fn.ajaxSubmit ) {
		jQuery.eoAjaxSubmit = function( form, data, callback ) {
			if ( xhr ) {
				xhr.abort();
			}

			if ( typeof callback == 'function' ) {
				var form_data = form.ajaxSubmit( {
					'data': data,
					'success': function( response ) {
						create_notification( response );

						if( response && !response.success ) {
						}
						else {
							callback.call( response.data );
						}
					}
				} );
				xhr = form_data.data( 'jqxhr' );

			}

			return this;
		}
	}

	if( jQuery.fn.ajaxForm ) {
		jQuery.eoAjaxForm = function( form, data, callback ) {
			if ( xhr ) {
				xhr.abort();
			}
			if ( typeof callback == 'function' ) {
				var form_data = form.ajaxForm( {
					'data': data,
					'beforeSubmit': function( arra, $form, options ) {
						return false;
					},
					'success': function( response ) {
						create_notification( response );
						if( response && !response.success ) {
						}
						else {
							callback.call( response.data );
						}
					}
				} );

				xhr = form_data.data( 'jqxhr' );
	    }

			return this;
		}
	}
} );
