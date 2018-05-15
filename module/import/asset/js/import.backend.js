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
};

/**
 * Callback de l'import des tâches.
 *
 * @return void
 */
window.eoxiaJS.taskManager.import.importSuccess = function( element, response ) {
	if ( 'tasks' === response.data.type ) {
		window.eoxiaJS.taskManager.task.createdTaskSuccess( element, response );
	} else if ( 'points' === response.data.type ) {
		var task = jQuery( "div.wpeo-project-task[data-id='" + response.data.task_id + "']" );

		task.find( '.total-point' ).text( response.data.task.data.count_all_points );
		task.find( '.points.sortable .point:last' ).before( response.data.view );

		window.eoxiaJS.taskManager.point.initAutoComplete();
		window.eoxiaJS.refresh();
		window.eoxiaJS.taskManager.core.initSafeExit( false );
	}

	jQuery( '.wpeo-modal.tm-import-tasks.modal-active .modal-close' ).click();
};

/**
 * Fonction permettant d'insérer un mot clés dans le textarea contenant les données à importer
 */
window.eoxiaJS.taskManager.import.addKeywordToTextarea = function( event ) {
	var importContent = jQuery( this ).closest( '.tm-import-tasks.modal-active' ).find( 'textarea' );
	var keyword = '%' + jQuery( this ).attr( 'data-type' ) + '%';
	event.preventDefault();

	importContent.append( '\r\n' + keyword );
};
