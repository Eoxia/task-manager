window.eoxiaJS.taskManagerFrontend.comment = {};

window.eoxiaJS.taskManagerFrontend.comment.init = function() {
	window.eoxiaJS.taskManagerFrontend.comment.event();
};

window.eoxiaJS.taskManagerFrontend.comment.event = function() {
	jQuery( document ).on( 'blur keyup paste keydown click', '.comment .content', window.eoxiaJS.taskManagerFrontend.comment.updateHiddenInput );
	jQuery( document ).on( 'click', '.point.edit .point-container', window.eoxiaJS.taskManagerFrontend.comment.loadComments );
};

window.eoxiaJS.taskManagerFrontend.comment.beforeLoadComment = function( triggeredElement, response ) {
	if ( ! triggeredElement.closest( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp();
	}

	if ( triggeredElement.closest( 'div.point' ).find( '.comments' ).is( ':visible'  ) ) {
		triggeredElement.closest( '.point' ).find( '.wpeo-point-summary .fa' ).toggleClass( 'fa-angle-down fa-angle-right' );
		triggeredElement.closest( 'div.point' ).find( '.comments' ).slideUp();

		return false;
	}

	jQuery( '.wpeo-project-task .point .wpeo-point-summary .fa.fa-angle-down' ).toggleClass( 'fa-angle-right fa-angle-down' );

	return true;
};

window.eoxiaJS.taskManagerFrontend.comment.loadedCommentsSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.point' ).find( '.wpeo-point-summary .fa' ).toggleClass( 'fa-angle-right fa-angle-down' );
	triggeredElement.closest( '.point' ).find( '.comments' ).html( response.data.view );
	triggeredElement.closest( 'div.point' ).find( '.comments' ).slideDown( 400, function() {
		window.eoxiaJS.taskManagerFrontend.task.refresh();
	} );
};

window.eoxiaJS.taskManagerFrontend.comment.addedCommentSuccess = function( triggeredElement, response ) {
	var currentNumberComment = parseInt( triggeredElement.closest( '.point' ).find( '.wpeo-point-comment span:last' ).text() );
	currentNumberComment++;

	triggeredElement.closest( '.wpeo-comment-container' ).find( 'input[name="content"]' ).val( '' );
	triggeredElement.closest( '.wpeo-comment-container' ).find( '.content' ).html( '' );
	triggeredElement.closest( '.comments .comment.new' ).after( response.data.view );
	triggeredElement.closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	triggeredElement.closest( '.point' ).find( '.wpeo-point-comment span:last' ).text( currentNumberComment );
};

/**
 * Met à jour le champ caché contenant le texte du comment écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManagerFrontend.comment.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 1 );
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
	} else {
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	}

	jQuery( this ).closest( '.comment' ).find( '.wpeo-comment-content input[name="content"]' ).val( jQuery( this ).html() );
};

/**
 * Charges les commentaires au clic sur le content editable.
 *
 * @param  {MouseEvent} event L'évènement du clic
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManagerFrontend.comment.loadComments = function( event ) {
	var data = {};

	data.action   = 'load_comments';
	data.task_id  = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	data.point_id = jQuery( this ).closest( '.point' ).data( 'id' );
	data.frontend = true;

	if ( ! jQuery( this ).closest( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.taskManagerFrontend.task.refresh();
		} );

		window.eoxiaJS.loader.display( jQuery( this ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}
};
