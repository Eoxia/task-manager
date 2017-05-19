/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task = {};
window.eoxiaJS.taskManager.task.offset = 0;
window.eoxiaJS.taskManager.task.canLoadMore = true;

window.eoxiaJS.taskManager.task.init = function() {
	window.eoxiaJS.taskManager.task.event();
	jQuery( '.list-task' ).masonry( {
		itemSelector: '.wpeo-project-task'
	} );
};

window.eoxiaJS.taskManager.task.refresh = function() {
	jQuery( '.list-task' ).masonry( 'layout' );
};

window.eoxiaJS.taskManager.task.event = function() {
	jQuery( '.wpeo-project-wrap' ).on( 'blur', '.wpeo-project-task-title', window.eoxiaJS.taskManager.task.editTitle );
	jQuery( window ).scroll( window.eoxiaJS.taskManager.task.onScrollLoadMore );
};

window.eoxiaJS.taskManager.task.onScrollLoadMore = function() {
	var data = {};

	if ( 1 !== jQuery( '#poststuff' ).length ) {
		if ( ( jQuery( window ).scrollTop() == jQuery( document ).height() - jQuery( window ).height() ) && window.eoxiaJS.taskManager.task.canLoadMore ) {
			window.eoxiaJS.taskManager.task.offset += parseInt( window.task_manager_posts_per_page );
			window.eoxiaJS.taskManager.task.canLoadMore = false;

			data.action = 'load_more_task';
			data.offset = window.eoxiaJS.taskManager.task.offset;
			data.posts_per_page = window.task_manager_posts_per_page;
			data.term = jQuery( '.wpeo-header-bar input[name="term"]' ).val();
			data.users_id = ( 'load_my_task' == jQuery( '.wpeo-header-bar li.active' ).data( 'action' ) ) ? jQuery( 'input.user-id' ).val() : jQuery( '.wpeo-header-search select[name="follower_id_selected"]' ).val();
			data.status = ( 'load_archived_task' == jQuery( '.wpeo-header-bar li.active' ).data( 'action' ) ) ? 'archive' : 'publish';

			window.eoxiaJS.taskManager.navigation.checkDataBeforeSearch( undefined );

			data.categories_id = jQuery( '.wpeo-header-search input[name="categories_id_selected"]' ).val();

			jQuery( '.load-more' ).addClass( 'loading' );
			window.eoxiaJS.taskManager.request.send( jQuery( '.load-more' ), data );
		}
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_more_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.loadedMoreTask = function( triggeredElement, response ) {
	var element = jQuery( response.data.view );
	jQuery( '.list-task' ).append( element ).masonry( 'appended', element );
	window.eoxiaJS.taskManager.task.canLoadMore = response.data.can_load_more;
	window.eoxiaJS.refresh();
};

window.eoxiaJS.taskManager.task.editTitle = function( event ) {
	var data = {
		action: 'edit_title',
		_wpnonce: jQuery( this ).data( 'nonce' ),
		task_id: jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' ),
		title: jQuery( this ).val()
	};

	jQuery( this ).closest( '.wpeo-task-header' ).addClass( 'loading' );

	window.eoxiaJS.taskManager.request.send( jQuery( this ), data );
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.createdTaskSuccess = function( element, response ) {
	var element = jQuery( response.data.view );
	window.eoxiaJS.taskManager.task.offset++;
	jQuery( '.list-task' ).prepend( element ).masonry( 'prepended', element );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "delete_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.deletedTaskSuccess = function( element, response ) {
	jQuery( '.list-task' ).masonry( 'remove', element.closest( '.wpeo-project-task' ) );
	jQuery( element ).closest( '.wpeo-project-task' ).remove();

	window.eoxiaJS.refresh();
};

/**
 * Avant d'envoyer la requête pour changer la tâche de couleur.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant l'action.
 * @param  {Object}         data          		Les données du l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.beforeChangeColor = function( triggeredElement, data ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).removeClass( 'red yellow purple white blue green' );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).addClass( jQuery( triggeredElement ).data( 'color' ) );

	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_all_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.loadedAllTask = function( triggeredElement, response ) {
	jQuery( '.list-task' ).masonry( 'remove', jQuery( '.wpeo-project-task' ) );
	jQuery( '.list-task' ).replaceWith( response.data.view );
	jQuery( '.list-task' ).masonry();
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_task_properties".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.loadedTaskProperties = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .content' ).html( response.data.view );

	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .container' ).removeClass( 'loading' );

	jQuery( '.search-parent' ).autocomplete( {
		'source': 'admin-ajax.php?action=search_parent',
		'appendTo': '.list-posts',
		'select': function( event, ui ) {
			jQuery( 'input[name="to_element_id"]' ).val( ui.item.id );
		}
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "move_task_to".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.movedTaskTo = function( triggeredElement, response ) {
	jQuery( '.list-task' ).masonry( 'remove', triggeredElement.closest( '.wpeo-project-task' ) );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).remove();
	window.eoxiaJS.refresh();
};
