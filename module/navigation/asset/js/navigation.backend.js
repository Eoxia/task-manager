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
	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.task_manager.navigation.toggleMoreOptions );
	jQuery( document ).on( 'click', '.wpeo-tag-search', window.task_manager.navigation.selectTag );
};

/**
 * Toggle le barre de recherche avancée.
 *
 * @return void
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.navigation.toggleMoreOptions = function() {
	jQuery( '.wpeo-header-search' ).toggle();
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
	window.task_manager.task.offset = 0;
	window.task_manager.task.canLoadMore = true;
};

window.task_manager.navigation.selectTag = function() {
	jQuery( this ).toggleClass( 'active' );

	if ( jQuery( this ).hasClass( 'active' ) ) {
		// window.task_manager.navigation.searchData.tags.push( jQuery( this ).text() );
	} else {
		// window.task_manager.navigation.searchData.tags.splice( window.task_manager.navigation.searchData.tags.indexOf( jQuery( this ).text() ), 1 );
	}
};

window.task_manager.navigation.searchedSuccess = function( triggeredElement, response ) {
	jQuery( '.list-task' ).replaceWith( response.data.view );
};
