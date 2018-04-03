/**
 * Initialise l'objet "timeExceeded" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.6.1
 */
window.eoxiaJS.taskManager.timeExceeded = {};

window.eoxiaJS.taskManager.timeExceeded.init = function() {
	window.eoxiaJS.taskManager.timeExceeded.event();
};

window.eoxiaJS.taskManager.timeExceeded.event = function() {
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_time_exceeded".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.6.1
 */
window.eoxiaJS.taskManager.timeExceeded.loadedTimeExceeded = function( triggeredElement, response ) {
	jQuery( '#tm-indicator-time-exceeded .inside table' ).replaceWith( response.data.view );
};
