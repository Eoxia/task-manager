/** File tag/asset/js/backend.js */

window.task_manager.tag = {};
window.task_manager.tag.init = function() {
	window.task_manager.tag.event();
};
window.task_manager.tag.event = function() { };

/**
 * Lorsqu'on clique sur la barre des tags, avant de lancer l'action on ajoute une classe permettant de bloquer les actions futures tant que cette action n'est pas terminée
 *
 * @param  {HTMLUListElement} element  The element clicked where to display tags.
 */
window.task_manager.tag.before_load_tags = function( element ) {
	element.addClass( 'no-action' );

	return true;
};
/**
 * Affiche la liste des tags pour affectation à un élément
 *
 * @param  {Object} element  The element clicked where to display tags.
 * @param  {Object} response The response to use to display tags
 */
window.task_manager.tag.load_tag_success = function( element, response ) {
	element.html( response.data.view );
};

window.task_manager.tag.load_archived_task = function( element, response ) {
	element.html( response.data.view );
};

window.task_manager.tag.tag_affectation_success = function( element, response ) {
	element.addClass( 'active' );
};
