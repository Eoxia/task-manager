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
window.task_manager.comment.event = function() {};

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
	jQuery( triggeredElement ).closest( 'form' ).next( '.comments' ).html( response.data.view );
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
window.task_manager.comment.addedCommentSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comments' ).append( response.data.view );

	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time.task );
	jQuery( triggeredElement ).closest( '.comments' ).prev( 'form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );
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
};
