/**
 * Initialise l'objet "notify" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.notify = {};

/**
 * Méthode 'init' obligatoire.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.notify.init = function() {
	window.eoxiaJS.taskManager.notify.event();
};

/**
 * Méthode 'event'.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.notify.event = function() {
	jQuery( document ).on( 'click', '.popup.popup-notification ul li', window.eoxiaJS.taskManager.notify.selectUser );
};

/**
 * Méthode qui permet d'ajouter/supprimer l'utilisateur ID dans champs caché users_id.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.notify.selectUser = function() {
	var index = 0;
	var popup = jQuery( this ).closest( '.popup.popup-notification' );
	var input = popup.find( 'input[name="users_id"]' );
	var currentVal = input.val();

	if ( currentVal ) {
		currentVal = currentVal.split( ',' );
	} else {
		currentVal = [];
	}

	for ( var i = 0; i < currentVal.length; i++ ) {
		currentVal[i] = parseInt( currentVal[i] );
	}

	jQuery( this ).toggleClass( 'active' );

	if ( jQuery( this ).hasClass( 'active' ) ) {
		currentVal.push( parseInt( jQuery( this ).data( 'id' ) ) );
	} else {
		index = currentVal.indexOf( jQuery( this ).data( 'id' ) );

		if ( -1 != index ) {
			currentVal.splice( index, 1 );
		}
	}

	input.val( currentVal.join( ',' ) );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_notify_popup".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.notify.loadedNotifyPopup = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .content' ).html( response.data.view );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .container' ).removeClass( 'loading' );
};

/**
 * Le callback en cas de réussite à la requête Ajax "send_notification".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.notify.sendedNotification = function( triggeredElement, response ) {
	// var successElement = triggeredElement.closest( '.task-header-action' ).find( '.success' );
	// successElement.addClass( 'active' );
	//
	// successElement.interval = setTimeout( function() {
	// 	successElement.removeClass( 'active' );
	// }, 3000 );

	triggeredElement.closest( '.popup' ).find( '.close' ).click();


};
