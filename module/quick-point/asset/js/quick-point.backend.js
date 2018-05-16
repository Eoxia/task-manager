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
};

/**
 * Lance la fermeture de la modal une fois que le point est ajouté avec succés.
 *
 * @param  {CustomEvent} event Envoyé par Task Manager/Point.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.quickPoint.addedPointSuccess = function( event ) {
	event.preventDefault();
	window.eoxiaJS.modal.close();
};
