/**
 * Initialise l'objet "historyTime" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.historyTime = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.historyTime.init = function() {
	window.eoxiaJS.taskManager.historyTime.event();
};

/**
 * Initialise tous les évènements liés au historyTime de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.historyTime.event = function() {
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_history_time".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.historyTime.loadedTimeHistorySuccess = function( element, response ) {
	jQuery( element ).closest( '.wpeo-project-task' ).find( '.popup .content' ).html( response.data.view );
	jQuery( element ).closest( '.wpeo-project-task' ).find( '.popup .container' ).removeClass( 'loading' );
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_history_time".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.historyTime.createdHistoryTime = function( element, response ) {
	jQuery( element ).closest( '.wpeo-project-task' ).find( '.popup .content .history-time-container' ).replaceWith( response.data.history_time_view );
	jQuery( 'div[data-id="' + response.data.task_id + '"]' ).find( 'ul.wpeo-task-time-manage' ).replaceWith( response.data.task_header_view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "delete_history_time".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.historyTime.deletedHistoryTime = function( element, response ) {
	jQuery( element ).closest( '.list-element' ).fadeOut();
	jQuery( 'div[data-id="' + response.data.task_id + '"]' ).find( 'ul.wpeo-task-time-manage' ).replaceWith( response.data.task_header_view );
};
