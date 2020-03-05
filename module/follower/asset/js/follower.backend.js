/**
 * Initialise l'objet "point" ainsi que la méthode "follower" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower = {};

window.eoxiaJS.taskManager.follower.init = function() {
	window.eoxiaJS.taskManager.follower.event();
};

window.eoxiaJS.taskManager.follower.event = function() {
	jQuery( document ).on( 'click', '.showfullplanning', window.eoxiaJS.taskManager.follower.showfullplanning );

	jQuery( document ).on( 'hover', '.planninguser .tm-planning-add-row .table-cell', window.eoxiaJS.taskManager.follower.showMoreInformationAboutElement );

	jQuery( document ).on( 'hover', '.planninguser .wpeo-table .table-row .tm-action-delete-row-planning', window.eoxiaJS.taskManager.follower.showMoreInformationAboutElement );

	jQuery( document ).on( 'click', '.planninguser .tm-planning-add-row .table-cell .dropdown-content .dropdown-item', window.eoxiaJS.taskManager.follower.selectItemFromDropdownPlanning );

	jQuery( document ).on( 'click', '.planninguser .tm-planning-add-row .tm-planning-action', window.eoxiaJS.taskManager.follower.prepareRequestToAddRow );

	jQuery( document ).on( 'click', '.planninguser .tm-planning-add-row .tm-planning-period .dropdown-content .dropdown-item', window.eoxiaJS.taskManager.follower.selectItemFromDropdownAutoUpdateHour );

	jQuery( document ).on( 'click', '.planninguser .tm-expand-table', window.eoxiaJS.taskManager.follower.expandTableIndicator);

	jQuery( document ).on( 'click', '.planninguser .tm-modal-archive', window.eoxiaJS.taskManager.follower.modalArchiveRequest);

	jQuery( document ).on( 'click', '.planninguser .wpeo-table .table-row .tm-action-delete-row-planning', window.eoxiaJS.taskManager.follower.deleteActionRowPlanning );

	jQuery( document ).on( 'click', '.planninguser .tm-information-run-for-another-day .tm-information-notice-action > .wpeo-button', window.eoxiaJS.taskManager.follower.informationNoticeActionToRerunForAnotherDay );

	jQuery( document ).on( 'click', '.planninguser .tm-information-run-for-another-day .tm-information-notice-day > .wpeo-button', window.eoxiaJS.taskManager.follower.validThisDay );

	jQuery( document ).on( 'click', '.planninguser .tm-date-end-contract input[ type="radio"]', window.eoxiaJS.taskManager.follower.selectTypeDateEnd );

	jQuery( document ).on( 'click', '.planninguser .tm-display-new-contract', window.eoxiaJS.taskManager.follower.displayNewContract );


	jQuery( document ).on( 'click', '.planninguser .tm-date-end-contract .group-date input[ type="text"]', window.eoxiaJS.taskManager.follower.focusDateChamp );

	jQuery( document ).on( 'change', '.planninguser .tm-table-planning .tm-contract-planning-dynamic-update', window.eoxiaJS.taskManager.follower.updateHourPerDay );

 };

/**
 * Le callback en cas de réussite à la requête Ajax "load_followers".
 * Remplaces le contenu de l'element cliqué par la vue reçu dans la réponse AJAX.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower.loadedFollowersSuccess = function( element, response ) {
	element.closest( '.wpeo-ul-users' ).replaceWith( response.data.view );
	window.eoxiaJS.refresh();
};

/**
 * Le callback en cas de réussite à la requête Ajax "close_followers_edit_mode".
 * Remplaces le contenu de l'element cliqué par la vue reçu dans la réponse AJAX.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower.closedFollowersEditMode = function( element, response ) {
	element.closest( '.wpeo-ul-users' ).replaceWith( response.data.view );
	window.eoxiaJS.refresh();
	window.eoxiaJS.taskManager.newTask.clickUsers();
};

/**
 * Cette méthode est appelé automatiquement lors du clique sur une catégorie a affecter.
 *
 * @param  {HTMLUListElement} element L'élément déclenchant la méthode au clique.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.follower.beforeAffectFollower = function( element ) {
	element.addClass( 'active' );

	return true;
};

/**
 * Cette méthode est appelé automatiquement lors du clique sur une catégorie a désaffecter.
 *
 * @param  {HTMLUListElement} element L'élément déclenchant la méthode au clique.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.follower.beforeUnaffectFollower = function( element ) {
	element.removeClass( 'active' );

	return true;
};

/**
 * Le callback en cas de réussite à la requête Ajax "follower_affectation".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower.affectedFollowerSuccess = function( element, response ) {
	element.attr( 'data-action', 'follower_unaffectation' );
	element.attr( 'data-before-method', 'beforeUnaffectFollower' );
	element.attr( 'data-nonce', response.data.nonce );
};

/**
 * Le callback en cas de réussite à la requête Ajax "follower_unaffectation".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.follower.unaffectedFollowerSuccess = function( element, response ) {
	element.attr( 'data-action', 'follower_affectation' );
	element.attr( 'data-before-method', 'beforeAffectFollower' );
	element.attr( 'data-nonce', response.data.nonce );
};

window.eoxiaJS.taskManager.follower.showfullplanning = function( event ){
	jQuery( '.showfullplanning' ).css( 'display', 'none' );
}

window.eoxiaJS.taskManager.follower.reloadPlanningUser = function( element, response ){
	jQuery( '.planninguser' ).replaceWith( response.data.view );
}

window.eoxiaJS.taskManager.follower.showMoreInformationAboutElement = function( event ){
	var this_class= '.' + jQuery( this ).attr('data-class');

	jQuery( '.planninguser .tm-information-planning .tm-focus-element' ).hide();
	jQuery( '.planninguser .tm-information-planning .tm-focus-element' ).removeClass( 'tm-focus-element' );

	jQuery( '.planninguser .tm-information-planning ' + this_class ).show();
	jQuery( '.planninguser .tm-information-planning ' + this_class ).addClass( 'tm-focus-element' );
}

window.eoxiaJS.taskManager.follower.selectItemFromDropdownPlanning = function( event ){
	jQuery( this ).closest( '.table-cell' ).find( '.tm-planning-display-day' ).html( jQuery( this ).html() );
	jQuery( this ).closest( '.table-cell' ).find( 'input[ type="hidden"]' ).val( jQuery( this ).attr( 'data-select' ) );
}

window.eoxiaJS.taskManager.follower.selectItemFromDropdownAutoUpdateHour = function( event ){
	var from = '09:00';
	var to = '12:00';

	if( jQuery( this ).attr( 'data-select' ) == "afternoon" ){
		from = '14:00';
		to = '18:00';
	}

	jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-work-from input' ).val( from );
	jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-work-to input' ).val( to );
}

window.eoxiaJS.taskManager.follower.prepareRequestToAddRow = function( event ){
	var data = {};
	var valid = {};

	var name = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-custom-name input[name="name"]' );
	valid[ 'name' ] = window.eoxiaJS.taskManager.follower.inputIsValid( name );

	var work_from = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-work-from input[name="work_from"]' );
	valid[ 'work_from' ] = window.eoxiaJS.taskManager.follower.inputIsValid( work_from );

	var work_to = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-work-to input[name="work_to"]' );
	valid[ 'work_to' ] = window.eoxiaJS.taskManager.follower.inputIsValid( work_to );

	var day_start = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-day-start input[name="day_start"]' );
	valid[ 'day_start' ] = window.eoxiaJS.taskManager.follower.inputIsValid( day_start );

	if( valid[ 'name' ] && valid[ 'work_from' ] && valid[ 'work_to' ] && valid[ 'day_start' ] ){

		data.name      = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-custom-name input[ name="name"]' ).val();
		data.day       = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-dropdown-day input[ name="day"]' ).val();
		data.period    = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-period input[ name="period"]' ).val();
		data.work_from = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-work-from input[ name="work_from"]' ).val();
		data.work_to   = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-work-to input[ name="work_to"]' ).val();
		data.day_start = jQuery( this ).closest( '.tm-planning-add-row' ).find( '.tm-planning-day-start input[ name="day_start"]' ).val();
		data.action    = jQuery( this ).attr( 'data-action' );
		data._wpnonce  = jQuery( this ).attr( 'data-wpnonce' );

		window.eoxiaJS.loader.display( jQuery( this ).closest( '.tm-planning-add-row' ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}
}

window.eoxiaJS.taskManager.follower.inputIsValid = function( element ){
	if( element.val().trim() == "" ){
		element.css( {'border' : '2px solid #ff3232'} );
		return false;
	}
	return true;
}

window.eoxiaJS.taskManager.follower.reloadPlanningUserIndicator = function( element, response ){
	var information_element = jQuery( element ).closest( '.planninguser' ).find( '.tm-information-status-request .notice-success' );
	information_element.css( 'display', 'flex' );
	information_element.find( '.notice-title' ).html( response.data.action_text );

	if( response.data.view_rerun != "" ){
		jQuery( element ).closest( '.planninguser' ).find( '.tm-information-run-for-another-day' ).removeClass( 'wpeo-loader' ).show();
	}
	jQuery( element ).closest( '.planninguser' ).find( '.tm-information-run-for-another-day' ).html( response.data.view_rerun );
	var elm = jQuery( element ).closest( '.planninguser' ).find( '.tm-table-planning' );
	jQuery( element ).closest( '.planninguser' ).find( '.tm-table-planning' ).replaceWith( response.data.view );
}


window.eoxiaJS.taskManager.follower.expandTableIndicator = function( event ){
	if( jQuery( this ).attr( 'data-expand' ) == "true" ){
		jQuery( '.planninguser .wpeo-table.table-flex .table-cell' ).css( 'padding', '0.1em 0.6em' );
		jQuery( '.planninguser .wpeo-table.table-flex .tm-planning-add-row .table-cell' ).css( 'padding', '0.8em 0.6em' );
		jQuery( this ).attr( 'data-expand', 'false' );
		jQuery( this ).find( '.wpeo-button' ).removeClass( 'button-grey' ).addClass( 'button-blue');
	}else{
		jQuery( '.planninguser .wpeo-table.table-flex .table-cell' ).css( 'padding', '0.8em 0.6em' );
		jQuery( this ).attr( 'data-expand', 'true' );
		jQuery( this ).find( '.wpeo-button' ).removeClass( 'button-blue' ).addClass( 'button-grey');
	}
}

window.eoxiaJS.taskManager.follower.deleteActionRowPlanning = function ( event ){
	var default_color = jQuery( this ).closest( '.table-row' ).css( 'background-color' );
	jQuery( this ).closest( '.table-row' ).css( 'background-color', 'red' );
	var element = jQuery( this );

	setTimeout(function(){
		if ( window.confirm( element.attr( 'data-message-delete' ) ) ) {
			data = {};
			data.action   = 'delete_element_from_planning_user';
			data._wpnonce = element.attr( 'data-wpnonce' );
			data.day      = element.attr( 'data-day' );
			data.period   = element.attr( 'data-period' );
			window.eoxiaJS.loader.display( element.closest( '.table-row' ) );
			window.eoxiaJS.request.send( element, data );

		}else{
			element.closest( '.table-row' ).css( 'background-color', default_color );
		}
	}, 1);
}

window.eoxiaJS.taskManager.follower.informationNoticeActionToRerunForAnotherDay = function( event ){
	var action = jQuery( this ).attr( 'data-actionjs' );
	if( action == "hide" ){
		jQuery( this ).closest( '.tm-information-run-for-another-day' ).hide( '200' );
	}else if( action == "request" ){
		data = {};
		data.action   = jQuery( this ).attr( 'data-action' );
		data._wpnonce = jQuery( this ).attr( 'data-wpnonce' );

		var parent_element = jQuery( this ).closest( '.tm-information-run-for-another-day' ).find( '.tm-information-secret-element' );
		data.name      = parent_element.find( 'input[ name="name"]' ).val();
		data.period    = parent_element.find( 'input[ name="period"]' ).val();
		data.work_from = parent_element.find( 'input[ name="from"]' ).val();
		data.work_to   = parent_element.find( 'input[ name="to"]' ).val();
		data.day_start = parent_element.find( 'input[ name="daystart"]' ).val();

		data.day = [];
		var parent_button_element = jQuery( this ).closest( '.wpeo-notice' ).find( '.notice-subtitle .tm-information-notice-day' );
		parent_button_element.find( '.wpeo-button' ).each( function( e ){
			if( jQuery( this ).attr( 'data-valid' ) == "true" ){
				data.day.push( jQuery( this ).attr( 'data-day' ) );
			}
		})

		var element_focus =  jQuery( this ).closest( '.planninguser' ).find( '.tm-planning-add-row .tm-planning-action' );
		window.eoxiaJS.loader.display( jQuery( this ).closest( '.tm-information-run-for-another-day' ) );
		window.eoxiaJS.request.send( element_focus, data );
	}else{

	}
}

window.eoxiaJS.taskManager.follower.validThisDay = function(){
	if( jQuery( this ).attr( 'data-valid' ) == "true" ){
		jQuery( this ).attr( 'data-valid', 'false' );
		jQuery( this ).removeClass( 'button-blue' ).addClass( 'button-grey' );
		jQuery( this ).find( '.button-icon' ).removeClass( 'fa-check-square' ).addClass( 'fa-square' );
	}else{
		jQuery( this ).attr( 'data-valid', 'true' );
		jQuery( this ).removeClass( 'button-grey' ).addClass( 'button-blue' );
		jQuery( this ).find( '.button-icon' ).removeClass( 'fa-square' ).addClass( 'fa-check-square' );
	}
}

window.eoxiaJS.taskManager.follower.modalArchiveRequest = function( event ){
	data = {};
	data.action   = jQuery( this ).attr( 'data-action' );
	data._wpnonce = jQuery( this ).attr( 'data-wpnonce' );

	window.eoxiaJS.loader.display( jQuery( this ) );
	window.eoxiaJS.request.send( jQuery( this ), data );
}

window.eoxiaJS.taskManager.follower.displayAchiveUser = function( element, response ){
	element.closest( '.tm-modal-archive' ).find( '.tm-information-modal-view' ).html( response.data.view );
}

window.eoxiaJS.taskManager.follower.selectTypeDateEnd = function( event ){
	var input = jQuery( '#tm-date-end-value' );
	input.val( jQuery( this ).attr( 'data-type' ) );
}

window.eoxiaJS.taskManager.follower.reloadViewProfilePlanning = function( element, response ){
	element.closest( '.planninguser' ).replaceWith( response.data.info.view );
}

window.eoxiaJS.taskManager.follower.reloadViewProfilePlanningError = function( element, response ){
	var text_error = response.data.info.data.error;
	if( text_error != "" ){
		element.closest( '.planninguser' ).find( '.tm-user-add-contract-error' ).show( '200' );
		element.closest( '.planninguser' ).find( '.tm-user-add-contract-error .notice-title' ).html( text_error );
	}
}

window.eoxiaJS.taskManager.follower.displayNewContract = function( event ){
	jQuery( this ).closest( '.planninguser' ).find( '.tm-user-add-contract' ).show( '200' );
}

window.eoxiaJS.taskManager.follower.reloadViewProfileContract = function( element, response ){

	element.closest( '.planninguser' ).find( '.tm-list-contract-button .tm-table-edit' ).remove();
	element.closest( '.planninguser' ).find( '.tm-list-contract-button .tm-hidden-row' ).removeClass( 'tm-hidden-row' ).show();
	element.closest( '.planninguser' ).find( '.tm-user-add-contract' ).html( '' );

	element.closest( '.planninguser' ).find( '.tm-contract-info-empty' ).hide( '200' );
	if( response.data.id > 0 ){
		element.closest( '.planninguser' ).find( '.tm-list-contract-button .table-row[ data-id ="' + response.data.id + '"]' ).before( response.data.view );

		element.closest( '.planninguser' ).find( '.tm-list-contract-button .table-row[ data-id ="' + response.data.id + '"]' ).after( response.data.view_edit );

		element.closest( '.planninguser' ).find( '.tm-list-contract-button .table-row[ data-id ="' + response.data.id + '"]' ).addClass( 'tm-hidden-row' ).hide();
	}else{
		element.closest( '.planninguser' ).find( '.tm-user-add-contract' ).html( response.data.view );
	}
}

window.eoxiaJS.taskManager.follower.focusDateChamp = function( event ){
	jQuery( "#tm-radio-contract-date" ).prop("checked", true);
	jQuery( "#tm-radio-contract-actual" ).prop("checked", false);
	jQuery( '#tm-date-end-value' ).val( 'sql' );
}

window.eoxiaJS.taskManager.follower.updateHourPerDay = function( event ){

	var table_element = jQuery( this ).closest( '.tm-table-planning' );
	var day = jQuery( this ).parent().attr( 'data-day' );
	var element = jQuery( this ).val();

	var total_minute = 0;
	table_element.find( '.tm-contract-planning-from' ).each( function( e ){
		if( jQuery( this ).parent().attr( 'data-day' ) == day ){
			from = jQuery( this ).parent().find( '[data-work="from"]').val();
			to = jQuery( this ).parent().find( '[data-work="to"]').val();

			from = window.eoxiaJS.taskManager.follower.fromTimeToMinute( from );
			to = window.eoxiaJS.taskManager.follower.fromTimeToMinute( to );

			if( from > to ){
				from = jQuery( this ).parent().find( '[data-work="from"]').val( jQuery( this ).parent().find( '[data-work="to"]').val() );
				from = to;
			}
			var tempval = isNaN( to - from ) ? 0 : to - from;
			total_minute += tempval;
		}
	});

	table_element.find( '.table-header [data-title="' + day + '"] .tm-minute-per-day span' ).html( total_minute );

}

window.eoxiaJS.taskManager.follower.fromTimeToMinute = function fromTimeToMinute(time) {
  var timeArray = time.toString().split(':');
  var hours = parseInt(timeArray[0]);
  var minutes = parseInt(timeArray[1]);
  return (hours * 60) + minutes;
};

/**
 * Le callback en cas de réussite à la requête Ajax "general_settings".
 * Affiches le message de "success".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 6.4.0
 */
window.eoxiaJS.taskManager.follower.savedUsersProfile = function( element, response ) {
	element.addClass( 'button-success' );
	setTimeout( function() {
		element.removeClass( 'button-success' );
	}, 1000 );
	element.addClass( 'button-disable' );
	element.removeClass( 'button-valid' );
};
