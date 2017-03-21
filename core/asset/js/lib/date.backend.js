window.task_manager.date = {};

window.task_manager.date.init = function() {
	jQuery( document ).on( 'click', 'input.date', function( e ) {
		jQuery( this ).datepicker( {
			dateFormat: 'dd/mm/yy'
		} );

		jQuery( this ).datepicker( 'show' );
	} );
};
