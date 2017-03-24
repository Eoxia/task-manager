/**
 * Initialise l'objet "point" ainsi que la méthode "searchBar" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.searchBar = {};

window.task_manager.searchBar.init = function() {
	window.task_manager.searchBar.event();
};

window.task_manager.searchBar.event = function() {
	jQuery( document ).on( 'click', '.wpeo-header-bar button', window.task_manager.searchBar.search );
};

window.task_manager.searchBar.search = function() {
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
