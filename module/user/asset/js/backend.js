/** wpeo_users/asset/js/backend.js wpeo_user */

jQuery( document ).ready(function() {
	wpeo_user.init();
});

var wpeo_user = {
	list_user_open: false,
	list_user_owner_open: false,
	list_user_id: [],

	init: function() {
		wpeo_user.event();
	},

	event: function() {
		jQuery( document ).on( 'click', '.wpeo-main-user .wpeo-user-add:not(.wpeo-confirm-user)', function( event ) { wpeo_user.open_list( event, jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-bloc-user', function( e ) { e.stopPropagation(); } );
		jQuery( document ).on( 'click', '.wpeo-edit-select-user li', function( event ) { wpeo_user.select_user( event, jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-main-user .wpeo-confirm-user', function( event ) { wpeo_user.hide_no_active( event ); } );

		jQuery( document ).on( 'click', 'body', function( event ) { wpeo_user.hide_no_active(); wpeo_user.close_list_owner(); } );

		/** Chosen */
		jQuery( '.wpeo-user-filter' ).chosen().change( function( evt, params ) { wpeo_user.change_filter( evt, params ); } );
		jQuery( '.wpeo-user-filter .chosen-choices' ).prepend( '<span class="dashicons dashicons-search"></span>' );

		/** Onwer event */
		jQuery( document ).on( 'click', '.wpeo-main-user .wpeo-user-owner:not(.wpeo-confirm-user)', function( event ) { wpeo_user.open_list_owner( event, jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-main-user .wpeo-user-owner.wpeo-confirm-user li', function( event ) { wpeo_user.select_user_owner( event, jQuery( this ) ); } );
	},

	/**
	 * Press on add user button open the template wit ajax request.
	 * The template allow to set users to the object
	 * If already open close it.
	 * If we have oepn it one time, just show it.
	 */
	open_list: function( event, element ) {
		var bloc_user = jQuery( element ).closest( '.wpeo-bloc-user' );
		var ul_user	= bloc_user.find( '.wpeo-ul-user' );
		var object_id = bloc_user.data( 'id' );
		wpeo_user.close_list_owner();
		wpeo_user.hide_no_active();
		wpeo_user.list_user_open = true;

		bloc_user.bgLoad();

		jQuery( element ).addClass( 'wpeo-confirm-user' );
		jQuery( element ).toggleClass( 'dashicons-plus dashicons-edit' );
		ul_user.addClass( 'wpeo-edit-select-user' );
		var data = {
			action: 'wpeo-view-user',
			object_id: object_id,
			_wpnonce: jQuery( element ).data( 'nonce' )
		};

		/** Animation **/
		/** On rentre */
		bloc_user.find( '.wpeo-ul-user li' ).addClass( 'bounceOutRight' );

		jQuery.eoajax( ajaxurl, data, function() {
			ul_user.html( this.template );
			bloc_user.bgLoad( 'stop' );
			bloc_user.find( '.wpeo-ul-user li' ).addClass( 'fadeInRightBig' );
		} );

		event.stopPropagation();
		event.preventDefault();
	},

	select_user: function( event, element ) {
		var bloc_user 	= jQuery( element ).closest( '.wpeo-bloc-user' );
		var object_id 	= bloc_user.data( 'id' );
		var user_id		= jQuery( element ).data( 'id' );
		jQuery( element ).toggleClass( 'active' );

		var data = {
			action: 'wpeo-update-user',
			object_id: object_id,
			user_id: user_id,
			selected: jQuery( element ).hasClass( 'active' ),
			_wpnonce: jQuery( element ).data( 'nonce' )
		};

		jQuery.eoajax( ajaxurl, data, function() {
			if ( this.affected_to_task ) {
				bloc_user.closest( '.wpeo-project-task' ).addClass( 'wpeo-affected-task' );
			} else {
				bloc_user.closest( '.wpeo-project-task' ).removeClass( 'wpeo-affected-task' );
			}
		} );
	},

	hide_no_active: function() {
		if ( wpeo_user.list_user_open ) {
			jQuery( '.wpeo-edit-select-user' ).each( function( index, element ) {
				var bloc_user = jQuery( element ).closest( '.wpeo-bloc-user' );

				var affected_id = [];
				bloc_user.find( '.wpeo-current-user li.active' ).each( function( index, element ) {
					affected_id.push( jQuery( element ).data( 'id' ) );
				} );
				bloc_user.closest( '.wpeo-project-task' ).attr( 'data-affected-id', affected_id.join() );

				bloc_user.find( '.wpeo-ul-user li' ).removeClass( 'fadeInRightBig' ).addClass( 'bounceOutRight' );

				setTimeout( function() {
				 	bloc_user.find( '.wpeo-ul-user li:not(.active)' ).remove();
				 	bloc_user.find( '.wpeo-ul-user li' ).removeClass( 'bounceOutRight' ).addClass( 'fadeInRightBig' );
				 }, 500, function() {
				 	bloc_user.find( '.wpeo-ul-user li' ).removeClass( 'fadeInRightBig' );
				 } );

				bloc_user.find( '.wpeo-confirm-user' ).removeClass( 'wpeo-confirm-user' );
				bloc_user.find( '.wpeo-edit-select-user' ).removeClass( 'wpeo-edit-select-user' );

				bloc_user.find( '.dashicons-edit' ).toggleClass( 'dashicons-plus dashicons-edit' );
			} );
		}
		wpeo_user.list_user_open = false;
	},

	/**
	 * Envoie une requête AJAX qui affiches un template avec tous les utilisateurs dont le role est
	 * administrateur sur WordPress. Ne renvoie pas l'utilisateur owner_id ou  ceux qui sont
	 * déjà afféctés à la tâche.
	 */
	open_list_owner: function( event, element ) {
		var bloc_user 	= jQuery( element ).closest( '.wpeo-bloc-user' );
		var task_id 	= bloc_user.data( 'id' );
		wpeo_user.hide_no_active();
		wpeo_user.close_list_owner();

		/** On rajoute une class à l'objet pour ne pas reéxécuter cette requête AJAX */
		bloc_user.find( '.wpeo-user-owner' ).addClass( 'wpeo-confirm-user wpeo-edit-select-owner' );

		var data = {
			action: 'wpeo-render-edit-owner-user',
			_wpnonce: jQuery( element ).data( 'nonce' ),
			task_id: task_id,
			owner_id: jQuery( element ).find( 'li' ).data( 'id' ),
		};

		jQuery.eoajax( ajaxurl, data, function( ) {
			wpeo_user.list_user_owner_open = true;
			bloc_user.find( '.wpeo-user-owner' ).append( this.template );
		} );
	},

	select_user_owner: function( event, element ) {
		var bloc_user 	= jQuery( element ).closest( '.wpeo-bloc-user' );
		var task_id 	= bloc_user.data( 'id' );

		var data = {
			action: 'wpeo-edit-task-owner-user',
			_wpnonce: jQuery( element ).data( 'nonce' ),
			task_id: task_id,
			owner_id: jQuery( element ).data( 'id' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			 bloc_user.find( '.wpeo-user-owner' ).html( bloc_user.find( '.wpeo-user-owner li.wpeo-user-' + this.owner_id ) );
			 bloc_user.closest( '.wpeo-project-task' ).attr( 'data-owner-id', this.owner_id );
			 wpeo_user.close_list_owner();
		} );
	},

	close_list_owner: function() {
		if( wpeo_user.list_user_owner_open) {
			jQuery( '.wpeo-edit-select-owner' ).each( function( index, element ) {
				var bloc_user 	= jQuery( element ).closest( '.wpeo-bloc-user' );
				bloc_user.find( '.wpeo-user-owner' ).html( bloc_user.find( '.wpeo-user-owner li:first' ) );
				bloc_user.find( '.wpeo-user-owner' ).removeClass( 'wpeo-confirm-user' );
			} );
		}
		wpeo_user.list_user_owner_open = false;
	},

	change_filter: function( event, params ) {
		list_user_id = jQuery( '.wpeo-user-filter' ).val();
		wpeo_global.filter();
	}
};
