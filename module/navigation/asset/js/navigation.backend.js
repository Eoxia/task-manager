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
	jQuery( document ).on( 'keyup', '.wpeo-header-bar input[name="term"]', window.task_manager.navigation.triggerSearch );

	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.task_manager.navigation.toggleMoreOptions );
	jQuery( document ).on( 'click', '.wpeo-tag-search', window.task_manager.navigation.selectTag );
};

window.task_manager.navigation.triggerSearch = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.wpeo-header-search .action-input' ).click();
	}
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

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
};

/**
 * Toggle la classe "active" à l'élement cliqué.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.navigation.selectTag = function() {
	jQuery( this ).toggleClass( 'active' );
};

/**
 * Vérifies les données pour la recherche avant d'exécuter la requête.
 *
 * @param  {HTMLSpanElement} triggeredElement L'élement déclenchant l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.navigation.checkDataBeforeSearch = function( triggeredElement ) {
	var categoriesIdSelected = [];

	jQuery( '.tag-search .tags li.active' ).each( function( key, item ) {
		categoriesIdSelected.push( parseInt( jQuery( item ).attr( 'data-tag-id' ) ) );
	} );

	jQuery( 'input[name="categories_id_selected"] ' ).val( categoriesIdSelected.join( ',' ) );

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
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.navigation.searchedSuccess = function( triggeredElement, response ) {
	jQuery( '.list-task' ).replaceWith( response.data.view.tasks );
	jQuery( '.search-results' ).replaceWith( response.data.view.search_result );
};
