/**
 * Initialise l'objet "comment" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManagerFrontend.comment = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManagerFrontend.comment.init = function() {
	window.eoxiaJS.taskManagerFrontend.comment.event();
};

/**
 * Initialise tous les évènements liés au comment de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManagerFrontend.comment.event = function() {
	jQuery( document ).on( 'keyup', '.comment div[contenteditable="true"], .comment input[name="time"]', window.eoxiaJS.taskManagerFrontend.comment.triggerCreate );
	jQuery( document ).on( 'blur keyup paste keydown click', '.comments .comment .content', window.eoxiaJS.taskManagerFrontend.comment.updateHiddenInput );
	jQuery( document ).on( 'click', '.point.edit .point-container', window.eoxiaJS.taskManagerFrontend.comment.loadComments );
};

/**
 * Fermes les points.active ainsi que leurs commentaires
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManagerFrontend.comment.closePoint = function( event ) {
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
window.eoxiaJS.taskManagerFrontend.comment.preventClosePoint = function( event ) {
	event.stopPropagation();
};

window.eoxiaJS.taskManagerFrontend.comment.triggerCreate = function( event ) {
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
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManagerFrontend.comment.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.comment' ).find( '.placeholder' ).addClass( 'hidden' );
		jQuery( this ).closest( '.comment' ).removeClass( 'add' ).addClass( 'edit' );
		// window.eoxiaJS.taskManagerFrontend.core.initSafeExit( true );
	} else {
		jQuery( this ).closest( '.comment' ).find( '.placeholder' ).removeClass( 'hidden' );
		jQuery( this ).closest( '.comment' ).removeClass( 'edit' ).addClass( 'add' );
		// window.eoxiaJS.taskManagerFrontend.core.initSafeExit( false );
	}

	jQuery( this ).closest( '.comment' ).find( 'input[name="content"]' ).val( jQuery( this ).html() );

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
window.eoxiaJS.taskManagerFrontend.comment.loadComments = function( event ) {
	var data = {};

	data.action   = 'load_comments';
	data.task_id  = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	data.point_id = jQuery( this ).closest( '.point' ).data( 'id' );
	data.frontend = true;

	if ( ! jQuery( this ).closest( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.refresh();
		} );

		window.eoxiaJS.loader.display( jQuery( this ) );
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
window.eoxiaJS.taskManagerFrontend.comment.loadedCommentsSuccess = function( triggeredElement, response ) {
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
window.eoxiaJS.taskManagerFrontend.comment.addedCommentSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.comment' ).find( 'div.content' ).html( '' );

	triggeredElement.closest( '.wpeo-project-task' ).find( '.wpeo-task-time-info .elapsed' ).text( response.data.time.task );
	triggeredElement.closest( '.comments' ).prev( '.form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );

	triggeredElement.closest( 'div.point' ).find( '.comments' ).html( response.data.view );

	jQuery( '.wpeo-project-task[data-id="' + response.data.comment.post_id + '"] .point[data-id="' + response.data.comment.parent_id + '"] .comment.new div.content' ).focus();

	window.eoxiaJS.refresh();
	window.eoxiaJS.taskManagerFrontend.core.initSafeExit( false );
};