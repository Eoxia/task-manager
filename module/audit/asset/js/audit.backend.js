/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.audit = {};

window.eoxiaJS.taskManager.audit.init = function() {
	window.eoxiaJS.taskManager.audit.event();
	window.eoxiaJS.taskManager.audit.initAutoComplete();

	jQuery( '.tm_client_audit_list_task' ).colcade( {
		items: '.wpeo-project-task',
		columns: '.grid-col'
	} );

	window.eoxiaJS.taskManager.task.initAutoComplete();

};

window.eoxiaJS.taskManager.audit.refresh = function() {
	window.eoxiaJS.taskManager.task.initAutoComplete();
	window.eoxiaJS.taskManager.audit.initAutoComplete();
};


window.eoxiaJS.taskManager.audit.event = function() {
	jQuery( document ).on( 'click', '#tm_client_audit_backtomain', window.eoxiaJS.taskManager.audit.clientAuditBackToMain );
	jQuery( document ).on( 'click change', '.audit-search-customers', window.eoxiaJS.taskManager.audit.initAutoComplete );

	jQuery( document ).on( 'change keyup paste', '#tm_client_audit_title_new', window.eoxiaJS.taskManager.audit.checkIfTitleIsOk );
	jQuery( document ).on( 'change', '#tm_audit_client_date_deadline', window.eoxiaJS.taskManager.audit.checkIfDateIsOk );

	// jQuery( document ).on( 'click', '#tm_audit_client_button_accesstotask', window.eoxiaJS.taskManager.audit.accessToTaskView );

	jQuery( document ).on( 'click', '.tm-import-add-keyword-audit > .wpeo-button', window.eoxiaJS.taskManager.audit.addKeywordToTextarea );

	jQuery( document ).on( 'click', '#wpeo-task-metabox-auditlist h2 span .action-attribute', window.eoxiaJS.taskManager.audit.preventDefaultHeader );

	jQuery( document ).on( 'click', '#tm_audit_selector_search .dropdown-item', window.eoxiaJS.taskManager.audit.updateSearchSelect );

	jQuery( document ).on( 'click', '.tm-audit .audit-container .audit-header', window.eoxiaJS.taskManager.audit.displayInputAuditToEdit );

	jQuery( document ).on( 'click', '.tm-unlink-audit-parent', window.eoxiaJS.taskManager.audit.unlinkAuditParent );

	jQuery( document ).on( 'click', '.update-to-edit-view-audit', window.eoxiaJS.taskManager.audit.updateToEditViewAudit );

	// jQuery( document ).on( 'click', '.update-to-edit-view-audit .tm-valid-edit', )
};

window.eoxiaJS.taskManager.audit.initAutoComplete = function() {
	jQuery( '.audit-search-customers' ).autocomplete( {
		source: 'admin-ajax.php?action=search_client_for_audit',
		delay: 0,
		select: function( event, ui ) {
			var data = {
				action: 'customer_is_valid',
				customer_id: ui.item.id
			};

			jQuery( 'input[name="task_id"]' ).val( ui.item.id );
			jQuery( '.audit_search-customers-id' ).val( ui.item.id );

			event.stopPropagation();

			// window.eoxiaJS.request.send( jQuery( this ).closest( '.form' ), data );
			//jQuery( '#tm_client_audit_buttonsavetitle' ).removeClass( 'button-disable' );
		}
	} );
};


window.eoxiaJS.taskManager.audit.settingRefreshedPoint = function( triggeredElement, response ) {
	triggeredElement.closest( '.form' ).find( 'select' ).html( response.data.view );
};

window.eoxiaJS.taskManager.audit.startNewAudit = function( triggeredElement, response ){

	jQuery( '.tm_client_audit_main' ).css( 'display', 'none' );
	jQuery( '.tm_client_audit_edit' ).css( 'display', 'block' );

	jQuery( '.tm_client_audit_edit' ).html( response.data.view );

}

window.eoxiaJS.taskManager.audit.updateMainPage = function( triggeredElement, response ){
	jQuery( '.tm_client_audit_main' ).css( 'display', 'block' );
	jQuery( '.tm_client_audit_edit' ).css( 'display', 'none' );

	jQuery( '#tm_client_audit_listauditmain' ).html( response.data.view );
}

window.eoxiaJS.taskManager.audit.clientAuditBackToMain = function ( trigerredElement ){
	jQuery( '.tm_client_audit_main' ).css( 'display', 'block' );
	jQuery( '.tm_client_audit_edit' ).css( 'display', 'none' );
}

