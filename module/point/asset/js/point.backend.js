/**
 * Initialise l'objet "point" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.6.0
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
	jQuery( document ).on( 'keyup', '.point:not(.edit) .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.triggerCreate );

	jQuery( document ).on( 'click', '.point.edit .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.activePoint );
	jQuery( document ).on( 'blur keyup paste keydown click', '.point .point-content .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.updateHiddenInput );
	jQuery( document ).on( 'blur paste', '.point.edit .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.point.editPoint );
	jQuery( document ).on( 'click', '.form .completed-point', window.eoxiaJS.taskManager.point.completePoint );
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
	jQuery( '.wpeo-project-wrap .points.sortable' ).sortable( {
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
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 1 );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).removeClass( 'no-action' );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-placeholder' ).addClass( 'hidden' );
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'pointerEvents', 'auto' );
			window.eoxiaJS.taskManager.core.initSafeExit( true );
		} else {
			jQuery( this ).closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
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
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.point.addedPointSuccess = function( triggeredElement, response ) {
	var totalPoint = parseInt( triggeredElement.closest( '.wpeo-project-task' ).find( '.total-point' ).text() );
	totalPoint++;

	triggeredElement.closest( '.wpeo-project-task' ).find( '.total-point' ).text( totalPoint );

	triggeredElement.closest( '.point' ).find( '.wpeo-point-new-contenteditable' ).text( '' );
	triggeredElement.closest( '.point' ).find( 'input[name="content"]' ).val( '' );

	triggeredElement.closest( '.point' ).find( '.wpeo-point-new-btn' ).css( 'opacity', 0.4 );
	triggeredElement.closest( '.point' ).find( '.wpeo-point-new-btn' ).addClass( 'no-action' );
	triggeredElement.closest( '.point' ).find( '.wpeo-point-new-placeholder' ).removeClass( 'hidden' );

	triggeredElement.closest( '.wpeo-project-task' ).find( '.points.sortable .point:last' ).before( response.data.view );

	window.eoxiaJS.taskManager.point.initAutoComplete();
	window.eoxiaJS.refresh();

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
	triggeredElement.closest( '.form' ).removeClass( 'loading' );
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
		jQuery( this ).closest( '.form' ).addClass( 'loading' );
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
	var totalPoint = jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.total-point' ).text();
	var totalCompletedPoint = jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text();
	totalPoint--;
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.total-point' ).text( totalPoint );

	if ( jQuery( triggeredElement ).closest( '.point' ).find( '.completed-point' ).is( ':checked' ) ) {
		totalCompletedPoint--;
		jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text( totalCompletedPoint );
	}

	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.wpeo-task-time-manage .elapsed' ).text( response.data.time );

	jQuery( triggeredElement ).closest( '.wpeo-project-task.mask' ).removeClass( 'mask' );

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
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.point.completePoint = function() {
	var totalCompletedPoint = jQuery( this ).closest( '.wpeo-project-task' ).find( '.point-completed' ).text();

	var data = {
		action: 'complete_point',
		_wpnonce: jQuery( this ).data( 'nonce' ),
		point_id: jQuery( this ).closest( '.form' ).find( 'input[name="id"]' ).val(),
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
	window.eoxiaJS.request.send( jQuery( this ), data );
};

/**
 * Avant de charger les points complétés, toggle la classe de la dashicons.
 *
 * @param  {HTMLSpanElement} triggeredElement L'élément HTML déclenchant l'action.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.point.beforeLoadCompletedPoint = function( triggeredElement ) {
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
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.point.loadedCompletedPoint = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.wpeo-project-task' ).find( '.points.completed' ).html( response.data.view );
	window.eoxiaJS.refresh();
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
		currentTask.find( '.wpeo-task-time-manage' ).find( '.elapsed' ).html( response.data.current_task_elapsed_time );
		currentTask.find( '.wpeo-point-toggle-a' ).find( '.total-point' ).html( response.data.current_task.data.count_completed_points + response.data.current_task.data.count_uncompleted_points );

		if ( response.data.point.data.completed ) {
			currentTask.find( '.wpeo-point-toggle-a .point-completed' ).html( response.data.current_task.data.count_completed_points );
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
		toTask.find( '.wpeo-task-time-manage .elapsed' ).text( response.data.to_task_elapsed_time );

		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-toggle .action-attribute' ).attr( 'data-task-id', response.data.to_task.data.id );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .form-fields .action-input' ).removeClass( 'active' );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .form-fields .search-task' ).val( '' );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .move-to input[name="task_id"]' ).val( response.data.to_task.data.id );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action .move-to input[name="to_task_id"]' ).val( '' );
		toTask.find( '.point.edit[data-id=' + response.data.point.data.id + '] .point-header-action.active' ).removeClass( 'active' );

		if ( response.data.point.data.point_info.completed ) {
			toTask.find( '.wpeo-point-toggle-a .point-completed' ).html( response.data.to_task.data.count_completed_points );
		}

		// Met à jour le nombre de point sur la tâche reçevant le point.
		toTask.find( '.wpeo-point-toggle-a' ).find( '.total-point' ).html( response.data.to_task.data.count_completed_points + response.data.to_task.data.count_uncompleted_points );
	}

	window.eoxiaJS.refresh();
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
