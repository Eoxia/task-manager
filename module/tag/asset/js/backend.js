/** wpeo_tag/asset/js/backend.js */

jQuery( document ).ready( function() {
	wpeo_tag.init();
});

var wpeo_tag = {
	tags_is_open: false,
	current_task_id: 0,
	archived_task: false,

	init: function() {
		this.event();
	},

	event: function() {
		jQuery( document ).on( 'click', '.wpeo-tag-wrap:not(.wpeo-tag-wrap-edit)', function(e) { wpeo_tag.load_tag( e, jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-tag-wrap-edit', function( e ) { e.stopPropagation(); } );
		jQuery( document ).on( 'click', 'body', function( e ) { if( wpeo_tag.current_task_id != 0 ) wpeo_tag.edit_tag( e ); } );

		jQuery( document ).on( 'click', '.wpeo-tag-edit-tag-btn', function( e ) { wpeo_tag.edit_tag( e ); } );
		jQuery( document ).on( 'click', '.wpeo-tag-wrap-edit li:not(.wpeo-tag-edit-tag-btn)', function( e ) { wpeo_tag.select_tag(e, jQuery( this ) ); } );

		jQuery( document ).on( 'click', '.wpeo-button-archived-task', function() { wpeo_tag.display_archived_task( jQuery( this ) ); } );

		/** Chosen */
		/*jQuery( document ).on( 'click', '.chosen-results .no-results', function( e ) { wpeo_tag.create_tag( e, jQuery( this ) ); } );
		jQuery( '.wpeo-tag-filter' ).chosen( { no_results_text: wpeo_project_create_tag + " : ", } );
		jQuery( '.wpeo-tag-filter' ).chosen().change( function( evt, params ) { wpeo_tag.change_filter( evt, params ); } );
		jQuery( '.chosen-choices' ).prepend( '<span class="dashicons dashicons-search"></span>' );*/
		jQuery( document ).on( 'click', '.wpeo-tag-search', function() { wpeo_tag.change_filter( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-new-tag-search-btn', function() { wpeo_tag.create_tag( jQuery( 'input[name="new_tag"]' ).val() ); } );
	},

	load_tag: function( e, element ) {
		var tag_wrap 		= jQuery( element ).closest( '.wpeo-tag-wrap' );
		var bloc_tag		= jQuery( element );
		var object_id 		= jQuery( element ).data( 'id' );
		if( wpeo_tag.tags_is_open && wpeo_tag.current_task_id != 0 ) {
			wpeo_tag.edit_tag( 0 );
		}
		wpeo_tag.current_task_id = object_id;
		var list_tag_id		= jQuery( element ).data( 'listtagid' );
		bloc_tag.addClass( 'wpeo-tag-wrap-edit' );
		jQuery( '.wpeo-window-dashboard .wpeo-tag-wrap' ).addClass('wpeo-tag-wrap-edit');

		tag_wrap.bgLoad();

		var data = {
			action: 'wpeo-view-all-tag',
			object_id: object_id,
			list_tag_id: list_tag_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			bloc_tag.html( this.template );
			tag_wrap.bgLoad( 'stop' );
			wpeo_tag.tags_is_open = true;
		} );

		e.preventDefault();
		e.stopPropagation();
	},

	/**
	 * Quand on clique sur le crayon ou en dehors de la tâche, lance une requête POST pour
	 * actualiser le bloc des tâche afin d'avoir que les catégories affectées.
	 */
	edit_tag: function( e ) {
		var task = jQuery( '.wpeo-project-task[data-id="' + wpeo_tag.current_task_id + '"]' );
		var bloc_tag 	= task.find( '.wpeo-tag-wrap' );
		var object_id 	= wpeo_tag.current_task_id;

		bloc_tag.bgLoad();

		var data = {
			action: 'view_task_tag',
			object_id: object_id,
		};

		jQuery.eoajax( ajaxurl, data, function() {
			bloc_tag.bgLoad( 'stop' );
			bloc_tag.replaceWith( this.template );
			//bloc_tag.removeClass( 'wpeo-tag-wrap-edit' );

			//jQuery( '.wpeo-window-dashboard .wpeo-tag-wrap' ).replaceWith(this.template);
			//jQuery( '.wpeo-window-dashboard .wpeo-tag-wrap' ).removeClass('wpeo-tag-wrap-edit');

			task.attr( 'data-affected-tag-id', task.find( '.wpeo-tag-wrap' ).data( 'listtagid' ).join() );
		} );

		wpeo_tag.current_task_id = 0;
		wpeo_tag.tags_is_open = false;
	},

	/**
	 * Quand on clique sur une catégorie, sélectionne celle-ci et envoie une requête POST
	 * pour ajouter le term à la tâche.
	 */
	select_tag: function( e, element ) {
		var bloc_tag 	= jQuery( element ).closest( '.wpeo-tag-wrap' );
		var object_id	= bloc_tag.data( 'id' );

		jQuery( element ).toggleClass( 'wpeo-tag-tag-selected' );
		var selected 	= jQuery( element ).hasClass( 'wpeo-tag-tag-selected' );

		var element_class = jQuery( element ).data( 'slug' );

		if( selected ) {
			bloc_tag.closest( '.wpeo-project-task' ).addClass( element_class );
		}
		else {
			bloc_tag.closest( '.wpeo-project-task' ).removeClass( element_class );
		}

		var data = {
			action: 'edit_task_tag',
			object_id: object_id,
			tag_id:	jQuery( element ).data( 'id' ),
			selected: selected,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			if( selected )
				jQuery( '.wpeo-project-task-' + object_id ).addClass( 'wpeo-' + element_class );
			else
				jQuery( '.wpeo-project-task-' + object_id ).removeClass( 'wpeo-' + element_class );
		} );
	},

	display_archived_task: function( element ) {
		jQuery( '.wpeo-button-active' ).removeClass( 'wpeo-button-active' );
		jQuery( element ).addClass( 'wpeo-button-active' );

		var data = {
			'action': 'load_archived_task'
		};

		jQuery( 'body' ).bgLoad();

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( 'body' ).bgLoad( 'stop' );
			jQuery( '.wpeo-project-wrap .list-task' ).replaceWith( this.template );
			wpeo_global.load();
		});
	},

	create_tag: function( tag_name ) {
		var data = {
			action: 'create-tag',
			tag_name: tag_name,
		};

		jQuery.eoajax( ajaxurl, data, function() {
			/*jQuery( '.wpeo-tag-filter' ).append( '<option value="' + this.slug + '">' + this.name + '</option>' );
			jQuery( '.wpeo-tag-filter' ).trigger( "chosen:updated" );*/
			jQuery( '.wpeo-new-tag-search' ).before( '<li class="wpeo-tag-search" data-tag-id="' + this.slug + '">' + this.name + '</li>' );
			wpeo_tag.change_filter( this.slug );
		} );
	},

	change_filter: function( element ) {
		var tag_id = element.data( 'tag-id' );

		element.toggleClass('active');

		if( list_tag_id.indexOf( tag_id ) == -1 ) {
			list_tag_id.push( tag_id );
		} else {
			list_tag_id.splice( list_tag_id.indexOf( tag_id ), 1);
		}
		wpeo_global.filter();
	}
};
