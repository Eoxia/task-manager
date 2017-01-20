window.eoxiaJS.array_form = {};

window.eoxiaJS.array_form.init = function() {
	window.eoxiaJS.array_form.event();
};

window.eoxiaJS.array_form.event = function() {
	jQuery( document ).on( 'click', '.wp-digi-action-edit', window.eoxiaJS.array_form.send_form );
};

window.eoxiaJS.array_form.get_input = function( parent ) {
	return parent.find('input, textarea');
};

window.eoxiaJS.array_form.get_input_value = function( input ) {
	switch( input.getAttribute( 'type' ) ) {
		case "checkbox":
			return input.checked;
			break;
		default:
			return input.value;
			break;
	}
}

window.eoxiaJS.array_form.send_form = function( event ) {
	event.preventDefault();

	var element = jQuery( this );
	element.closest( '.wp-digi-bloc-loader' ).addClass( 'wp-digi-bloc-loading' );
	var parent = element.closest( '.wp-digi-list-item' );
	var list_input = window.eoxiaJS.array_form.get_input( parent );

	var data = {};
	for (var i = 0; i < list_input.length; i++) {
		if ( list_input[i].name ) {
			data[list_input[i].name] = window.eoxiaJS.array_form.get_input_value( list_input[i] );
		}
	}

	window.task_manager.request.send( element, data );
};
