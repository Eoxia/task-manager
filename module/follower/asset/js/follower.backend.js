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

/**
 * Cette méthode est appelé automatiquement lors du clique sur une catégorie a affecter.
 *
 * @param  {HTMLUListElement} element L'élément déclenchant la méthode au clique.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.follower.beforeAffectFollower = function( element ) {
	element.addClass( 'active' );

	return true;
};

/**
 * Cette méthode est appelé automatiquement lors du clique sur une catégorie a désaffecter.
 *
 * @param  {HTMLUListElement} element L'élément déclenchant la méthode au clique.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.follower.beforeUnaffectFollower = function( element ) {
	element.removeClass( 'active' );

	return true;
};
