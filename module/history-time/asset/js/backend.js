jQuery( document ).ready( function() {
	wpeo_history_time.init();
});

var wpeo_history_time = {
	init: function() {
		this.event();
	},

	event: function() {
		/** Créer un temps voulu */
		jQuery( document ).on('click', '.add-history-time', function() { wpeo_history_time.create( jQuery( this ).data( 'nonce' ),
			jQuery( this ).closest('.history-time-container').find( '.history-time-list' ),
			jQuery( this ).data( 'task-id' ),
			jQuery( 'input[name="due_date"]' ).val(), 
			jQuery( 'input[name="estimated_time"]' ).val() ); }
		);
		jQuery( document ).on('click', '.delete-history-time', function() { wpeo_history_time.delete( jQuery( this ).data( 'nonce' ), jQuery( this ).parent().parent(), jQuery( this ).parent().data( 'id' ) ); } );
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
			jQuery( '.wpeo-project-task[data-id="' + task_id + '"] .task-history-time' ).replaceWith( this.task_history_time );
		} );
	},

	delete: function( nonce, list_history_time, history_time ) {
		var data = {
			'action': 'delete_history_time',
			'history_time': history_time,
			'_ajax_nonce': nonce
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-task[data-id="' + this.to_task_id + '"] .task-history-time' ).replaceWith( this.task_history_time );
		} );

		jQuery( list_history_time ).find( '*[data-id="' + history_time + '"]' ).remove();
	}
};
