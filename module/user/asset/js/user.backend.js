/**
 * Initialise l'objet "user" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.user = {};

window.task_manager.user.init = function() {
	window.task_manager.user.event();
};

/**
 * Initialise les évènements des utilisateurs
 *
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.task_manager.user.event = function() {
	jQuery( document ).on( 'click', '.wpeo-bloc-user li', window.task_manager.user.toggleActiveUser );
	jQuery( document ).on( 'click', '.wpeo-bloc-user .save-user', window.task_manager.user.saveUsers );
};

/**
 * Ajoutes ou enlève la classe "active" à l'élement cliqué.
 *
 * @param  {ClickEvent} event L'élément cliqué.
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.task_manager.user.toggleActiveUser = function( event ) {
	jQuery( this ).toggleClass( 'active' );
};

/**
 * Envoie une requête pour enregistrer les utilisateurs lors de la fermeture du mode édtion des utilisateurs
 *
 * @param  {ClickEvent} event L'état du clic.
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.task_manager.user.saveUsers = function( event ) {
	var data = {
		action: 'save_user',
		task_id: jQuery( this ).data( 'id' ),
		_wpnonce: jQuery( this ).data( 'nonce' ),
		affected_id: []
	};

	jQuery( this ).closest( '.wpeo-bloc-user' ).find( 'li.active' ).each( function( key, f ) {
		data.affected_id.push( jQuery( f ).data( 'id' ) );
	} );

	jQuery.post( ajaxurl, data );
};

/**
 * Callback en cas de réussite de la requête Ajax "load_edit_mode_owner"
 * Remplaces le template de .users pour afficher les utilisateurs.
 *
 * @param  {HTMLSpanElement} triggeredElement   L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}        response             Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1
 * @version 1.3.6.0
 */
window.task_manager.user.loadedEditModeOwnerSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-task-author' ).find( '.users' ).html( response.data.view );
};

/**
 * Callback en cas de réussite de la requête Ajax "switch_owner"
 * Remplaces le template du responsable
 *
 * @param  {HTMLSpanElement} triggeredElement   L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}        response             Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 0.1
 * @version 1.3.6.0
 */
window.task_manager.user.switchedOwnerSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-task-author' ).html( response.data.view );
};

/**
 * Lorsqu'on clique sur la barre des utilisateurs, avant de lancer l'action on ajoute une classe permettant de bloquer les actions futures
 * tant que cette action n'est pas terminée
 *
 * @param  {HTMLDivElement} element  L'élément déclenchant l'action.
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.user.beforeLoadEditModeUser = function( element ) {
	element.addClass( 'no-action' );

	return true;
};

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
window.task_manager.user.loadedEditModeUserSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-bloc-user' ).html( response.data.template );
};