window.eoxiaJS.taskManager.audit.checkIfTitleIsOk = function( event ){ // If title and date

	var element = jQuery( '#tm_client_audit_title_new' );

	if( element.val() != jQuery( '#tm_client_audit_title_old' ).val() && element.val() != "" ){
		jQuery( '#tm_client_audit_buttonsavetitle' ).removeClass( 'button-disable' );
	}else{
		jQuery( '#tm_client_audit_buttonsavetitle' ).addClass( 'button-disable' );
	}
}

window.eoxiaJS.taskManager.audit.checkIfDateIsOk = function( event ){ // If title and date
	jQuery( '#tm_client_audit_buttonsavetitle' ).removeClass( 'button-disable' );
}

window.eoxiaJS.taskManager.audit.updateTitle = function( element, response ){
	//jQuery( '#tm_client_audit_title_old' ).val( response.data.title );
	//window.eoxiaJS.taskManager.audit.checkIfTitleIsOk();

	jQuery( element ).closest( '.audit-container' ).find( '.tm-audit-display-editmode' ).html( response.data.editview );
	jQuery( element ).closest( '.audit-container' ).find( '.tm-audit-display-readonly' ).html( response.data.readonlyview );

	jQuery( element ).closest( '.audit-container' ).find( '.tm-audit-display-editmode' ).hide();
	jQuery( element ).closest( '.audit-container' ).find( '.tm-audit-display-readonly' ).show();
	// window.eoxiaJS.loader.display( jQuery( this ).closest( '.audit-container' ) );
	window.eoxiaJS.loader.remove( jQuery( element ).closest( '.audit-container' ) );

}

window.eoxiaJS.taskManager.audit.displayShortcodeTask = function( element, response ){

	var elements = jQuery( response.data.view );

	jQuery( '#tm_audit_client_generate_tasklink' ).html( response.data.view );

}

window.eoxiaJS.taskManager.audit.viewMainPage = function( element, response ){
	jQuery( '.tm_client_audit_main' ).replaceWith( response.data.view );
	jQuery( '.tm_client_audit_edit' ).css( 'display', 'none' );
}

window.eoxiaJS.taskManager.audit.generateAuditIndicator = function( task_id, complet_point, uncomplet_point, audit_id, task_title ){

	jQuery( "#audit_client_indicator_" + audit_id ).append( '<div class="audit-chart-item wpeo-tooltip-event" aria-label="' + task_title +'"><canvas id="audit_client_indicator_task_' + task_id + '" class="wpeo-modal-event alignright" style=""></canvas></div>' );

	var canvasDonut = document.getElementById( "audit_client_indicator_task_" + task_id ).getContext('2d');

	if( complet_point == 0 && uncomplet_point == 0 ){
		var data_canvas_doghnut = {
			labels : [ window.indicatorString.taskempty, window.indicatorString.taskempty ],
			datasets: [
					{
						backgroundColor: [ '#D3D3D3', 'D3D3D3' ],
						data: [ 1, 0 ]
					}
				],
		};

	}else{

		var data_canvas_doghnut = {
			labels : [ window.indicatorString.completed, window.indicatorString.uncompleted ],
			datasets: [
					{
						backgroundColor: [ "#0099FF", "#5A5A5A" ],
						data: [ complet_point, uncomplet_point ],
					}
				],
		};
	}

	var option = {
		title: {
			display: true,
			text: '#' + task_id,
			position: 'bottom',
		},
		tooltips: {
			custom: function(tooltip) {
	    }
		},
		legend: {
      display: false
	   },


	};

	canvasDonut.canvas.width = 100;
	canvasDonut.canvas.height = 100;

	new Chart( canvasDonut, {
    type: 'doughnut',
    data: data_canvas_doghnut,
    options: option
	});
}


window.eoxiaJS.taskManager.audit.addKeywordToTextarea = function( event ) {
	var importContent = jQuery( this ).closest( '.tm-audit-import.modal-active' ).find( 'textarea' );
	var keyword       = '%' + jQuery( this ).attr( 'data-type' ) + '%';
	importContent.val( importContent.val() + '\r\n' + keyword );
};

window.eoxiaJS.taskManager.audit.createdAuditTaskSuccess = function ( trigerredElement, response ){
	jQuery( '#tm_audit_client_generate_tasklink .list-task' ).prepend( response.data.view );
}

