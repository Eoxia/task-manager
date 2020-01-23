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
	// jQuery( document ).on( 'keyup', '.header-searchbar input', window.eoxiaJS.taskManager.navigation.triggerSearch );
	// jQuery( document ).on( 'change', '.wpeo-header-bar .wpeo-autocomplete', window.eoxiaJS.taskManager.navigation.closeResults );

	// jQuery( document ).on( 'click', '.autocomplete-search-list .autocomplete-result', window.eoxiaJS.taskManager.navigation.triggerSearchAuto/Complete );
	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.eoxiaJS.taskManager.navigation.toggleMoreOptions );
	// jQuery( document ).on( 'click', '.wpeo-tag-search', window.eoxiaJS.taskManager.navigation.selectTag );
	jQuery( document ).on( 'click', '.tm-search .field-elements', window.eoxiaJS.taskManager.navigation.searchCategories );

	// jQuery( document ).on( 'keyup', '.tm-search .tm-filter-customer', window.eoxiaJS.taskManager.navigation.filterTags );
	jQuery( document ).on( 'keyup', '.search-customers .tm-filter-customer', window.eoxiaJS.taskManager.navigation.filterCustomers );
	jQuery( document ).on( 'keyup', '.search-categories .tm-filter', window.eoxiaJS.taskManager.navigation.filterTags );
	jQuery( document ).on( 'click', '.tm-search .dropdown-item:not(.me)', window.eoxiaJS.taskManager.navigation.selectTags );

	jQuery( document ).on( 'click', '.wpeo-dropdown .wpeo-button .fa-times', window.eoxiaJS.taskManager.navigation.deleteEntry );
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

window.eoxiaJS.taskManager.navigation.filterCustomers = function( event ) {
	if (event.keyCode == 13 && jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item.dropdown-active' ).length == 1) {
		jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item.dropdown-active' ).click();
		jQuery( '.search-action .action-input' ).click();
	} else {
		var search = jQuery(this).text();
		search = search.split(' ').join( '' );
		search = search.toLowerCase();

		if (search.length > 2) {
			jQuery( '.item-info' ).addClass( 'wpeo-util-hidden' );
			var items = jQuery('.dropdown-customers .dropdown-item:not(.me)');

			jQuery( this ).closest( '.wpeo-dropdown' ).addClass( 'dropdown-active' );

			var founded = false;

			items.each(function (key) {
				jQuery( this ).addClass( 'wpeo-util-hidden' );
				if ( jQuery( this ).data( 'title' ).indexOf(search) != -1) {
					jQuery( this ).removeClass( 'wpeo-util-hidden' );

					founded = true;
				}
			});

			if ( ! founded ) {
				items.each(function (key) {
					jQuery( this ).addClass( 'wpeo-util-hidden' );
					if ( jQuery( this ).data( 'content' ).indexOf(search) != -1) {
						jQuery( this ).removeClass( 'wpeo-util-hidden' );
					}
				});
			}

			if ( items.length == 0 ) {
				jQuery( '.item-nothing' ).removeClass( 'wpeo-util-hidden' );
			} else {
				jQuery( '.item-nothing' ).addClass( 'wpeo-util-hidden' );
			}

			if (jQuery(this).closest('.tm-search').find('.wpeo-dropdown .dropdown-item:not(.me):visible').length == 1) {
				jQuery(this).closest('.tm-search').find('.wpeo-dropdown .dropdown-item:visible').addClass('dropdown-active');
			} else {
				jQuery(this).closest('.tm-search').find('.wpeo-dropdown .dropdown-item').removeClass('dropdown-active');
			}
		} else {
			var items = jQuery('.dropdown-customers .dropdown-item:not(.me)');

			items.each(function (key) {
				jQuery(this).addClass('wpeo-util-hidden');
			});

			jQuery( '.item-info' ).removeClass( 'wpeo-util-hidden' );
		}
	}
};

window.eoxiaJS.taskManager.navigation.filterTags = function( event ) {
	if (event.keyCode == 13 && jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item.dropdown-active' ).length == 1) {
		jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown .dropdown-item.dropdown-active' ).click();
		jQuery( '.search-action .action-input' ).click();
	} else {
		var search = jQuery(this).text();
		search = search.split(' ').join( '' );
		search = search.toLowerCase();

		jQuery( this ).closest( '.wpeo-dropdown' ).addClass( 'dropdown-active' );

		var items = jQuery('.dropdown-categories .dropdown-item:not(.me)');

		items.each(function (key) {
			jQuery( this ).addClass( 'wpeo-util-hidden' );
			if ( jQuery( this ).data( 'content' ).indexOf(search) != -1) {
				jQuery( this ).removeClass( 'wpeo-util-hidden' );
			}
		});

		if (jQuery(this).closest('.tm-search').find('.wpeo-dropdown .dropdown-item:not(.me):visible').length == 1) {
			jQuery(this).closest('.tm-search').find('.wpeo-dropdown .dropdown-item:visible').addClass('dropdown-active');
		} else {
			jQuery(this).closest('.tm-search').find('.wpeo-dropdown .dropdown-item').removeClass('dropdown-active');
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
	var newElement = jQuery( '<div data-id="' + jQuery( this ).attr( 'data-id' ) + '" class="wpeo-button button-grey button-radius-2" style="display: flex;"></div>' );
	newElement.append( '<span>' + jQuery( this ).find( '.dropdown-result-title' ).text().trim() + '</span>' );
	newElement.append( '<i class="fas fa-times"></i>' );

	var currentVal = jQuery( this ).closest( '.wpeo-dropdown' ).find( 'input[type="hidden"]' ).val();

	currentVal = currentVal ? currentVal.split(',') : [];

	if (!currentVal.includes(jQuery( this ).attr( 'data-id' ))) {
		currentVal.push(jQuery( this ).attr( 'data-id' ) );
	}

	currentVal = currentVal.join( ',' );

	jQuery( this ).closest( '.wpeo-dropdown' ).find( 'input[type="hidden"]' ).val( currentVal );

	jQuery( this ).closest( '.wpeo-dropdown' ).find( '.form-field .tm-filter' ).before( newElement );
	jQuery( this ).closest( '.wpeo-dropdown' ).find( '.form-field .tm-filter' ).text( '' );

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

	jQuery( '.more-button' ).remove();
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
	jQuery( this ).closest( '.tm-search' ).find( '.wpeo-dropdown' ).addClass( 'dropdown-active' );
	event.stopPropagation();
	event.preventDefault();
};

window.eoxiaJS.taskManager.navigation.deleteEntry = function ( evt ) {
	var id = jQuery( this ).closest( '.wpeo-button' ).data( 'id' );
	var currentVal = jQuery( this ).closest( '.wpeo-dropdown' ).find( 'input[type="hidden"]' ).val();

	currentVal = currentVal.split(',');

	for (var key in currentVal) {
		if (currentVal[key] == id) {
			currentVal.splice(key, 1);
		}
	}

	currentVal = currentVal.join( ',' );

	jQuery( this ).closest( '.wpeo-dropdown' ).find( 'input[type="hidden"]' ).val( currentVal );
	jQuery( this ).closest( '.wpeo-button' ).remove();
};
