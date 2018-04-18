/**
 * Initialise l'objet "point" ainsi que la méthode "navigation" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.navigation = {};

window.eoxiaJS.taskManager.navigation.init = function() {
	window.eoxiaJS.taskManager.navigation.event();
};

window.eoxiaJS.taskManager.navigation.event = function() {
	jQuery( document ).on( 'keyup', '.wpeo-header-bar input[name="term"]', window.eoxiaJS.taskManager.navigation.triggerSearch );

	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.eoxiaJS.taskManager.navigation.toggleMoreOptions );
	jQuery( document ).on( 'click', '.wpeo-tag-search', window.eoxiaJS.taskManager.navigation.selectTag );
};

window.eoxiaJS.taskManager.navigation.triggerSearch = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.wpeo-header-search .action-input' ).click();
	}
};

/**
 * Toggle le barre de recherche avancée.
 *
 * @return void
 *
 * @since 1.0.0
 * @version 1.4.0
 */
window.eoxiaJS.taskManager.navigation.toggleMoreOptions = function() {
	jQuery( '.wpeo-header-search' ).toggle();
};


/**
 * Toggle la classe "active" à l'élement cliqué.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.3.6
 */
window.eoxiaJS.taskManager.navigation.selectTag = function() {
	jQuery( this ).toggleClass( 'active' );
};

/**
 * Vérifies les données pour la recherche avant d'exécuter la requête.
 *
 * @param  {HTMLSpanElement} triggeredElement L'élement déclenchant l'action.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.navigation.checkDataBeforeSearch = function( triggeredElement ) {
	var categoriesIdSelected = [];

	jQuery( '.tag-search .tags li.active' ).each( function( key, item ) {
		categoriesIdSelected.push( parseInt( jQuery( item ).attr( 'data-tag-id' ) ) );
	} );

	jQuery( 'input[name="categories_id_selected"]' ).val( categoriesIdSelected.join( ',' ) );

	if ( triggeredElement && ! triggeredElement.hasClass( 'change-status' ) ) {
		window.eoxiaJS.loader.display( jQuery( '.wpeo-general-search' ) );
	} else if ( triggeredElement && triggeredElement.hasClass( 'change-status' ) ) {
		jQuery( '.wpeo-header-bar input[name="status"]' ).val( triggeredElement.data( 'status' ) );
	}

	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "search".
 * Remplaces le contenu des tâches du dashboard et affiches la div contenant le résultat de la recherche.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.navigation.searchedSuccess = function( triggeredElement, response ) {
	jQuery( '.wpeo-project-wrap .load-more' ).remove();
	window.eoxiaJS.loader.remove( jQuery( '.wpeo-general-search' ) );

	jQuery( '.list-task' ).masonry( 'remove', jQuery( '.wpeo-project-task' ) );
	jQuery( '.list-task' ).replaceWith( response.data.view.tasks );
	jQuery( '.list-task' ).masonry();
	jQuery( '.search-results' ).replaceWith( response.data.view.search_result );
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	// Changes l'onglet "active" dans la barre de navigation.
	if ( triggeredElement.hasClass( 'change-status' ) ) {
		jQuery( '.wpeo-header-bar .change-status.active' ).removeClass( 'active' );
		jQuery( triggeredElement ).addClass( 'active' );
	}

	window.eoxiaJS.refresh();
};