window.eoxiaJS.taskManager.audit.importAuditTaskSuccess = function ( trigerredElement, response ){
	jQuery( '#tm_audit_client_generate_tasklink .list-task' ).prepend( response.data.view );
	jQuery( trigerredElement ).closest( '.modal-active' ).removeClass( 'modal-active' );

	if( response.data.category_info.length != 0 ){
		if( jQuery( '#tm_audit_client_generate_tasklink' ) ){
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

			  jQuery( "#tm_audit_client_generate_tasklink" ).prepend( content );
			});
		}else{
			console.log( 'audit/asset/js/audit.backend.js : L.230' );
		}
	}
}

window.eoxiaJS.taskManager.audit.searchAuditFilter = function ( trigerredElement, response ){
	var data = response.data.list_audit;

	for( var i = 0; i < data.length; i ++ ){
		if( data[ i ][ 'valid' ] ){
			jQuery( '.tm_audit_item_' + data[ i ][ 'id' ] ).show();
		}else{
			jQuery( '.tm_audit_item_' + data[ i ][ 'id' ] ).hide();
		}
	}
}

window.eoxiaJS.taskManager.audit.preventDefaultHeader = function( event ){

	jQuery( '#wpeo-task-metabox-auditlist' ).removeClass( 'closed' );
}

window.eoxiaJS.taskManager.audit.updateSearchSelect = function( event ){
	jQuery( '#tm_audit_button_search' ).attr( 'data-modification', 'true' );
	var value = jQuery( this ).closest( '.dropdown-item' ).find( 'input[type="hidden"]').val();

	jQuery( this ).closest( '.wpeo-dropdown' ).find( '.tm_audit_search_hidden' ).val( value );
	jQuery( this ).closest( '.wpeo-dropdown' ).find( '.display-text-audit' ).html( jQuery( this ).html() );
}

window.eoxiaJS.taskManager.audit.displayInputAuditToEdit = function( event ){

}

window.eoxiaJS.taskManager.audit.unlinkAuditParent = function( event ){
	if( confirm( window.indicatorString.delink_audit ) ){
		var data = {
			action: jQuery( this ).data( 'action' ), // delink_parent_to_audit
			id : jQuery( this ).data( 'id' ),
			_wpnonce : jQuery( this ).data( 'nonce' )
		};

		window.eoxiaJS.loader.display( jQuery( this ).closest( '.audit-summary' ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}
}

window.eoxiaJS.taskManager.audit.updateToEditViewAudit = function( event ){
	var edit_element = jQuery( this ).closest( '.audit-container' ).find( '.tm-audit-display-editmode' );
	var readonly_element = jQuery( this ).closest( '.audit-container' ).find( '.tm-audit-display-readonly' );
	if( jQuery( this ).data( 'editmode' ) == '0' ){
		jQuery( this ).data( 'editmode' ,'1' );
		jQuery( this ).find( '.tm-main-mode' ).hide();
		jQuery( this ).find( '.tm-valid-edit' ).show();

		jQuery( this ).closest( '.audit-container' ).find( '.tm-audit-display-readonly' ).hide();
		jQuery( this ).closest( '.audit-container' ).find( '.tm-audit-display-editmode' ).show();

	}else{
		jQuery( this ).data( 'editmode' ,'0' );
		jQuery( this ).find( '.tm-main-mode' ).show();
		jQuery( this ).find( '.tm-valid-edit' ).hide();

	  window.eoxiaJS.loader.display( jQuery( this ).closest( '.audit-container' ) );

		/*jQuery( this ).closest( '.audit-container' ).find( '.tm-audit-display-editmode' ).hide();
		jQuery( this ).closest( '.audit-container' ).find( '.tm-audit-display-readonly' ).show();*/
	}
}

window.eoxiaJS.taskManager.audit.delinkAuditParent = function( element, response ){
	jQuery( element ).closest( '.audit-summary' ).find( '.tm-display-audit-parent-link' ).hide();
	jQuery( element ).closest( '.audit-summary' ).find( '.tm-define-customer-to-audit' ).show();
}

window.eoxiaJS.taskManager.audit.audit_is_created = function( element, response ){
	jQuery( '#tm_client_audit_listauditmain' ).prepend( response.data.view );
}

window.eoxiaJS.taskManager.audit.deleteAudit = function( element, response ){
	var parent = jQuery( element ).closest( '.tm-audit' );
	parent.hide();
	console.log( parent );
}
