/**
 * Initialise l'objet "point" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.point = {};
window.eoxiaJS.taskManager.point.lastContent = '';
/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.point.init = function() {
	window.eoxiaJS.taskManager.point.initAutoComplete();
	window.eoxiaJS.taskManager.point.event();
	window.eoxiaJS.taskManager.point.refresh();
};

/**
 * Initialise tous les évènements liés au point de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.point.event = function() {
	jQuery( document ).on( 'keyup', '.wpeo-project-task .point:not(.edit) .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.triggerCreate );

	jQuery( document ).on( 'click', '.wpeo-project-task .point.edit .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.activePoint );
	jQuery( document ).on( 'blur keyup paste keydown click', '.point .point-content .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.updateHiddenInput );
	jQuery( document ).on( 'blur paste', '.wpeo-project-task .point.edit .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.editPoint );
	// jQuery( document ).on( 'click', '.wpeo-project-task .form .completed-point', window.eoxiaJS.taskManager.point.completePoint );

	jQuery( document ).on( 'click', '.point-type-display-buttons div.active', window.eoxiaJS.taskManager.point.undisplayPoint );

	jQuery( document ).on( 'click', '.modal-prompt-point .action-input', window.eoxiaJS.taskManager.point.pointAddLoader );
};

/**
 * Initialise l'autocomplete pour déplacer les points.
 *
 * @return {void}
 *
 * @since 1.4.0-ford
 * @version 1.4.0-ford
 */
window.eoxiaJS.taskManager.point.initAutoComplete = function() {
	jQuery( '.search-task' ).autocomplete( {
		'source': 'admin-ajax.php?action=search_task',
		'delay': 0,
		'select': function( event, ui ) {
			jQuery( 'input[name="to_task_id"]' ).val( ui.item.id );
			jQuery( this ).closest( '.form-fields' ).find( '.action-input' ).addClass( 'active' );
			event.stopPropagation();
		}
	} );
};

window.eoxiaJS.taskManager.point.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).click();
	}
};

window.eoxiaJS.taskManager.point.activePoint = function( event ) {
	jQuery( '.point.active' ).removeClass( 'active' );

	jQuery( this ).closest( '.point' ).addClass( 'active' );
	window.eoxiaJS.taskManager.point.lastContent = jQuery( this ).closest( '.point' ).find( '.point-content input[name="content"]' ).val();
};

/**
 * Cette méthode est appelé automatiquement lors de l'appel à la méthode window.eoxiaJS.taskManager.refresh().
 *
 * @return void
 *
 * @since 1.0.0
 * @version 1.4.0
 */
window.eoxiaJS.taskManager.point.refresh = function() {
	jQuery( '.tm-wrap .points.sortable' ).sortable( {
		handle: '.wpeo-sort-point',
		items: 'div.point.edit',
		update: window.eoxiaJS.taskManager.point.editOrder
	} );

	window.eoxiaJS.taskManager.point.initAutoComplete();
};

/**
 * Met à jour le champ caché contenant le texte du point écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.point.updateHiddenInput = function( event ) {
	if ( ! jQuery( this ).closest( '.point' ).hasClass( 'edit' ) ) {
		if ( 0 < jQuery( this ).text().length ) {
			jQuery( this ).closest( '.point' ).find( '.quick-point-event' ).hide();
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).show();
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).removeClass( 'no-action' );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'pointerEvents', 'auto' );
			window.eoxiaJS.taskManager.core.initSafeExit( true );
		} else {
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).hide();
			jQuery( this ).closest( '.point' ).find( '.quick-point-event' ).show();
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).addClass( 'no-action' );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'pointerEvents', 'none' );
			window.eoxiaJS.taskManager.core.initSafeExit( false );
		}
	}

	jQuery( this ).closest( '.point' ).find( '.point-content input[name="content"]' ).val( jQuery( this ).html() );

	jQuery( this ).closest( '.point' ).find( '.point-content input[name="content"]' ).trigger( 'change' );

	// window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_point".
 * Ajoutes le point avant le formulaire pour ajouter un point dans le ul.wpeo-task-point-sortable
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.7.0
 */
