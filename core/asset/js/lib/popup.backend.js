window.task_manager.popup = {};

window.task_manager.popup.init = function() {
	window.task_manager.popup.event();
};

window.task_manager.popup.event = function() {
  jQuery( document ).on( 'click', '.open-popup', window.task_manager.popup.open );
  jQuery( document ).on( 'click', '.open-popup-ajax', window.task_manager.popup.open_ajax );
  jQuery( document ).on( 'click', '.popup .container, .digi-popup-propagation', window.task_manager.popup.stop );
  jQuery( document ).on( 'click', '.popup .container .button-primary', window.task_manager.popup.confirm );
  jQuery( document ).on( 'click', '.popup .container .button-secondary, .popup .close', window.task_manager.popup.close );
  jQuery( document ).on( 'click', 'body', window.task_manager.popup.close );
};

window.task_manager.popup.open = function( event ) {
	var triggered_element = jQuery( this );
  // Récupères la box de destination mis dans l'attribut du popup
  var target = triggered_element.closest(  "." + triggered_element.data( 'parent' ) ).find( "." + triggered_element.data( 'target' ) );
	target.toggle();

	if ( target.is( ":visible" ) && triggered_element.data( 'cb-object' ) && triggered_element.data( 'cb-func' ) ) {
		var callback_object = triggered_element.data( 'cb-object' );
		var callback_func = triggered_element.data( 'cb-func' );

		// On récupères les "data" sur l'élement en tant qu'args.
		triggered_element.get_data( function( data ) {
			window.task_manager[callback_object][callback_func]( triggered_element, target, event, data );
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
window.task_manager.popup.open_ajax = function( event ) {
	var element = jQuery( this );

  // Récupères la box de destination mis dans l'attribut du popup
  var target = jQuery( this ).closest(  "." + jQuery( this ).data( 'parent' ) ).find( "." + jQuery( this ).data( 'target' ) );
	target.toggle();


	jQuery( this ).get_data( function( data ) {
		delete data.parent;
		delete data.target;
		window.task_manager.request.send( element, data );
	});

  event.stopPropagation();
};

window.task_manager.popup.confirm = function( event ) {
	var triggered_element = jQuery( this );
	jQuery( '.popup' ).hide();

	if ( triggered_element.data( 'cb-object' ) && triggered_element.data( 'cb-func' ) ) {
		var callback_object = triggered_element.data( 'cb-object' );
		var callback_func = triggered_element.data( 'cb-func' );

		// On récupères les "data" sur l'élement en tant qu'args.
		triggered_element.get_data( function( data ) {
			window.task_manager[callback_object][callback_func]( triggered_element, event, data );
		} );
	}
}

window.task_manager.popup.stop = function( event ) {
	event.stopPropagation();
};

window.task_manager.popup.close = function( event ) {
  jQuery( '.popup' ).hide();
  jQuery( '.digi-popup' ).hide();
}
