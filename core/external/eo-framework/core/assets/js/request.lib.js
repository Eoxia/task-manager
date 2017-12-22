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
		jQuery.post( window.ajaxurl, data, function( response ) {
			window.eoxiaJS.loader.remove( element.closest( '.wpeo-loader' ) );

			if ( element.hasClass( 'button-progress' ) ) {
				element.removeClass( 'button-load' ).addClass( 'button-success' );
				setTimeout( function() {
					element.removeClass( 'button-success' );

					if ( cb ) {
						cb( element, response );
					} else {
						if ( response && response.success ) {
							if ( response.data.namespace && response.data.module && response.data.callback_success ) {
								window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( element, response );
							} else if ( response.data.module && response.data.callback_success ) {
								window.eoxiaJS[response.data.module][response.data.callback_success]( element, response );
							}
						} else {
							if ( response.data.namespace && response.data.module && response.data.callback_error ) {
								window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( element, response );
							}
						}
					}
				}, 1000 );
			} else {
				if ( cb ) {
					cb( element, response );
				} else {
					if ( response && response.success ) {
						if ( response.data.namespace && response.data.module && response.data.callback_success ) {
							window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( element, response );
						} else if ( response.data.module && response.data.callback_success ) {
							window.eoxiaJS[response.data.module][response.data.callback_success]( element, response );
						}
					} else {
						if ( response.data.namespace && response.data.module && response.data.callback_error ) {
							window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( element, response );
						}
					}
				}
			}
		}, 'json').fail( function() {
			window.eoxiaJS.loader.remove( element.closest( '.wpeo-loader' ) );

			if ( element.hasClass( 'button-progress' ) ) {
				element.removeClass( 'button-load' ).addClass( 'button-error' );
				setTimeout( function() {
					element.removeClass( 'button-error' );
				}, 1000 );
			}
		});
	};

	window.eoxiaJS.request.get = function( url, data ) {
		jQuery.get( url, data, function( response ) {
			if ( response && response.success ) {
				if ( response.data.namespace && response.data.module && response.data.callback_success ) {
					window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( response );
				}
			} else {
				if ( response.data.namespace && response.data.module && response.data.callback_error ) {
					window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( response );
				}
			}
		}, 'json' );
	};

}
