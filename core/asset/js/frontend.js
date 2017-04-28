jQuery( document ).ready( function() {
  wpeo_point.event();
  wpeo_task.event();
} );

var wpeo_task = {
  event: function() {
    jQuery( document ).on( 'click', '.wpeo-ask-task', function( event ) { wpeo_task.ask_task( event, jQuery( this ) ); } );
    jQuery( document ).on( 'click', '#wpeo-window-ask-task form input[type="button"]', function() { wpeo_task.form_ask_task( jQuery( this ) ); } );

		jQuery( document ).on( 'keyup', '.wps-section-content .task-search', function( event ) { wpeo_task.search_key( jQuery( this ), event ); } );
		jQuery( document ).on( 'click', '.wps-section-content .search-button', function( event ) { wpeo_task.search_in( jQuery( this ) ); } );
  },

  ask_task: function( event, element ) {
    event.preventDefault();

    jQuery( '#wpeo-window-ask-task' ).slideToggle();
  },

  form_ask_task: function( element ) {
    jQuery.eoAjaxSubmit( jQuery( '#wpeo-window-ask-task form' ), {}, function() {
      jQuery( '#wpeo-window-ask-task form' ).clearForm();
      jQuery( '#wpeo-window-ask-task' ).hide();
      if( this.edit ) {
        jQuery( '.wpeo-project-wrap .grid-item div[data-id="' + this.task_id + '"]' ).replaceWith( this.template );
      }
      else {
        jQuery( '.wpeo-project-wrap .grid-item' ).prepend( this.template );
      }
    } );
  },

	search_key: function( element, event ) {
		if ( event.keyCode === 13 ) {
			jQuery( '.wps-section-content .search-button' ).click();
		}
	},

	search_in: function( element ) {
		if ( 0 == jQuery( element ).closest( '.wps-section-content' ).find( '.task-search' ).val().length ) {
			jQuery( '.grid-item .task' ).show();
		} else {
			jQuery( '.grid-item .task:visible' ).each( function() {
				var synthesis_task = '';
				synthesis_task += jQuery( this ).text();
				jQuery( this ).find( 'input' ).each( function() {
					synthesis_task += jQuery( this ).val() + ' ';
				} );
				synthesis_task = synthesis_task.replace( /\s+\s/g, ' ' ).trim();

				if ( synthesis_task.search( new RegExp( jQuery( element ).closest( '.wps-section-content' ).find( '.task-search' ).val(), 'i' ) ) == -1 ) {
					jQuery( this ).hide();
				}
			} );
		}
	}
};

var wpeo_point = {
  event: function() {
    jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle', function( event ) { wpeo_point.toggle_completed( event, jQuery( this ) ); } );
    jQuery( document ).on( 'click', '#wpeo-task-form-point-time .wpeo-submit', function() { wpeo_point.create_point_time( jQuery( this ) ); } );
    jQuery( document ).on( 'click', '.point-content', function() { wpeo_point.point_class_active( jQuery( this ) ); } );
    jQuery( document ).on( 'click', '.wpeo-point-comment .wpeo-submit', function() { wpeo_point.edit_point_time_form( jQuery( this ) ); } );
    jQuery( document ).on( 'click', '.wpeo-point-time-edit-btn', function( event ) { wpeo_point.edit_point_time( event, jQuery( this ) ); });
    jQuery( document ).on( 'click', '.wpeo-send-point-time-to-trash', function( event ) { wpeo_point.delete_point_time( event, jQuery( this ) ); } );
		jQuery( document ).on( 'scroll', '', function( event ) { wpeo_point.fixed_window( event, jQuery( this ) ); } );
  },

	point_class_active: function( element ) {
		jQuery( '.point-content.active' ).removeClass('active');
		jQuery( element ).addClass('active');
	},

	fixed_window: function( element ) {
		var element = jQuery( '.wpeo-task-account-window-content' );
		if ( ! element && element == undefined ) return;
		if ( jQuery( window ).scrollTop() <= jQuery( '.wpeo-project-wrap' ).offset().top ) {
			element.removeClass('fixe');
		} else {
			element.addClass('fixe');
		}
	},

  open_window: function( element ) {
		element = jQuery( element ).find( '.wpeo-point-input' );
    jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0);

		var bloc_task 	= jQuery( element ).closest( '.wpeo-project-task' );
		var task_id 	= bloc_task.data( 'id' );
		var point_id 	= jQuery( element ).data( 'id' );

		jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard' ).css( { 'display': 'flex', 'left': '-80px', 'opacity': 0.10 } );
		jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard' ).animate({
			'opacity': 1,
			'left': 0,
		}, 200);

		var data = {
			action: 'load_dashboard_frontend',
			element_id: point_id,
			global: 'point_controller',
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard' ).replaceWith( this.template );
			jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0).animate({
				opacity: 1,
			}, 400);
			jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard' ).attr( 'data-id', point_id );
			// jQuery( element ).closest( '.wps-section-content' ).find( '.wpeo-window-dashboard' ).css( 'display', 'flex' );
			wpeo_point.fixed_window();
		} );
  },

  create_point_time: function(element) {
    var point_id = jQuery(element).closest('form').find('.wpeo-point-id').val();

    jQuery.eoAjaxSubmit( jQuery(element).closest('form'), { action: 'create_point_time' }, function() {
      jQuery( '.wpeo-point-no-comment' ).hide();
			jQuery( element ).closest( 'form' ).find( '.wpeo-point-comment' ).val( '' );
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
     jQuery( element ).closest('.wpeo-task-point-use-toggle').next('.completed-point').toggleClass( 'hidden' );
  },

};
