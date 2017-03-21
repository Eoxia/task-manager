/**
 * Initialise l'objet "historyTime" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.historyTime = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.historyTime.init = function() {
	window.task_manager.historyTime.event();
};

/**
 * Initialise tous les évènements liés au historyTime de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.historyTime.event = function() {
	jQuery( document ).on( 'blur keyup paste keydown', '.wpeo-add-historyTime .wpeo-historyTime-new-contenteditable', window.task_manager.historyTime.updateHiddenInput );
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
window.task_manager.historyTime.loadedTimeHistorySuccess = function( element, response ) {
	jQuery( element ).closest( '.wpeo-project-task' ).find( '.popup .content' ).html( response.data.view );
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
window.task_manager.historyTime.createdHistoryTime = function( element, response ) {
};
