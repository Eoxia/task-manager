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
	jQuery( '.tm-wrap' ).on( 'blur', '.task-column .task-title', window.eoxiaJS.taskManager.newPoint.editTitle );
	jQuery( '.tm-wrap' ).on( 'click', '.task-column .task-complete-point', window.eoxiaJS.taskManager.newPoint.completePoint );

	jQuery( '.tm-wrap' ).on( 'click', '.task-column .task-toggle-comment', window.eoxiaJS.taskManager.newPoint.toggleComments );
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
	data._wpnonce  = element.closest( '.task-column' ).data( 'nonce' );
	data.id        = element.closest( '.task-column' ).data( 'id' );
	data.parent_id = element.closest( '.task-column' ).data( 'parent-id' );
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
	const numberComment = jQuery( this ).closest( '.task-column' ).find( '.number-comments' ).text();
	const isCompleted   = jQuery( this ).closest( '.task-column' ).hasClass( 'task-completed' );

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
			_wpnonce: jQuery( this ).closest( '.task-column' ).data('nonce' ),
			parent_id: jQuery( this ).closest( '.task-column' ).data( 'parent_id' ),
			id: jQuery( this ).closest( '.task-column' ).data( 'id' ),
			complete: jQuery( this ).is( ':checked' )
		};

		window.eoxiaJS.request.send( jQuery( this ), data, function( triggeredElement, response ) {
			if ( response.success ) {
				if ( response.data.completed ) {
					triggeredElement.closest( '.task-column' ).addClass( 'task-completed' );
				} else {
					triggeredElement.closest( '.task-column' ).removeClass( 'task-completed' );
				}
			}
		} );
	}
};

window.eoxiaJS.taskManager.newPoint.toggleComments = function() {
	if ( jQuery( this ).hasClass( 'fa-angle-down' ) ) {
		jQuery( this ).removeClass( 'fa-angle-down' ).addClass( 'fa-angle-right' );
		jQuery( this ).closest( '.task-column' ).find( '.column-extend' ).slideUp( 400 );
	} else {
		var data = {};
		var element;

		if ( ! element ) {
			element = jQuery( this );
		}

		data.action = 'load_comments';
		data._wpnonce = element.closest( '.task-column' ).data( 'nonce' );
		data.id = element.closest( '.task-column' ).data( 'id' );
		data.parent_id = element.closest( '.task-column' ).data('parent-id');

		window.eoxiaJS.loader.display( element.closest( '.task-column' ) );
		window.eoxiaJS.request.send( element, data );

		jQuery( this ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
	}
};
