/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.newTask = {};

window.eoxiaJS.taskManager.newTask.init = function() {
	window.eoxiaJS.taskManager.newTask.event();
};

window.eoxiaJS.taskManager.newTask.event = function() {
	jQuery( '.tm-wrap' ).on( 'blur', '.table-projects .table-type-project  .project-title', window.eoxiaJS.taskManager.newTask.editTitle );
	jQuery( '.tm-wrap' ).on( 'click', '.table-type-project .project-toggle-task', window.eoxiaJS.taskManager.newTask.togglePoints );
	jQuery( '.tm-wrap' ).on( 'click', '.table-type-project .project-state .dropdown-item',  window.eoxiaJS.taskManager.newTask.displayState );
	jQuery( '.tm-wrap' ).on( 'click', '.list-task .table-cell div[contenteditable="true"]', function(e) {
		jQuery( '.cell-focus .table-cell.cell-focus' ).removeClass( 'cell-focus' );
		jQuery( this ).closest( '.table-cell' ).addClass( 'cell-focus' );
	});
	jQuery( '.tm-wrap' ).on( 'blur', '.list-task .table-cell.cell-focus', function(e) {
		jQuery( this ).removeClass( 'cell-focus' );
	});
	jQuery( '.list-task' ).on( 'scroll', window.eoxiaJS.taskManager.newTask.stickyAction );
	window.eoxiaJS.taskManager.newTask.stickyAction();

};

window.eoxiaJS.taskManager.newTask.editTitle = function() {
	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action  = 'edit_title';
	data.task_id = element.closest( '.table-row' ).data( 'id' );
	data.title   = element.text();

	window.eoxiaJS.loader.display( element.closest( 'div' ) );
	window.eoxiaJS.request.send( element, data );
};

window.eoxiaJS.taskManager.newTask.togglePoints = function() {
	const projectID = jQuery( this ).closest( '.table-row' ).data( 'id' );
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	if ( jQuery( this ).find( '.fas' ).hasClass( 'fa-angle-down' ) ) {

		jQuery( this ).find( '.fas' ).removeClass( 'fa-angle-down' ).addClass( 'fa-angle-right' );
		jQuery( '.table-type-task[data-post-id=' + projectID + ']' ).slideUp(400, function() {
			jQuery( this ).remove();
		});
		jQuery( '.table-type-comment[data-post-id=' + projectID + ']' ).slideUp(400, function() {
			jQuery( this ).remove();
		});

		jQuery( '.table-type-project[data-id=' + projectID + '] .cell-sticky div[data-action="edit_point"]' ).attr( 'data-toggle', false );

	} else {
		var data = {};

		data.action   = 'load_point';
		data._wpnonce = element.data( 'nonce' );
		data.task_id  = element.data( 'id' );
		window.eoxiaJS.loader.display( element );
		window.eoxiaJS.request.send( element, data );

		jQuery( this ).find( '.fas' ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
		jQuery( '.table-type-project[data-id=' + projectID + '] .cell-sticky div[data-action="edit_point"]' ).attr( 'data-toggle', true );
	}
};

window.eoxiaJS.taskManager.newTask.displayState = function ( event ) {
	var state          = jQuery( this ).attr( 'data-state' );
	var parent_element = jQuery( this ).closest( '.project-state' );
	parent_element.find( 'input[name="state"]' ).val( state );

	var this_html = jQuery( this ).html();
	parent_element.find( '.dropdown-toggle' ).html( this_html );

	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}
	data.action   = 'task_state';
	data.task_id  = parent_element.data( 'id' );
	data.state = state;
	window.eoxiaJS.loader.display( element );
	window.eoxiaJS.request.send( element, data );
};

window.eoxiaJS.taskManager.newTask.taskStateSuccess = function( element, response ) {
	jQuery( element ).closest( '.table-column' ).replaceWith( response.data.view );
};

window.eoxiaJS.taskManager.newTask.stickyAction = function( e ) {
	var tables = jQuery( '.list-task' );

	tables.each( function( i ) {
		var scrollPos = jQuery( this ).scrollLeft();
		var finalPos = scrollPos + jQuery( this ).width() - 100;

		jQuery( this ).find( '.cell-sticky' ).css({left: finalPos});
	});

	// console.log('Niveau de scroll : ' + e.target.scrollLeft);
	// console.log('Niveau de scroll width : ' + e.target.scrollWidth);
	// console.log('Niveau de scroll width supposé : ' + scrollPos);
	// console.log('Taille du contenaire : ' + jQuery( this ).width());
	// console.log('Calcul : ' + (e.target.scrollLeft + jQuery( this ).width() - 100));

	// var finalPos = scrollPos + jQuery(this).width() - 100;
}
