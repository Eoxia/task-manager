/**
 * Initialise l'objet "setting" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.setting = {};

window.eoxiaJS.taskManager.setting.init = function() {
	window.eoxiaJS.taskManager.setting.event();
};

window.eoxiaJS.taskManager.setting.event = function() {
	jQuery( document ).on( 'click', '.settings_page_task-manager-setting .list-users .wp-digi-pagination a', window.eoxiaJS.taskManager.setting.pagination );
};

/**
 * Gestion de la pagination des utilisateurs.
 *
 * @param  {ClickEvent} event [description]
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.setting.pagination = function( event ) {
	var href = jQuery( this ).attr( 'href' ).split( '&' );
	var nextPage = href[1].replace( 'current_page=', '' );

	jQuery( '.list-users' ).addClass( 'loading' );

	var data = {
		action: 'paginate_setting_epi_page_user',
		next_page: nextPage
	};

	event.preventDefault();

	jQuery.post( window.ajaxurl, data, function( view ) {
		jQuery( '.list-users' ).replaceWith( view );
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "save_capacity".
 * Affiches le message de "success".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.setting.savedCapability = function( triggeredElement, response ) {
	triggeredElement.addClass( 'button-success' );

	setTimeout( function() {
		triggeredElement.removeClass( 'button-success' );
	}, 1500 );
};
