/**
 * Initialise l'objet "point" ainsi que la méthode "shortcut" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.shortcut = {};

window.eoxiaJS.taskManager.shortcut.init = function() {
	window.eoxiaJS.taskManager.shortcut.event();
};

window.eoxiaJS.taskManager.shortcut.draggedElement;

window.eoxiaJS.taskManager.shortcut.event = function() {
	jQuery( document ).on( 'click', '.shortcuts .shortcut.folder:not(.edit)', window.eoxiaJS.taskManager.shortcut.openFolder );
	jQuery( document ).on( 'click', '.tree .item', window.eoxiaJS.taskManager.shortcut.openFolder );

	jQuery( document ).on( 'click', '.create-folder', function() {
		jQuery( '.create-folder-form' ).slideToggle();
		jQuery( this ).slideToggle();
	});


	jQuery( document ).on( 'dragstart', '.shortcuts .shortcut', function( e ) {
		window.eoxiaJS.taskManager.shortcut.draggedElement = e.currentTarget;
		e.currentTarget.style.border = 'dashed';
		e.originalEvent.dataTransfer.setData("text/plain", e.target.id );
	} );

	jQuery( document ).on( 'dragend', '.shortcut, .dropable', function( event ) {
		event.preventDefault();
	} );

	jQuery( document ).on( 'dragover', '.shortcut, .dropable', function( event ) {
		event.preventDefault();
		return false;
	} );

	jQuery( document ).on( "dragenter", '.shortcut, .dropable', function( event ) {
		if (jQuery( event.target ).hasClass( 'shortcut' ) || jQuery( event.target ).hasClass( 'dropable' ) ) {
			jQuery( event.target )[0].style.border = "dashed";
		}
	} );

	jQuery( document ).on( "dragleave", '.shortcut, .dropable', function( event ) {
		if (jQuery( event.target ).hasClass( 'shortcut' )  || jQuery( event.target ).hasClass( 'dropable' ) ) {
			jQuery( event.target )[0].style.border = "none";
		}
	} );

	jQuery( document ).on( 'drop', '.shortcut, .dropable', function( ev ) {
		ev.preventDefault();

		if ( ev.stopPropagation() ) {
			ev.stopPropagation();
		}

		var currentElement = jQuery( window.eoxiaJS.taskManager.shortcut.draggedElement );
		var newElement     = currentElement.clone();
		var target         = jQuery( ev.target );

		var id = parseInt( target.data( 'id' ) );

		if ( id == currentElement.data( 'id' ) || ! jQuery( ev.target ).hasClass( 'shortcut' ) ) {
			jQuery( ev.target )[0].style.border = "none";
			return;
		}

		if ( jQuery( ev.target ).hasClass( "folder" ) && ! currentElement.hasClass("folder") )  {
			if ( jQuery( ev.target ).data( 'parent' ) ) {
				jQuery('.folder-0').append(newElement[0].outerHTML);
			} else {
				jQuery('.folder-' + id).append(newElement[0].outerHTML);
			}
		} else {
			jQuery( ev.target ).after( newElement[0].outerHTML );
		}

		jQuery( ev.target )[0].style.border = "none";

		window.eoxiaJS.taskManager.shortcut.draggedElement.remove();

		window.eoxiaJS.taskManager.shortcut.refreshKey();

		return false;
	});
};

window.eoxiaJS.taskManager.shortcut.LoadedShortcutSuccess = function( triggeredElement, response ) {
	triggeredElement.after( response.data.template );
	triggeredElement.closest( '.tm-advanced-search' ).find( '.modal-content' ).html( response.data.view );
	triggeredElement.closest( '.tm-advanced-search' ).find( '.modal-footer' ).html( response.data.buttons_view );
};

window.eoxiaJS.taskManager.shortcut.createdShortcutSuccess = function( triggeredElement, response ) {
	jQuery( '.tm-dashboard-shortcuts .active' ).removeClass( 'active' );
	jQuery( '.tm-dashboard-shortcuts .handle-shortcut' ).before( response.data.view_shortcut );
	triggeredElement.closest( '.wpeo-modal' ).find( '.modal-content' ).html( response.data.view_content );
	triggeredElement.closest( '.wpeo-modal' ).find( '.modal-footer' ).html( response.data.view_button );
};

window.eoxiaJS.taskManager.shortcut.deletedShortcutSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.shortcut' ).remove();
	jQuery( '.tm-dashboard-shortcuts li[data-key="' + response.data.key + '"]' ).fadeOut();
};

window.eoxiaJS.taskManager.shortcut.displayEditShortcutSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.shortcut' ).replaceWith( response.data.view );
};

window.eoxiaJS.taskManager.shortcut.editShortcutSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.shortcut' ).html(response.data.view);
};

window.eoxiaJS.taskManager.shortcut.createdFolderShortcutSuccess = function( triggeredElement, response ) {
	jQuery( '.modal-shortcut .folder-0' ).append( response.data.new_item );
	jQuery( '.modal-shortcut .shortcuts-content' ).append( response.data.view );

	jQuery( '.create-folder-form' ).slideToggle();
	jQuery( '.create-folder' ).slideToggle();

	jQuery( '.modal-shortcut .tree .descendants' ).append( response.data.tree_item_view );
};

window.eoxiaJS.taskManager.shortcut.savedOrder = function( triggeredElement, response ) {
	jQuery( '.tm-dashboard-shortcuts' ).replaceWith( response.data.view );
	jQuery( '.modal-shortcut' ).removeClass( 'modal-active' );
};


window.eoxiaJS.taskManager.shortcut.openFolder = function( event ) {
	jQuery( '.modal-shortcut .shortcuts' ).hide();

	var id = parseInt( jQuery( this ).data( 'id' ) );

	jQuery('.tree .item.active').removeClass('active' );

	if ( jQuery( this ).data( 'parent' ) ) {
		jQuery( '.tree .item-0' ).addClass( 'active' );
		jQuery( '.shortcuts.folder-0' ).show();
	} else {
		jQuery('.tree .item.item-' + id).addClass('active');

		jQuery('.folder-' + id ).show();
	}
};

window.eoxiaJS.taskManager.shortcut.refreshKey = function( event ) {
	jQuery( '.shortcuts' ).each( function( key ) {
		var _parent = jQuery( this );
		jQuery( this ).find( '.shortcut' ).each( function ( key_item ) {
			jQuery( this ).find( 'input.order_input' ).attr( 'name', 'order_shortcut[' + _parent.data( 'id' ) + '][' + jQuery( this ).data( 'id' ) + ']' );
		} );
	} );
};
