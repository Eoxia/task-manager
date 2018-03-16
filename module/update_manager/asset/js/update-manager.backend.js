/**
 * Initialise l'objet "updateManager" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.6.0
 * @version 1.6.0
 */

window.eoxiaJS.taskManager.updateManager = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.6.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.updateManager.init = function() {
	window.eoxiaJS.taskManager.updateManager.requestUpdate();
	window.addEventListener( 'beforeunload', window.eoxiaJS.taskManager.updateManager.safeExit );
};

window.eoxiaJS.taskManager.updateManager.requestUpdateFunc = {
	endMethod: []
};
window.eoxiaJS.taskManager.updateManager.requestUpdate = function( args ) {
	var redirectAction  = jQuery( 'input[name="action_when_update_finished"]' ).val();
	var key             = jQuery( 'input.current-key' ).val();
	var versionToUpdate = jQuery( 'input[name="version_available[]"]:first' ).val();
	var action          = jQuery( 'input[name="version[' + versionToUpdate + '][action][]"]:first' ).val();
	var description     = jQuery( 'input[name="version[' + versionToUpdate + '][description][]"]:first' ).val();
	var data = {
		action: action,
		versionToUpdate: versionToUpdate,
		args: args
	};

	if ( versionToUpdate ) {
		if ( ( args && ! args.more ) || ! args ) {
			jQuery( '.log' ).append( '<li><h2>' + taskManager.updateManagerInProgress.replace( '{{ versionNumber }}', versionToUpdate ) + '</h2></li>' );
		}

		if ( action ) {
			if ( args && args.moreDescription ) {
				description += args.moreDescription;
			}

			jQuery( '.log' ).append( '<li>' + description + taskManager.updateManagerloader + '</li>' );

			jQuery.post( ajaxurl, data, function( response ) {
				jQuery( '.log img' ).remove();

				if ( response.data.done ) {
					if ( response.data.args && response.data.args.doneDescription ) {
						jQuery( '.log' ).append( '<li>' + response.data.args.doneDescription + '</li>' );
						delete response.data.args.doneDescription;
					}

					jQuery( 'input[name="version[' + versionToUpdate + '][action][]"]:first' ).remove();
					jQuery( 'input[name="version[' + versionToUpdate + '][description][]"]:first' ).remove();

					if ( 0 == jQuery( 'input[name="version[' + versionToUpdate + '][action][]"]:first' ).length ) {
						delete response.data.args;

						jQuery( 'input[name="version_available[]"]:first' ).remove();
					}
					if ( 0 == jQuery( 'input[name="version_available[]"]:first' ).length ) {
						delete response.data.args;

						window.eoxiaJS.taskManager.updateManager.redirect( { action: redirectAction }, true );
					} else {
						if ( response.data.args.resetArgs ) {
							delete response.data.args;
						}
						window.eoxiaJS.taskManager.updateManager.requestUpdate( response.data.args );
					}
				} else {
					window.eoxiaJS.taskManager.updateManager.requestUpdate( response.data.args );
				}
			} )
			.fail( function( error, t, r ) {
				jQuery( '.log' ).append( '<li>' + taskManager.updateManagerErrorOccured.replace( '{{ versionNumber }}', versionToUpdate ) + '</li>' );
				window.eoxiaJS.taskManager.updateManager.redirect( { action: redirectAction, error_version: versionToUpdate, error_status: error.status, error_text: error.responseText }, false );
			} );
		}
	}

	if ( jQuery( '.no-update' ).length ) {
		window.eoxiaJS.taskManager.updateManager.redirect( { action: redirectAction }, true );
	}
};

/**
 * Redirection vers la page principale de l'application une fois les mises à jour terminées.
 *
 * @param  {[type]} key [description]
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.updateManager.redirect = function( requestArgs, redirect ) {
	jQuery.post( ajaxurl, requestArgs, function( response ) {
		jQuery( '.log' ).append( '<li>' + response.data.message + '</li>' );
		window.removeEventListener( 'beforeunload', window.eoxiaJS.taskManager.updateManager.safeExit );
		if ( redirect ) {
			window.location = response.data.url;
		}
	});
};

/**
 * Vérification avant la fermeture de la page si la mise à jour est terminée.
 *
 * @since 1.6.0
 * @version 1.6.0
 *
 * @param  {WindowEventHandlers} event L'évènement de la fenêtre.
 * @return {string}
 */
window.eoxiaJS.taskManager.updateManager.safeExit = function( event ) {
	var confirmationMessage = taskManager.updateManagerconfirmExit;
	if ( taskManager.updateManagerUrlPage === event.currentTarget.adminpage ) {
		event.returnValue = confirmationMessage;
		return confirmationMessage;
	}
};
