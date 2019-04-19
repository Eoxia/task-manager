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

	jQuery( document ).on( 'change', '.tm_audit_search_update', window.eoxiaJS.taskManager.audit.updateSearchSelect );

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
			jQuery( '#tm_client_audit_buttonsavetitle' ).removeClass( 'button-disable' );
		}
	} );
};


window.eoxiaJS.taskManager.audit.settingRefreshedPoint = function( triggeredElement, response ) {
	console.log( 'return' );
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
	jQuery( '#tm_client_audit_title_old' ).val( response.data.title );
	window.eoxiaJS.taskManager.audit.checkIfTitleIsOk();

	if( response.data.customer_id != 0 ){

		jQuery( '.tm-define-customer-to-audit' ).replaceWith( '' );
		jQuery( '.tm-define-customer-to-audit-after' ).html( '<i class="far fa-clone"></i>' + response.data.customer_id );
		jQuery( '.tm-define-customer-to-audit-after' ).show( 200 );
	}

	jQuery( '.tm_client_audit_main' ).css( 'display', 'none' );
}

window.eoxiaJS.taskManager.audit.displayShortcodeTask = function( element, response ){

	var elements = jQuery( response.data.view );

	jQuery( '#tm_audit_client_generate_tasklink' ).html( response.data.view );

}

window.eoxiaJS.taskManager.audit.viewMainPage = function( element, response ){
	jQuery( '.tm_client_audit_main' ).replaceWith( response.data.view );
	jQuery( '.tm_client_audit_edit' ).css( 'display', 'none' );
}

window.eoxiaJS.taskManager.audit.generateAuditIndicator = function( task_id, complet_point, uncomplet_point, audit_id, audit_title ){

	jQuery( "#audit_client_indicator_" + audit_id ).append( '<div class="audit-chart-item"><canvas id="audit_client_indicator_task_' + task_id + '" class="wpeo-modal-event alignright" style=""></canvas></div>' );

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
			text: audit_title + '(#' + task_id + ')',
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
}
