/**
 * Initialise l'objet "quickPoint" (point rapide) ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.7.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.quickPoint = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.7.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.quickPoint.init = function() {
	window.eoxiaJS.taskManager.quickPoint.event();
};

/**
 * Initialise tous les évènements liés aux points rapide de Task Manager.
 *
 * @return {void}
 *
 * @since 1.7.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.quickPoint.event = function() {
	jQuery( document ).on( 'keyup', '.wpeo-modal.quick-point .point:not(.edit) .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.quickPoint.triggerCreate );

	jQuery( document ).on( 'change', '.point-content input[name="content"]', window.eoxiaJS.taskManager.quickPoint.onChange );
};

/**
 * Passes le contenu de la modal en "success".
 *
 * @since 1.7.0
 * @version 1.7.0
 *
 * @param  {CustomEvent} event Envoyé par Task Manager/Point.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.quickPoint.addedPointSuccess = function( triggeredElement, response ) {
	window.eoxiaJS.taskManager.point.addedPointSuccess( triggeredElement, response );

	jQuery( '.wpeo-modal.quick-point .modal-content' ).html( response.data.modal_view );
	jQuery( '.wpeo-modal.quick-point .modal-footer' ).html( response.data.modal_buttons_view );
};

/**
 * Reload la modal.
 *
 * @since 1.7.0
 * @version 1.7.0
 *
 * @param  {CustomEvent} event Envoyé par Task Manager/Point.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.quickPoint.reloadModal = function( triggeredElement, response ) {
	jQuery( '.wpeo-modal.quick-point .modal-content' ).html( response.data.view );
	jQuery( '.wpeo-modal.quick-point .modal-footer' ).html( response.data.buttons_view );
};

/**
 * Clic sur le bouton "Add" de la modal.
 *
 * @since 1.7.0
 * @version 1.7.0
 *
 * @param  {KeyboardEvent} event L'état du clavier à l'instant du keyUp.
 * @return {void}
 */
window.eoxiaJS.taskManager.quickPoint.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( '.wpeo-modal.quick-point .action-input.button-main:not(.button-disable)' ).click();
	}
};

/**
 * Enlève la classe button-disable si le contenu du bouton n'est pas vide.
 *
 * @since 1.7.0
 * @version 1.7.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.quickPoint.onChange = function() {
	if ( 0 < jQuery( this ).val().length ) {
		jQuery( '.wpeo-modal.quick-point .modal-footer .button-main.button-disable' ).removeClass( 'button-disable' );
	} else {
		jQuery( '.wpeo-modal.quick-point .modal-footer .button-main' ).addClass( 'button-disable' );
	}
};
