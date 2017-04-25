/**
 * Initialise l'objet "point" ainsi que la méthode "tag" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
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
 * Le callback en cas de réussite à la requête Ajax "archive_task".
 * Remplaces le contenu de list-task.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.tag.archivedTaskSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).remove();
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "unarchive_task".
 * Remplaces le contenu de list-task.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.tag.unarchivedTaskSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).remove();
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_tags".
 * Remplaces le contenu de ".wpeo-tag-wrap" par la vue reçu dans la réponse AJAX.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.tag.loadedTagSuccess = function( element, response ) {
	element.closest( '.wpeo-tag-wrap' ).replaceWith( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "close_tag_edit_mode".
 * Remplaces le contenu de ".wpeo-tag-wrap" par la vue reçu dans la réponse AJAX.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.tag.closedTagEditMode = function( element, response ) {
	element.closest( '.wpeo-tag-wrap' ).replaceWith( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_archived_task".
 * Remplaces le contenu de list-task.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.tag.loadedArchivedTask = function( triggeredElement, response ) {
	jQuery( '.list-task' ).replaceWith( response.data.view );
	window.task_manager.task.offset = 0;
	window.task_manager.task.canLoadMore = true;

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
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
window.task_manager.tag.beforeAffectTag = function( element ) {
	element.addClass( 'wpeo-tag-tag-selected' );

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
window.task_manager.tag.beforeUnaffectTag = function( element ) {
	element.removeClass( 'wpeo-tag-tag-selected' );

	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_tag".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.tag.createdTagSuccess = function( triggeredElement, response ) {
	jQuery( '.wpeo-tag-title' ).after( response.data.view );
	jQuery( 'input[name="tag_name"]' ).val( '' );
};
