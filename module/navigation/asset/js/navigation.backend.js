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
	jQuery( document ).on( 'keyup', '.wpeo-header-bar input[name="term"]', window.eoxiaJS.taskManager.navigation.triggerSearch );
	// jQuery( document ).on( 'change', '.wpeo-header-bar .wpeo-autocomplete', window.eoxiaJS.taskManager.navigation.closeResults );

	jQuery( document ).on( 'click', '.wpeo-header-bar .more-search-options', window.eoxiaJS.taskManager.navigation.toggleMoreOptions );
	jQuery( document ).on( 'click', '.wpeo-tag-search', window.eoxiaJS.taskManager.navigation.selectTag );

	jQuery( document ).on( 'modal-opened', '.modal-shortcut', window.eoxiaJS.taskManager.navigation.initSortable );

	jQuery( document ).on( 'click', '.create-folder', function() {
		jQuery( '.create-folder-form' ).slideToggle();
		jQuery( this ).slideToggle();
	});


	jQuery( document ).on( 'dragstart', '.shortcut-sortable', function( e ) {
		window.eoxiaJS.taskManager.navigation.draggedElement = e.currentTarget;
		e.currentTarget.style.border = "dashed";
		e.originalEvent.dataTransfer.setData("text/plain", e.target.id );
	} );

	jQuery( document ).on( 'dragover', '.shortcut-dropzone', function( event ) {
		event.preventDefault();
	} );

	jQuery( document ).on( "dragenter", '.shortcut-dropzone', function( event ) {
		if (jQuery( event.target ).hasClass( 'shortcut-dropzone' ) ) {
			jQuery( event.target )[0].style.border = "dashed";
		}
	} );

	jQuery( document ).on( "dragleave", '.shortcut-dropzone', function( event ) {
		if (jQuery( event.target ).hasClass( 'shortcut-dropzone' ) ) {
			jQuery( event.target )[0].style.border = "none";
		}
	} );

	jQuery( document ).on( 'drop', '.shortcut-dropzone', function( ev ) {
		ev.preventDefault();

		if ( jQuery( ev.target ).hasClass( "shortcut-dropzone" ) )  {
			var data = ev.originalEvent.dataTransfer.getData("text" );
			jQuery( window.eoxiaJS.taskManager.navigation.draggedElement )[0].style.border = "none";
			var clone = jQuery( window.eoxiaJS.taskManager.navigation.draggedElement ).clone();
			jQuery( ev.target ).after( clone[0].outerHTML );

			jQuery( window.eoxiaJS.taskManager.navigation.draggedElement ).remove();
			window.eoxiaJS.taskManager.navigation.draggedElement = null;
			jQuery( '.shortcut-dropzone' ).css( 'border', 'none' );
			ev.originalEvent.dataTransfer.clearData();
		}

	});



};

window.eoxiaJS.taskManager.navigation.triggerSearch = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.tm-advanced-search .action-input' ).click();
	}
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

	jQuery( '.dropdown-content .tags li.active' ).each( function( key, item ) {
		categoriesIdSelected.push( parseInt( jQuery( item ).attr( 'data-tag-id' ) ) );
	} );

	jQuery( 'input[name="categories_id"]' ).val( categoriesIdSelected.join( ',' ) );

	if ( triggeredElement && ! triggeredElement.hasClass( 'change-status' ) ) {
		// window.eoxiaJS.loader.display( jQuery( '.wpeo-general-search' ) );
	} else if ( triggeredElement && triggeredElement.hasClass( 'change-status' ) ) {
		jQuery( '.wpeo-header-bar input[name="status"]' ).val( triggeredElement.data( 'status' ) );
	}

	if ( jQuery( '.wpeo-header-bar input[name="post_parent_order"]' ).val() != 0 ) {
		jQuery( '.wpeo-header-bar input[name="post_parent"]' ).val( jQuery( '.wpeo-header-bar input[name="post_parent_order"]' ).val() );
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
	jQuery( '.eo-search-value' ).val( '' );

	jQuery( '.tm-dashboard-shortcuts .active' ).removeClass( 'active' );
	jQuery( '.list-task' ).replaceWith( response.data.view.tasks );
	jQuery( '.list-task' ).colcade( {
		items: '.wpeo-project-task',
		columns: '.grid-col'
	} );
	jQuery( '.search-results' ).replaceWith( response.data.view.search_result );
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	// Changes l'onglet "active" dans la barre de navigation.
	if ( triggeredElement.hasClass( 'change-status' ) ) {
		jQuery( '.wpeo-header-bar .change-status.active' ).removeClass( 'active' );
		jQuery( triggeredElement ).addClass( 'active' );
	}

	triggeredElement.closest( '.wpeo-dropdown' ).removeClass( 'dropdown-active' );

	window.eoxiaJS.taskManager.task.initAutoComplete();
	window.eoxiaJS.taskManager.point.refresh();
};

window.eoxiaJS.taskManager.navigation.createdShortcutSuccess = function( triggeredElement, response ) {
	jQuery( '.tm-dashboard-shortcuts .active' ).removeClass( 'active' );
	jQuery( '.tm-dashboard-shortcuts .handle-shortcut' ).before( response.data.view_shortcut );
	triggeredElement.closest( '.wpeo-modal' ).find( '.modal-content' ).html( response.data.view_content );
	triggeredElement.closest( '.wpeo-modal' ).find( '.modal-footer' ).html( response.data.view_button );
};

window.eoxiaJS.taskManager.navigation.deletedShortcutSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.table-row' ).fadeOut();
	jQuery( '.tm-dashboard-shortcuts li[data-key="' + response.data.key + '"]' ).fadeOut();
};

window.eoxiaJS.taskManager.navigation.displayEditShortcutSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.table-row' ).html( response.data.view );
};

window.eoxiaJS.taskManager.navigation.editShortcutSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.modal-content' ).html( response.data.view );
	jQuery( '.tm-dashboard-shortcuts li[data-key="' + response.data.key + '"] a' ).html( response.data.name );
};

window.eoxiaJS.taskManager.navigation.initSortable = function( event ) {
};

window.eoxiaJS.taskManager.navigation.createdFolderShortcutSuccess = function( triggeredElement, response ) {
	jQuery( '.modal-content' ).html( response.data.view );
};




window.eoxiaJS.taskManager.navigation.savedOrder = function( triggeredElement, response ) {
	jQuery( '.tm-dashboard-shortcuts' ).html( response.data.view );
	jQuery( '.modal-shortcut' ).removeClass( 'modal-active' );
};
