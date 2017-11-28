/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.task = {};
window.eoxiaJS.taskManager.task.offset = 0;
window.eoxiaJS.taskManager.task.canLoadMore = true;

window.eoxiaJS.taskManager.task.init = function() {
	window.eoxiaJS.taskManager.task.event();
	jQuery( '.list-task' ).masonry( {
		itemSelector: '.wpeo-project-task'
	} );

	window.eoxiaJS.taskManager.task.initAutoComplete();
};

window.eoxiaJS.taskManager.task.refresh = function() {
	jQuery( '.list-task' ).masonry( 'layout' );
	window.eoxiaJS.taskManager.task.initAutoComplete();
};

window.eoxiaJS.taskManager.task.event = function() {
	jQuery( '.wpeo-project-wrap' ).on( 'keypress', '.wpeo-project-task-title', window.eoxiaJS.taskManager.task.keyEnterEditTitle );
	jQuery( '.wpeo-project-wrap' ).on( 'blur', '.wpeo-project-task-title', window.eoxiaJS.taskManager.task.editTitle );
	jQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-task-time-manage .dashicons-editor-ul', window.eoxiaJS.taskManager.task.switchViewToLine );
	jQuery( window ).scroll( window.eoxiaJS.taskManager.task.onScrollLoadMore );
	jQuery( '.wpeo-project-wrap' ).on( 'click', '.task-header-action .success span', window.eoxiaJS.taskManager.task.closeSuccess );
	jQuery( '#poststuff' ).on( 'click', '#wpeo-task-metabox', window.eoxiaJS.taskManager.task.refresh );
	jQuery( '#wpeo-task-metabox h2 span .action-attribute' ).click( window.eoxiaJS.action.execAttribute );
};

/**
 * Initialise l'autocomplete pour déplacer la tâche.
 *
 * @return {void}
 *
 * @since 1.4.0-ford
 * @version 1.4.0-ford
 */
window.eoxiaJS.taskManager.task.initAutoComplete = function() {
	jQuery( '.search-parent' ).autocomplete( {
		'source': 'admin-ajax.php?action=search_parent',
		'delay': 0,
		'select': function( event, ui ) {
			jQuery( 'input[name="to_element_id"]' ).val( ui.item.id );
			jQuery( this ).closest( '.form-fields' ).find( '.action-input' ).addClass( 'active' );
			event.stopPropagation();
		}
	} );
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

			window.eoxiaJS.loader.display( jQuery( '.load-more' ) );
			jQuery( '.load-more' ).show();
			window.eoxiaJS.request.send( jQuery( '.load-more' ), data );
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
	jQuery( '.load-more' ).hide();
	jQuery( '.list-task' ).append( element ).masonry( 'appended', element );
	window.eoxiaJS.taskManager.task.canLoadMore = response.data.can_load_more;
	window.eoxiaJS.refresh();
};

/**
 * Envoie une requête pour enregsitrer le nouveau titre de la tâche.
 *
 * @since 1.0.0
 * @version 1.4.0
 *
 * @param  {FocusEvent} event         L'état de l'évènement lors du 'blur'.
 * @param  {HTMLInputElement} element Le champ de texte contenant le titre.
 * @return {void}
 */
window.eoxiaJS.taskManager.task.editTitle = function( event, element ) {
	var data = {};

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action = 'edit_title';
	data._wpnonce = element.data( 'nonce' );
	data.task_id = element.closest( '.wpeo-project-task' ).data( 'id' );
	data.title = element.val();

	window.eoxiaJS.loader.display( element.closest( '.wpeo-task-header' ) );
	window.eoxiaJS.request.send( element, data );
};

/**
 * Réaffiches les points lors du clic.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @param  {ClickEvent} event         L'état de l'évènement lors du 'click'.
 * @return {void}
 */
window.eoxiaJS.taskManager.task.switchViewToLine = function( event ) {
	jQuery( this ).addClass( 'active' );
	jQuery( this ).closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .dashicons-screenoptions.active' ).removeClass( 'active' );
	jQuery( this ).closest( '.wpeo-project-task' ).find( '.activities' ).hide();
	jQuery( this ).closest( '.wpeo-project-task' ).find( '.points.sortable' ).show();
	jQuery( this ).closest( '.wpeo-project-task' ).find( '.wpeo-task-point-use-toggle' ).show();
	window.eoxiaJS.refresh();
};

/**
 * Appel la méthode 'editTitle' pour modifier le titre lors de l'appuie de la touche entré.
 *
 * @since 1.0.0
 * @version 1.4.0
 *
 * @param  {KeyboardEvent} event L'état du clavier.
 * @return {void}
 */
window.eoxiaJS.taskManager.task.keyEnterEditTitle = function( event ) {
	if ( 13 === event.which || 13 === event.keyCode ) {
		window.eoxiaJS.taskManager.task.editTitle( event, jQuery( this ) );
	}
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
	jQuery( '.wpeo-project-wrap .load-more' ).remove();

	jQuery( '.list-task' ).masonry( 'remove', jQuery( '.wpeo-project-task' ) );
	jQuery( '.list-task' ).replaceWith( response.data.view );
	jQuery( '.list-task' ).masonry();
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
	window.eoxiaJS.refresh();
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

window.eoxiaJS.taskManager.task.loadedCorretiveTaskSuccess = function( triggeredElement, response ) {

	jQuery( '.list-task' ).masonry( 'remove', jQuery( '.wpeo-project-task' ) );
	jQuery( '.list-task' ).replaceWith( response.data.view );
	jQuery( '.list-task' ).masonry();
	window.eoxiaJS.taskManager.task.offset = 0;
	window.eoxiaJS.taskManager.task.canLoadMore = true;
	jQuery( '.wpeo-project-wrap .load-more' ).remove();

	jQuery( '.wpeo-header-bar li.active' ).removeClass( 'active' );
	jQuery( triggeredElement ).addClass( 'active' );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "export_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.task.exportedTask = function( triggeredElement, response ) {
	window.eoxiaJS.global.downloadFile( response.data.url, response.data.filename );
};

/**
 * Le callback en cas de réussite à la requête Ajax "notify_by_mail".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.task.notifiedByMail = function( triggeredElement, response ) {

};


/**
 * Enlève la classe 'active' de l'élement 'success'.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @param  {MouseEvent} event L'état de la souri.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.task.closeSuccess = function( event ) {
	jQuery( this ).closest( '.success.active' ).removeClass( 'active' );
};
