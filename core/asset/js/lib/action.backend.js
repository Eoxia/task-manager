window.task_manager.action = {};

window.task_manager.action.init = function() {
	window.task_manager.action.event();
};

window.task_manager.action.event = function() {
  jQuery( document ).on( 'click', '.action-input', window.task_manager.action.exec_input );
  jQuery( document ).on( 'click', '.action-attribute', window.task_manager.action.exec_attribute );
  jQuery( document ).on( 'click', '.wp-digi-action-delete', window.task_manager.action.delete );
};

window.task_manager.action.exec_input = function( event ) {
  var element = jQuery( this );
	var parent_element = element;

	if ( element.data( 'parent' ) ) {
			parent_element = element.closest( '.' + element.data( 'parent' ) );
	}

	if ( !element[0].getAttribute(' disabled' ) ) {
		element[0].setAttribute( 'disabled', true );
		element.closest( '.wp-digi-bloc-loader' ).addClass( 'wp-digi-bloc-loading' );

		var list_input = window.eoxia_lib.array_form.get_input( parent_element );
		var data = {};
		for (var i = 0; i < list_input.length; i++) {
			if ( list_input[i].name ) {
				data[list_input[i].name] = list_input[i].value;
			}
		}

    window.task_manager.request.send( element, data );
	}
};

window.task_manager.action.exec_attribute = function(event) {
  var element = jQuery( this );

	if ( !element[0].getAttribute(' disabled' ) ) {
		element[0].setAttribute( 'disabled', true );

		if ( jQuery( this ).data( 'confirm' ) ) {
			if ( window.confirm( jQuery( this ).data( 'confirm' ) ) ) {
				element.get_data( function ( data ) {
					element.closest( '.wp-digi-bloc-loader' ).addClass( 'wp-digi-bloc-loading' );
					window.task_manager.request.send( element, data );
				} );
			}
		}
		else {
			element.get_data( function ( data ) {
				element.closest( '.wp-digi-bloc-loader' ).addClass( 'wp-digi-bloc-loading' );
				window.task_manager.request.send( element, data );
			} );
		}
	}
};

window.task_manager.action.delete = function(event) {
  var element = jQuery( this );

	if ( !element[0].getAttribute( 'disabled' ) ) {
  	if ( window.confirm( window.digi_confirm_delete ) ) {
			element[0].setAttribute( 'disabled', true );
	    element.get_data( function ( data ) {
				element.closest( '.wp-digi-bloc-loader' ).addClass( 'wp-digi-bloc-loading' );
	      window.task_manager.request.send( element, data );
	    } );
	  }
	}
};
