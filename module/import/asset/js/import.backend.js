/**
 * Initialise l'objet "import" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.7.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.import = {};

window.eoxiaJS.taskManager.import.init = function() {
	window.eoxiaJS.taskManager.import.event();
};

window.eoxiaJS.taskManager.import.event = function() {
	jQuery( document ).on( 'click', '.tm-import-add-keyword > .wpeo-button', window.eoxiaJS.taskManager.import.addKeywordToTextarea );
	jQuery( document ).on( 'click', '.tm-alert-category-not-found > .wpeo-button', window.eoxiaJS.taskManager.import.updateCategoryNotFound );
	jQuery( document ).on( 'click', '.wpeo-notice .notice-close', window.eoxiaJS.taskManager.import.hideThisWpeoNotice );
	jQuery( document ).on( 'keyup', '.tm-import-add-keyword .tm-info-import-link input', window.eoxiaJS.taskManager.import.updateImportTextFromUrl );

};

/**
 * Callback de l'import des tâches.
 *
 * @return void
 */
window.eoxiaJS.taskManager.import.importSuccess = function( element, response ) {
	if( response.data.category_info.length != 0 ){
		if( jQuery( '.wpeo-wrap tm-wrap' ) ){
			jQuery.each( response.data.category_info, function( index, val ) {
				var content = "";
				var notice_content_start  = '<div class="wpeo-notice notice-warning" style="margin: 0 5em" data-taskid="' + val[ 'id' ] + '" data-tagname="' + val[ 'line' ] + '"><div class="notice-content"><div class="notice-title">';
				var notice_content_title  = window.indicatorString.cat_head;
				var notice_content_middle = '</div><div class="notice-subtitle">';
				var notice_content_body   = window.indicatorString.cat_body + '"<b>'+ val[ 'line' ] + '</b>" ' + window.indicatorString.cat_question;
				var notice_content_button = '<div class="tm-alert-category-not-found" style="float : right"> <div class="wpeo-button button-red" style="margin-right: 10px" data-doaction="delete" >' + window.indicatorString.cat_nothing + '</div>';
				notice_content_button     += '<div class="wpeo-button button-green" data-doaction="create">' + window.indicatorString.cat_create + '</div></div>';
				var notice_content_end    = '</div></div><div class="notice-close"><i class="fas fa-times"></i></div></div>';

				var content = notice_content_start + notice_content_title + notice_content_middle + notice_content_body + notice_content_button + notice_content_end;

			  jQuery( ".wpeo-wrap" ).prepend( content );
			});
		}else{
			console.log( 'import/asset/js/import.backend.js : L.29' );
		}
	}
	if ( 'tasks' === response.data.type ) {
		window.eoxiaJS.taskManager.task.createdTaskSuccess( element, response );
	} else if ( 'points' === response.data.type ) {
		var task = jQuery( "div.wpeo-project-task[data-id='" + response.data.task_id + "']" );

		task.find( '.total-point' ).text( response.data.task.data.count_all_points );
		task.find( '.points.sortable .point:last' ).before( response.data.view );

		window.eoxiaJS.taskManager.point.initAutoComplete();
		window.eoxiaJS.refresh();
		window.eoxiaJS.taskManager.core.initSafeExit( false );

		task.find( '.wpeo-task-filter .point-uncompleted' ).html( response.data.task.data.count_uncompleted_points );
	}

	window.eoxiaJS.taskManager.task.initAutoComplete();

	window.eoxiaJS.modal.close();
};

/**
 * Fonction permettant d'insérer un mot clés dans le textarea contenant les données à importer
 */
window.eoxiaJS.taskManager.import.addKeywordToTextarea = function( event ) {
	var importContent = jQuery( this ).closest( '.tm-import-tasks.modal-active' ).find( 'textarea' );

	if( ! importContent.length ){
		importContent = jQuery( this ).closest( '.tm-audit-import.modal-active' ).find( 'textarea' );
	}
	var keyword = "";

	if( jQuery( this ).attr( 'data-type' ) == "category" ){
		keyword = window.eoxiaJS.taskManager.import.tagKeywordToTextarea( this, importContent );
		importContent.focus().val( importContent.val() + '\r\n' + keyword );

	}else if( jQuery( this ).attr( 'data-type' ) == "link" ){
		window.eoxiaJS.taskManager.import.buttonLinkExternalText( jQuery( this ), importContent );
	}else{
		keyword = '%' + jQuery( this ).attr( 'data-type' ) + '%';
		importContent.focus().val( importContent.val() + '\r\n' + keyword );
	}
};

