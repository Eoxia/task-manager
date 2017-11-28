/**
 * Initialise l'objet "comment" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.4.0-ford
 */
window.eoxiaJS.taskManager.comment = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.comment.init = function() {
	window.eoxiaJS.taskManager.comment.event();
};

/**
 * Initialise tous les évènements liés au comment de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.comment.event = function() {
	// jQuery( document ).on( 'click', 'body', window.eoxiaJS.taskManager.comment.closePoint );
	// jQuery( document ).on( 'click', '.wpeo-project-task .point .comment', window.eoxiaJS.taskManager.comment.preventClosePoint );
	jQuery( document ).on( 'keyup', '.wpeo-comment-container div.content[contenteditable="true"], .wpeo-comment-container input[name="time"]', window.eoxiaJS.taskManager.comment.triggerCreate );
	jQuery( document ).on( 'blur keyup paste keydown click', '.comment .content', window.eoxiaJS.taskManager.comment.updateHiddenInput );
	jQuery( document ).on( 'click', '.point.edit div[contenteditable="true"].wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.comment.loadComments );
};

/**
 * Fermes les points.active ainsi que leurs commentaires
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.comment.closePoint = function( event ) {
	jQuery( '.point.active' ).removeClass( 'active' );
	if ( jQuery( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.refresh();
		} );
	}
};

/**
 * Stop propagation afin d'éviter la fermeture du point.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.comment.preventClosePoint = function( event ) {
	event.stopPropagation();
};

window.eoxiaJS.taskManager.comment.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.comment' ).find( '.action-input' ).click();
	}
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
window.eoxiaJS.taskManager.comment.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 1 );
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
	} else {
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
		jQuery( this ).closest( '.comment' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	}

	jQuery( this ).closest( '.comment' ).find( '.wpeo-comment-content input[name="content"]' ).val( jQuery( this ).html() );

	window.eoxiaJS.refresh();
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
window.eoxiaJS.taskManager.comment.loadComments = function( event ) {
	var data = {};

	data.action = 'load_comments';
	data.task_id = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	data.point_id = jQuery( this ).closest( '.point' ).data( 'id' );

	if ( ! jQuery( this ).closest( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.refresh();
		} );

		jQuery( this ).addClass( 'loading' );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_comments".
 * Met le contenu dans la div.comments.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.comment.loadedCommentsSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( 'div.point' ).find( '.comments' ).html( response.data.view );

	triggeredElement.removeClass( 'loading' );
	triggeredElement.closest( 'div.point' ).find( '.comments' ).slideDown( 400, function() {
		window.eoxiaJS.refresh();
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "edit_comment".
 * Met le contenu dans la div.comments.
 *
 * @since 1.0.0
 * @version 1.5.0
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 */
window.eoxiaJS.taskManager.comment.addedCommentSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.comment' ).find( 'div.content' ).html( '' );

	triggeredElement.closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time.task );
	triggeredElement.closest( '.comments' ).prev( '.form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );

	triggeredElement.closest( 'div.point' ).find( '.comments' ).html( response.data.view );

	jQuery( '.wpeo-project-task[data-id="' + response.data.comment.post_id + '"] .point[data-id="' + response.data.comment.parent_id + '"] .comment.new div.content' ).focus();

	window.eoxiaJS.refresh();

};

/**
 * Le callback en cas de réussite à la requête Ajax "delete_comment".
 * Supprimes la ligne.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.comment.deletedCommentSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.comment' ).fadeOut();

	triggeredElement.closest( '.wpeo-project-task.mask' ).removeClass( 'mask' );
	triggeredElement.closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time.task );
	triggeredElement.closest( '.comments' ).prev( 'form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );

	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_edit_view_comment".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.comment.loadedEditViewComment = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comment' ).replaceWith( response.data.view );
	jQuery( '.wpeo-project-task.mask' ).removeClass( 'mask' );
};

window.eoxiaJS.taskManager.comment.afterTriggerChangeDate = function( $input ) {
	$input.closest( '.group-date' ).find( 'div' ).attr( 'aria-label', window.eoxiaJS.date.convertMySQLDate( $input.val() ) );
	$input.closest( '.group-date' ).find( 'span' ).css( 'background', '#389af6' );
};
