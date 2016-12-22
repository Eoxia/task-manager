window.task_manager.form = {};

window.task_manager.form.init = function() {
    window.task_manager.form.event();
};
window.task_manager.form.event = function() {
    jQuery( document ).on( 'click submit_form', '.submit-form, *[class*="submit-form-"]', window.task_manager.form.sumbit_form );
};

window.task_manager.form.sumbit_form = function( event ) {
	var element = jQuery( this );
	var callback = element.attr( 'class' ).match( /submit-form-.*?[^ ]+/g );
    event.preventDefault();
	if ( callback ) {
		callback = callback[0].split( '-' );
		if ( callback[2] && callback[3] && window.task_manager[callback[2]][callback[3]] ) {
			window.task_manager[callback[2]][callback[3]]( element );
		}
	}
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
};
