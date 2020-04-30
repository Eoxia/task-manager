/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.newPoint = {};

window.eoxiaJS.taskManager.newPoint.init = function() {
	window.eoxiaJS.taskManager.newPoint.event();
};

window.eoxiaJS.taskManager.newPoint.event = function() {
	jQuery( document ).on( 'blur', '.table-row.table-type-task .task-title', window.eoxiaJS.taskManager.newPoint.editTitle );
	jQuery( document ).on( 'blur', '.table-row.table-type-task  .task-created-date', window.eoxiaJS.taskManager.newPoint.editCreatedDate );
	jQuery( document ).on( 'click', '.table-row.table-type-task .task-complete-point-field', window.eoxiaJS.taskManager.newPoint.completePoint );

	jQuery( document ).on( 'click', '.table-row.table-type-task .task-toggle-comment', window.eoxiaJS.taskManager.newPoint.toggleComments );
};

window.eoxiaJS.taskManager.newPoint.addedPointSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.row-empty' ).remove();
	var element = jQuery( '.table-projects .table-row[data-id=' + response.data.task_id + '] .cell-project-status .table-cell-container' );

	if ( ! response.data.toggle ) {
		response.newTask = true;
		this.loadedPointSuccess( triggeredElement, response );

		jQuery( '.table-type-project[data-id=' + response.data.task_id + '] .wpeo-util-hidden' ).removeClass( 'wpeo-util-hidden' );
		jQuery( '.table-type-project[data-id=' + response.data.task_id + '] .fas.fa-angle-right' ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
		element.find( '.load-complete-point[data-point-state="uncompleted"]' ).addClass( 'active' );
		element.find( '.load-complete-point[data-point-state="uncompleted"]' ).removeClass( 'button-transparent' ).addClass( 'button-main' );
		count_uncompleted_tasks = parseInt ( element.find( '.count-uncompleted-tasks' ).text() );
		count_uncompleted_tasks = count_uncompleted_tasks + 1 ;
		element.find( '.count-uncompleted-tasks' ).html( count_uncompleted_tasks );
	} else {
		var tmp = jQuery( response.data.view );
		tmp.css({display: 'none'});
		jQuery( '.table-type-project[data-id=' + response.data.task_id + ']' ).after( tmp );
		tmp.slideDown(400);
		window.eoxiaJS.taskManager.core.selectContentEditable( tmp.find( '.task-title' ) );
		count_uncompleted_tasks = parseInt ( element.find( '.count-uncompleted-tasks' ).text() );
		count_uncompleted_tasks = count_uncompleted_tasks + 1 ;
		element.find( '.count-uncompleted-tasks' ).html( count_uncompleted_tasks );
	}


	window.eoxiaJS.taskManager.newTask.stickyAction();

};

window.eoxiaJS.taskManager.newPoint.editTitle = function() {
	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action    = 'edit_point';
	data._wpnonce  = element.closest( '.table-row' ).data( 'nonce' );
	data.id        = element.closest( '.table-row' ).data( 'id' );
	data.parent_id = element.closest( '.table-row' ).data( 'post-id' );
	data.content   = element.html();

	data.notif = window.eoxiaJS.taskManager.comment.searchFollowerInContentEditable( element );

	window.eoxiaJS.loader.display( element.closest( 'div' ) );
	window.eoxiaJS.request.send( element, data );
};

window.eoxiaJS.taskManager.newPoint.editCreatedDate = function() {
	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action  = 'edit_created_date';
	data.id        = element.closest( '.table-row' ).data( 'id' );
	data.created_date   = element.closest( '.table-row' ).find( '.mysql-date' ).val();

	window.eoxiaJS.loader.display( element.closest( 'div' ) );
	window.eoxiaJS.request.send( element, data );
};

/**
 * Envoie une requête pour passer le point en compléter ou décompléter.
 * Déplace le point vers la liste à puce "compléter" ou "décompléter".
 *
 * @since 1.0.0
 */
