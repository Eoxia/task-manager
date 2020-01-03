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
	jQuery( '.tm-wrap' ).on( 'blur', '.table-column .task-title', window.eoxiaJS.taskManager.newPoint.editTitle );
	jQuery( '.tm-wrap' ).on( 'click', '.table-column .task-complete-point', window.eoxiaJS.taskManager.newPoint.completePoint );

	jQuery( '.tm-wrap' ).on( 'click', '.table-column .task-toggle-comment', window.eoxiaJS.taskManager.newPoint.toggleComments );
};

window.eoxiaJS.taskManager.newPoint.addedPointSuccess = function ( triggeredElement, response ) {
	var tmp = jQuery( response.data.view );
	tmp.css({display: 'none'});
	triggeredElement.closest( '.column-extend' ).find( '.table-header' ).after( tmp );
	tmp.slideDown(400);
};

window.eoxiaJS.taskManager.newPoint.editTitle = function() {
	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action    = 'edit_point';
	data._wpnonce  = element.closest( '.table-column' ).data( 'nonce' );
	data.id        = element.closest( '.table-column' ).data( 'id' );
	data.parent_id = element.closest( '.table-column' ).data( 'parent-id' );
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
	const numberComment = jQuery( this ).closest( '.table-column' ).find( '.number-comments' ).text();
	const isCompleted   = jQuery( this ).closest( '.table-column' ).hasClass( 'task-completed' );

	if ( numberComment == 0 && ! isCompleted ) {
		jQuery( '.modal-prompt-point' ).addClass( 'modal-active' );
		jQuery( '.modal-prompt-point input[name="post_id"]' ).val( jQuery( this ).closest( '.point' ).find( 'input[name="parent_id"]').val());
		jQuery( '.modal-prompt-point input[name="point_id"]' ).val( jQuery( this ).closest( '.point' ).find( 'input[name="id"]').val() );
		jQuery( '.modal-prompt-point .content' ).html( '#' + jQuery( this ).closest( '.point' ).find( 'input[name="id"]').val() + ' - ' + jQuery( this ).closest( '.point' ).find( '.point-content input[name="content"]').val() );
		event.preventDefault();
		return false;
	} else {
		const data = {
			action:  'complete_point',
			_wpnonce: jQuery( this ).closest( '.table-column' ).data('nonce' ),
			parent_id: jQuery( this ).closest( '.table-column' ).data( 'parent_id' ),
			id: jQuery( this ).closest( '.table-column' ).data( 'id' ),
			complete: jQuery( this ).is( ':checked' )
		};

		window.eoxiaJS.request.send( jQuery( this ), data, function( triggeredElement, response ) {
			if ( response.success ) {
				if ( response.data.completed ) {
					triggeredElement.closest( '.table-column' ).addClass( 'task-completed' );
				} else {
					triggeredElement.closest( '.table-column' ).removeClass( 'task-completed' );
				}
			}
		} );
	}
};

window.eoxiaJS.taskManager.newPoint.toggleComments = function() {
	if ( jQuery( this ).hasClass( 'fa-angle-down' ) ) {
		jQuery( this ).removeClass( 'fa-angle-down' ).addClass( 'fa-angle-right' );
		jQuery( this ).closest( '.table-column' ).find( '.column-extend' ).slideUp( 400 );
	} else {
		var data = {};
		var element;

		if ( ! element ) {
			element = jQuery( this );
		}

		data.action = 'load_comments';
		data._wpnonce = element.closest( '.table-column' ).data( 'nonce' );
		data.id = element.closest( '.table-column' ).data( 'id' );
		data.parent_id = element.closest( '.table-column' ).data('parent-id');

		window.eoxiaJS.loader.display( element.closest( '.table-column' ) );
		window.eoxiaJS.request.send( element, data );

		jQuery( this ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
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
	var taskColumn = triggeredElement.closest( '.table-column' );
	console.log( 'ok');
	taskColumn.find( '.column-extend' ).html( response.data.view );

	triggeredElement.removeClass( 'loading' );
	taskColumn.find( '.column-extend' ).slideDown( 400, function() {
	} );
};
