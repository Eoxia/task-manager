/**
 * Gestion des onglets.
 *
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! window.eoxiaJS.tab ) {
	window.eoxiaJS.tab = {};

	window.eoxiaJS.tab.init = function() {
		window.eoxiaJS.tab.event();
	};

	window.eoxiaJS.tab.event = function() {
	  jQuery( document ).on( 'click', '.wpeo-tab .tab-element', window.eoxiaJS.tab.load );
	};

	window.eoxiaJS.tab.load = function( event ) {
		var tabTriggered = jQuery( this );
		var data = {};

	  event.preventDefault();
		event.stopPropagation();

		tabTriggered.closest( '.wpeo-tab' ).find( '.tab-element.tab-active' ).removeClass( 'tab-active' );
		tabTriggered.addClass( 'tab-active' );

		if ( ! tabTriggered.attr( 'data-action' ) ) {
			tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content.tab-active' ).removeClass( 'tab-active' );
			tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content[id="' + tabTriggered.attr( 'data-target' ) + '"]' ).addClass( 'tab-active' );
		} else {
			data = {
				action: tabTriggered.attr( 'data-action' ),
				_wpnonce: tabTriggered.attr( 'data-nonce' ),
				target: tabTriggered.attr( 'data-target' ),
				title: tabTriggered.attr( 'data-title' ),
				element_id: tabTriggered.attr( 'data-id' )
		  };

			window.eoxiaJS.loader.display( tabTriggered );

			jQuery.post( window.ajaxurl, data, function( response ) {
				window.eoxiaJS.loader.remove( tabTriggered );
				tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content.tab-active' ).removeClass( 'tab-active' );
				tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content' ).addClass( 'tab-active' );
				tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content' ).html( response.data.view );

				window.eoxiaJS.tab.callTabChanged();
			} );
		}

	};

	window.eoxiaJS.tab.callTabChanged = function() {
		var key = undefined, slug = undefined;
		for ( key in window.eoxiaJS ) {
			for ( slug in window.eoxiaJS[key] ) {
				if ( window.eoxiaJS && window.eoxiaJS[key] && window.eoxiaJS[key][slug].tabChanged ) {
					window.eoxiaJS[key][slug].tabChanged();
				}
			}
		}
	};
}
