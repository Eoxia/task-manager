/**
 * Initialise l'objet "activity" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.activity = {};

/**
 * Méthode 'init' obligatoire.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.activity.init = function() {
	window.eoxiaJS.taskManager.activity.event();
};

/**
 * Méthode 'event'.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.activity.event = function() {
	jQuery( document ).on( 'click', '.activities .load-more-history', window.eoxiaJS.taskManager.activity.loadMoreHistory );
};

/**
 * Envoie une requête pour charger plus d'évènement dans l'historique.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return void
 */
window.eoxiaJS.taskManager.activity.loadMoreHistory = function( event ) {
	var element = jQuery( this );

	var data = {
		action: 'load_last_activity',
		// _wpnonce: element.closest( '.wpeo-project-task' ).find( '.dashicons-screenoptions' ).data( 'nonce' ),
		tasks_id: element.closest( '.wpeo-project-task' ).data( 'id' ),
		offset: element.closest( '.activities' ).find( '.offset-event' ).val(),
		last_date: element.closest( '.activities' ).find( '.last-date' ).val()
	};

	if ( element.closest( '.popup.last-activity' ).length ) {
		data.term = jQuery( '.wpeo-general-search input[type="text"]' ).val();
		data.follower_id_selected = jQuery( '.wpeo-header-search .follower_id_selected' ).val();
		data.categories_id_selected = jQuery( 'input[name="categories_id_selected"]' ).val();
	}

	jQuery.post( ajaxurl, data, function( response ) {
		element.closest( '.activities' ).find( '.offset-event' ).val( response.data.offset );
		element.closest( '.activities' ).find( '.content:first' ).append( response.data.view );
		element.closest( '.activities' ).find( '.last-date' ).val( response.data.last_date );

		if ( response.data.end ) {
			element.closest( '.activities' ).find( '.load-more-history' ).hide();
		}

		window.eoxiaJS.refresh();
	} );
};

/**
 * Récupères les critères de recherche dans la navigation avant d'ouvrir la POPUP des dernières activitées.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @param  {Element} element L'élément déclenchant l'ouverture de la POPUP.
 * @return {Object}
 */
window.eoxiaJS.taskManager.activity.getDataBeforeOpenPopup = function( element ) {
	return {
		term: jQuery( '.wpeo-general-search input[type="text"]' ).val(),
		follower_id_selected: jQuery( '.wpeo-header-search .follower_id_selected' ).val(),
		categories_id_selected: jQuery( 'input[name="categories_id_selected"]' ).val()
	};
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_last_activity".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.activity.loadedLastActivity = function( triggeredElement, response ) {
	if ( triggeredElement.closest( '.wpeo-project-task' ).length ) {
		triggeredElement.addClass( 'active' );
		triggeredElement.closest( '.wpeo-project-task' ).find( '.activities .load-more-history' ).show();
		triggeredElement.closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .dashicons-editor-ul.active' ).removeClass( 'active' );
		triggeredElement.closest( '.wpeo-project-task' ).find( '.points.sortable, .wpeo-task-point-use-toggle' ).hide();
		triggeredElement.closest( '.wpeo-project-task' ).find( '.activities .offset-event' ).val( response.data.offset );
		triggeredElement.closest( '.wpeo-project-task' ).find( '.activities .last-date' ).val( response.data.last_date );
		triggeredElement.closest( '.wpeo-project-task' ).find( '.activities .content' ).html( response.data.view );
		triggeredElement.closest( '.wpeo-project-task' ).find( '.activities' ).show();
		triggeredElement.closest( '.wpeo-project-task' ).find( '.activities .load-more-history' ).show();
	} else {
		jQuery( '.popup.last-activity .content' ).html( response.data.view );
		jQuery( '.popup.last-activity .container' ).removeClass( 'loading' );
		jQuery( '.popup.last-activity .title' ).html( response.data.title_popup );
		jQuery( '.popup.last-activity .load-more-history' ).show();
		jQuery( '.popup.last-activity .offset-event' ).val( response.data.offset );
		jQuery( '.popup.last-activity .last-date' ).val( response.data.last_date );
	}

	window.eoxiaJS.refresh();
};
