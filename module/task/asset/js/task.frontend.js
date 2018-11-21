/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManagerFrontend.task = {};

window.eoxiaJS.taskManagerFrontend.task.init = function() {
	jQuery( '.list-task' ).colcade( {
		items: '.wpeo-project-task',
		columns: '.grid-col'
	} );
};

window.eoxiaJS.taskManagerFrontend.task.refresh = function() {
};
