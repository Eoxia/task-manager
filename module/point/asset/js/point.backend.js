/**
 * Initialise l'objet "point" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.init = function() {
	window.task_manager.point.event();
};

/**
 * Initialise tous les évènements liés au point de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.event = function() {
	jQuery( document ).on( 'blur keyup paste keydown', '.wpeo-add-point .wpeo-point-new-contenteditable', window.task_manager.point.updateHiddenInput );
	// jQuery( document ).on( 'blur paste', '.wpeo-edit-point .wpeo-point-contenteditable', window.task_manager.point.edit_point );
	//
	// jQuery( document ).on( 'click', '.wpeo-done-point', window.task_manager.point.done_point );
	// jQuery( document ).on( 'click', '.wpeo-send-point-to-trash', window.task_manager.point.delete_point );
	// jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle p', window.task_manager.point.toggle_completed );
};

/**
 * Met à jour le champ caché contenant le texte du point écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.wpeo-point-input' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
	} else {
		jQuery( this ).closest( '.wpeo-point-input' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	}

	jQuery( this ).closest( '.wpeo-point-input' ).find( 'input[type="hidden"]' ).val( jQuery( this ).text() );
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_point".
 * Ajoutes le point avant le formulaire pour ajouter un point dans le ul.wpeo-task-point-sortable
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.addedPointSuccess = function( element, response ) {
	jQuery( element ).closest( '.wpeo-project-task' ).find( 'ul.wpeo-task-point-sortable form:last' ).before( response.data.view );
};

window.task_manager.point.deletedPointSuccess = function( element, response ) {
	jQuery( element ).closest( 'form' ).fadeOut();
};

window.task_manager.point.toggle_completed = function( event ) {
	var element = jQuery( this );
	element.find( '.wpeo-point-toggle-arrow' ).toggleClass( 'dashicons-plus dashicons-minus' );
	element.closest( '.wpeo-task-point-use-toggle' ).find( 'ul:first' ).toggle( 200 );
};

window.task_manager.point.toggle_completed_callback_success = function( element, response ) {
	element.closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).html( response.data.template );
};

window.task_manager.point.toggle_completed_callback_error = function( element, response ) {
	element.closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).html( response.data.template );
};
