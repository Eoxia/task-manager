// jQuery( document ).ready( function() {
// 	wpeo_point_frontend.init();
// });
//
// var pos_y =  316;
//
// var wpeo_point_frontend = {
// 	init: function() {
// 		wpeo_point_frontend.event();
// 	},
//
// 	event: function() {
// 		jQuery( document ).on( 'click', '.wpeo-task-account .task a', function( event ) { wpeo_point_frontend.click( event, jQuery( this ) ); } );
// 		jQuery( document ).on( 'click', '#wpeo-task-account-window form input[type="button"]', function() { wpeo_point_frontend.create_point_time( jQuery( this ) ); } );
// 		jQuery( document ).on( 'click', '.wpeo-point-time-edit-btn', function( event ) { wpeo_point_frontend.get_point_time( event, jQuery( this ) ); } );
// 		jQuery( document ).on( 'click', '.wpeo-ask-task', function( event ) { wpeo_point_frontend.ask_task( event, jQuery( this ) ); } );
// 		jQuery( document ).on( 'click', '#wpeo-window-ask-task form input[type="button"]', function() { wpeo_point_frontend.form_ask_task(); } );
// 		jQuery( document ).on( 'click', '.wpeo-point-title', function( event ) { wpeo_point_frontend.toggle_point( event, jQuery( this ) ); } );
//
//
// 		jQuery(window).scroll(function () {
// 			if ( jQuery( window ).scrollTop() > pos_y ) {
// 				jQuery( '.wpeo-task-account-window-content' ).addClass( 'fixed-window' );
// 			}
// 			else {
// 				jQuery( '.wpeo-task-account-window-content').removeClass( 'fixed-window' );
// 			}
//
// 		});
// 	},
//
// 	click: function( event, element ) {
// 		event.preventDefault();
//
// 		var task_id = jQuery( element ).closest( 'div.task' ).data( 'id' );
// 		var point_id = jQuery( element ).data( 'id' );
//
// 		// Requête AJAX pour récupérer tous les commentaires sur ce point
// 		var data = {
// 			action: 'load_frontend_dashboard_point',
// 			task_id: task_id,
// 			point_id: point_id,
// 			_wpnonce: jQuery( element ).data( 'nonce' ),
// 		}
//
// 		jQuery.eoajax( ajaxurl, data, function() {
// 			jQuery( '#wpeo-task-account-window' ).replaceWith( this.template );
// 			jQuery( window ).scroll();
// 		} );
// 	},
//
// 	create_point_time: function( element ) {
// 		jQuery.eoAjaxSubmit( jQuery( element ).closest( 'form' ), {}, function() {
// 			if( this.edit ) {
// 				jQuery( '#wpeo-task-account-window .wpeo-point-comment-' + jQuery( '.point_time_id' ).val() ).replaceWith( this.template );
// 			}
// 			else {
// 				jQuery( '#wpeo-task-account-window ul:first' ).before( this.template );
// 			}
//
// 			jQuery( '#wpeo-task-account-window form' ).clearForm();
// 		} );
// 	},
//
// 	/**
// 	 * Charges le point, et l'affiches dans le formulaire.
// 	 */
// 	get_point_time: function( event, element ) {
// 		var point_time_id = jQuery( element ).closest( '.wpeo-point-comment' ).data( 'id' );
//
// 		var data = {
// 			action: 'get_point_time',
// 			point_time_id: point_time_id,
// 			_wpnonce: jQuery( element ).data( 'nonce' ),
// 		};
//
// 		jQuery.eoajax( ajaxurl, data, function() {
// 			jQuery( '#wpeo-task-account-window form textarea' ).val( this.content );
// 			jQuery( '#wpeo-task-account-window form .point_time_id' ).val( point_time_id );
// 		} );
// 	},
//
// 	/**
// 	 * Quand on clique sur ce lien, ouvre une fenêtre qui permet de demander une tâche
// 	 */
// 	ask_task: function( event, element ) {
// 		event.preventDefault();
//
// 		jQuery( '#wpeo-window-ask-task' ).show();
// 	},
//
// 	/**
// 	 * Quand on confirme le formulaire, crée une nouvelle tâche pour le client.
// 	 */
// 	form_ask_task: function() {
// 		jQuery.eoAjaxSubmit( jQuery( '#wpeo-window-ask-task form' ), {}, function() {
// 			jQuery( '#wpeo-window-ask-task form' ).clearForm();
// 			jQuery( '#wpeo-window-ask-task' ).hide();
// 			if( this.edit ) {
// 				jQuery( '.wpeo-task-account > div[data-id="' + this.task_id + '"]' ).replaceWith( this.template );
// 			}
// 			else {
// 				jQuery( '.wpeo-task-account' ).prepend( this.template );
// 			}
// 		} );
// 	},
//
// 	toggle_point: function( event, element ) {
// 		event.preventDefault();
//
// 		jQuery( element ).find('.wpeo-point-toggle-arrow').toggleClass('dashicons-plus dashicons-minus');
// 			jQuery( element ).closest( '.wpeo-task-point' ).find( '.completed-point' ).toggle(200, function() {
//
// 			});
// 	}
// };
