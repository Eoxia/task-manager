/**
 * Initialise l'objet "point" ainsi que la méthode "follower" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower = {};

window.eoxiaJS.taskManager.follower.init = function() {
	window.eoxiaJS.taskManager.follower.event();
};

window.eoxiaJS.taskManager.follower.event = function() { };

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
window.eoxiaJS.taskManager.follower.loadedFollowersSuccess = function( element, response ) {
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
window.eoxiaJS.taskManager.follower.closedFollowersEditMode = function( element, response ) {
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
window.eoxiaJS.taskManager.follower.beforeAffectFollower = function( element ) {
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
window.eoxiaJS.taskManager.follower.beforeUnaffectFollower = function( element ) {
	element.removeClass( 'active' );

	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "follower_affectation".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower.affectedFollowerSuccess = function( element, response ) {
	element.attr( 'data-action', 'follower_unaffectation' );
	element.attr( 'data-before-method', 'beforeUnaffectFollower' );
	element.attr( 'data-nonce', response.data.nonce );
};

/**
 * Le callback en cas de réussite à la requête Ajax "follower_unaffectation".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower.unaffectedFollowerSuccess = function( element, response ) {
	element.attr( 'data-action', 'follower_affectation' );
	element.attr( 'data-before-method', 'beforeAffectFollower' );
	element.attr( 'data-nonce', response.data.nonce );
};
