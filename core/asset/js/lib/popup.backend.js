window.task_manager.popup = {};

window.task_manager.popup.init = function() {
	window.task_manager.popup.event();
};

window.task_manager.popup.event = function() {
	jQuery( document ).on( 'keyup', window.task_manager.popup.keyup );
  jQuery( document ).on( 'click', '.open-popup, .open-popup i', window.task_manager.popup.open );
  jQuery( document ).on( 'click', '.open-popup-ajax', window.task_manager.popup.openAjax );
  jQuery( document ).on( 'click', '.popup .container, .digi-popup-propagation', window.task_manager.popup.stop );
  jQuery( document ).on( 'click', '.popup .container .button.green', window.task_manager.popup.confirm );
  jQuery( document ).on( 'click', '.popup .close', window.task_manager.popup.close );
  jQuery( document ).on( 'click', 'body', window.task_manager.popup.close );
};

window.task_manager.popup.keyup = function( event ) {
	if ( 27 === event.keyCode ) {
		jQuery( '.popup .close' ).click();
	}
};

window.task_manager.popup.open = function( event ) {
	var triggeredElement = jQuery( this );

	if ( triggeredElement.is( 'i' ) ) {
		triggeredElement = triggeredElement.parents( '.open-popup' );
	}

	var target = triggeredElement.closest(  '.' + triggeredElement.data( 'parent' ) ).find( '.' + triggeredElement.data( 'target' ) );
	var cbObject, cbFunc = undefined;
	target.addClass( 'active' );

	if ( target.is( ':visible' ) && triggeredElement.data( 'cb-object' ) && triggeredElement.data( 'cb-func' ) ) {
		cbObject = triggeredElement.data( 'cb-object' );
		cbFunc = triggeredElement.data( 'cb-func' );

		// On récupères les "data" sur l'élement en tant qu'args.
		triggeredElement.get_data( function( data ) {
			window.task_manager[cbObject][cbFunc]( triggeredElement, target, event, data );
		} );
	}

  event.stopPropagation();
};

/**
 * Ouvre la popup en envoyant une requête AJAX.
 * Les paramètres de la requête doivent être configurer directement sur l'élement
 * Ex: data-action="load-workunit" data-id="190"
 *
 * @param  {[type]} event [description]
 * @return {[type]}       [description]
 */
window.task_manager.popup.openAjax = function( event ) {
	var element = jQuery( this );
	var target = jQuery( this ).closest(  '.' + jQuery( this ).data( 'parent' ) ).find( '.' + jQuery( this ).data( 'target' ) );
	target.addClass( 'active' );

	jQuery( this ).get_data( function( data ) {
		delete data.parent;
		delete data.target;
		window.task_manager.request.send( element, data );
	});

	event.stopPropagation();
};

window.task_manager.popup.confirm = function( event ) {
	var triggeredElement = jQuery( this );
	var cbObject, cbFunc = undefined;

	if ( ! jQuery( '.popup' ).hasClass( 'no-close' ) ) {
		jQuery( '.popup' ).removeClass( 'active' );

		if ( triggeredElement.attr( 'data-cb-object' ) && triggeredElement.attr( 'data-cb-func' ) ) {
			cbObject = triggeredElement.attr( 'data-cb-object' );
			cbFunc = triggeredElement.attr( 'data-cb-func' );

			// On récupères les "data" sur l'élement en tant qu'args.
			triggeredElement.get_data( function( data ) {
				window.task_manager[cbObject][cbFunc]( triggeredElement, event, data );
			} );
		}
	}
};

window.task_manager.popup.stop = function( event ) {
	event.stopPropagation();
};

window.task_manager.popup.close = function( event ) {
	jQuery( '.popup:not(.no-close)' ).removeClass( 'active' );
	jQuery( '.digi-popup:not(.no-close)' ).removeClass( 'active' );
};
