/**
 * Initialise l'objet "point" ainsi que la méthode "navigation" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.navigation = {};

window.task_manager.navigation.init = function() {
	window.task_manager.navigation.event();
};

window.task_manager.navigation.event = function() {
	jQuery( document ).on( 'click', '.wpeo-header-bar button', window.task_manager.navigation.search );
};

window.task_manager.navigation.search = function() {
	var synthesisTask = '';
	var search = jQuery( this ).closest( 'li' ).find( 'input[type="text"]' ).val();

	jQuery( '.wpeo-project-task' ).show();

	jQuery( '.wpeo-project-task:visible' ).each( function() {
		synthesisTask = '';
		synthesisTask += jQuery( this ).text();
		jQuery( this ).find( 'input' ).each( function() {
			synthesisTask += jQuery( this ).val() + ' ';
		} );

		synthesisTask = synthesisTask.replace( /\s+\s/g, ' ' ).trim();

		if ( synthesisTask.search( new RegExp( search, 'i' ) ) == -1 ) {
			jQuery( this ).hide();
		}
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_my_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.navigation.loadedMyTask = function( triggeredElement, response ) {
	jQuery( '.list-task' ).replaceWith( response.data.view );
};
