/**
 * Initialise l'objet "user" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.3.6
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.owner = {};

window.eoxiaJS.taskManager.owner.init = function() {
	window.eoxiaJS.taskManager.owner.event();
};

/**
 * Initialise les évènements des utilisateurs
 *
 * @since 1.3.6
 * @version 1.3.6
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.owner.event = function() {};

/**
 * Callback en cas de réussite de la requête Ajax "switch_owner"
 * Remplaces le template du responsable
 *
 * @since 1.3.6
 * @version 1.6.0
 *
 * @param  {HTMLSpanElement} triggeredElement   L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}        response             Les données renvoyées par la requête Ajax.
 * @return {void}
 */
window.eoxiaJS.taskManager.owner.switchedOwnerSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-dropdown' ).replaceWith( response.data.view );
};
