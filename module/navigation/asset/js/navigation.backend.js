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
	jQuery( document ).on( 'click', '.wpeo-general-search div[contenteditable="true"]', window.eoxiaJS.taskManager.navigation.openResult );
	jQuery( document ).on( 'keydown', '.wpeo-general-search div[contenteditable="true"]', window.eoxiaJS.taskManager.navigation.checkContent );
	jQuery( document ).on( 'click', '.wpeo-general-search .autocomplete-result', window.eoxiaJS.taskManager.navigation.selectResult );
	
	jQuery( '.can-write' ).on( 'DOMNodeRemoved', window.eoxiaJS.taskManager.navigation.deletedCanWrite );

	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.eoxiaJS.taskManager.navigation.toggleMoreOptions );
	jQuery( document ).on( 'click', '.wpeo-tag-search', window.eoxiaJS.taskManager.navigation.selectTag );
};


window.eoxiaJS.taskManager.navigation.openResult = function( event ) {
	jQuery( this ).find( '.can-write' ).focus();
	event.preventDefault();
	jQuery( this ).closest( '.wpeo-autocomplete' ).addClass( 'autocomplete-active' );
	return false;
};

window.eoxiaJS.taskManager.navigation.checkContent = function( event ) {
	if ( jQuery( '.wpeo-general-search div[contenteditable="true"]' ).find( '.can-write' ).text().length == 1 && event.keyCode == 8 ) {
		// Bonne piste!
		jQuery( '.wpeo-autocomplete div[contenteditable="true"]' ).append( '<span class="can-write" contenteditable="true" data-text="true"></span>' );
		jQuery( '.wpeo-autocomplete div[contenteditable="true"] .can-write' ).focus();
	}
	
	if ( jQuery( '.wpeo-general-search div[contenteditable="true"]' ).find( '.can-write' ).text().length == 0 && event.keyCode == 8 ) {
		event.preventDefault();
		return false;
	}
};

window.eoxiaJS.taskManager.navigation.deletedCanWrite = function( event ) {
	// jQuery( '.wpeo-autocomplete div[contenteditable="true"]' ).append( '<span class="can-write" contenteditable="true" data-text="true"></span>' );
	// jQuery( '.wpeo-autocomplete' ).find( '.can-write' ).focus();
}

window.eoxiaJS.taskManager.navigation.selectResult = function( event ) {
	jQuery( '.wpeo-header-bar *[data-text="true"]' ).before( '<div onclick="return false" class="bg-red">' + jQuery( this ).data( 'value' ) + '</div>' );
	window.eoxiaJS.taskManager.navigation.updateSearch( jQuery( this ).data( 'value' ));
};

window.eoxiaJS.taskManager.navigation.updateSearch = function( slug ) {
	var outputHTML = '';
	
	if ( 'user' === slug ) {
		for ( var key in taskManager.search.user ) {
			console.log(taskManager.search.user[key] );
		}
	}
	
	jQuery( 'wpeo-general-search autocomplete-search-list' ).html( outputHTML );
}

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
	jQuery( '.tm-wrap .load-more' ).remove();
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