window.eoxiaJS.taskManager.point.addedPointSuccess = function( triggeredElement, response ) {
	var task = jQuery( "div.wpeo-project-task[data-id='" + response.data.task_id + "']" );

	task.find( '.point-uncompleted' ).text( response.data.task.data.count_uncompleted_points );
	task.find( '.point-completed' ).text( response.data.task.data.count_completed_points );

	if ( triggeredElement.closest( '.point' ).length ) {
		triggeredElement.closest( '.point' ).find( '.wpeo-point-new-contenteditable' ).text( '' );
		triggeredElement.closest( '.point' ).find( 'input[name="content"]' ).val( '' );
		triggeredElement.closest( '.point' ).find( '.quick-point-event' ).hide();
		triggeredElement.closest( '.point' ).find( '.wpeo-point-new-btn' ).hide();
		triggeredElement.closest( '.point' ).find( '.wpeo-point-new-btn' ).addClass( 'no-action' );
		triggeredElement.closest( '.point' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );
		triggeredElement.closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'pointerEvents', 'auto' )
	}

	window.eoxiaJS.taskManager.point.initAutoComplete();
	triggeredElement.trigger( 'addedPointSuccess' );
	window.eoxiaJS.taskManager.core.initSafeExit( false );
};

/**
 * Le callback en cas de réussite à la requête Ajax "create_point".
 * Ajoutes le point avant le formulaire pour ajouter un point dans le ul.wpeo-task-point-sortable
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.point.editedPointSuccess = function( triggeredElement, response ) {
	window.eoxiaJS.loader.remove( triggeredElement.closest( '.form' ) );
};

/**
 * Met à jour un point en cliquant sur le bouton pour envoyer le formulaire.
 *
 * @since 1.0.0
 * @version 1.5.0
 *
 * @return void
 */
window.eoxiaJS.taskManager.point.editPoint = function() {
	if ( window.eoxiaJS.taskManager.point.lastContent !== jQuery( this ).closest( '.form' ).find( '.point-content input[name="content"]' ).val() ) {
		window.eoxiaJS.loader.display( jQuery( this ).closest( '.form' ) );
		jQuery( this ).closest( '.form' ).find( '.action-input.update' ).click();
	}
};

/**
 * Supprimes la ligne du point.
 *
 * @param  {HTMLSpanElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {object} response                   Les données renvoyées par la requête Ajax.
 * @return void
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.point.deletedPointSuccess = function( triggeredElement, response ) {
	const point = response.data.point;
	jQuery( triggeredElement ).closest( '.table-row' ).fadeOut( 400, function() {
		jQuery( this ).remove();
	} );

	jQuery( '.table-type-project[data-id=' + point.data.post_id + '] .project-time .elapsed' ).text( response.data.time );
	jQuery( '.table-type-comment[data-post-id=' + point.data.post_id + ']' ).fadeOut(400, function() {
		jQuery( this ).remove();
	});

};

var longpress = 500; // Durée par défaut d'un clic => 2 sec
var start = 0; // Creer un timer au début du clic (pour calculer sa durée)

window.eoxiaJS.taskManager.point.completePointChoices = function( e ){

	if( e.type == "mousedown" ){ // L'utilisateur click sur la checkbox
		start = new Date().getTime();

	}else if( e.type == "mouseup" ){ // L'utilisateur relache le click sur la metabox
		if( ! jQuery( this ).data( 'checked' ) ){ // Avec les events mouseup, mousedown, mouseleave, l'utilisateur ne coche pas
			jQuery( this ).data( 'checked', 'true' ); // La metabox au moment du clic
			jQuery( this ).prop( "checked", true ); // Donc on le fait manuellement
		}else{
			jQuery( this ).data( 'checked', 'false' );
			jQuery( this ).prop( "checked", false );
		}

		if( new Date().getTime() >= ( start + longpress ) ){ // Si durée du clic > 2 sec => On affiche les options
			console.log( ' - LONG' );
			jQuery( this ).parent().find( '.point-list-element' ).show( '200' );


		}else{ // Sinon on coche simplement, la tache est complétée
			console.log( ' - SHORT' );

			window.eoxiaJS.taskManager.point.completePoint( this );
		}
	}else{ // L'utilisateur enleve sa souris de la metabox, on reset le chronometre qui calcul la durée du clic
		start = 0;
	}
}


/**
 * Envoie une requête pour passer le point en compléter ou décompléter.
 * Déplace le point vers la liste à puce "compléter" ou "décompléter".
 *
 * @since 1.0.0
 */
