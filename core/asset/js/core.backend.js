/**
 * Initialise l'objet "core" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.0.0
 */

window.eoxiaJS.taskManager.core = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.core.init = function() {
	window.eoxiaJS.taskManager.core.event();
};

/**
 * La méthode contenant tous les évènements pour la core.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.core.event = function() {
	jQuery( document ).on( 'click', '.wpeo-project-wrap .notification.patch-note.active', window.eoxiaJS.taskManager.core.openPopup );
	jQuery( document ).on( 'click', '.wpeo-project-wrap .notification.patch-note .close', window.eoxiaJS.taskManager.core.closeNotification );
};

/**
 * Ajoutes la classe 'active' dans l'élement 'popup.path-note'.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @param  {MouseEvent} event Les attributs de l'évènement.
 * @return {void}
 */
window.eoxiaJS.taskManager.core.openPopup = function( event ) {
	event.stopPropagation();
	jQuery( '.wpeo-project-wrap .popup.patch-note' ).addClass( 'active' );
};

/**
 * Ajoutes la classe 'active' dans l'élement 'popup.path-note'.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @param  {MouseEvent} event Les attributs de l'évènement.
 * @return {void}
 */
window.eoxiaJS.taskManager.core.closeNotification = function( event ) {
	event.stopPropagation();
	jQuery( this ).closest( '.notification' ).removeClass( 'active' );
};

/**
 * Actives ou désactive l'évènement unload pour le "safeExit".
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param  {boolean} add True active, false désactive l'évènement.
 * @return {void}
 */
window.eoxiaJS.taskManager.core.initSafeExit = function( add ) {
	if ( add ) {
		window.addEventListener( 'beforeunload', window.eoxiaJS.taskManager.core.safeExit );
	} else {
		window.removeEventListener( 'beforeunload', window.eoxiaJS.taskManager.core.safeExit );

	}
}
/**
 * Ajoutes une popup si l'utilisateur essai de quitter la page.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.core.safeExit = function() {
	var confirmationMessage = 'The changes you have made will not be saved.';

	event.returnValue = confirmationMessage;
	return confirmationMessage;
}
