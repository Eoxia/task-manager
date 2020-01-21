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

window.eoxiaJS.taskManager.navigation.draggedElement;

window.eoxiaJS.taskManager.navigation.event = function() {
	jQuery( document ).on( 'keyup', '.header-searchbar input', window.eoxiaJS.taskManager.navigation.triggerSearch );
	// jQuery( document ).on( 'change', '.wpeo-header-bar .wpeo-autocomplete', window.eoxiaJS.taskManager.navigation.closeResults );

	// jQuery( document ).on( 'click', '.autocomplete-search-list .autocomplete-result', window.eoxiaJS.taskManager.navigation.triggerSearchAuto/Complete );
	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.eoxiaJS.taskManager.navigation.toggleMoreOptions );
	// jQuery( document ).on( 'click', '.wpeo-tag-search', window.eoxiaJS.taskManager.navigation.selectTag );
	jQuery( document ).on( 'click', '.tm-search input', window.eoxiaJS.taskManager.navigation.searchCategories );

	jQuery( document ).on( 'keyup', '.tm-search .tm-filter', window.eoxiaJS.taskManager.navigation.filterTags );
	jQuery( document ).on( 'click', '.tm-search .dropdown-item', window.eoxiaJS.taskManager.navigation.selectTags );
};

window.eoxiaJS.taskManager.navigation.triggerSearch = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.search-action .action-input' ).click();
	}
};

window.eoxiaJS.taskManager.navigation.triggerSearchAutoComplete = function( event ) {
	jQuery( '.search-action .action-input' ).click();
};

window.eoxiaJS.taskManager.navigation.closeResults = function( event ) {
	jQuery( this ).removeClass( 'autocomplete-active' );
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

window.eoxiaJS.taskManager.navigation.filterTags = function( event ) {
	if (event.keyCode == 13 && jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item.dropdown-active' ).length == 1) {
		jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item.dropdown-active' ).click();
		jQuery( '.search-action .action-input' ).click();
	} else {
		var categories = jQuery(this).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item');
		categories.show();
		categories.removeClass('.dropdown-active');

		var search = jQuery(this).val();
		search = search.toLowerCase();
		search = search.split(' ').join('');

		for (var i = 0; i < categories.length; i++) {
			var text = jQuery(categories[i]).text();
			text = text.toLowerCase();
			text = text.replace(/\n/g, " ");
			text = text.replace(/^\s+|\s+$/g, "");
			text = text.split(' ').join('');
			text = text.replace(/\t/g, "");

			if (text.indexOf(search) == -1) {
				jQuery(categories[i]).hide();
			}
		}

		if (jQuery(this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item:visible').length == 1) {
			jQuery(this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item:visible').addClass('dropdown-active');
		} else {
			jQuery(this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item').removeClass('dropdown-active');
		}
	}
};

/**
 * Toggle la classe "active" à l'élement cliqué.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.3.6
 */
window.eoxiaJS.taskManager.navigation.selectTags = function( event ) {
	jQuery( this ).closest( '.wpeo-dropdown' ).find( 'input[type="hidden"]' ).val( jQuery( this ).attr( 'data-id' ) );

	jQuery( this ).closest( '.wpeo-dropdown' ).find( 'input[type="text"]' ).val( jQuery( this ).text().trim() );

	jQuery( this ).closest( '.wpeo-dropdown' ).removeClass( 'dropdown-active' );

	event.stopPropagation();
	event.preventDefault();
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
	window.eoxiaJS.loader.remove( jQuery( '.wpeo-general-search' ) );

	jQuery( '.tm-dashboard-shortcuts .active' ).removeClass( 'active' );

	jQuery( '.list-task' ).replaceWith( response.data.view.tasks );
	jQuery( '.search-results' ).replaceWith( response.data.view.search_result );

	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	window.eoxiaJS.taskManager.newTask.stickyAction();
	jQuery( '.list-task' ).on( 'scroll', window.eoxiaJS.taskManager.newTask.stickyAction );

	// Mise à jour URL.
	history.pushState('data', '', response.data.url );

};

window.eoxiaJS.taskManager.navigation.searchCategories = function ( event ) {
	jQuery( this ).closest( '.search-categories' ).find( '.wpeo-dropdown' ).addClass( 'dropdown-active' );
	event.stopPropagation();
	event.preventDefault();
};