window.eoxiaJS.taskManager.point.completePoint = function( event ) {
	/*var numberComment = jQuery( this ).closest( '.point' ).find( '.number-comments' ).text();

	// if ( numberComment == 0 && jQuery( this ).closest( '.point' ).attr( 'data-point-state' ) == 'uncompleted' ) {
	// 	jQuery( '.modal-prompt-point' ).addClass( 'modal-active' );
	// 	jQuery( '.modal-prompt-point input[name="post_id"]' ).val( jQuery( this ).closest( '.point' ).find( 'input[name="parent_id"]').val());
	// 	jQuery( '.modal-prompt-point input[name="point_id"]' ).val( jQuery( this ).closest( '.point' ).find( 'input[name="id"]').val() );
	// 	jQuery( '.modal-prompt-point .content' ).html( '#' + jQuery( this ).closest( '.point' ).find( 'input[name="id"]').val() + ' - ' + jQuery( this ).closest( '.point' ).find( '.point-content input[name="content"]').val() );
	// 	event.preventDefault();
	// 	return false;
	// } else {

		var totalCompletedPoint = jQuery(this).closest('.wpeo-project-task').find('.point-completed').text();
		var totalUncompletedPoint = jQuery(this).closest('.wpeo-project-task').find('.point-uncompleted').text();
		var completedButton = jQuery('.point-type-display-buttons button[data-point-state="completed"]');
		var uncompletedButton = jQuery('.point-type-display-buttons button[data-point-state="uncompleted"]');

		var data = {
			action: 'complete_point',
			_wpnonce: jQuery(this).data('nonce'),
			point_id: jQuery(this).closest('.form').find('input[name="id"]').val(),
			complete: jQuery(this).is(':checked')
		};

		if (jQuery(this).is(':checked')) {
			totalCompletedPoint++;
			totalUncompletedPoint--;
			jQuery(this).closest('.wpeo-project-task').find('.point-completed').text(totalCompletedPoint);
			jQuery(this).closest('.wpeo-project-task').find('.point-uncompleted').text(totalUncompletedPoint);

			if (completedButton.hasClass('active')) {
				jQuery(this).closest('.point').attr('data-point-state', 'completed');
			} else {
				jQuery(this).closest('.point').remove();
			}

		} else {
			totalCompletedPoint--;
			totalUncompletedPoint++;
			jQuery(this).closest('.wpeo-project-task').find('.point-completed').text(totalCompletedPoint);
			jQuery(this).closest('.wpeo-project-task').find('.point-uncompleted').text(totalUncompletedPoint);

			if (uncompletedButton.hasClass('active')) {
				jQuery(this).closest('.point').attr('data-point-state', 'uncompleted');
			} else {
				jQuery(this).closest('.point').remove();
			}
		}

		window.eoxiaJS.request.send(jQuery(this), data);
	// }*/
};

