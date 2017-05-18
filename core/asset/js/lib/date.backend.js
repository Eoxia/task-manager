window.task_manager.date = {};

window.task_manager.date.init = function() {
	jQuery( document ).on( 'click', 'input.date', function( e ) {
		jQuery( this ).datepicker( {
			dateFormat: 'dd/mm/yy'
		} );

		jQuery( this ).datepicker( 'show' );
	} );

	jQuery( document ).on( 'click', 'input.date-time', function( e ) {
		jQuery( this ).datetimepicker( {
			'lang': 'fr',
			'format': 'd/m/Y h:i:s'
		} );
		jQuery( this ).datetimepicker( 'show' );
	} );
};
