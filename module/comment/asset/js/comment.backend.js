/**
 * Initialise l'objet "comment" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.comment = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.comment.init = function() {
	window.task_manager.comment.event();
};

/**
 * Initialise tous les évènements liés au comment de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.comment.event = function() {
	jQuery( document ).on( 'keyup', '.comment.edit input[name="content"]', window.task_manager.comment.triggerCreate );
};

window.task_manager.comment.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.comment' ).find( '.action-input' ).click();
	}
};

/**
 * Avant de charger les commentaires, change la dashicons.
 *
 * @param  {HTMLSpanElement} triggeredElement L'élément HTML déclenchant l'action.
 * @return void
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.task_manager.comment.beforeLoadComments = function( triggeredElement ) {
	triggeredElement.toggleClass( 'dashicons-arrow-right-alt2 dashicons-arrow-down-alt2' );

	triggeredElement.closest( 'div.point' ).find( '.comments' ).toggleClass( 'hidden' );

	return true;
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
window.task_manager.comment.loadedCommentsSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( 'div.point' ).find( '.comments' ).html( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "edit_comment".
 * Met le contenu dans la div.comments.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.comment.addedCommentSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comments' ).append( response.data.view );

	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time.task );
	jQuery( triggeredElement ).closest( '.comments' ).prev( 'form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "edit_comment".
 * Remplace la ligne courante du commentaire.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.comment.editedCommentSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time.task );
	jQuery( triggeredElement ).closest( '.comments' ).prev( 'form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );

	jQuery( triggeredElement ).closest( '.comment.edit' ).replaceWith( response.data.view );
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
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.comment.deletedCommentSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comment' ).fadeOut();

	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time.task );
	jQuery( triggeredElement ).closest( '.comments' ).prev( 'form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );

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
window.task_manager.comment.loadedEditViewComment = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comment' ).replaceWith( response.data.view );
};
