/**
 * Gestion des requêtes XHR.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.request ) {
	window.eoxiaJS.request = {};

	window.eoxiaJS.request.init = function() {};

	window.eoxiaJS.request.send = function( element, data, cb ) {
		return jQuery.post( window.ajaxurl, data, function( response ) {
			// Normal loader.
			window.eoxiaJS.loader.remove( element.closest( '.wpeo-loader' ) );

			// Handle button progress.
			if ( element.hasClass( 'button-progress' ) ) {
				element.removeClass( 'button-load' ).addClass( 'button-success' );
				setTimeout( function() {
					element.removeClass( 'button-success' );

					window.eoxiaJS.request.callCB( element, response, cb )
				}, 1000 );
			} else {
				window.eoxiaJS.request.callCB( element, response, cb )
			}
		}, 'json').fail( function() {
			window.eoxiaJS.request.fail( element );
		} );
	};

	window.eoxiaJS.request.get = function( element, url, data, cb ) {
		jQuery.get( url, data, function( response ) {
			window.eoxiaJS.request.callCB( element, response, cb );
		}, 'json' ).fail( function() {
			window.eoxiaJS.request.fail( element );
		} );
	};

	window.eoxiaJS.request.callCB = function( element, response, cb ) {
		if ( cb ) {
			cb( element, response );
		} else {
			if ( response && response.success ) {
				if ( response.data && response.data.namespace && response.data.module && response.data.callback_success ) {
					window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( element, response );
				} else if ( response.data && response.data.module && response.data.callback_success ) {
					window.eoxiaJS[response.data.module][response.data.callback_success]( element, response );
				}
			} else {
				if ( response.data && response.data.namespace && response.data.module && response.data.callback_error ) {
					window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( element, response );
				}
			}
		}
	}

	window.eoxiaJS.request.fail = function( element ) {
		if ( element ) {
			window.eoxiaJS.loader.remove( element.closest( '.wpeo-loader' ) );

			if ( element.hasClass( 'button-progress' ) ) {
				element.removeClass( 'button-load' ).addClass( 'button-error' );
				setTimeout( function() {
					element.removeClass( 'button-error' );
				}, 1000 );
			}
		}
	}
}
