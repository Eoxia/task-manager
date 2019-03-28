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
	jQuery( '.list-task' ).colcade( {
		items: '.wpeo-project-task',
		columns: '.grid-col'
	} );

	window.eoxiaJS.taskManager.task.initAutoComplete();
};

window.eoxiaJS.taskManager.task.refresh = function() {
	window.eoxiaJS.taskManager.task.initAutoComplete();
};

window.eoxiaJS.taskManager.task.event = function() {
	// jQuery( '.tm-wrap' ).on( 'keypress', '.wpeo-project-task-title', window.eoxiaJS.taskManager.task.keyEnterEditTitle );
	jQuery( '.tm-wrap' ).on( 'blur', '.wpeo-project-task-title', window.eoxiaJS.taskManager.task.editTitle );
	jQuery( window ).scroll( '.wpeo-wrap .tm-wrap #poststuff', window.eoxiaJS.taskManager.task.onScrollLoadMore );
	jQuery( '.tm-wrap' ).on( 'click', '.task-header-action .success span', window.eoxiaJS.taskManager.task.closeSuccess );
	jQuery( '#poststuff' ).on( 'click', '#wpeo-task-metabox', window.eoxiaJS.taskManager.task.refresh );
	jQuery( document ).on( 'click', '#tm_include_archive_client', window.eoxiaJS.taskManager.task.showArchiveClient );

	jQuery( document ).on( 'click', '.tm_client_indicator_update', window.eoxiaJS.taskManager.audit.preventDefaultHeader );
	jQuery( document ).on( 'click', '.tm_client_indicator_update_body table tbody .tm_client_indicator', window.eoxiaJS.taskManager.audit.openTaskRow );
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
	console.log( '-------' );
	var data = {};

	if ( 1 !== jQuery( '#poststuff' ).length ) {
		if ( ( jQuery( window ).scrollTop() == jQuery( document ).height() - jQuery( window ).height() ) && window.eoxiaJS.taskManager.task.canLoadMore ) {

			window.eoxiaJS.taskManager.task.offset += parseInt( window.task_manager_posts_per_page );
			window.eoxiaJS.taskManager.task.canLoadMore = false;

			data.action = 'load_more_task';
			data.offset = window.eoxiaJS.taskManager.task.offset;
			data.posts_per_page = window.task_manager_posts_per_page;
			data.term = jQuery( '.wpeo-header-bar input[name="term"]' ).val();
			data.users_id = jQuery( '.wpeo-header-search select[name="follower_id_selected"]' ).val();
			data.status = jQuery( '.wpeo-header-bar input[name="status"]' ).val();

			window.eoxiaJS.taskManager.navigation.checkDataBeforeSearch( undefined );

			data.categories_id = jQuery( '.wpeo-header-search input[name="categories_id_selected"]' ).val();

			window.eoxiaJS.loader.display( jQuery( '.load-more' ) );
			jQuery( '.load-more' ).show();
			window.eoxiaJS.request.send( jQuery( '.load-more' ), data );
		}
	}
};

window.eoxiaJS.taskManager.task.loadedMoreTask = function( element, response ){

	window.eoxiaJS.taskManager.task.canLoadMore = response.data.can_load_more;

	var elements = jQuery( response.data.view );
	jQuery( '.list-task' ).colcade( 'append', elements );

	jQuery( '.load-more' ).hide();
}
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
	data.title = element.text();

	window.eoxiaJS.loader.display( element.closest( '.wpeo-task-header' ) );
	window.eoxiaJS.request.send( element, data );
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
	jQuery( '.list-task' ).colcade( 'prepend', element );
	//jQuery( '.tm-dashboard-primary .list-task .grid-col--1').prepend( element );
	window.eoxiaJS.taskManager.task.initAutoComplete();
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
	element.closest( '.wpeo-project-task' ).hide();
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
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).hide();
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
 * Le callback en cas de réussite à la requête Ajax "recompile_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.task.recompiledTask = function( triggeredElement, response ) {
	triggeredElement.closest( '.wpeo-project-task' ).html( response.data.view );
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

window.eoxiaJS.taskManager.task.updateIndicatorClientSuccess = function( element, response ) {
	jQuery( '.tm_client_indicator_update_body' ).replaceWith( response.data.view );
	jQuery( '.tm_client_indicator_update #tm_client_indicator_header_minus' ).attr( 'data-year', response.data.year - 1 );
	jQuery( '.tm_client_indicator_update #tm_client_indicator_header_actual' ).attr( 'data-year', response.data.year );
	jQuery( '.tm_client_indicator_update #tm_client_indicator_header_display' ).html( response.data.year );
	jQuery( '.tm_client_indicator_update #tm_client_indicator_header_plus' ).attr( 'data-year', response.data.year + 1 );
};

window.eoxiaJS.taskManager.task.showArchiveClient = function( triggeredElement, response ){
	if( jQuery( '#tm_include_archive_client' ).data('showarchive') ){
		jQuery( '#tm_include_archive_client' ).data('showarchive', false );

		jQuery( '#tm_show_archive_button_client' ).show();
		jQuery( '#tm_hide_archive_button_client' ).hide();

		jQuery( '.wpeo-project-wrap .list-task .wpeo-project-task' ).each(function(){
			if( jQuery( this ).data( 'status' ) == 'archive' ){
				jQuery( this ).css( 'display', 'none' );
			}
		});
	}else{
		jQuery( '#tm_include_archive_client' ).data('showarchive', true );

		jQuery( '#tm_show_archive_button_client' ).hide();
		jQuery( '#tm_hide_archive_button_client' ).show();

		jQuery( '.wpeo-project-wrap .list-task .wpeo-project-task' ).each(function(){
			if( jQuery( this ).data( 'status' ) == 'archive' ){
				jQuery( this ).css( 'display', 'block' );
			}
		});

	}
}

window.eoxiaJS.taskManager.audit.openTaskRow = function( event ){
	var select = jQuery( this );

	if( select.attr( 'data-show' ) == 'true' ){
		select.attr( 'data-show', 'false' );
		jQuery( '.tm_client_indicator_' + select.attr( 'data-id' ) + '_' + select.attr( 'data-type' ) ).hide( '200' );
	}else{
		select.attr( 'data-show', 'true' );
		jQuery( '.tm_client_indicator_' + select.attr( 'data-id' ) + '_' + select.attr( 'data-type' ) ).show( '500' );
	}
}