window.eoxiaJS.taskManager.import.tagKeywordToTextarea = function( element, importContent ){
	var content = importContent.val();
	var tag_content = jQuery( element ).closest( '.tm-import-add-keyword' ).find( 'select option:selected' ).val();

	if( tag_content === undefined ){
		tag_content = "";
	}
	return keyword  = '%category%' + tag_content;
}

window.eoxiaJS.taskManager.import.updateCategoryNotFound = function( event ){
	var action         = jQuery( this ).attr( 'data-doaction' );
	var element_parent = jQuery( this ).closest( '.wpeo-notice' );

	if( action == "create" ){

		var data         = {};
		data.action         = 'category_not_found_so_create_it';
		data.task_id        = element_parent.attr( 'data-taskid' ); //
		data.category_name  = element_parent.attr( 'data-tagname' );

		window.eoxiaJS.loader.display( jQuery( this ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}else{
		element_parent.hide( 500 );
	}
}

window.eoxiaJS.taskManager.import.update_footer_task_category = function( element, response ){
	var element_parent = jQuery( element ).closest( '.wpeo-notice' );
	element_parent.hide();

	jQuery( '.list-task' ).find( '.wpeo-project-task' ).each(function( element ) {
		if( jQuery( this ).attr( 'data-id' ) == response.data.taskid ){
			jQuery( this ).find( '.wpeo-task-footer .wpeo-tag-wrap' ).not( '.wpeo-ul-parent' ).replaceWith( response.data.footertask );
		}else{
			// console.log( jQuery( this ).attr( 'data-id' ) );
		}
	});
}

window.eoxiaJS.taskManager.import.hideThisWpeoNotice = function( event ){
	var element_parent = jQuery( this ).closest( '.wpeo-notice' );
	element_parent.hide( 500 );
}

window.eoxiaJS.taskManager.import.buttonLinkExternalText = function( element, importContent ){

	if( element.closest( '.tm-import-add-keyword' ).find( '.tm-info-import-link input' ).attr( 'data-import' ) == "true" ){
		//send request
		var data         = {};
		data.action  = 'get_text_from_url_tm';
		data.content = element.closest( '.tm-import-add-keyword' ).find( '.tm-info-import-link input' ).val(); // On recupere le contenu

		window.eoxiaJS.loader.display( element );
		window.eoxiaJS.request.send( element, data );
	}else{
		if( element.attr( 'data-link' ) == "no"){
			element.find( '.tm_save_backup' ).val( importContent.val() ); // On recupere le contenu

			var next_step = 'yes';
			element.removeClass( 'button-grey' ).addClass( 'button-green' );
			element.closest( '.tm-import-add-keyword' ).find( '.tm-info-import-link' ).show( '200' );
		}else{
			importContent.focus().val( element.find( '.tm_save_backup' ).val() );

			var next_step = 'no';
			element.removeClass( 'button-green' ).addClass( 'button-grey' );
			element.closest( '.tm-import-add-keyword' ).find( '.tm-info-import-link' ).hide( '200' );
		}

		element.attr( 'data-link', next_step );
		element.find( '.tm_link_external' ).val( next_step );
	}
}



window.eoxiaJS.taskManager.import.updateImportTextFromUrl = function( event ){
	if( jQuery( this ).val().trim() != "" ){
		jQuery( this ).closest( '.tm-import-add-keyword' ).find( '.tm-icon-import-from-url' ).removeClass( 'fa-link' ).addClass( 'fa-file-import' );
		jQuery( this ).attr( 'data-import', "true" );
	}else{
		jQuery( this ).closest( '.tm-import-add-keyword' ).find( '.tm-icon-import-from-url' ).removeClass( 'fa-file-import' ).addClass( 'fa-link' );
		jQuery( this ).attr( 'data-import', "false" );
	}
}

window.eoxiaJS.taskManager.import.get_content_from_url_to_import_textarea = function( element, response ){
	if( response.data.error == "" ){
		element.closest( '.tm-import-tasks.modal-active' ).find( 'textarea' ).val( response.data.content );
	}

	element.closest( '.tm-import-add-keyword' ).find( '.tm-info-import-link input' ).val( '' );

	element.removeClass( 'button-green' ).addClass( 'button-grey' );
	element.closest( '.tm-import-add-keyword' ).find( '.tm-info-import-link' ).hide( '200' );

	element.attr( 'data-link', "no" );
	element.find( '.tm_link_external' ).val( "no" );
}
