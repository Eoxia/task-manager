function point_toggle_completed( event, element ) {
	event.preventDefault();

	jQuery( element ).find('.wpeo-point-toggle-arrow').toggleClass('dashicons-plus dashicons-minus');
  jQuery( element ).closest('.wpeo-task-point-use-toggle').find('ul:first').toggle(200);
}

function create_notification( notification ) {
	if ( notification.data.message ) {
		var dashicons = 'yes';
		var message = notification.data.message;
		var type = 'info';

		if ( notification.success === false ) {
			dashicons = 'no';
			type = 'error';
		}
		var data = {
			action: "wpeo-load-notification",
			type: type,
			message: message,
			dashicons: dashicons,
			_wpnonce: jQuery( '.wpeo-container-notification' ).data( 'nonce' ),
		};

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
				notify_and_send_to_callback( response, callback );
			} );
	  }
	};


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
						notify_and_send_to_callback( response, callback );
					}
				} );
				xhr = form_data.data( 'jqxhr' );

			}

			return this;
		};
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
						notify_and_send_to_callback( response, callback );
					}
				} );

				xhr = form_data.data( 'jqxhr' );
	    }

			return this;
		};
	}
} );

function notify_and_send_to_callback( response, callback ) {
	create_notification( response );
	callback.call( response.data );
}
