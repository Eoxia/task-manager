window.task_manager.form = {};

window.task_manager.form.init = function() {
  window.task_manager.form.event();
};
window.task_manager.form.event = function() {
  jQuery( document ).on( 'click', '.submit-form', window.task_manager.form.sumbit_form );
};

window.task_manager.form.sumbit_form = function( event ) {
	event.preventDefault();
  var element = jQuery( this );
	element.closest( '.wp-digi-bloc-loader' ).addClass( 'wp-digi-bloc-loading' );
  element.closest( 'form' ).ajaxSubmit( {
    success: function( response ) {
			element.closest( '.wp-digi-bloc-loader' ).removeClass( 'wp-digi-bloc-loading' );

      if ( response && response.success ) {
        if ( response.data.module && response.data.callback_success ) {
          window.task_manager[response.data.module][response.data.callback_success]( element, response );
        }
      }
      else {
        alert('error');
        if ( response.data.module && response.data.callback_error ) {
          window.task_manager[response.data.module][response.data.callback_error]( element, response );
        }
      }
    }
  } );
}
