/**
 * Initialise l'objet core dans taskManagerWPShop.
 *
 * @since 1.5.0
 * @version 1.5.0
 */

if ( undefined === window.eoxiaJS.taskManager ) {
	window.eoxiaJS.taskManager = {};
}

/**
 * Initialise l'objet "activity" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.adminBar = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.adminBar.init = function() {
	window.eoxiaJS.taskManager.adminBar.initAutoComplete();
	window.eoxiaJS.taskManager.adminBar.event();
};

window.eoxiaJS.taskManager.adminBar.refresh = function() {
	window.eoxiaJS.taskManager.adminBar.initAutoComplete();
};

/**
 * Initialise tous les évènements liés au tâche rapide de Task Manager.
 *
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.adminBar.event = function() {
	jQuery( document ).on( 'click', '.quick-time-content .header input[type="checkbox"]', window.eoxiaJS.taskManager.adminBar.onCheckedCheckAll );
	jQuery( document ).on( 'click', '.quick-time-content .item .set_time', window.eoxiaJS.taskManager.adminBar.onChecked );
	jQuery( document ).on( 'keyup', '.quick-time-content .item .min .displayed', window.eoxiaJS.taskManager.adminBar.onKeyUp );
};

/**
 * Initialise l'autocomplete pour rechercher la tâche.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.adminBar.initAutoComplete = function() {
	if ( jQuery.autocomplete ) {
		jQuery( '.quick-time-search-task' ).autocomplete( {
			source: 'admin-ajax.php?action=search_task',
			delay: 0,
			select: function( event, ui ) {
				var data = {
					action: 'quick_task_setting_refresh_point',
					task_id: ui.item.id
				};

				jQuery( 'input[name="task_id"]' ).val( ui.item.id );
				jQuery( this ).closest( '.form-fields' ).find( '.action-input' ).addClass( 'active' );
				event.stopPropagation();

				window.eoxiaJS.loader.display( jQuery( this ).closest( '.form' ) );
				window.eoxiaJS.request.send( jQuery( this ).closest( '.form' ), data );
			}
		} );
	}
};

/**
 * Le callback en cas de réussite à la requête Ajax "quick_task_setting_refresh_point".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.adminBar.settingRefreshedPoint = function( triggeredElement, response ) {
	triggeredElement.closest( '.form' ).find( 'select' ).html( response.data.view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "add_config_quick_time".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.adminBar.addedConfigQuickTime = function( triggeredElement, response ) {
	var el = jQuery( response.data.view ).hide();
	jQuery( '.setting-quick-time .list .form' ).after( el );
	el.fadeIn();
};

/**
 * Le callback en cas de réussite à la requête Ajax "remove_config_quick_time".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.adminBar.deletedConfigQuickTime = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( 'ul.item' ).fadeOut();
};

/**
 * Le callback en cas de réussite à la requête Ajax "quick_time_add_comment".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.adminBar.quickTimeAddedComment = function( triggeredElement, response ) {
	jQuery( '.quick-time-content' ).replaceWith( response.data.view );
};

/**
 * Quand on clic sur la checkbox parent, check toutes les checkbox des lignes et met à jour le temps en appelant updateTime.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param  {ClickEvent} event L'état de la souris au clic.
 * @return {void}
 */
window.eoxiaJS.taskManager.adminBar.onCheckedCheckAll = function( event ) {
	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( this ).closest( '.quick-time-content' ).find( '.item input[type="checkbox"]' ).attr( 'checked', true );
	} else {
		jQuery( this ).closest( '.quick-time-content' ).find( '.item input[type="checkbox"]' ).attr( 'checked', false );
		jQuery( this ).closest( '.quick-time-content' ).find( '.item .displayed' ).val( '' );
		jQuery( this ).closest( '.quick-time-content' ).find( '.item input.time' ).val( '' );
	}

	window.eoxiaJS.taskManager.adminBar.updateTime( jQuery( this ) );
};

/**
 * Quand on check la checkbox d'une ligne, met à jour le temps en appelant updateTime.
 * Si la checkbox n'est pas checké, enlève le contenu du texte.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param  {clickEvent} event L'état de la souris au clic.
 * @return {void}
 */
window.eoxiaJS.taskManager.adminBar.onChecked = function( event ) {
	if ( ! jQuery( this ).is( ':checked' ) ) {
		jQuery( this ).closest( 'ul' ).find( '.displayed' ).val( '' );
		jQuery( this ).closest( 'ul' ).find( 'input.time' ).val( '' );
	}

	window.eoxiaJS.taskManager.adminBar.updateTime( jQuery( this ) );
};

/**
 * Quand on met à jour le contenu de .displayed, check la checkbox si le contenu n'est pas vide. Sinon fait l'inverse.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param  {KeyboardEvent} event L'état du clavier au keyUp.
 * @return {void}
 */
window.eoxiaJS.taskManager.adminBar.onKeyUp = function( event ) {
	if ( '' !== jQuery( this ).val() ) {
		jQuery( this ).closest( 'ul' ).find( 'input[type="checkbox"]' ).attr( 'checked', true );
	} else {
		jQuery( this ).closest( 'ul' ).find( 'input[type="checkbox"]' ).attr( 'checked', false );
	}

	window.eoxiaJS.taskManager.adminBar.updateTime( jQuery( this ) );
};

/**
 * Met à jour le temps dans tous les input type text selon le nombre d'élement coché.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param {mixed} element L'élément déclenchant l'action.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.adminBar.updateTime = function( element ) {
	var totalTime = parseInt( element.closest( '.quick-time-content' ).find( '.header .time' ).text() );
	var checkedElements, container;
	var numberCheckedElement = 0;

	checkedElements      = element.closest( '.quick-time-content' ).find( '.item .set_time:checked' );
	numberCheckedElement = checkedElements.length;

	element.closest( '.quick-time-content' ).find( '.item .min input.displayed' ).each( function() {
		if ( jQuery( this ).val() && ! isNaN( jQuery( this ).val() ) ) {
			totalTime -= parseInt( jQuery( this ).val() );
			numberCheckedElement--;
		}
	} );

	// Force le temps a rester positive.
	if ( totalTime <= 0 ) {
		totalTime = 0;
	}

	element.closest( '.quick-time-content' ).find( '.set_time' ).each( function() {
		container = jQuery( this ).closest( 'ul' );

		if ( jQuery( this ).is( ':checked' ) ) {
			container.find( 'input.displayed' ).attr( 'placeholder', parseInt( totalTime / numberCheckedElement ) );

			if ( container.find( 'input.displayed' ).val() && ! isNaN( container.find( 'input.displayed' ).val() ) ) {
				container.find( 'input.time' ).val( parseInt( container.find( 'input.displayed' ).val() ) );
			} else {
				container.find( 'input.time' ).val( parseInt( totalTime / numberCheckedElement ) );
			}
		} else {
			container.find( 'input.displayed' ).attr( 'placeholder', '' );
			container.find( 'input.time' ).val( '' );
		}
	} );
}