window.eoxiaJS.taskManager.newPoint.completePoint = function( event ) {
	const data = {
		action:  'complete_point',
		_wpnonce: jQuery( this ).closest( '.table-row' ).data('nonce' ),
		parent_id: jQuery( this ).closest( '.table-row' ).data( 'post-id' ),
		id: jQuery( this ).closest( '.table-row' ).data( 'id' ),
		complete: jQuery( this ).is( ':checked' )
	};

	window.eoxiaJS.request.send( jQuery( this ), data, function( triggeredElement, response ) {
		var tableRow = triggeredElement.closest( '.table-row' );
		var projectID = tableRow.attr( 'data-post-id' );
		var element = jQuery( '.table-projects .table-row[data-id=' + projectID + '] .cell-project-status .table-cell-container' );

		if ( response.success ) {
			if ( response.data.completed ) {
				tableRow.addClass( 'task-completed' );
				if (jQuery( '.table-projects .table-row[data-id=' + projectID + '] .load-complete-point.active[data-point-state=completed]' ).length > 0) {
					jQuery( '.table-projects .table-row[data-post-id=' + projectID + ']:last' ).after( tableRow );
				} else {
					count_uncompleted_tasks = element.find( '.count-uncompleted-tasks' ).text();
					count_completed_tasks   = element.find( '.count-completed-tasks' ).text();
					count_uncompleted_tasks = count_uncompleted_tasks - 1;
					count_completed_tasks   =  parseInt ( count_completed_tasks ) + 1;
					element.find( '.count-uncompleted-tasks' ).html( count_uncompleted_tasks );
					element.find( '.count-completed-tasks' ).text( count_completed_tasks );
					tableRow.fadeOut();
				}
			} else {
				count_uncompleted_tasks = parseInt ( element.find( '.count-uncompleted-tasks' ).text());
				count_completed_tasks   = element.find( '.count-completed-tasks' ).text();
				count_uncompleted_tasks = parseInt ( count_uncompleted_tasks + 1 );
				count_completed_tasks   = count_completed_tasks - 1;
				element.find( '.count-uncompleted-tasks' ).html( count_uncompleted_tasks );
				element.find( '.count-completed-tasks' ).text( count_completed_tasks );
				tableRow.removeClass( 'task-completed' );
			}
		}
	} );
};

window.eoxiaJS.taskManager.newPoint.toggleComments = function() {
	const taskID = jQuery( this ).closest( '.table-row' ).data( 'id' );
	if ( jQuery( this ).find( '.fas' ).hasClass( 'fa-angle-down' ) ) {

		jQuery( this ).find( '.fas' ).removeClass( 'fa-angle-down' ).addClass( 'fa-angle-right' );
		jQuery( '.table-type-comment[data-parent-id=' + taskID + ']' ).slideUp(400, function() {
			jQuery( this ).remove();
		});

		jQuery( '.table-type-task[data-id=' + taskID + '] .task-add div[data-action="edit_comment"]' ).attr( 'data-toggle', false );
	} else {
		var data = {};
		var element;

		if ( ! element ) {
			element = jQuery( this );
		}

		data.action = 'load_comments';
		data._wpnonce = element.closest( '.table-row' ).data( 'nonce' );
		data.id = element.closest( '.table-row' ).data( 'id' );
		data.parent_id = element.closest( '.table-row' ).data('post-id');

		window.eoxiaJS.loader.display( element );
		window.eoxiaJS.request.send( element, data );

		jQuery( this ).find( '.fas' ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
		jQuery( '.table-type-task[data-id=' + taskID + '] .task-add div[data-action="edit_comment"]' ).attr( 'data-toggle', true );
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_point".
 * Met le contenu dans la div.point.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.newPoint.loadedPointSuccess = function( triggeredElement, response ) {
	var view = jQuery( response.data.view );
	view.css({display: 'none'});

	var row = triggeredElement.closest( '.table-row' );
	row.after(view);

	view.slideDown(400);

	window.eoxiaJS.taskManager.newTask.stickyAction();

	triggeredElement.removeClass( 'loading' );
	if ( triggeredElement.hasClass( 'action-attribute' ) ) {
		triggeredElement.attr( 'data-toggle', true );
	}

	if (triggeredElement.hasClass( 'cell-toggle' ) ) {
		triggeredElement.closest( '.table-row' ).find( '.load-complete-point:not(.active)[data-point-state="uncompleted"]' ).addClass( 'active' );
	}

	if ( 'addedPointSuccess' == response.data.callback_success ) {
		window.eoxiaJS.taskManager.core.selectContentEditable( jQuery( '.table-type-task[data-id=' + response.data.point.data.id + '] .task-title' ) );
	}

};

