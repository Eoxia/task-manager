/** Wp_projects/asset/js/backend.js wpeo_task */

var list_tag_id = [];
var list_user_id = undefined;
var load_all_task = false;

jQuery( document ).ready( function() {
	wpeo_global.init();
	wpeo_task.event();
	wpeo_point.event();
	wpeo_wpshop.init();

	/** Système pour gérer les loaders */
	jQuery.fn.bgLoad = function( action ) {
		return this.each( function() {
			if ( action == undefined )
				jQuery( this ).append( '<div class="mask-loader"><div class="sk-circle"><div class="sk-circle1 sk-child"></div><div class="sk-circle2 sk-child"></div><div class="sk-circle3 sk-child"></div><div class="sk-circle4 sk-child"></div><div class="sk-circle5 sk-child"></div><div class="sk-circle6 sk-child"></div><div class="sk-circle7 sk-child"></div><div class="sk-circle8 sk-child"></div><div class="sk-circle9 sk-child"></div><div class="sk-circle10 sk-child"></div><div class="sk-circle11 sk-child"></div><div class="sk-circle12 sk-child"></div></div></div>' );
			else {
				jQuery( this ).find( '.mask-loader' ).remove();
			}
		});
	};
});

var wpeo_global = {
	init: function() {
		jQuery( 'input[name="general-search"]' ).on( 'keypress', function( e ) {
 			if ( e.which == 13 ) {
 				wpeo_global.filter( jQuery( this ).val() );
 			}
		} );
		jQuery( '.open-search-filter' ).on( 'click', function() {
	 		jQuery( '.wpeo-header-search' ).toggleClass( 'active' );
		} );

		jQuery( '.wpeo-project-wrap .wpeo-add-point .wpeo-point-input > textarea' ).keypress( function( event ) {
			if ( event.which == 10 ) {
				wpeo_point.create( jQuery( event.currentTarget ) );
			}
		} );

		this.load();
	},

	load: function() {
		jQuery( '.isDate' ).datepicker( { dateFormat: 'yy-mm-dd' } );

		jQuery( '.wpeo-project-wrap .wpeo-task-point-sortable' ).sortable( {
			handle: '.dashicons-screenoptions',
			items: '.wpeo-task-li-point',
			update: function() {
				wpeo_point.edit_order( jQuery( this ) );
			}
		} );

		jQuery.each( jQuery( '.wpeo-task-auto-complete' ), function( key, element ) {
			jQuery( element ).autocomplete( {
		 		'source': 'admin-ajax.php?action=search&type=' + jQuery( element ).data( 'type' ),
		 		'select': function( event, ui ) {
		 			jQuery( element ).closest( 'div' ).find( 'input[name="element_id"]' ).val( ui.item.id );
		 		}
		 	} );
		} );

		jQuery.each( jQuery( '.wpeo-task-setting .task-color' ), function( index, element ) {
			var actualColor = '';
			jQuery( element ).children().each( function( index, subElement ) {
				if ( jQuery( subElement ).closest( '.wpeo-project-task' ).hasClass( jQuery( subElement ).attr( 'class' ) ) ) {
					actualColor = jQuery( subElement ).attr( 'class' );
				}
				jQuery( subElement ).click( function() {
					jQuery( this ).closest( '.wpeo-project-task' ).removeClass( actualColor );
					jQuery( this ).closest( '.wpeo-project-task' ).addClass( jQuery( this ).attr( 'class' ) );
					jQuery( this ).closest( '.wpeo-project-task' ).find( 'input[name="task[option][front_info][display_color]"]' ).val( jQuery( this ).attr( 'class' ) );
					wpeo_task.edit( this );
					actualColor = jQuery( this ).attr( 'class' );
				} );
			} );
		} );
	},

	filter: function( search = undefined ) {
		jQuery( '.wpeo-window-dashboard' ).hide();
		jQuery( '.wpeo-project-task' ).show();
		jQuery( '.wpeo-project-task.active' ).removeClass( 'active' );
		jQuery( '.wpeo-point-textarea.active' ).removeClass( 'active' );

		if ( jQuery( '.wpeo-button-all-task' ).hasClass( 'wpeo-button-active' ) ) {
			jQuery( '.wpeo-project-task.archive' ).hide();
		}

		if ( jQuery( '.wpeo-button-my-task' ).hasClass( 'wpeo-button-active' ) ) {
			jQuery( '.wpeo-project-task:not(.wpeo-project-task[data-owner-id="' + jQuery( '#wpeo_user_id' ).val() + '"])' ).hide();
			jQuery( '.wpeo-project-task.archive' ).hide();
		} else if ( jQuery( '.wpeo-button-assigned-task' ).hasClass( 'wpeo-button-active' ) ) {
			jQuery( '.wpeo-project-task:not(.wpeo-project-task[data-affected-id*="' + jQuery( '#wpeo_user_id' ).val() + '"])' ).hide();
			jQuery( '.wpeo-project-task.archive' ).hide();
		} else if ( jQuery( '.wpeo-button-archived-task' ).hasClass( 'wpeo-button-active' ) ) {
			jQuery( '.wpeo-project-task:not(.wpeo-project-task.archive)' ).hide();
		}

		if ( list_tag_id.length !== 0 ) {
			var tag_searcher = '';
			for( var i = 0; i < list_tag_id.length; i++ ) {
				tag_searcher += '[data-affected-tag-id*="' + list_tag_id[i] + '"]';
			}
			jQuery( '.wpeo-project-task:visible:not(.wpeo-project-task:visible' + tag_searcher + ')' ).hide();
		}

		if ( list_user_id != undefined ) {
			var owner_searcher = '';
			var affected_searcher = '';
			for( var i = 0; i < list_user_id.length; i++ ) {
				owner_searcher += '[data-owner-id*="' + list_user_id[i] + '"]';
				affected_searcher += '[data-affected-id*="' + list_user_id[i] + '"]';
			}
			jQuery( '.wpeo-project-task:visible:not(.wpeo-project-task:visible' + owner_searcher + '):not(.wpeo-project-task:visible' + affected_searcher + ')' ).hide();
		}

		if ( search != undefined ) {
			jQuery( '.wpeo-project-task:visible' ).each( function() {
				var synthesis_task = '';
				synthesis_task += jQuery( this ).text();
				jQuery( this ).find( 'input' ).each( function() {
					synthesis_task += jQuery( this ).val() + ' ';
				} );
				synthesis_task = synthesis_task.replace( /\s+\s/g, ' ' ).trim();
				if ( synthesis_task.search( new RegExp( search, 'i' ) ) == -1 ) {
					jQuery( this ).hide();
				}
			} );
		}

		wpeo_global.load();
	}
};

