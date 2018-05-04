/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.7.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.import = {};

window.eoxiaJS.taskManager.import.init = function() {
	window.eoxiaJS.taskManager.import.event();
};

window.eoxiaJS.taskManager.import.event = function() {};

/**
 * Callback de l'import des tâches.
 *
 * @return void
 */
window.eoxiaJS.taskManager.import.importSuccess = function( element, response ) {
	if ( 'tasks' === response.data.type ) {
		window.eoxiaJS.taskManager.task.createdTaskSuccess( element, response );
	} else if ( 'points' === response.data.type ) {
		window.eoxiaJS.taskManager.point.addedPointSuccess( element, response );
	}

	jQuery( '.tm-import-tasks .modal-close' ).click();
};
