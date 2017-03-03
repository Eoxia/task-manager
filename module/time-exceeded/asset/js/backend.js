jQuery( document ).ready( function() {
	wpeo_history_time.init();
});

var wpeo_history_time = {
	init: function() {
		this.event();
	},

	event: function() {
		/** Créer un temps voulu */
		jQuery( document ).on( 'time_history_task', function() {
			jQuery( '#TB_window' ).addClass( 'time_history_task_tb_window' );
			jQuery( 'input[name="due_date"]' ).datepicker( { dateFormat: 'yy-mm-dd' } );
		} );
		jQuery( document ).on('click', '.add-history-time', function() { wpeo_history_time.create( jQuery( this ).data( 'nonce' ),
			jQuery( this ).closest('.history-time-container').find( '.history-time-list' ),
			jQuery( this ).data( 'task-id' ),
			jQuery( 'input[name="due_date"]' ).val(),
			jQuery( 'input[name="estimated_time"]' ).val() ); }
		);
		jQuery( document ).on('click', '.delete-history-time', function() { wpeo_history_time.delete( jQuery( this ).data( 'nonce' ),
		jQuery( this ).closest('.history-time-list'),
		jQuery( this ).closest('.list-element').data( 'id' ) ); } );
		jQuery( document ).on( 'keypress', '.history-time-new', function( event ) {
			if( event.which == 13 ) {
				jQuery( '.add-history-time' ).click();
			}
		} );
	},

	create: function( nonce, list_history_time, task_id, due_date, estimated_time ) {
		var data = {
			'action': 'create_history_time',
			'due_date': due_date,
			'estimated_time': estimated_time,
			'task_id': task_id,
			'_ajax_nonce': nonce
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( list_history_time ).prepend( this.template );
			jQuery( '.wpeo-project-task[data-id="' + task_id + '"] .wpeo-task-time-manage' ).html( this.task_header_information );
		} );
	},

	delete: function( nonce, list_history_time, history_time ) {
		var data = {
			'action': 'delete_history_time',
			'history_time': history_time,
			'_ajax_nonce': nonce
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-task[data-id="' + this.to_task_id + '"] .wpeo-task-time-manage' ).html( this.task_header_information );
		} );

		jQuery( list_history_time ).find( '*[data-id="' + history_time + '"]' ).remove();
	}
};
