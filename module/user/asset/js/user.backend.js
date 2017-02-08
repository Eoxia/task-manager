window.task_manager.user = {};

window.task_manager.user.init = function() {};

/**
 * Callback en cas de réussite de la requête Ajax "load_edit_mode_user"
 * Remplaces le template de .wpeo-ul-user pour afficher les utilisateurs.
 *
 * @param  {HTMLSpanElement} triggeredElement   L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}        response             Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1
 * @version 1.3.6.0
 */
window.task_manager.user.loadedEditModeUser = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-bloc-user' ).html( response.data.template );
};
