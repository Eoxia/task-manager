/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.newComment = {};

window.eoxiaJS.taskManager.newComment.init = function() {
	window.eoxiaJS.taskManager.newComment.event();
};

window.eoxiaJS.taskManager.newComment.event = function() {};

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
window.eoxiaJS.taskManager.newComment.loadedCommentsSuccess = function( triggeredElement, response ) {
	var taskColumn = triggeredElement.closest( '.task-column' );

	taskColumn.find( '.column-extend' ).html( response.data.view );

	triggeredElement.removeClass( 'loading' );
	taskColumn.find( '.column-extend' ).slideDown( 400, function() {
	} );
};
