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

	jQuery( '.tm-wrap' ).on( 'click', '.tm-task-display-method-buttons .list-display', window.eoxiaJS.taskManager.activity.switchViewToLine );
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
window.eoxiaJS.taskManager.activity.switchViewToLine = function( event ) {
	var taskElement = jQuery( this ).closest( '.wpeo-project-task' );
	taskElement.find( '.tm-task-display-method-buttons .wpeo-button.active' ).removeClass( 'active' );
	jQuery( this ).addClass( 'active' );
	taskElement[0].querySelector( '.bloc-activities' ).style.display = 'none';
	this.closest( '.wpeo-project-task' ).querySelector( '.points.sortable' ).style.display = 'block';
};

/**
 * Envoie une requête pour charger plus d'évènement dans l'historique.
 *
 * @since 1.5.0
 * @version 1.6.0
 *
 * @return void
 */
window.eoxiaJS.taskManager.activity.loadMoreHistory = function( event ) {
	var element = jQuery( this );
	var data = {
		action: 'load_last_activity',
		_wpnonce: element.closest( '.wpeo-project-task' ).find( '.dashicons-screenoptions' ).data( 'nonce' ),
		tasks_id: element.closest( '.wpeo-project-task' ).data( 'id' ),
		offset: element.closest( '.activities' ).find( '.offset-event' ).val(),
		last_date: element.closest( '.activities' ).find( '.last-date' ).val()
	};
	window.eoxiaJS.loader.display( element );

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
		} else {
			element.closest( '.activities' ).find( '.load-more-history' ).show();
		}

		window.eoxiaJS.loader.remove( element.closest( '.activities' ).find( '.load-more-history' ) );
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
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.activity.loadedLastActivity = function( triggeredElement, response ) {

	if ( triggeredElement.closest( '.wpeo-project-task' ).length ) {
		var taskElement = triggeredElement.closest( '.wpeo-project-task' );
		triggeredElement.addClass( 'active' );
		triggeredElement.closest( '.tm-task-display-method-buttons' ).find( '.list-display.active' ).removeClass( 'active' );
		triggeredElement[0].closest( '.wpeo-project-task' ).querySelector( '.points' ).style.display = 'none';
		taskElement.find( '.bloc-activities' ).html( response.data.view ).show();
	} else{
		var element = triggeredElement.closest( '.inside' );
		element.html( response.data.view ).show();
		// jQuery( '#tm-indicator-activity .inside' ).html( response.data.view ); // 28/03/2019 Inutilisé ?
	};

	if( response.data.data_indicator != null && response.data.data_indicator != '' ){
		window.eoxiaJS.taskManager.activity.loadIndicatorActivity( response.data.data_indicator );
	}
};

window.eoxiaJS.taskManager.activity.loadIndicatorActivity = function( data ){

	if( document.getElementById( "tm_activity_post_indicator_doghnut_" + data[ 'task_id' ][ 0 ] ) !== null  ){
		jQuery( "#tm_activity_post_indicator_doghnut_" + data[ 'task_id' ][ 0 ] ).html( '<canvas id="tm_activity_post_indicator_doghnut' + data[ 'task_id' ][ 0 ] + '" class="wpeo-modal-event" ></canvas>' );

		var canvasDonut = document.getElementById( "tm_activity_post_indicator_doghnut" + data[ 'task_id' ][ 0 ] ).getContext('2d');

		var data_canvas_doghnut = {
			labels : [ window.indicatorString.completed, window.indicatorString.uncompleted ],
			datasets: [
					{
						backgroundColor: [ "#005387", "#ee6123" ],
						data: [ data[ 'count_completed_points' ], data[ 'count_uncompleted_points' ] ],
					}
				],
		};

		new Chart( canvasDonut, {
	    type: 'doughnut',
	    data: data_canvas_doghnut,
	    options: ''
		});
	}

};

/**
 * Le callback de la requête ajax "export_activity".
 *
 * @param  {HTMLButtonElement} triggeredElement L'élement HTML déclenchant la requête Ajax.
 * @param  {Object} response                    Les données renvoyées par la requête Ajax.
 *
 * @since 1.7.1
 */
window.eoxiaJS.taskManager.activity.exportedActivity = function( triggeredElement, response ) {
	window.eoxiaJS.global.downloadFile( response.data.url_to_file, response.data.filename );
};
