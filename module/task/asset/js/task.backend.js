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
	window.eoxiaJS.taskManager.task.clignotePetitIcone();
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
	jQuery( document ).on( 'click', '.tm_client_indicator_update_body table tbody .tm_client_indicator', window.eoxiaJS.taskManager.task.OpenTaskRow );

	jQuery( document ).on( 'click', '.wpeo-pagination.pagination-task .pagination-element', window.eoxiaJS.taskManager.task.paginationUpdateTasks );

	jQuery( document ).on( 'click', '.add_parent_to_task', window.eoxiaJS.taskManager.task.displayInputTextParent );

	jQuery( document ).on( 'click change', '.task-search-taxonomy', window.eoxiaJS.taskManager.task.initAutoComplete );

	jQuery( document ).on( 'click', '.tm-task-delink-parent', window.eoxiaJS.taskManager.task.delinkTaskFromParent );

	jQuery( document ).on( 'click', '.wpeo-task-parent-add .wpeo-tag label', window.eoxiaJS.taskManager.task.taskShowAutocompleteParent );
	jQuery( document ).on( 'focusout', '.wpeo-task-parent-add .wpeo-tag ul', window.eoxiaJS.taskManager.task.taskHideAutocompleteParent );


	jQuery( document ).on( 'keyup', '.tm_task_autocomplete_parent', window.eoxiaJS.taskManager.task.taskUpdateAutocompleteParent );

	jQuery( document ).on( 'click keyup', '.wpeo-ul-parent.wpeo-tag-wrap', window.eoxiaJS.taskManager.task.allClientsFocusIn );

	jQuery( document ).on( 'click', '.wpeo-task-parent-add .tm_list_parent_li_element', window.eoxiaJS.taskManager.task.getValueAutocompleteParent );

	jQuery( document ).on( 'change keyup', '.tm_indicator_updateprofile input[type="number"]', window.eoxiaJS.taskManager.task.activateButtonPlanning );

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

	if ( 1 !== jQuery( '#poststuff' ).length && jQuery( '.tm-dashboard-header' )[0] ) {
		if ( ( jQuery( window ).scrollTop() == jQuery( document ).height() - jQuery( window ).height() ) && window.eoxiaJS.taskManager.task.canLoadMore ) {

			window.eoxiaJS.taskManager.task.offset += parseInt( window.task_manager_posts_per_page );
			window.eoxiaJS.taskManager.task.canLoadMore = false;

			console.log( 'oui' );

			var button_search = jQuery( '.wpeo-modal-event.load_more_result' );
			data.action = 'load_more_task';
			data.offset = window.eoxiaJS.taskManager.task.offset;
			data.posts_per_page = window.task_manager_posts_per_page;
			data.post_parent = button_search.data( 'post-parent' );
			data.term = button_search.data( 'term' );
			data.user_id = button_search.data( 'user-id' );

			data.task_id = button_search.data( 'task-id' );
			data.point_id = button_search.data( 'point-id' );
			data.categories_id = button_search.data( 'categories-id' );
			// data.term = jQuery( '.wpeo-header-bar input[name="term"]' ).val();
			// data.users_id = jQuery( '.wpeo-header-search select[name="follower_id_selected"]' ).val();
			data.status = jQuery( '.wpeo-header-bar input[name="status"]' ).val();

			window.eoxiaJS.taskManager.navigation.checkDataBeforeSearch( undefined );
			console.log( data );
			//data.categories_id = jQuery( '.wpeo-header-search input[name="categories_id_selected"]' ).val();

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

  window.eoxiaJS.taskManager.task.editButtonPaginationClient();

	var pagination_parent = jQuery( this ).parent();

	var data = {};
	data.action    = 'pagination_update_tasks';
	data.page      = jQuery( this ).parent().find( '.wpeo-pagination' ).data( 'page' );
	data.post_id  = jQuery( this ).data( 'post-id' );
	data.next      = jQuery( this ).parent().find( '.wpeo-pagination' ).data( 'page' ); // on récupere la meme page
	data.show      = jQuery( this ).data( 'showarchive' );

	window.eoxiaJS.loader.display( jQuery( this ).parent() );
	window.eoxiaJS.request.send( jQuery( this ), data );
}

window.eoxiaJS.taskManager.task.OpenTaskRow = function( event ){
	var select = jQuery( this );
	var icondown = jQuery( this ).find( '.tag-title .fa-caret-down' );
	var iconup = jQuery( this ).find( '.tag-title .fa-caret-right' );

	if( select.attr( 'data-show' ) == 'true' ){
		icondown.hide();
		iconup.show();
		select.attr( 'data-show', 'false' );
		jQuery( '.tm_client_indicator_' + select.attr( 'data-id' ) + '_' + select.attr( 'data-type' ) ).hide( '200' );
	}else{
		icondown.show();
		iconup.hide();
		select.attr( 'data-show', 'true' );
		jQuery( '.tm_client_indicator_' + select.attr( 'data-id' ) + '_' + select.attr( 'data-type' ) ).show( '200' );
	}
}

window.eoxiaJS.taskManager.task.paginationUpdateTasks = function( event ) {
	var data = {};

	var pagination_parent = jQuery( this ).parent();

	data.action   = 'pagination_update_tasks';
	data.page     = pagination_parent.data( 'page' );
	data.post_id  = pagination_parent.data( 'post-id' );
	data.next     = jQuery( this ).data( 'pagination' );
	data.show     = jQuery( "#tm_include_archive_client" ).data( 'showarchive' );

	window.eoxiaJS.loader.display( jQuery( this ).parent() );
	window.eoxiaJS.request.send( jQuery( this ), data );
}


window.eoxiaJS.taskManager.task.loadedTasksSuccess = function( element, response ) {
	jQuery( '#tm_client_load_task_page' ).replaceWith( response.data.view );

	jQuery( '.list-task' ).colcade( {
		items: '.wpeo-project-task',
		columns: '.grid-col'
	} );

	if( response.data.show_archive ){
		window.eoxiaJS.taskManager.task.editButtonPaginationClient( true, response.data.show_archive );
	}
}

window.eoxiaJS.taskManager.task.editButtonPaginationClient = function( dontKnowStatut = true, showArchive = false ){

  var button_element = jQuery( '#tm_include_archive_client' );

	if( ! dontKnowStatut ){
		var checked = dontKnowStatut;
	}else{
		var checked = button_element.data( 'showarchive' );

	}

	if( checked ){
		button_element.data( 'showarchive', false );

		button_element.css( 'background' , '#f7f7f7' );
		button_element.css( 'color' , '#0073aa;' );

		button_element.find( '.button-icon' ).removeClass( 'fa-check-square' ).addClass( 'fa-square' );

	}else{
		button_element.data('showarchive', true );

		button_element.css( 'background' , '#0084ff' );
		button_element.css( 'color' , '#fff' );

		button_element.find( '.button-icon' ).removeClass( 'fa-square' ).addClass( 'fa-check-square' );

	}
}

window.eoxiaJS.taskManager.task.displayInputTextParent = function( event ){

	if( ! jQuery( this ).data( 'request_send' ) ){
		jQuery( this ).addClass( 'button-disabled' );
		jQuery( this ).css( 'background', '#0084ff' );
		jQuery( this ).css( 'color', 'white' );
		jQuery( this ).data( 'request_send', "true" );
		var data = {
			action: 'load_all_task_parent_data',
			_wpnonce: jQuery( this ).data( 'nonce' )
		};

		window.eoxiaJS.loader.display( jQuery( this ).closest( '.wpeo-ul-parent' ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}else{
		if( ! jQuery( this ).hasClass( 'button-disabled' ) && jQuery( this ).closest( '.wpeo-ul-parent' ).find( '.task_search-taxonomy' ).val() ){

			var data = {
				action: 'link_parent_to_task',
				id : jQuery( this ).data( 'id' ),
				parent_id: jQuery( this ).closest( '.wpeo-ul-parent' ).find( '.task_search-taxonomy' ).val()
			};

			window.eoxiaJS.loader.display( jQuery( this ).closest( '.wpeo-ul-parent' ) );
			window.eoxiaJS.request.send( jQuery( this ), data );
		}else{
			// INVALID ID
		}
	}
}

window.eoxiaJS.taskManager.task.delinkTaskFromParent = function( event ){
	if( confirm( window.indicatorString.delink_parent ) ){
		var data = {
			action: 'delink_parent_to_task',
			id : jQuery( this ).data( 'id' ),
		};

		window.eoxiaJS.loader.display( jQuery( this ).closest( '.wpeo-ul-parent' ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}
}

window.eoxiaJS.taskManager.task.loadedAllClientsCommands = function( element, response ){

	jQuery( element ).parent().find('.wpeo-task-parent-add').html( response.data.view );
	jQuery( element ).parent().find('.wpeo-task-parent-add').show( '400' );
	jQuery( element ).parent().find('.wpeo-task-parent-add .tm_task_autocomplete_parent').focus();

}

window.eoxiaJS.taskManager.task.allClientsFocusIn = function( event ){
	jQuery( this ).find('.wpeo-task-parent-add .wpeo-tag ul').show();
}

window.eoxiaJS.taskManager.task.taskShowAutocompleteParent = function( event ){
	jQuery( this ).find( 'ul' ).show( '200' );
	var div = jQuery( this ).find( '.tm_task_autocomplete_parent' );
	setTimeout(function() {
			div.focus();
	}, 0);
}

window.eoxiaJS.taskManager.task.taskHideAutocompleteParent = function( event ){
	jQuery( this ).find( 'ul' ).hide( '200' );
}


window.eoxiaJS.taskManager.task.taskUpdateAutocompleteParent = function( event ){
	event.stopPropagation();
	var value = jQuery( this ).val().toLowerCase();

	var list_parent = jQuery( this ).closest( '.wpeo-tag' ).find( 'ul' );
	var all = 0;
	var valid = 0;
	if( value == "" ){
		jQuery( this ).closest( '.wpeo-tag' ).find( 'ul li' ).show();
		list_parent.find( '.tm_list_infoempty' ).hide();
		return true;
	}

	var valid_parent = [];

	list_parent.find( '.tm_list_parent_li_element' ).each(function( element ) {
		if( valid >= 12 ){
			jQuery( this ).hide();
		}else{
			var a = jQuery( this ).html().trim().toLowerCase();
			if( a.includes( value ) ){
				if( ! jQuery.inArray( jQuery( this ).data( 'key' ), valid_parent ) !== -1 ){
					valid_parent.push( jQuery( this ).data( 'key' ) );
				}
				valid ++;
				jQuery( this ).show();
			}else{
				jQuery( this ).hide();
			}
		}
	});

	var elementfound = false;
	list_parent.find( '.tm_list_parent' ).each(function( element ) {
		if( jQuery.inArray( jQuery( this ).data( 'key' ), valid_parent ) !== -1 ){
			elementfound = true;
			jQuery( this ).show();
		}else{
			jQuery( this ).hide();
		}
	});

	if( ! elementfound ){ // Aucun element n'a était trouvé
		list_parent.find( '.tm_list_infoempty' ).show();
	}else{
		list_parent.find( '.tm_list_infoempty' ).hide();
	}
}

window.eoxiaJS.taskManager.task.getValueAutocompleteParent = function( event ){
	var value = jQuery( this ).html();
	var id = jQuery( this ).data( 'id' );

	jQuery( this ).closest( '.wpeo-tag' ).find( 'input[type="text"]' ).val( value.trim() );
	jQuery( this ).closest( '.wpeo-tag' ).find( 'input[type="hidden"]' ).val( id );
	jQuery( this ).closest( '.wpeo-ul-parent' ).find( '.add_parent_to_task' ).removeClass( 'button-disabled' );

	jQuery( this ).parent().hide( '200' );
}

window.eoxiaJS.taskManager.task.reloadTaskParentElement = function( element, response ){
	jQuery( element ).closest( '.wpeo-ul-parent' ).replaceWith( response.data.view );
}

window.eoxiaJS.taskManager.task.clignotePetitIcone = function( event ){
	var interval = 0;
	var myReq;
	var k = [67, 65, 77, 73, 76, 76, 69],
	n = 0;

	var oui = false;
	var color = [];

	jQuery(document).keydown(function (e) {

		if( oui ){
			clearInterval( interval );
			jQuery( '.fas' ).each( function(){
				 jQuery( this ).css( 'color', '#000000' );
				});
		 	return;
		}

   if (e.keyCode === k[n++]) {
     if (n === k.length) {
         oui = true;
         interval = setInterval( function(){ jQuery( '.fas' ).each( function( ){
						jQuery( this ).css( 'color', '#'+Math.floor(Math.random()*9999).toString(16) );
	         	// jQuery( this ).rotate(Math.floor(Math.random()*25));
         }); }, 200 );
         n = 0;
         return false;
     }
   }else {
		 clearInterval( interval );
      n = 0;
   }
	});
}

window.eoxiaJS.taskManager.task.activateButtonPlanning = function( event ){
	jQuery( this ).closest( '.tm_indicator_updateprofile' ).find( '.button-add-row-plan .disabled' ).removeClass( 'disabled' );
}

window.eoxiaJS.taskManager.task.returnSuccessUpdateTaskPerPage = function( element, response ){
	jQuery( '.pmg-sotut-container' ).append( '<p style="color:green">' + response.data.text_success + '</p>' );
	window.location.reload();
}


//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
