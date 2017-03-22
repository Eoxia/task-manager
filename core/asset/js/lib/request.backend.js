window.task_manager.request = {};

window.task_manager.request.init = function() {};

window.task_manager.request.send = function( element, data ) {
	jQuery.post( window.ajaxurl, data, function( response ) {
		element.closest( '.loading' ).removeClass( 'loading' );

		if ( response && response.success ) {
			if ( response.data.module && response.data.callback_success ) {
				window.task_manager[response.data.module][response.data.callback_success]( element, response );
			}
		} else {
			if ( response.data.module && response.data.callback_error ) {
				window.task_manager[response.data.module][response.data.callback_error]( element, response );
			}
		}
	}, 'json' );
};

window.task_manager.request.get = function( url, data ) {
	jQuery.get( url, data, function( response ) {
		if ( response && response.success ) {
			if ( response.data.module && response.data.callback_success ) {
				window.task_manager[response.data.module][response.data.callback_success]( response );
			}
		} else {
			if ( response.data.module && response.data.callback_error ) {
				window.task_manager[response.data.module][response.data.callback_error]( response );
			}
		}
	}, 'json' );
};
