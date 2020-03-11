window.eoxiaJS.taskManagerFrontend.frontendSupport = {};

window.eoxiaJS.taskManagerFrontend.frontendSupport.init = function() {
	window.eoxiaJS.taskManagerFrontend.frontendSupport.event();
};

window.eoxiaJS.taskManagerFrontend.frontendSupport.event = function() {
	jQuery( document ).on( 'click', '.wpeo-ask-task', window.eoxiaJS.taskManagerFrontend.frontendSupport.slideAskTask );
	jQuery( document ).on( 'keyup', '.wps-section-content .task-search', window.eoxiaJS.taskManagerFrontend.frontendSupport.searchKey );
	jQuery( document ).on( 'click', '.wps-section-content .search-button', window.eoxiaJS.taskManagerFrontend.frontendSupport.searchIn );
	jQuery( document ).on( 'click', '.wps-section-content .button.blue', window.eoxiaJS.taskManagerFrontend.frontendSupport.closePopup );
	jQuery( document ).on( 'keydown', '.wps-section-content .popup input', window.eoxiaJS.taskManagerFrontend.frontendSupport.preventDefaultForm );
};

window.eoxiaJS.taskManagerFrontend.frontendSupport.slideAskTask = function( event ) {
	event.preventDefault();
	jQuery( '#wpeo-window-ask-task' ).slideToggle();
};

window.eoxiaJS.taskManagerFrontend.frontendSupport.searchKey = function( event ) {
	if ( 13 === event.keyCode ) {
		jQuery( '.wps-section-content .search-button' ).click();
	}
};

window.eoxiaJS.taskManagerFrontend.frontendSupport.searchIn = function( event ) {
	var element = jQuery( this );
	if ( 0 == jQuery( this ).closest( '.wps-section-content' ).find( '.task-search' ).val().length ) {
		jQuery( '.list-task .wpeo-project-task' ).show();
	} else {
		jQuery( '.list-task .wpeo-project-task:visible' ).each( function() {
			var synthesis_task = '';
			synthesis_task += jQuery( this ).text();
			jQuery( this ).find( 'input' ).each( function() {
				synthesis_task += jQuery( this ).val() + ' ';
			} );
			synthesis_task = synthesis_task.replace( /\s+\s/g, ' ' ).trim();

			if ( synthesis_task.search( new RegExp( jQuery( element ).closest( '.wps-section-content' ).find( '.task-search' ).val(), 'i' ) ) == -1 ) {
				jQuery( this ).hide();
			}
		} );
	}
};

window.eoxiaJS.taskManagerFrontend.frontendSupport.createdTicket = function( triggeredElement, response ) {
	if ( response.data.edit ) {
		triggeredElement.closest( '.wpeo-project-wrap' ).find( '.tm-list-ticket .tm-project[data-id="' + response.data.project_id + '"]  .project-header' ).after( response.data.task_view );
	} else {
		triggeredElement.closest( '.wpeo-project-wrap' ).find( '.tm-list-ticket h3').after( response.data.project_view );
		triggeredElement.closest( '.wpeo-project-wrap' ).find( '.tm-list-ticket .tm-project[data-id="' + response.data.project_id + '"]  .project-header' ).after( response.data.task_view );
	}
	//jQuery( '.wpeo-modal .modal-container .modal-content' ).html( response.data.success_view );
};

/**
 * Le callback en cas de réussite à la requête Ajax "open_popup_create_ticket".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.2.0
 * @version 1.2.0
 */
window.eoxiaJS.taskManagerFrontend.frontendSupport.openedPopupCreateTicket = function( triggeredElement, response ) {
	jQuery( '.wpeo-project-wrap .popup .container.loading' ).removeClass( 'loading' );
	jQuery( '.wpeo-project-wrap .popup .container .content' ).html( response.data.view );
};

/**
 * Fermes la popup
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManagerFrontend.frontendSupport.closePopup = function() {
	jQuery( '.wpeo-project-wrap .popup.active' ).removeClass( 'active' );
};

/**
 * Empèches l'évènement de la touche 'entrer' dans le formulaire de la popup pour faire une demande.
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @param  {KeyEvent} event L'état du clavier.
 * @return {void}
 */
window.eoxiaJS.taskManagerFrontend.frontendSupport.preventDefaultForm = function( event ) {
	if ( 13 === event.keyCode ) {
		event.preventDefault();
	}

	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.popup' ).find( '.action-input' ).click();
	}
};

/**
 * Les dernières activitéés faites sur le support.
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @param  {SpanElement} triggeredElement L'élément déclenchant la requête Ajax.
 * @param  {Object} response              les données renvoyées par la requêtes Ajax.
 * @return {[type]}
 */
window.eoxiaJS.taskManagerFrontend.frontendSupport.loadedLastActivity = function( triggeredElement, response ) {
	jQuery( '.wps-section-content .popup .content' ).html( response.data.view );
	jQuery( '.wps-section-content .popup .container' ).removeClass( 'loading' );
	jQuery( '.wps-section-content .popup .title' ).html( response.data.title_popup );
	jQuery( '.wps-section-content .popup .load-more-history' ).show();
	jQuery( '.wps-section-content .popup .offset-event' ).val( response.data.offset );
	jQuery( '.wps-section-content .popup .last-date' ).val( response.data.last_date );
};

window.eoxiaJS.taskManagerFrontend.frontendSupport.sendedResponseToSupport = function( triggeredElement, response ) {
	triggeredElement.closest( '.wpeo-project-wrap' ).find( '.comment-new' ).after( response.data.view );
	triggeredElement.closest( '.wpeo-project-wrap' ).find( '.comment-new textarea' ).val( '' );
};
