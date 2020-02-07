/**
 * Initialise l'objet "notify" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.6.1
 */
window.eoxiaJS.taskManager.notify = {};
window.eoxiaJS.taskManager.notify.oldTitle = undefined;
window.eoxiaJS.taskManager.notify.newTitle = undefined;

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
	window.eoxiaJS.taskManager.notify.oldTitle = document.title;

	jQuery( document ).on( 'click', '.popup-notification ul li', window.eoxiaJS.taskManager.notify.selectUser );

	jQuery( document ).on( 'heartbeat-tick', function (event, data) {
		if ( data.number_notifications > 0 ) {
			jQuery( '.tm-notification .wpeo-button' ).removeClass( 'notification-active' ).addClass( 'notification-active' );
			jQuery( '.tm-notification .wpeo-button .notification-number' ).addClass( 'notification-number-active' );

			jQuery( '.tm-notification .dropdown-toggle .notification-number' ).text( data.number_notifications );
			jQuery( '.tm-notification .wpeo-dropdown .dropdown-content' ).html( data.notification_view );

			window.eoxiaJS.taskManager.notify.newTitle = window.eoxiaJS.taskManager.notify.oldTitle.replace('‹', '(' + data.number_notifications + ') ‹');
			document.title = window.eoxiaJS.taskManager.notify.newTitle;

			setTimeout(function () {
				document.title = window.eoxiaJS.taskManager.notify.oldTitle;
				setTimeout(function () {
					document.title = window.eoxiaJS.taskManager.notify.newTitle;
					setTimeout(function () {
						document.title = window.eoxiaJS.taskManager.notify.oldTitle;
						setTimeout(function () {
							document.title = window.eoxiaJS.taskManager.notify.newTitle;
						}, 2000);
					}, 2000);
				}, 2000);
			}, 2000);
		}
	});
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
	var index      = 0;
	var container  = jQuery( this ).closest( 'ul' );
	var input      = container.find( 'input' );
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

	jQuery( this ).closest( 'div' ).find( '.selected-number' ).text( currentVal.length );

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
 * @version 1.6.1
 */
window.eoxiaJS.taskManager.notify.sendedNotification = function( triggeredElement, response ) {
	triggeredElement.closest( '.wpeo-modal' ).find( '.modal-close' ).click();


};

window.eoxiaJS.taskManager.notify.closedNotification = function( triggeredElement, response ) {
	triggeredElement.closest( '.notification-content' ).fadeOut();
};

window.eoxiaJS.taskManager.notify.markedAllAsRead = function( triggeredElement, response ) {
	jQuery( '.dropdown-content.notification-container .notification-content' ).each( function() {
		jQuery( this ).slideUp();
	} );

	jQuery( '.tm-notification .notification-number-active' ).removeClass( 'notification-number-active' );
};
