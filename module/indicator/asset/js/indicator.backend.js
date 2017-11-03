/**
 * Initialise l'objet "indicator" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.indicator = {};

/**
 * Le callback en cas de réussite à la requête Ajax "load_customer_activity".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.indicator.loadedCustomerActivity = function( triggeredElement, response ) {
	jQuery( '#tm-indicator-activity .inside' ).html( response.data.view );
};
