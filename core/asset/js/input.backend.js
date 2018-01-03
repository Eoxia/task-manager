window.eoxiaJS.taskManager.input = {};

window.eoxiaJS.taskManager.input.init = function() {
	window.eoxiaJS.taskManager.input.event();
};

window.eoxiaJS.taskManager.input.event = function() {
  jQuery( document ).on( 'keyup', '.wpeo-project-wrap .form-element input, .wpeo-project-wrap .form-element textarea', window.eoxiaJS.taskManager.input.keyUp );
};

window.eoxiaJS.taskManager.input.keyUp = function( event ) {
	if ( 0 < jQuery( this ).val().length ) {
		jQuery( this ).closest( '.form-element' ).addClass( 'form-active' );
	} else {
		jQuery( this ).closest( '.form-element' ).removeClass( 'form-active' );
	}
};
