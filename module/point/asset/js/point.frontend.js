window.eoxiaJS.taskManagerFrontend.point = {};

window.eoxiaJS.taskManagerFrontend.point.init = function() {
	window.eoxiaJS.taskManagerFrontend.point.event();
};

window.eoxiaJS.taskManagerFrontend.point.event = function() {
	jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle', window.eoxiaJS.taskManagerFrontend.point.togglePoint );
	jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle .points.completed', function( e ) { event.preventDefault(); return false; } );
	jQuery( document ).on( 'click', '.point-content', window.eoxiaJS.taskManagerFrontend.point.setPointActive );

	jQuery( document ).on( 'click', '.point-type-display-buttons button.active', window.eoxiaJS.taskManagerFrontend.point.undisplayPoint );
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

/**
 * Méthode appelée lors du clic sur les boutons de hoix du type de points affichés dans une tâche.
 *
 * @since 1.8.0
 *
 * @param  {type} event  L'événement lancé lors de l'action.
 */
window.eoxiaJS.taskManagerFrontend.point.undisplayPoint = function( event ) {
	var pointState = jQuery( this ).attr( 'data-point-state' );
	event.preventDefault();
	jQuery( this ).removeClass( 'active' ).addClass( 'action-input' );
	jQuery( this ).closest( '.wpeo-project-task-container' ).find( '.points .point.edit[data-point-state="' + pointState + '"]' ).remove();

	window.eoxiaJS.refresh();
};
