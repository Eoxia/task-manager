/**
 * Initialise l'objet "point" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManagerFrontend.point = {};
/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManagerFrontend.point.init = function() {
	window.eoxiaJS.taskManagerFrontend.point.event();
};

/**
 * Initialise tous les évènements liés au point de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManagerFrontend.point.event = function() {
	jQuery( document ).on( 'click', '.point-type-display-buttons button.active', window.eoxiaJS.taskManagerFrontend.point.undisplayPoint );
	
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_completed_point".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManagerFrontend.point.loadedPoint = function( triggeredElement, response ) {
	jQuery( triggeredElement ).addClass( 'active' ).removeClass( 'action-input' );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.points' ).append( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Méthode appelée lors du clic sur les boutons de hoix du type de points affichés dans une tâche.
 *
 * @since 1.8.0
 *
 * @param  {type} event  L'événement lancé lors de l'action.
 */
window.eoxiaJS.taskManagerFrontend.point.undisplayPoint = function( event ) {
	var pointState = jQuery( this ).attr( 'data-point-state' );
	event.preventDefault();
	jQuery( this ).removeClass( 'active' ).addClass( 'action-input' );
	jQuery( this ).closest( '.wpeo-project-task-container' ).find( '.points .point.edit[data-point-state="' + pointState + '"]' ).remove();

	window.eoxiaJS.refresh();
};
