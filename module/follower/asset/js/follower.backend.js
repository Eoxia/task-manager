/**
 * Initialise l'objet "point" ainsi que la méthode "follower" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.follower = {};

window.task_manager.follower.init = function() {
	window.task_manager.follower.event();
};

window.task_manager.follower.event = function() { };

/**
 * Le callback en cas de réussite à la requête Ajax "load_followers".
 * Remplaces le contenu de l'element cliqué par la vue reçu dans la réponse AJAX.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.follower.loadedFollowersSuccess = function( element, response ) {
	element.closest( '.wpeo-ul-users' ).replaceWith( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "close_followers_edit_mode".
 * Remplaces le contenu de l'element cliqué par la vue reçu dans la réponse AJAX.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.follower.closedFollowersEditMode = function( element, response ) {
	element.closest( '.wpeo-ul-users' ).replaceWith( response.data.view );
	window.eoxiaJS.refresh();
};
