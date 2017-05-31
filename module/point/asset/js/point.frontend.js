window.eoxiaJS.taskManagerFrontend.point = {};

window.eoxiaJS.taskManagerFrontend.point.init = function() {
	window.eoxiaJS.taskManagerFrontend.point.event();
};

window.eoxiaJS.taskManagerFrontend.point.event = function() {
	jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle', window.eoxiaJS.taskManagerFrontend.point.togglePoint );
	jQuery( document ).on( 'click', '.point-content', window.eoxiaJS.taskManagerFrontend.point.setPointActive );

	jQuery( document ).on( 'blur keyup paste keydown click', '.comment .content', window.eoxiaJS.taskManagerFrontend.point.updateHiddenInput );
};

window.eoxiaJS.taskManagerFrontend.point.togglePoint = function( event ) {
	event.preventDefault();
	jQuery( this ).find( '.wpeo-point-toggle-arrow' ).toggleClass( 'dashicons-plus dashicons-minus' );
	jQuery( this ).closest( '.wpeo-task-point-use-toggle' ).find( '.points.completed' ).toggleClass( 'hidden' );
};

window.eoxiaJS.taskManagerFrontend.point.setPointActive = function( event ) {
	jQuery( '.point-content.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
};
