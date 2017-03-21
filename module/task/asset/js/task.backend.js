/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.task = {};

window.task_manager.task.init = function() {
	window.task_manager.task.event();
};

window.task_manager.task.event = function() {
	jQuery( '.wpeo-project-wrap' ).on( 'blur', '.wpeo-project-task-title', window.task_manager.task.editTitle );
};

window.task_manager.task.editTitle = function( event ) {
	var data = {
		action: 'edit_title',
		_wpnonce: jQuery( this ).data( 'nonce' ),
		task_id: jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' ),
		title: jQuery( this ).val()
	};

	window.task_manager.request.send( this, data );
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.task.createdTaskSuccess = function( element, response ) {
	jQuery( '.list-task' ).prepend( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "delete_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.task.deletedTaskSuccess = function( element, response ) {
	jQuery( element ).closest( '.wpeo-project-task' ).fadeOut();
};
