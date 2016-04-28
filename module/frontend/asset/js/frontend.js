jQuery( document ).ready( function() {
  wpeo_custom.event();
} );

var wpeo_custom = {
  event: function() {
    jQuery( '.wpeo-task-li-point' ).click( function() { wpeo_custom.open_window( jQuery( this ) ); } );
  },

  open_window: function( element ) {
    var bloc_task 	= jQuery( element ).closest( '.wpeo-project-task' );
		var task_id 	= bloc_task.data( 'id' );
		var point_id 	= jQuery( element ).closest( '.wpeo-task-li-point' ).data( 'id' );

    var data = {
			action: 'load_dashboard_point_custom',
			task_id: task_id,
			point_id: point_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '#wpeo-task-window' ).html( this.template );
			jQuery( '#wpeo-task-window' ).attr( 'data-id', point_id );
		} );
  }
};
