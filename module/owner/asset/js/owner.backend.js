/**
 * Initialise l'objet "user" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.owner = {};

window.eoxiaJS.taskManager.owner.init = function() {
	window.eoxiaJS.taskManager.owner.event();
};

/**
 * Initialise les évènements des utilisateurs
 *
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.owner.event = function() {};


/**
 * Callback en cas de réussite de la requête Ajax "load_edit_mode_owner"
 * Remplaces le template de .users pour afficher les utilisateurs.
 *
 * @param  {HTMLSpanElement} triggeredElement   L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}        response             Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.owner.loadedEditModeOwnerSuccess = function( triggeredElement, response ) {
	triggeredElement.removeClass( 'loading' );
	jQuery( triggeredElement ).find( '.content' ).html( response.data.view );
};

/**
 * Callback en cas de réussite de la requête Ajax "switch_owner"
 * Remplaces le template du responsable
 *
 * @param  {HTMLSpanElement} triggeredElement   L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}        response             Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.owner.switchedOwnerSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.toggle' ).replaceWith( response.data.view );
};
