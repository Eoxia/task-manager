/**
 * Initialise l'objet core dans taskManagerWPShop.
 *
 * @since 1.5.0
 * @version 1.5.0
 */

if ( undefined === window.eoxiaJS.taskManager ) {
	window.eoxiaJS.taskManager = {};
}

/**
 * Initialise l'objet "activity" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.adminBar = {};

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
window.eoxiaJS.taskManager.adminBar.loadedCustomerActivity = function( triggeredElement, response ) {
	jQuery( '#TB_ajaxContent' ).html( response.data.view );
};
