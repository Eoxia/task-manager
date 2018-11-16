/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManagerFrontend.task = {};

window.eoxiaJS.taskManagerFrontend.task.init = function() {
	// window.eoxiaJS.taskManager.task.event();
	jQuery( '.list-task' ).masonry( {
		itemSelector: '.wpeo-project-task'
	} );
};
