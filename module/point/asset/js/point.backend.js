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
	jQuery( document ).on( 'blur keyup paste keydown', '.wpeo-add-point .wpeo-point-new-contenteditable', window.task_manager.point.updateHiddenInput );
	jQuery( document ).on( 'blur paste', 'form.edit .wpeo-point-new-contenteditable', window.task_manager.point.editPoint );
	jQuery( document ).on( 'click', 'form .completed-point', window.task_manager.point.completePoint );
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
	jQuery( '.wpeo-project-wrap .wpeo-task-point-sortable' ).sortable( {
		handle: '.dashicons-screenoptions',
		items: 'form',
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
		jQuery( this ).closest( '.wpeo-add-point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 1 );
		jQuery( this ).closest( '.wpeo-point-input' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
	} else {
		jQuery( this ).closest( '.wpeo-add-point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
		jQuery( this ).closest( '.wpeo-point-input' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
	}

	jQuery( this ).closest( '.wpeo-point-input' ).find( 'input[type="hidden"]' ).val( jQuery( this ).text() );
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
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( 'ul.wpeo-task-point-sortable form:last' ).before( response.data.view );
};

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
	jQuery( triggeredElement ).closest( 'form' ).fadeOut();
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
	var data = {
		action: 'complete_point',
		_wpnonce: jQuery( this ).data( 'nonce' ),
		point_id: jQuery( this ).closest( 'form' ).find( 'input[name="id"]' ).val(),
		complete: jQuery( this ).is( ':checked' )
	};

	if ( jQuery( this ).is( ':checked' ) ) {
		jQuery( this ).closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).append( jQuery( this ).closest( 'form' ) );
	} else {
		jQuery( this ).closest( '.wpeo-project-task' ).find( 'ul.wpeo-task-point-sortable form:last' ).before( jQuery( this ).closest( 'form' ) );
	}

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
	jQuery( triggeredElement ).closest( '.wpeo-task-point-use-toggle' ).find( '.wpeo-task-point-completed' ).toggleClass( 'hidden' );
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
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).html( response.data.view );
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

	jQuery( this ).find( '.form.point.edit' ).each( function() {
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

window.task_manager.point.movedPointTo = function( triggeredElement, response ) {

};