/**
 * Récupères les ID des points dans l'ordre de l'affichage et les envoies à l'action "edit_order_point".
 *
 * @return void
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.point.editOrder = function() {
	var orderPointId = [];
	var objectId     = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	var data         = {};

	jQuery( this ).find( '.point.edit' ).each( function() {
		orderPointId.push( jQuery( this ).data( 'id' ) );
	} );

	data.action         = 'edit_order_point';
	data.task_id        = objectId;
	data.order_point_id = orderPointId;

	window.eoxiaJS.request.send( jQuery( this ), data );
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_point_properties".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.point.loadedPointProperties = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .content' ).html( response.data.view );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.popup .container' ).removeClass( 'loading' );
};

/**
 * Le callback en cas de réussite à la requête Ajax "move_point_to".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.point.movedPointTo = function( triggeredElement, response ) {
	var currentTask = jQuery( '.wpeo-project-task[data-id=' + response.data.current_task.data.id + ']' );
	var toTask      = jQuery( '.wpeo-project-task[data-id=' + response.data.to_task.data.id + ']' );

	jQuery( '.wpeo-project-task.mask' ).removeClass( 'mask' );

	// Met à jour le temps et le nombre de point sur la tâche.
	if ( currentTask.length ) {
		currentTask.find( '.wpeo-task-time-info' ).find( '.elapsed' ).html( response.data.current_task_elapsed_time );
		//currentTask.find( '.wpeo-point-toggle-a' ).find( '.total-point' ).html( response.data.current_task.data.count_completed_points + response.data.current_task.data.count_uncompleted_points );

		if ( response.data.point.data.completed ) {
			currentTask.find( '.wpeo-task-filter .point-completed' ).html( response.data.current_task.data.count_completed_points );
		}else{
			currentTask.find( '.wpeo-task-filter .point-uncompleted' ).html( response.data.current_task.data.count_uncompleted_points );
		}

		if ( toTask.length ) {
			if ( response.data.point.data.completed && toTask.find( '.points.completed:not(.hidden)' ).length ) {
				toTask.find( '.points.completed div.point:last' ).before( jQuery( '.point.edit[data-id=' + response.data.point.data.id + ']' ) );
			} else if ( response.data.point.data.completed && ! toTask.find( '.points.completed:not(.hidden)' ).length ) {
				jQuery( '.point.edit[data-id=' + response.data.point.data.id + ']' ).fadeOut( 400, function() {
					jQuery( this ).remove();
				} );
			} else {
				toTask.find( '.points.sortable div.point:last' ).before( jQuery( '.point.edit[data-id=' + response.data.point.data.id + ']' ) );
			}
		} else {
			jQuery( '.point.edit[data-id=' + response.data.point.data.id + ']' ).fadeOut( 400, function() {
				jQuery( this ).remove();
			} );
		}
	}

	triggeredElement.closest( '.wpeo-dropdown' ).removeClass( 'dropdown-active' );

	if ( toTask.length ) {
		toTask.find( '.wpeo-task-time-info .elapsed' ).text( response.data.to_task_elapsed_time );

		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .wpeo-point-summary .action-attribute' ).attr( 'data-task-id', response.data.to_task.data.id );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .form-fields .action-input' ).removeClass( 'active' );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .form-fields .search-task' ).val( '' );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .move-to input[name="task_id"]' ).val( response.data.to_task.data.id );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .move-to input[name="to_task_id"]' ).val( '' );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action.active' ).removeClass( 'active' );

		if ( response.data.point.data.point_info.completed ) {
			toTask.find( '.wpeo-task-filter .point-completed' ).html( response.data.to_task.data.count_completed_points );
		}else{
			toTask.find( '.wpeo-task-filter .point-uncompleted' ).html( response.data.to_task.data.count_uncompleted_points );
		}

		// Met à jour le nombre de point sur la tâche reçevant le point.
		//toTask.find( '.wpeo-point-toggle-a' ).find( '.total-point' ).html( response.data.to_task.data.count_completed_points + response.data.to_task.data.count_uncompleted_points );
	}
};

/**
 * Méthode appelé lors de la modification de la date du point.
 * Envoie une requête AJAX pour effectuer la mise à jour en base de donnée.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.point.afterTriggerChangeDate = function( triggeredElement ) {
	var data = {
		action: 'change_date_point',
		id: triggeredElement.closest( '.point' ).attr( 'data-id' ),
		date: triggeredElement.val()
	};

	window.eoxiaJS.request.send( triggeredElement, data );
};


/**
 * Le callback en cas de réussite à la requête Ajax "load_completed_point".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.point.loadedPoint = function( triggeredElement, response ) {
	jQuery( triggeredElement ).addClass( 'active' ).removeClass( 'action-input' );
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.points .point:not(.edit)' ).after( response.data.view );
};

/**
 * Méthode appelée lors du clic sur les boutons de hoix du type de points affichés dans une tâche.
 *
 * @since 1.8.0
 *
 * @param  {type} event  L'événement lancé lors de l'action.
 */
window.eoxiaJS.taskManager.point.undisplayPoint = function( event ) {
	var pointState = jQuery( this ).attr( 'data-point-state' );
	// event.preventDefault();

	jQuery( this ).removeClass( 'active' ).addClass( 'action-input' );

	var points = this.closest( '.wpeo-project-task-container' ).querySelectorAll( '.points .point.edit[data-point-state="' + pointState + '"]' );
	for( var i = 0; i < points.length; i ++ ){
		points[i].remove();
	}
	// jQuery( this ).closest( '.wpeo-project-task-container' ).find( '.points .point.edit[data-point-state="' + pointState + '"]' ).remove();
};

window.eoxiaJS.taskManager.point.completedWithPrompt = function( triggeredElement, response ) {
	jQuery( '.wpeo-project-task[data-id=' + response.data.id + ']' ).replaceWith( response.data.view );
};

window.eoxiaJS.taskManager.point.pointAddLoader = function (event) {
	var taskID = jQuery( this ).closest( '.wpeo-modal' ).find( 'input[name="post_id"]' ).val();
	window.eoxiaJS.loader.display( jQuery( '.wpeo-project-task[data-id=' + taskID + ']' ) );
}
