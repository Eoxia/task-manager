/**
 * Initialise l'objet "point" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.init = function() {
	window.task_manager.point.event();
	window.task_manager.point.refresh();
};

/**
 * Initialise tous les évènements liés au point de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.event = function() {
	jQuery( document ).on( 'keyup', '.point:not(.edit) .wpeo-point-new-contenteditable', window.task_manager.point.triggerCreate );

	jQuery( document ).on( 'click', '.point.edit .wpeo-point-new-contenteditable', window.task_manager.point.activePoint );
	jQuery( document ).on( 'blur keyup paste keydown click', '.point .wpeo-point-new-contenteditable', window.task_manager.point.updateHiddenInput );
	jQuery( document ).on( 'blur paste', '.point.edit .wpeo-point-new-contenteditable', window.task_manager.point.editPoint );
	jQuery( document ).on( 'click', 'form .completed-point', window.task_manager.point.completePoint );

};

window.task_manager.point.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).click();
	}
};

window.task_manager.point.activePoint = function( event ) {
	jQuery( '.point.active' ).removeClass( 'active' );

	jQuery( this ).closest( '.point' ).addClass( 'active' );
};

/**
 * Cette méthode est appelé automatiquement lors de l'appel à la méthode window.task_manager.refresh().
 *
 * @return void
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.refresh = function() {
	jQuery( '.wpeo-project-wrap .points.sortable' ).sortable( {
		handle: '.dashicons-screenoptions',
		items: 'div.point.edit',
		update: window.task_manager.point.editOrder
	} );
};

/**
 * Met à jour le champ caché contenant le texte du point écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 1 );
		jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
	} else {
		jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
		jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	}

	jQuery( this ).closest( '.point' ).find( 'input[name="content"]' ).val( jQuery( this ).html() );
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_point".
 * Ajoutes le point avant le formulaire pour ajouter un point dans le ul.wpeo-task-point-sortable
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.addedPointSuccess = function( triggeredElement, response ) {
	var totalPoint = jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.total-point' ).text();
	totalPoint++;
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.total-point' ).text( totalPoint );

	triggeredElement.closest( '.form' ).find( '.wpeo-point-new-contenteditable' ).text( '' );
	triggeredElement.closest( '.form' ).find( 'input[name="content"]' ).html( '' );

	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.points.sortable .point:last' ).before( response.data.view );

	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_point".
 * Ajoutes le point avant le formulaire pour ajouter un point dans le ul.wpeo-task-point-sortable
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.editedPointSuccess = function( triggeredElement, response ) {};

/**
 * Met à jour un point en cliquant sur le bouton pour envoyer le formulaire.
 *
 * @return void
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.editPoint = function() {
	jQuery( this ).closest( 'form' ).find( '.submit-form' ).click();
};

/**
 * Supprimes la ligne du point.
 *
 * @param  {HTMLSpanElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {object} response                   Les données renvoyées par la requête Ajax.
 * @return void
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.deletedPointSuccess = function( triggeredElement, response ) {
	var totalPoint = jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.total-point' ).text();
	var totalCompletedPoint = jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text();
	totalPoint--;
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.total-point' ).text( totalPoint );

	if ( jQuery( triggeredElement ).closest( '.point' ).find( '.completed-point' ).is( ':checked' ) ) {
		totalCompletedPoint--;
		jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text( totalCompletedPoint );
	}

	jQuery( triggeredElement ).closest( 'div.point.edit' ).fadeOut( 400, function() {
		window.eoxiaJS.refresh();
	} );
};

/**
 * Envoie une requête pour passer le point en compléter ou décompléter.
 * Déplace le point vers la liste à puce "compléter" ou "décompléter".
 *
 * @return void
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.completePoint = function() {
	var totalCompletedPoint = jQuery( this ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text();

	var data = {
		action: 'complete_point',
		_wpnonce: jQuery( this ).data( 'nonce' ),
		point_id: jQuery( this ).closest( 'form' ).find( 'input[name="id"]' ).val(),
		complete: jQuery( this ).is( ':checked' )
	};

	if ( jQuery( this ).is( ':checked' ) ) {
		totalCompletedPoint++;
		jQuery( this ).closest( '.wpeo-project-task' ).find( '.points.completed' ).append( jQuery( this ).closest( 'div.point' ) );
	} else {
		totalCompletedPoint--;
		jQuery( this ).closest( '.wpeo-project-task' ).find( '.points.sortable div.point:last' ).before( jQuery( this ).closest( 'div.point' ) );
	}

	jQuery( this ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text( totalCompletedPoint );

	window.eoxiaJS.refresh();
	window.task_manager.request.send( jQuery( this ), data );
};

/**
 * Avant de charger les points complétés, toggle la classe de la dashicons.
 *
 * @param  {HTMLSpanElement} triggeredElement L'élément HTML déclenchant l'action.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.beforeLoadCompletedPoint = function( triggeredElement ) {
	jQuery( triggeredElement ).closest( '.wpeo-task-point-use-toggle' ).find( '.dashicons' ).toggleClass( 'dashicons-minus dashicons-plus' );
	jQuery( triggeredElement ).closest( '.wpeo-task-point-use-toggle' ).find( '.points.completed' ).toggleClass( 'hidden' );
	window.eoxiaJS.refresh();
	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_completed_point".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.task_manager.point.loadedCompletedPoint = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.points.completed' ).html( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Récupères les ID des points dans l'ordre de l'affichage et les envoies à l'action "edit_order_point".
 *
 * @return void
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.editOrder = function() {
	var orderPointId = [];
	var objectId = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	var data = {};

	jQuery( this ).find( '.point.edit' ).each( function() {
		orderPointId.push( jQuery( this ).data( 'id' ) );
	} );

	data.action = 'edit_order_point';
	data.task_id = objectId;
	data.order_point_id = orderPointId;

	window.task_manager.request.send( jQuery( this ), data );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_point_properties".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.loadedPointProperties = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .content' ).html( response.data.view );

	jQuery( '.search-task' ).autocomplete( {
		'source': 'admin-ajax.php?action=search_task',
		'appendTo': '.list-tasks',
		'select': function( event, ui ) {
			jQuery( 'input[name="to_task_id"]' ).val( ui.item.id );
		}
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "move_point_to".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.task_manager.point.movedPointTo = function( triggeredElement, response ) {

	// Met à jour le temps.
	jQuery( '.wpeo-project-task[data-id=' + response.data.current_task.id + ']' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.current_task.time_info.elapsed );
	jQuery( '.wpeo-project-task[data-id=' + response.data.to_task.id + ']' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.to_task.time_info.elapsed );

	// Met à jour le contenu.
	jQuery( '.wpeo-project-task[data-id=' + response.data.to_task.id + ']' ).find( '.points div.point:last' ).before( jQuery( '.point.edit[data-id=' + response.data.point.id + ']' ) );

	window.eoxiaJS.refresh();
};
