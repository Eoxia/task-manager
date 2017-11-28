/**
 * Initialise l'objet "core" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 6.5.0
 * @version 6.5.0
 */

window.eoxiaJS.taskManager.core = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 6.5.0
 * @version 6.5.0
 */
window.eoxiaJS.taskManager.core.init = function() {
	window.eoxiaJS.taskManager.core.event();
};

/**
 * La méthode contenant tous les évènements pour la core.
 *
 * @since 6.5.0
 * @version 6.5.0
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
 * @since 6.5.0
 * @version 6.5.0
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
 * @since 6.5.0
 * @version 6.5.0
 *
 * @param  {MouseEvent} event Les attributs de l'évènement.
 * @return {void}
 */
window.eoxiaJS.taskManager.core.closeNotification = function( event ) {
	event.stopPropagation();
	jQuery( this ).closest( '.notification' ).removeClass( 'active' );
};
