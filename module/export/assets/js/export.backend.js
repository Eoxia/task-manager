/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.1
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.taskExport = {};

window.eoxiaJS.taskManager.taskExport.init = function() {
	window.eoxiaJS.taskManager.taskExport.event();
};

window.eoxiaJS.taskManager.taskExport.event = function() {
	jQuery( document ).on( 'change', 'input[name=export_type]', window.eoxiaJS.taskManager.taskExport.displayDateForExport );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_export_popup".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.taskExport.loadedExportPopup = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup.popup-export .content' ).html( response.data.view );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup.popup-export .container' ).removeClass( 'loading' );
};

/**
 * [description]
 * @method
 * @param  {[type]} $input [description]
 * @return {[type]}        [description]
 */
window.eoxiaJS.taskManager.taskExport.afterTriggerChangeDate = function( $input ) {
	$input.closest( '.group-date' ).find( '.date-display' ).html( $input.val() );
};

/**
 * [description]
 * @method
 * @param  {[type]} triggeredElement [description]
 * @return {[type]}                  [description]
 */
window.eoxiaJS.taskManager.taskExport.displayDateForExport = function( triggeredElement ) {
	if ( 'by_date' == jQuery( this ).val() ) {
		jQuery( '.tm_export_date_container' ).show();
	} else {
		jQuery( '.tm_export_date_container' ).hide();
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "export_task".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.3.6
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.taskExport.exportedTask = function( triggeredElement, response ) {
	jQuery( '.tm_export_result_container' ).find( 'textarea' ).html( response.data.content );
	jQuery( triggeredElement ).closest( '.wpeo-modal' ).find( '.modal-footer' ).html( response.data.time );
};
