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
	jQuery( '.tm-wrap' ).on( 'blur', '.table-row.table-type-task .task-title', window.eoxiaJS.taskManager.newPoint.editTitle );
	jQuery( '.tm-wrap' ).on( 'click', '.table-row.table-type-task .task-complete-point-field', window.eoxiaJS.taskManager.newPoint.completePoint );

	jQuery( '.tm-wrap' ).on( 'click', '.table-row.table-type-task .task-toggle-comment', window.eoxiaJS.taskManager.newPoint.toggleComments );
};

window.eoxiaJS.taskManager.newPoint.addedPointSuccess = function ( triggeredElement, response ) {
	if ( ! response.data.toggle ) {
		this.loadedPointSuccess( triggeredElement, response );

		jQuery( '.table-type-project[data-id=' + response.data.task_id + '] .fas.fa-angle-right' ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
	} else {
		var tmp = jQuery( response.data.view );
		tmp.css({display: 'none'});
		jQuery( '.table-type-project[data-id=' + response.data.task_id + ']' ).after( tmp );
		tmp.slideDown(400);
	}
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
	data.content   = element.text();

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
	const numberComment = jQuery( this ).closest( '.table-row' ).find( '.number-comments' ).text();
	const isCompleted   = jQuery( this ).closest( '.table-row' ).hasClass( 'task-completed' );

	/*if ( numberComment == 0 && ! isCompleted ) {
		jQuery( '.modal-prompt-point' ).addClass( 'modal-active' );
		jQuery( '.modal-prompt-point input[name="post_id"]' ).val( jQuery( this ).closest( '.point' ).find( 'input[name="parent_id"]').val());
		jQuery( '.modal-prompt-point input[name="point_id"]' ).val( jQuery( this ).closest( '.point' ).find( 'input[name="id"]').val() );
		jQuery( '.modal-prompt-point .content' ).html( '#' + jQuery( this ).closest( '.point' ).find( 'input[name="id"]').val() + ' - ' + jQuery( this ).closest( '.point' ).find( '.point-content input[name="content"]').val() );
		event.preventDefault();
		return false;
	} else {*/
		const data = {
			action:  'complete_point',
			_wpnonce: jQuery( this ).closest( '.table-row' ).data('nonce' ),
			parent_id: jQuery( this ).closest( '.table-row' ).data( 'post-id' ),
			id: jQuery( this ).closest( '.table-row' ).data( 'id' ),
			complete: jQuery( this ).is( ':checked' )
		};

		window.eoxiaJS.request.send( jQuery( this ), data, function( triggeredElement, response ) {
			if ( response.success ) {
				if ( response.data.completed ) {
					triggeredElement.closest( '.table-row' ).addClass( 'task-completed' );
				} else {
					triggeredElement.closest( '.table-row' ).removeClass( 'task-completed' );
				}
			}
		} );
	//}
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

	view.slideDown( 400 );

	triggeredElement.removeClass( 'loading' );

	if ( triggeredElement.hasClass( 'action-attribute' ) ) {
		triggeredElement.attr( 'data-toggle', true );
	}
};

