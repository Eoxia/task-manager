/**
 * Initialise l'objet "setting" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.stickyNote = {};

window.eoxiaJS.taskManager.stickyNote.init = function() {
	window.eoxiaJS.taskManager.stickyNote.event();
};

window.eoxiaJS.taskManager.stickyNote.event = function() {
	jQuery( document ).on( 'blur', '.postbox.sticky-note div[contenteditable="true"]', window.eoxiaJS.taskManager.stickyNote.editContent );
	jQuery( document ).on( 'blur keyup paste keydown click', '.postbox div[contenteditable="true"]', window.eoxiaJS.taskManager.stickyNote.updatePlaceholder );
};

window.eoxiaJS.taskManager.stickyNote.editContent = function() {
	var data = {};
	var element = jQuery( this );

	data.action  = 'edit_note';
	data.note_id = element.closest( '.postbox' ).find( 'input[type="hidden"]' ).val();
	data.content = element.html();

	window.eoxiaJS.loader.display( element.closest( '.postbox' ) );
	window.eoxiaJS.request.send( element, data );
}

window.eoxiaJS.taskManager.stickyNote.updatePlaceholder = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.postbox' ).find( '.placeholder' ).addClass( 'hidden' );
		window.eoxiaJS.taskManager.core.initSafeExit( true );
	} else {
		jQuery( this ).closest( '.postbox' ).find( '.placeholder' ).removeClass( 'hidden' );
		window.eoxiaJS.taskManager.core.initSafeExit( false );
	}
}

/**
 * Le callback en cas de réussite à la requête Ajax "delete_note".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.8.0
 */
window.eoxiaJS.taskManager.stickyNote.deletedNoteSuccess = function( element, response ) {
	element.closest( '.postbox' ).fadeOut();
};
/**
 * Le callback en cas de réussite à la requête Ajax "add_note".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.8.0
 */
window.eoxiaJS.taskManager.stickyNote.addedNote = function( element, response ) {
	jQuery( '#tm-indicator-note-add' ).before( response.data.view );
	window.eoxiaJS.taskManager.core.initSafeExit( false );
};