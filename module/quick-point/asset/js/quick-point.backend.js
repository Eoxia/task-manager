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
	jQuery( document ).on( 'addedPointSuccess', '.wpeo-modal.modal-active.quick-point .action-input', window.eoxiaJS.taskManager.quickPoint.addedPointSuccess );
	jQuery( document ).on( 'keyup', '.wpeo-modal.quick-point .point:not(.edit) .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.quickPoint.triggerCreate );
};

/**
 * Lance la fermeture de la modal une fois que le point est ajouté avec succés.
 *
 * @since 1.7.0
 * @version 1.7.0
 *
 * @param  {CustomEvent} event Envoyé par Task Manager/Point.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.quickPoint.addedPointSuccess = function( event ) {
	event.preventDefault();
	window.eoxiaJS.modal.close();
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
		jQuery( '.wpeo-modal.quick-point .action-input.button-main' ).click();
	}
};
