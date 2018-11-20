/**
 * Initialise l'objet "activity" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManagerFrontend.activity = {};

/**
 * Méthode 'init' obligatoire.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManagerFrontend.activity.init = function() {
	window.eoxiaJS.taskManagerFrontend.activity.event();
};

/**
 * Méthode 'event'.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManagerFrontend.activity.event = function() {
	jQuery( '.tm-wrap' ).on( 'click', '.tm-task-display-method-buttons .list-display', window.eoxiaJS.taskManagerFrontend.activity.switchViewToLine );
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
window.eoxiaJS.taskManagerFrontend.activity.switchViewToLine = function( event ) {
	var taskElement = jQuery( this ).closest( '.wpeo-project-task' );
	taskElement.find( '.tm-task-display-method-buttons .wpeo-button.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
	taskElement.find( '.bloc-activities' ).hide();
	taskElement.find( '.points.sortable' ).show();
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_last_activity".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManagerFrontend.activity.loadedLastActivity = function( triggeredElement, response ) {
	if ( triggeredElement.closest( '.wpeo-project-task' ).length ) {
		var taskElement = triggeredElement.closest( '.wpeo-project-task' );
		triggeredElement.addClass( 'active' );
		triggeredElement.closest( '.tm-task-display-method-buttons' ).find( '.list-display.active' ).removeClass( 'active' );
		taskElement.find( '.points' ).hide();
		taskElement.find( '.bloc-activities' ).html( response.data.view ).show();
	} else {
		jQuery( '.wpeo-modal.last-activity .modal-content' ).html( response.data.view );
	}

	window.eoxiaJS.refresh();
};