/** It's a Namespace. Use this for no conflict */
var wpeo_task = {
	grid: undefined,
	filter_class_task: [],
	array_marker: [],
	all_task: true,
	my_task: false,
	assigned_task: false,
	open_action_var: false,

	init: function() {
		this.event();
	},

	event: function() {
		/** Create task event */
		jQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-project-new-task', function( event ) { wpeo_task.create( event, jQuery( this ) ); } );
		/** Edit time estimated event */
		jQuery( '.wpeo-project-wrap' ).on( 'blur', '.wpeo-project-task-time-estimated', function() { wpeo_task.edit( jQuery( this ) ); } );
		jQuery( '.wpeo-project-wrap' ).on( 'blur', '.wpeo-project-task-title', function() { wpeo_task.edit( jQuery( this ) ); } );
		jQuery( '.wpeo-project-wrap' ).on( 'keydown', '.wpeo-project-task-title', function( e ) {
			if ( e.which == 13 ) {
				jQuery( this ).blur();
			}
		} );
		jQuery( '.wpeo-project-wrap' ).on( 'keyup', '.wpeo-project-task-title', function( e ) { wpeo_task.preview( jQuery( this ) ); } );
		/** Open action panel **/
		jQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-task-open-action', function() { wpeo_task.open_action( jQuery( this ).next( '.task-header-action' ) ); } );
		/** Open dashboard event */
		//JQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-project-task-title', function() { wpeo_task.open_window( jQuery( this ) ); } );
		jQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-task-open-dashboard', function() { wpeo_task.open_window( jQuery( this ) ); } );
		/** Archive */
		jQuery( document ).on( 'click', '.wpeo-send-task-to-archive', function() { wpeo_task.to_archive( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-send-task-to-unarchive', function() { wpeo_task.to_unarchive( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-send-mail', function() { wpeo_task.send_mail( jQuery( this ) ); } );
		/** Export */
		jQuery( document ).on( 'click', '.wpeo-export', function() { wpeo_task.export( jQuery( this ) ) } );
		jQuery(document).on('click', '.wpeo-export-comment', function() { wpeo_task.export_comment( jQuery( this ) ) } );
		jQuery(document).on('click', '.wpeo-project-export-all', function( event ) { wpeo_task.export_all( event, jQuery( this ) ); } );
		/** Trash */
		jQuery( document ).on( 'click', '.wpeo-send-task-to-trash', function( e ) { wpeo_task.delete( jQuery( this ) ); } );
		/** Envoyer une tâche vers un autre élément */
		jQuery( document ).on( 'click', '.wpeo-send-task-to-element', function( event ) { wpeo_task.send_to_element( event, jQuery( this ) ); } );

		/** Reload task */
		jQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-reload-task', function() { wpeo_task.reload_task( jQuery( this ) ); } );

		/** Time history task */
		jQuery( document ).on( 'click', '.wpeo-time-history-task', function() { wpeo_task.time_history_task( jQuery( this ) ); } );

		/** Marker */
		jQuery(document).on('click', '.task-marker', function() { wpeo_task.add_marker(jQuery(this)); } );

		/** Filter buttons */
		jQuery( document ).on( 'click', '.wpeo-button-all-task', function() { wpeo_task.display_all_task( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-button-my-task', function() { wpeo_task.display_my_task( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-button-assigned-task', function() { wpeo_task.display_assigned_task( jQuery( this ) ); } );

		/** Close window */
		jQuery( document ).on( 'click', '.wpeo-window-dashboard .dashicons-no-alt', function() { wpeo_global.filter(); } );

		/** WPShop - Charger un les tâches d'un client */
		/** Chosen WPShop */
		jQuery( '.wpeo-project-wrap .wpshop-customer-filter' ).chosen().change( function( evt, params ) { wpeo_task.change_customer_filter( evt, params ); } );

		//jQuery( document ).on( 'change', '.wpshop-customer-filter', function( event ) { wpeo_task.change_customer_filter( event, jQuery( this ) ); } );

		/** Pour recompiler le temps */
		jQuery( document ).on( 'click', '.wpeo-project-compile-time', function( event ) { wpeo_task.recompile_time( event, jQuery( this ) ); } );

		/** Liens rapide vers une tâche @TODO */
		// jQuery( document ).on( 'click', '.wpeo-project-last-comment .wpeo-last-point', function( event ) { wpeo_task.go_to( event, jQuery( this ) ); } );

		/** La date de fin d'une tâche */
		jQuery( document ).on( 'click', '.wpeo-update-due-date', function( event ) { wpeo_task.update_due_date( event, jQuery( this ) ); } );
	},

	/**
	 * ====================================== CREATE TASK =========================================
	 * When click on the new task button send a $.POST Request to the create_task action and
	 * preprend to the task list the template returned by the create_task method
	 */
	create: function( event, element ) {
		event.preventDefault();

		/** On lance le loader */
		jQuery( 'body' ).bgLoad();

		/** Construction du lien pour l'ajax */
		var url = jQuery( element ).attr( 'href' ).replace( 'admin-post.php', 'admin-ajax.php' );

		var data = {
			parent_id: jQuery( '.wpeo-task-post-parent' ).val(),
		};

		jQuery.eoajax( url, data, function() {
			jQuery( '.list-task:visible:first' ).prepend( this.template );

			/** Si jamais le message no-task est affiché on le cache */
			jQuery( '.wpeo-project-message-no-task' ).remove();

			/** Si jamais la fenêtre de droite est ouverte, on la cache */
			jQuery( '.wpeo-window-dashboard' ).hide();

			/** On defocus un point si il est sélectionné */
			jQuery( '.wpeo-point-textarea.active' ).removeClass( 'active' );

			/** On enlève le loader */
			jQuery( 'body' ).bgLoad( 'stop' );

			wpeo_global.load();
		} );
	},

	open_action: function( element ) {
		if( !wpeo_task.open_action_var ) {
			function open_action_func_click() {
				if( element.hasClass( 'active' ) ) {
					element.removeClass('active');
					jQuery( document ).off( 'click', open_action_func_click );
					wpeo_task.open_action_var = false;
				} else {
					element.addClass('active');
					wpeo_task.open_action_var = true;
				}
			}
			jQuery( document ).on( 'click', open_action_func_click );
		}
	},


	/**
	 * Quand on focus le titre de la tâche ou qu'on clique sur l'engrenage à droite d'une tâche,
	 * ouvre la fenêtre à droite qui permet de voir plus d'informations sur la tâche. Pour
	 * récupérer les informations de la tâche, une requête POST est envoyé avec l'id de la tâche.
	 */
	open_window: function( element ) {
		jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0);

		//wpeo_task.all_task = false;
		/** Remove wpeo-button-active class / Supprimes la class wpeo-button-active */
		//jQuery( '.wpeo-button-active' ).removeClass( 'wpeo-button-active' );

		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );

		jQuery( '.wpeo-project-task:not(.wpeo-project-task[data-id="' + task_id + '"])' ).hide();
		// wpeo_task.grid.masonry();

		jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).css( { 'display': 'flex', 'left': '-80px', 'opacity': 0.10 } ).bgLoad();
		jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).animate({
			'opacity': 1,
			'left': 0,
		}, 200);

		var data = {
			action: 'load_dashboard',
			element_id: task_id,
			global: 'task_controller',
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).replaceWith( this.template );

			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard *:not(.wpeo-window-background-avatar)' ).css('opacity', 0).animate({
				opacity: 1,
			}, 400);

			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).css( 'display', 'flex' );
			wpeo_global.load();
			// jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );
			// var height = parseInt( jQuery( '.wpeo-window-dashboard' ).height() + 200 );
			// jQuery( '#wpeo-tasks-metabox' ).css( 'height', height );
		} );
	},

	reload_task: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );
		jQuery( element ).closest( '.wpeo-project-task' ).bgLoad();

		var data = {
			action: 'reload_task',
			task_id: task_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-task[data-id="' + task_id + '"]' ).replaceWith( this.template );
			wpeo_global.load();
		} );
	},

	time_history_task: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );
		tb_show(
			jQuery( element ).data( 'title' ),
			jQuery( element ).data( 'url' ),
			false
		);
		jQuery( '#TB_load' ).on( 'remove', function () {
			jQuery( document ).trigger( "time_history_task", [ task_id ] );
		} );
	},

	/**
	 * ================================== TASK EDIT =========================================
	 * Quand on défocus le titre de la tâche, envoie une requête ajaxSubmit pour modifier le titre
	 * et/ou le temps estimé.
	 */
	edit: function( element ) {
		var bloc_task 	= jQuery( element ).closest( '.wpeo-project-task' );
		var task_id 	= bloc_task.data( 'id' );
		var form 		= jQuery( element ).closest( 'form' );
		var data = {
			action: 'edit_task',
			task: {
				id: task_id,
				parent_id: jQuery( '.wpeo-task-post-parent' ).val(),
			}
		};

		jQuery.eoAjaxSubmit( form, data, function() {
		} );
	},

	/**
	 * Met à jour le nom d'une tâche en temps réel dans la dashboard
	 */
	preview: function( element ) {
		jQuery( '.wpeo-window-dashboard header h2' ).text( jQuery( element ).val() );
	},

	/**
	 * Une fenêtre de confirmation s'affiche demandant si on est sur de vouloir effecteur cette
	 * action. Si l'utilisateur confirme, envoie une requête POST pour supprimer la tâche.
	 * Remove la tâche de l'interface avant la fin de la requête POST.
	 */
	delete: function( element ) {
		if ( confirm( wpeo_project_delete ) ) {
			var task_node = jQuery( element ).closest( '.wpeo-project-task' );

			var data = {
				action: 'delete_task',
				task_id:  task_node.data( 'id' ),
				_wpnonce: jQuery( element ).data( 'nonce' ),
			};

			task_node.remove();

			wpeo_global.filter();

			jQuery.eoajax( ajaxurl, data, function() {} );
		}
	},

	/**
	 * Modifier les options d'affichage de la tâche dans le thème : Utilisateurs assignées à la
	 * tâche et temps affecté. Envoie une requête POST pour mêttre à jour les informations.
	 */
	edit_setting: function( element ) {
		var data = {
			action: 'edit_task',
		};

		jQuery.eoAjaxSubmit( jQuery( '#wpeo-task-option form' ), data, function() {

		} );
	},

	/**
	 * Quand on clique sur le bouton archive, envoie une requête POST à archive_task.
	 */
	to_archive: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );

		var data = {
			action: 'archive_task',
			task_id: task_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-task[data-id="' + task_id + '"]' ).remove();
			//jQuery( '.wpeo-button-all-task' ).click();
		});
	},

	to_unarchive: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );

		var data = {
			action: 'unarchive_task',
			task_id: task_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-task[data-id="' + task_id + '"]' ).remove();
			//jQuery( '.wpeo-button-all-task' ).click();
		});
	},

	add_marker: function(element) {
		var task_bloc 			= jQuery( element ).closest( '.wpeo-project-task' );
		var task_id				= task_bloc.data( 'id' );
		var task_bloc_container = task_bloc.find( '.wpeo-project-task-container' );
		var marker 				= jQuery( element );

		if(!task_bloc.hasClass( 'wpeo-task-selected' )) {
			task_bloc.addClass( 'wpeo-task-selected' );

            /** Change the box shadow of the task to see that we selected */
			task_bloc_container.css( 'box-shadow', '0 0 0 4px #328DCF' );
			task_bloc_container.css( '-webkit-box-shadow', '0 0 0 4px #328DCF' );
			task_bloc_container.css( '-moz-box-shadow', '0 0 0 4px #328DCF' );
			task_bloc_container.css( '-o-box-shadow', '0 0 0 4px #328DCF' );

			marker.css( 'background', '#328DCF' );
			marker.css( 'border', '4px solid #fff' );

            /** Add task_id to the array_marker */
            this.array_marker.push(task_id);
		}
		else {
			task_bloc.removeClass('wpeo-task-selected');

            /** Get the index in the array for delete it */
            var index = this.array_marker.indexOf( task_id );

            if( index > -1 ) {
            	this.array_marker.splice( index, 1 );
            }

            /** Change the box shadow of the task to see that we selected */
            task_bloc_container.css( 'box-shadow', '1px 1px 5px 0px rgba(0,0,0,.2)' );
            task_bloc_container.css( '-webkit-box-shadow', '1px 1px 5px 0px rgba(0,0,0,.2)' );
            task_bloc_container.css( '-moz-box-shadow', '1px 1px 5px 0px rgba(0,0,0,.2)' );
            task_bloc_container.css( '-o-box-shadow', '1px 1px 5px 0px rgba(0,0,0,.2)' );

            marker.css( 'background', '#fff' );
            marker.css( 'border', '4px solid rgba(0,0,0,0.4)' );
		}

		if(this.array_marker.length == 0) {
			jQuery('.wpeo-project-export-all').addClass('disabled');
		}
		else {
			jQuery('.wpeo-project-export-all').removeClass('disabled');
		}
	},

	export: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );

		var data = {
			action: 'export_task',
			id: task_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function( ) {
			wpeo_task.download_export( this.url_to_file, false );
		});
	},

	send_mail: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );

		var data = {
			action: 'send_mail',
			id: task_id,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function( ) {

		} );
	},

	export_comment: function( element ) {
		var task_id = jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' );

		var data = {
			action: 'export_task',
			id: task_id,
			comment: true,
			_wpnonce: jQuery( element ).data( 'nonce' ),
		};

		jQuery.eoajax( ajaxurl, data, function( ) {
			wpeo_task.download_export( this.url_to_file, false );
		});
	},

	export_all: function( event, element ) {
		event.preventDefault();

		if( !jQuery( element ).hasClass( 'disabled' ) ) {
			var data = {
				action: 		'export_all_task',
				_wpnonce:			jQuery( element ).data( 'nonce' ),
				array_task_id: 	this.array_marker
			};

			jQuery.eoajax( ajaxurl, data, function() {
				wpeo_task.download_export( this.url_to_file, true );
			});
		}
	},

	download_export: function(url_to_file, deselect) {
		var url = jQuery('<a href="' + url_to_file + '" download="Export"></a>');
		jQuery('.wpeo-project-wrap').append(url);
		url[0].click();
		url.remove();

		if(deselect) {
			jQuery('.wpeo-task-selected').each(function() {
				jQuery(this).find('.task-marker').click();
			});
		}
	},

	display_all_task: function( element ) {
		jQuery( '.wpeo-button-active' ).removeClass( 'wpeo-button-active' );
		jQuery( element ).addClass( 'wpeo-button-active' );

		var data = {
			'action': 'load_all_task'
		};

		jQuery( 'body' ).bgLoad();
		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-wrap .list-task' ).replaceWith( this.template );
			jQuery( 'body' ).bgLoad('stop');
			wpeo_global.load();
		});
	},

	display_my_task: function( element ) {
		jQuery( '.wpeo-button-active' ).removeClass( 'wpeo-button-active' );
		jQuery( element ).addClass( 'wpeo-button-active' );
		wpeo_global.filter();
	},

	display_assigned_task: function( element ) {
		jQuery( '.wpeo-button-active' ).removeClass( 'wpeo-button-active' );
		jQuery( element ).addClass( 'wpeo-button-active' );
		wpeo_global.filter();
	},

	send_to_element: function( event, element ) {
		jQuery.eoAjaxSubmit( jQuery( element ).closest( 'form' ), { action: 'send_task_to_element' }, function() {
			jQuery( '.wpeo-project-task-' + this.task_id ).remove();
			jQuery( '.wpeo-window-dashboard' ).hide();
		} );
		return false;
	},

	update_due_date: function( event, element ) {
		jQuery.eoAjaxSubmit( jQuery( element ).closest( 'form' ), { action: 'update_due_date' }, function() {
		} );
		return false;
	}

};

