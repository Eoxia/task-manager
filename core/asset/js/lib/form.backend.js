window.task_manager.form = {};

window.task_manager.form.init = function() {
    window.task_manager.form.event();
};
window.task_manager.form.event = function() {
    jQuery( document ).on( 'click', '.submit-form', window.task_manager.form.sumbit_form );
};

window.task_manager.form.sumbit_form = function( event ) {
	var element = jQuery( this );
	var doAction = true;

	event.preventDefault();
	/** Méthode appelée avant l'action */
	if ( element.data( 'module' ) && element.data( 'before-method' ) ) {
		doAction = false;
		doAction = window.task_manager[element.data( 'module' )][element.data( 'before-method' )]( element );
	}

	if ( doAction ) {
    element.closest( 'form' ).ajaxSubmit({
        success: function( response ) {
			if ( response && response.data.module && response.data.callback ) {
				window.task_manager[response.data.module][response.data.callback]( element, response );
			}
            if ( response && response.success ) {
                if ( response.data.module && response.data.callback_success ) {
                    window.task_manager[response.data.module][response.data.callback_success]( element, response );
                }
            } else {
                alert( 'error' );
                if ( response.data.module && response.data.callback_error ) {
                    window.task_manager[response.data.module][response.data.callback_error]( element, response );
                }
            }
        }
    });
	}
};
