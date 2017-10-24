/**
 * Initialise l'objet "tag" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.tag = {};

window.eoxiaJS.taskManager.tag.init = function() {
	window.eoxiaJS.taskManager.tag.event();
};

window.eoxiaJS.taskManager.tag.event = function() { };

/**
 * Lorsqu'on clique sur la barre des tags, avant de lancer l'action on ajoute une classe permettant de bloquer les actions futures tant que cette action n'est pas terminée
 *
 * @param  {HTMLUListElement} element  The element clicked where to display tags.
 */
window.eoxiaJS.taskManager.tag.before_load_tags = function( element ) {
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
window.eoxiaJS.taskManager.tag.archivedTaskSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).remove();

	if ( jQuery().masonry ) {
		jQuery( '.list-task' ).masonry( 'remove', triggeredElement.closest( '.wpeo-project-task' ) );
	}
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
window.eoxiaJS.taskManager.tag.unarchivedTaskSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).remove();

	if ( jQuery().masonry ) {
		jQuery( '.list-task' ).masonry( 'remove', triggeredElement.closest( '.wpeo-project-task' ) );
	}
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
window.eoxiaJS.taskManager.tag.loadedTagSuccess = function( element, response ) {
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
window.eoxiaJS.taskManager.tag.closedTagEditMode = function( element, response ) {
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
window.eoxiaJS.taskManager.tag.loadedArchivedTask = function( triggeredElement, response ) {
	jQuery( '.wpeo-project-wrap .load-more' ).remove();

	if ( jQuery().masonry ) {
		jQuery( '.list-task' ).masonry( 'remove', jQuery( '.wpeo-project-task' ) );
	}
	jQuery( '.list-task' ).replaceWith( response.data.view );

	if ( jQuery().masonry ) {
		jQuery( '.list-task' ).masonry();
	}
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
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
window.eoxiaJS.taskManager.tag.beforeAffectTag = function( element ) {
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
window.eoxiaJS.taskManager.tag.beforeUnaffectTag = function( element ) {
	element.removeClass( 'active' );

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
window.eoxiaJS.taskManager.tag.createdTagSuccess = function( triggeredElement, response ) {
	jQuery( '.wpeo-tag-title' ).after( response.data.view );
	jQuery( 'input[name="tag_name"]' ).val( '' );
};

/**
 * Le callback en cas de réussite à la requête Ajax "tag_affectation".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.tag.affectedTagSuccess = function( element, response ) {
	element.attr( 'data-action', 'tag_affectation' );
	element.attr( 'data-before-method', 'beforeAffectTag' );
	element.attr( 'data-nonce', response.data.nonce );
};

/**
 * Le callback en cas de réussite à la requête Ajax "tag_unaffectation".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.tag.unaffectedTagSuccess = function( element, response ) {
	element.attr( 'data-action', 'tag_unaffectation' );
	element.attr( 'data-before-method', 'beforeUnaffectTag' );
	element.attr( 'data-nonce', response.data.nonce );
};