var wpeo_point = {
	init: function() {
		this.event();
	},

	event: function() {
		/** Créer un point */
		jQuery( document ).on( 'blur click keyup paste keydown', '.wpeo-add-point .wpeo-point-textarea', function(event) { wpeo_point.add_point_display(event, jQuery(this), false); } );
		//jQuery( document ).on( 'keyup', '.wpeo-add-point .wpeo-point-textarea', function(event) { wpeo_point.key_up_add_point(event, jQuery(this)); } );
		jQuery( document ).on( 'click', '.wpeo-task-add-new-point', function() { wpeo_point.add_point_display( { 'which': 0 }, jQuery(this), true); } );

		jQuery( document ).on( 'click', '.wpeo-send-point-to-trash', function() { wpeo_point.delete( jQuery( this ) ); } );

		jQuery( document ).on( 'click', '.wpeo-done-point', function( e ) { wpeo_point.switch_completed( e, jQuery( this ) ); } );
		jQuery( document ).on( 'blur paste', '.wpeo-point-input .wpeo-point-textarea', function() { wpeo_point.edit( jQuery( this ) ); } );
		/*jQuery( document ).on( 'blur', '.wpeo-point-input .wpeo-point-textarea', function() { wpeo_point.edit( jQuery( this ) ); } );
		jQuery( document ).on( 'keyup', '.wpeo-point-input .wpeo-point-textarea', function() { wpeo_point.preview( jQuery( this ) ); } );*/
		jQuery( document ).on( 'click', '.wpeo-task-li-point .wpeo-point-input .wpeo-point-textarea', function() { wpeo_point.open_window( jQuery( this ) ); } );

		jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle p', function( event ) { wpeo_point.toggle_completed( event, jQuery( this ) ); } );

		jQuery( document ).on( 'click', '#wpeo-task-form-point-time .wpeo-open-point-time-form', function() { wpeo_point.update_form( jQuery( this ), true ); } );
		jQuery( document ).on( 'click', '#wpeo-task-form-point-time .wpeo-submit', function() { wpeo_point.create_point_time( jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-point-comment .wpeo-submit', function() { wpeo_point.edit_point_time_form( jQuery( this ) ); } );
		jQuery( document ).on( 'keypress', '.wpeo-point-comment, .wpeo-point-time-elapsed', function( event ) { if( event.which == 10 ) { jQuery( this ).parent().find( '.wpeo-submit' ).click(); } } );
		jQuery( document ).on( 'click', '.wpeo-send-point-time-to-trash', function( event ) { wpeo_point.delete_point_time( event, jQuery( this ) ); } );
		jQuery( document ).on( 'click', '.wpeo-point-time-edit-btn', function( event ) { wpeo_point.edit_point_time( event, jQuery( this ) ); });

		jQuery( document ).on( 'click', '.wpeo-send-point-to-task', function( event ) { wpeo_point.send_point_to_task( event, jQuery( this ) ); } );
		jQuery( document ).on( 'keypress', '.wpeo-window-dashboard #wpeo-point-option input[type="text"]', function( event ) { wpeo_point.key_up_text_send_point( event, jQuery( this ) ); } );
	},

	add_point_display: function( event, element, click ) {
		var task_bloc = jQuery( element ).closest( '.wpeo-project-task' );
		var placeholder = task_bloc.find( '.wpeo-point-textarea-placeholder' );
		if( click ) {
			var add_btn = element;
			element = task_bloc.find('.wpeo-add-point .wpeo-point-textarea');
		} else {
			var add_btn = task_bloc.find( '.wpeo-task-add-new-point' );
		}
		var add = function() {
			wpeo_point.create( element );
		};
		if( element.text().length == 0 ) {
			placeholder.show();
			add_btn.off( 'click' );
			task_bloc.find('.wpeo-task-add-new-point').css('opacity', 0.4);
		} else {
			placeholder.hide();
			if( ( event.which == 13 && window.ctrlKeyHold ) || click ) { add(); element.html( '' ); wpeo_point.add_point_display( { 'which': 0 }, element, false ); }
			task_bloc.find('.wpeo-task-add-new-point').css('opacity', 1);
		}
		if( event.which == 17 ) {
			window.ctrlKeyHold = true;
		} else {
			window.ctrlKeyHold = false;
		}
	},

	/**
	 * Quand on clique sur le dashicons (+), envoie un ajaxSubmit pour crée le point.
	 * Désactive cette event jusqu'a la fin de cette requête pour ne pas spammer le bouton.
	 */
	create: function( element ) {
		var form = jQuery( element ).closest( 'form' );
		var bloc_task = jQuery( element ).closest( '.wpeo-project-task' );

		var input = document.createElement('input');
		input.type = 'hidden';
		input.id = "temporary_input";
		input.name = 'point[content]';
		input.value = form.find('*[name="point[content]"]').html();
		if( input.value.length == 0 ) {
			return false;
		}
		form.append(input);

		jQuery( '.wpeo-project-wrap' ).off( 'click', '.wpeo-task-add-new-point' );
		jQuery( element ).closest( '.wpeo-project-task' ).bgLoad();

		jQuery.eoAjaxSubmit( form, {}, function() {
			jQuery( element ).closest( '.wpeo-project-task' ).bgLoad( 'stop' );

			/** Réactive les events */
			// jQuery( '.wpeo-project-wrap' ).on( 'click', '.wpeo-task-add-new-point', function() { wpeo_point.create( jQuery( this ) ); } );
			// jQuery( '.wpeo-project-wrap' ).on( 'keypress', '.wpeo-add-point textarea', function( event ) { wpeo_point.key_up( event, jQuery( this ) ); } );

			jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-point:first' ).append( this.template );
			bloc_task.find( '.wpeo-task-li-point:last' ).css( { 'opacity': 0, 'left': -20 } ).animate( {
				opacity: 	1,
				left: 		0
			}, 300 );

			var taskPointCompletedTotal = jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-count-completed' ).text();
			taskPointCompletedTotal = taskPointCompletedTotal.split('/');
			taskPointCompletedTotal[1]++;
			jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-count-completed' ).text( taskPointCompletedTotal[0] + '/' + taskPointCompletedTotal[1] );

			/** On met à jour l'interface */
			wpeo_global.load();
			//form.clearForm();
		} );
		jQuery( '#temporary_input' ).remove();
	},

	/**
	 * Quand on clique sur supprimer un point. Demande une confirmation puis envoie une requête
	 * POST et remove le point. On met également à jour l'interface au niveau des minutes.
	 */
	delete: function( element ) {
		if ( confirm ( wpeo_project_delete_comment ) ) {
			var point_bloc 	= jQuery( element ).closest( '.wpeo-task-li-point' );
			var task_bloc	= point_bloc.closest( '.wpeo-project-task' );
			var point_id	= point_bloc.data( 'id' );
			var object_id	= point_bloc.closest( '.wpeo-task-point' ).find( '.wpeo-object-id' ).val();
			var completed 	= point_bloc.find( '.wpeo-done-point' ).is( ':checked' );

			/** Animation quand le point disparait */
			point_bloc.animate( {
				opacity: 	0,
				left: 		"-=20",
				height: 	0,
			}, 300, function() {
				jQuery( this ).hide();
			} );

			var data = {
				action: 'delete_point',
				object_id: object_id,
				point_id: point_id,
				_wpnonce: jQuery( element ).data( 'nonce' ),
			};

			jQuery.eoajax( ajaxurl, data, function() {
				jQuery( '.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-time-manage' ).html( this.task_header_information );

				jQuery( '.wpeo-project-task-' + object_id + ' .wpeo-project-task-time' ).text( this.task.option.time_info.elapsed );

				var taskPointCompletedTotal = jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-count-completed' ).text();
				taskPointCompletedTotal = taskPointCompletedTotal.split('/');
				taskPointCompletedTotal[1]--;

				if( completed ) {
					taskPointCompletedTotal[0]--;
				}

				jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-count-completed' ).text( taskPointCompletedTotal[0] + '/' + taskPointCompletedTotal[1] );


				// wpeo_task.grid.masonry();
				jQuery( '.wpeo-window-dashboard' ).hide();
			} );
		}
	},

	edit: function( element ) {
		var form = jQuery( element ).closest( '.form' );

		var input = document.createElement('input');
	    input.type = 'hidden';
		input.id = "temporary_input";
	    input.name = 'point[content]';
	    input.value = form.find('*[name="point[content]"]').html();
    	form.append(input);

		jQuery.eoAjaxSubmit( form, { 'action': 'edit_point' }, function() {
		} );

		jQuery( '#temporary_input' ).remove();
	},

	preview: function( element ) {
		jQuery( '.wpeo-window-dashboard .wpeo-point-title' ).text( jQuery( element ).val() );
	},

	open_window: function( element ) {
		/** Spécial digirisk condition **/
		if ( jQuery( element ).closest( '#TB_ajaxContent' ).length === 0 ) {
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0);

			jQuery( '.wpeo-point-textarea.active' ).removeClass( 'active' );

			jQuery( element ).addClass( 'active' );
			//
			//wpeo_task.all_task = false;
			/** Remove wpeo-button-active class / Supprimes la class wpeo-button-active */
			//jQuery( '.wpeo-button-active' ).removeClass( 'wpeo-button-active' );

			var bloc_task 	= jQuery( element ).closest( '.wpeo-project-task' );
			var task_id 	= bloc_task.data( 'id' );
			var point_id 	= jQuery(element).closest( '.wpeo-task-li-point' ).data( 'id' );

			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).css( { 'display': 'flex', 'left': '-80px', 'opacity': 0.10 } ).bgLoad();
			jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).animate({
				'opacity': 1,
				'left': 0,
			}, 200);

			jQuery( '.wpeo-project-task:not(.wpeo-project-task[data-id="' + task_id + '"])' ).hide();
			jQuery( '.wpeo-project-task[data-id="' + task_id + '"]' ).addClass( 'active' );
			// wpeo_task.grid.masonry();

			var data = {
				action: 'load_dashboard',
				element_id: point_id,
				global: 'point_controller',
				_wpnonce: jQuery( element ).data( 'nonce' ),
			};

			jQuery.eoajax( ajaxurl, data, function() {
				jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).replaceWith( this.template );
				//jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );
				jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard *' ).css('opacity', 0).animate({
					opacity: 1,
				}, 400);
				jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).attr( 'data-id', point_id );
				jQuery( element ).closest( '.list-task' ).find( '.wpeo-window-dashboard' ).css( 'display', 'flex' );
				wpeo_global.load();
			} );
		}
	},

	edit_order: function( element ) {
		var order_point_id 	= [];
		var object_id 		= jQuery(element).closest( '.wpeo-project-task' ).data( 'id' );

		jQuery(element).find('.wpeo-task-li-point').each(function() {
			order_point_id.push( jQuery( this ).data('id' ) );
		});

		var data = {
			action: 'edit_order_point',
			object_id: object_id,
			order_point_id: order_point_id,
		};

		jQuery.post( ajaxurl, data, function() {
			// wpeo_task.grid.masonry();
		});


	},

	switch_completed: function( e, element ) {
		wpeo_point.edit( element );

		var bloc_task = jQuery( element ).closest( '.wpeo-project-task' );
		var bloc = jQuery( element ).closest( 'form' );
		var count_completed = bloc_task.find( '.wpeo-task-count-completed' ).text();
		var count_completed = count_completed.split( '/' );

		if( jQuery( element ).is( ':checked' ) ) {
			bloc_task.find( '.wpeo-task-point-completed:first' ).append( bloc );
			count_completed[0]++;
		}
		else {
			bloc_task.find( '.wpeo-task-point:first' ).append( bloc );
			count_completed[0]--;
		}

		bloc_task.find( '.wpeo-task-count-completed' ).text(count_completed[0] + '/' + count_completed[1]);
		// wpeo_task.grid.masonry();
		wpeo_point.refresh_sortable();
	},

	toggle_completed: function( event, element ) {
		event.preventDefault();

		jQuery( element ).find( '.wpeo-point-toggle-arrow' ).toggleClass( 'dashicons-plus dashicons-minus' );
		jQuery( element ).closest( '.wpeo-task-point-use-toggle' ).find( 'ul:first' ).toggle( 200, function() {

		});

		if ( jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed img' ).length > 0 ) {
			var data = {
				action: 'load_completed_point',
				_wpnonce: jQuery( element ).data( 'nonce' ),
				task_id: jQuery( element ).closest( '.wpeo-project-task' ).data( 'id' ),
			};

			jQuery.eoajax( ajaxurl, data, function() {
				jQuery( element ).closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).html( this.template );
			} );
		}
	},

	refresh_sortable: function() {
		jQuery( '.wpeo-project-wrap .wpeo-task-point-sortable' ).sortable( {
			handle: '.dashicons-screenoptions',
			items: '.wpeo-task-li-point',
			update: function() {
				wpeo_point.edit_order( jQuery( this ) );
			}
		} );
	},

	update_form: function( element, open ) {
	},

	create_point_time: function(element) {
		var point_id = jQuery(element).closest('form').find('.wpeo-point-id').val();

		jQuery( '.wpeo-window-dashboard' ).bgLoad();

		jQuery.eoAjaxSubmit( jQuery(element).closest('form'), { action: 'create_point_time' }, function() {
			jQuery( '.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-time-manage' ).html( this.task_header_information );
			jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );
			jQuery( '.wpeo-point-no-comment' ).hide();
			if( this.edit ) {
				jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-comment-' + this.point_time_id ).replaceWith( this.template );
			}
			else {
				jQuery(element).closest( '.wpeo-window-dashboard').find( '#wpeo-task-point-history' ).prepend( this.template );
			}
			jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-project-task-time').text( this.task.option.time_info.elapsed );
			jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-li-point[data-id="' + this.point.id + '"] .wpeo-time-in-point').text( this['point']['option']['time_info']['elapsed'] );

			jQuery(element).closest('form').find('.wpeo-point-time-id').val( 0 );
			jQuery(element).closest('form').find('.wpeo-point-comment').val( '' );
			jQuery(element).closest('form').find( 'input.wpeo-point-time-elapsed').val( 15 );

			jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-elapsed-time' ).text( this['point']['option']['time_info']['elapsed'] );
			var current_number_point_time = jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-list-point-time' ).text();
			current_number_point_time++;
			jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-list-point-time' ).text( current_number_point_time );

			wpeo_point.update_form( jQuery( this ), false );
		});
	},

	edit_point_time_form: function( element ) {
		jQuery( '.wpeo-window-dashboard' ).bgLoad();

		jQuery.eoAjaxSubmit( jQuery(element).closest('form'), { action: 'create_point_time' }, function() {
			jQuery( '.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-time-manage' ).html( this.task_header_information );
			jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );
			jQuery( '.wpeo-point-no-comment' ).hide();

			jQuery('.wpeo-point-comment-' + this.time.id ).replaceWith( this.template );

			jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-project-task-time').text( this.task.option.time_info.elapsed );
			jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-li-point[data-id="' + this.point.id + '"] .wpeo-time-in-point').text( this['point']['option']['time_info']['elapsed'] );

			jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-elapsed-time' ).text( this['point']['option']['time_info']['elapsed'] );
		});
	},

	edit_point_time: function( event, element ) {
		event.preventDefault();
		jQuery( '.wpeo-window-dashboard' ).bgLoad();

		var point_time_id = jQuery( element ).closest( '.wpeo-point-comment' ).data( 'id' );

		var data = {
			action: 'get_point_time',
			_wpnonce: jQuery( element ).data( 'nonce' ),
			point_time_id:	point_time_id,
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-window-dashboard' ).bgLoad( 'stop' );

			jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-comment-' + this.point_time_id ).replaceWith( this.template );
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
				jQuery( '.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-time-manage' ).html( this.task_header_information );
				jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-project-task-time').text( this.task.option.time_info.elapsed );
				jQuery('.wpeo-project-task[data-id="' + this.task.id + '"] .wpeo-task-li-point[data-id="' + this.point.id + '"] .wpeo-time-in-point').text( this['point']['option']['time_info']['elapsed'] );
				jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-elapsed-time' ).text( this['point']['option']['time_info']['elapsed'] );

				var current_number_point_time = jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-list-point-time' ).text();
				current_number_point_time--;
				jQuery(element).closest( '.wpeo-window-dashboard').find( '.wpeo-point-list-point-time' ).text( current_number_point_time );
			});
		}
	},

	send_point_to_task: function( event, element ) {
		jQuery.eoAjaxSubmit( jQuery( element ).closest( 'form' ), { action: 'send_point_to_task' }, function() {
			jQuery( '.wpeo-project-task[data-id="' + this.to_task_id + '"] .wpeo-task-point:first').append( this.template );
			jQuery( '.wpeo-project-task[data-id="' + this.current_task_id + '"] .wpeo-task-li-point[data-id="' + this.point_id + '"]' ).remove();

			jQuery( '.wpeo-project-task[data-id="' + this.to_task_id + '"] .wpeo-project-task-time').html( this.task_time );
			jQuery( '.wpeo-project-task[data-id="' + this.current_task_id + '"] .wpeo-project-task-time').html( this.current_task_time );
		} );

		return false;
	},

	key_up_text_send_point: function( event, element ) {
		if( event.which == 13 ) {
			jQuery( '.wpeo-window-dashboard #wpeo-point-option .wpeo-send-point-to-task' ).click();
			event.preventDefault();
			return false;
		}
	},

	pagination: function( event, element ) {
		event.preventDefault();

		var page_id = jQuery( element ).data( 'page' );
		jQuery( '.wpeo-project-last-comment' ).bgLoad();

		var data = {
			'action': 'load_last_comment',
			'page': page_id,
		};

		jQuery.eoajax( ajaxurl, data, function() {
			jQuery( '.wpeo-project-last-comment' ).replaceWith( this.template );
		} );
	}
};

var wpeo_wpshop = {
	init: function() {
		jQuery('.clean-wpshop-search').click(function() {
				jQuery('.auto-complete-user').val('');

				jQuery( '.list-task:not(.list-task:first)' ).remove();
				jQuery( '.task-wpshop' ).remove();
				jQuery( '.list-task-title' ).remove();
				wpeo_global.filter();
		});

		jQuery('.auto-complete-user').autocomplete( {
			source: ajaxurl + '?action=search_customer',
			minLength: 0,
			'select': function( event, ui ) {
				jQuery( '.wpeo-window-dashboard' ).hide();
				jQuery( '.wpeo-project-task' ).hide();

				var data = {
					'action': 'load_task_wpshop',
					'user_id': ui.item.id,
				};

				jQuery.eoajax( ajaxurl, data, function() {
					jQuery( '.wpeo-project-wrap' ).append( this.template );
				} );
			}
		} );
	}
};
