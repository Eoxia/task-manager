jQuery( document ).ready( function() {
  wpeo_point.event();
  wpeo_task.event();
} );

var wpeo_task = {
  event: function() {
    jQuery( document ).on( 'click', '.wpeo-ask-task', function( event ) { wpeo_task.ask_task( event, jQuery( this ) ); } );
    jQuery( document ).on( 'click', '#wpeo-window-ask-task form input[type="button"]', function() { wpeo_task.form_ask_task(); } );
  },

  ask_task: function( event, element ) {
    event.preventDefault();

    jQuery( '#wpeo-window-ask-task' ).show();
  },

  form_ask_task: function() {
    jQuery.eoAjaxSubmit( jQuery( '#wpeo-window-ask-task form' ), {}, function() {
      jQuery( '#wpeo-window-ask-task form' ).clearForm();
      jQuery( '#wpeo-window-ask-task' ).hide();
      if( this.edit ) {
        jQuery( '.wpeo-task-account > div[data-id="' + this.task_id + '"]' ).replaceWith( this.template );
      }
      else {
        jQuery( '.wpeo-task-account' ).prepend( this.template );
      }
    } );
  },
};

var wpeo_point = {
  event: function() {
    jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle p', function( event ) { wpeo_point.toggle_completed( event, jQuery( this ) ); } );
    jQuery( document ).on( 'click', '.wpeo-point-input', function() { wpeo_point.open_window( jQuery( this ) ); } );
    jQuery( document ).on( 'click', '#wpeo-task-form-point-time .wpeo-submit', function() { wpeo_point.create_point_time( jQuery( this ) ); } );
    jQuery( document ).on( 'click', '.wpeo-point-comment .wpeo-submit', function() { wpeo_point.edit_point_time_form( jQuery( this ) ); } );
    jQuery( document ).on( 'click', '.wpeo-point-time-edit-btn', function( event ) { wpeo_point.edit_point_time( event, jQuery( this ) ); });
    jQuery( document ).on( 'click', '.wpeo-send-point-time-to-trash', function( event ) { wpeo_point.delete_point_time( event, jQuery( this ) ); } );

  },

  open_window: function( element ) {
    jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0);

		var bloc_task 	= jQuery( element ).closest( '.wpeo-project-task' );
		var task_id 	= bloc_task.data( 'id' );
		var point_id 	= jQuery(element).closest( '.wpeo-task-li-point' ).data( 'id' );

		jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).css( { 'display': 'flex', 'left': '-80px', 'opacity': 0.10 } );
		jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).animate({
			'opacity': 1,
			'left': 0,
		}, 200);

		jQuery( '.wpeo-project-task:not(.wpeo-project-task[data-id="' + task_id + '"])' ).hide();

		var data = {
			action: 'load_dashboard_frontend',
			element_id: point_id,
			global: 'point_controller',
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).replaceWith( this.template );
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0).animate({
				opacity: 1,
			}, 400);
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).attr( 'data-id', point_id );
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).css( 'display', 'flex' );
		} );
  },

  create_point_time: function(element) {
    var point_id = jQuery(element).closest('form').find('.wpeo-point-id').val();

    jQuery.eoAjaxSubmit( jQuery(element).closest('form'), { action: 'create_point_time' }, function() {
      jQuery( '.wpeo-point-no-comment' ).hide();
      if( this.edit ) {
        jQuery( '.wpeo-point-comment-' + this.point_time_id ).replaceWith( this.template );
      }
      else {
        jQuery( '#wpeo-task-point-history' ).prepend( this.template );
      }
    });
  },

  edit_point_time_form: function( element ) {
		// jQuery( '.wpeo-window-dashboard' ).bgLoad();

		jQuery.eoAjaxSubmit( jQuery(element).closest('form'), { action: 'create_point_time' }, function() {
			// jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );
			jQuery( '.wpeo-point-no-comment' ).hide();

			jQuery('.wpeo-point-comment-' + this.time.id ).replaceWith( this.template );
		});
	},

	edit_point_time: function( event, element ) {
		event.preventDefault();
		// jQuery( '.wpeo-window-dashboard' ).bgLoad();

		var point_time_id = jQuery( element ).closest( '.wpeo-point-comment' ).data( 'id' );

		var data = {
			action: 'get_point_time',
			_wpnonce: jQuery( element ).data( 'nonce' ),
			point_time_id:	point_time_id,
		};

		jQuery.eoajax( ajaxurl, data, function() {
			// jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );

			jQuery( '.wpeo-window-dashboard .wpeo-point-comment-' + this.point_time_id ).replaceWith( this.template );
		});
	},

  delete_point_time: function(event, element) {
    event.preventDefault();

    if( confirm( wpeo_project_delete_comment_time ) ) {

      var point_id = jQuery( element ).closest( '.wpeo-window-dashboard' ).data( 'id' );
      var point_time_id = jQuery( element ).closest( '.wpeo-point-comment' ).data( 'id' );
      jQuery( element ).closest( 'ul' ).remove();

      var data = {
        action: 'delete_point_time',
        _wpnonce: jQuery( element ).data( 'nonce' ),
        point_time_id: point_time_id,
        point_id: point_id,
      };

      jQuery.eoajax( ajaxurl, data, function() {
        jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-project-task-time').text( this.task.option.time_info.elapsed );
        jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-li-point[data-id="' + this.point.id + '"] .wpeo-time-in-point').text( this['point']['option']['time_info']['elapsed'] );
        jQuery( '.wpeo-window-dashboard .wpeo-point-elapsed-time' ).text( this['point']['option']['time_info']['elapsed'] );

        var current_number_point_time = jQuery( '.wpeo-window-dashboard .wpeo-point-list-point-time' ).text();
        current_number_point_time--;
        jQuery( '.wpeo-window-dashboard .wpeo-point-list-point-time' ).text( current_number_point_time );
      });
    }
  },

  toggle_completed: function( event, element ) {
    event.preventDefault();

     jQuery( element ).find('.wpeo-point-toggle-arrow').toggleClass('dashicons-plus dashicons-minus');
         jQuery( element ).closest('.wpeo-task-point-use-toggle').find('ul:first').toggle(200, function() {
          //  wpeo_task.grid.masonry();
         });
  },

}
