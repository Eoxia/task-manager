/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.newTask = {};

window.eoxiaJS.taskManager.newTask.draggedElement;

window.eoxiaJS.taskManager.newTask.init = function() {
	window.eoxiaJS.taskManager.newTask.event();
};

window.eoxiaJS.taskManager.newTask.event = function() {
	jQuery( '.tm-wrap' ).on( 'blur', '.table-projects .table-type-project  .project-title', window.eoxiaJS.taskManager.newTask.editTitle );
	jQuery( '.tm-wrap' ).on( 'click', '.table-type-project .project-toggle-task', window.eoxiaJS.taskManager.newTask.togglePoints );
	jQuery( '.tm-wrap' ).on( 'click', '.table-type-project .project-state .dropdown-item',  window.eoxiaJS.taskManager.newTask.displayState );
	jQuery( '.tm-wrap' ).on( 'click, focus', '.list-task .table-cell div[contenteditable="true"]', function(e) {
		jQuery( '.cell-focus .table-cell.cell-focus' ).removeClass( 'cell-focus' );
		jQuery( this ).closest( '.table-cell' ).addClass( 'cell-focus' );
	});
	jQuery( '.tm-wrap' ).on( 'blur', '.list-task .table-cell.cell-focus', function(e) {
		jQuery( this ).removeClass( 'cell-focus' );
	});
	jQuery( '.list-task' ).on( 'scroll', window.eoxiaJS.taskManager.newTask.stickyAction );
	window.eoxiaJS.taskManager.newTask.stickyAction();

	jQuery( '.tm-wrap' ).on( 'click', '.table-projects .cell-affiliated', function(e) {
		e.stopPropagation();
		window.eoxiaJS.taskManager.task.displayInputTextParent(e, jQuery( this ).find( '.add_parent_to_task'));
	});


	window.eoxiaJS.taskManager.newTask.clickTags();
	window.eoxiaJS.taskManager.newTask.clickUsers();

	jQuery( document ).on( 'dragstart', '.table-header .table-cell', function( e ) {
		window.eoxiaJS.taskManager.newTask.draggedElement = e.currentTarget;
		e.currentTarget.style.border = 'dashed';
		e.originalEvent.dataTransfer.setData("text/plain", e.target.id );
	} );

	jQuery( document ).on( 'dragend', '.table-header .table-cell', function( event ) {
		event.preventDefault();
	} );

	jQuery( document ).on( 'dragover', '.table-header .table-cell', function( event ) {
		event.preventDefault();
		return false;
	} );

	jQuery( document ).on( "dragenter", '.table-header .table-cell', function( event ) {
		if (jQuery( event.target ).hasClass( 'table-cell' ) ) {
			jQuery( event.target )[0].style.border = "dashed";

			var target      = jQuery( event.target );
			var targetKey  = target.data('key');
			if (target.hasClass( 'table-cell' ) ) {
				var cells = jQuery( '.table-row:not(.table-header) .table-cell[data-key=' + targetKey + ']' ).addClass( 'border-active' );
			}
		}
	} );

	jQuery( document ).on( "dragleave", '.table-header .table-cell', function( event ) {
		if (jQuery( event.target ).hasClass( 'table-cell' ) ) {
			jQuery( event.target )[0].style.border = "none";

			var target      = jQuery( event.target );
			var targetKey  = target.data('key');
			jQuery( '.table-row:not(.table-header) .table-cell[data-key=' + targetKey + ']' ).removeClass( 'border-active' );


		}
	} );

	jQuery( document ).on( 'drop', '.table-header .table-cell', function( ev ) {
		ev.preventDefault();

		if ( ev.stopPropagation() ) {
			ev.stopPropagation();
		}

		var currentElement = jQuery( window.eoxiaJS.taskManager.newTask.draggedElement );
		var newElement     = currentElement.clone();
		var target         = jQuery( ev.target );

		if ( ! jQuery( ev.target ).hasClass( 'table-cell' ) ) {
			return;
		}

		var draggedKey = currentElement.data( 'key' );
		var targetKey  = target.data('key');

		var cells = jQuery( '.table-row:not(.table-header) .table-cell[data-key=' + draggedKey + ']' );

		cells.each(function() {
			var tmp = jQuery( this ).clone();

			jQuery(this).closest('.table-row').find('.table-cell[data-key=' + targetKey + ']').after(tmp[0].outerHTML);
			jQuery(this).remove();
		});

		jQuery( ev.target ).after( newElement[0].outerHTML );
		jQuery( ev.target )[0].style.border = "none";

		window.eoxiaJS.taskManager.newTask.draggedElement.remove();

		window.eoxiaJS.taskManager.newTask.refreshKey();

		jQuery( '.table-row:not(.table-header) .table-cell[data-key=' + targetKey + ']' ).removeClass( 'border-active' );


		return false;
	});
};

window.eoxiaJS.taskManager.newTask.clickTags = function() {
	jQuery( '.tm-wrap' ).one( 'click', '.table-projects .project-categories', function(e) {
		jQuery(this).find('.action-attribute').trigger('click');
	});
}

window.eoxiaJS.taskManager.newTask.clickUsers = function() {
	jQuery( '.tm-wrap' ).one( 'click', '.table-projects .project-users', function(e) {
		jQuery(this).find('.action-attribute').trigger('click');
	});
}

window.eoxiaJS.taskManager.newTask.editTitle = function() {
	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action  = 'edit_title';
	data.task_id = element.closest( '.table-row' ).data( 'id' );
	data.title   = element.html();
	window.eoxiaJS.loader.display( element.closest( 'div' ) );
	window.eoxiaJS.request.send( element, data );
};

window.eoxiaJS.taskManager.newTask.togglePoints = function() {
	const projectID = jQuery( this ).closest( '.table-row' ).data( 'id' );
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	if ( jQuery( this ).find( '.fas' ).hasClass( 'fa-angle-down' ) ) {

		jQuery( this ).find( '.fas' ).removeClass( 'fa-angle-down' ).addClass( 'fa-angle-right' );
		jQuery( '.table-type-task[data-post-id=' + projectID + ']' ).slideUp(400, function() {
			jQuery( this ).remove();
		});
		jQuery( '.table-type-comment[data-post-id=' + projectID + ']' ).slideUp(400, function() {
			jQuery( this ).remove();
		});

		jQuery( '.table-type-project[data-id=' + projectID + '] .cell-sticky div[data-action="edit_point"]' ).attr( 'data-toggle', false );

	} else {
		var data = {};

		data.action   = 'load_point';
		data._wpnonce = element.data( 'nonce' );
		data.task_id  = element.data( 'id' );
		window.eoxiaJS.loader.display( element );
		window.eoxiaJS.request.send( element, data );

		jQuery( this ).find( '.fas' ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
		jQuery( '.table-type-project[data-id=' + projectID + '] .cell-sticky div[data-action="edit_point"]' ).attr( 'data-toggle', true );
	}
};

window.eoxiaJS.taskManager.newTask.displayState = function ( event ) {
	var state          = jQuery( this ).attr( 'data-state' );
	var parent_element = jQuery( this ).closest( '.project-state' );
	parent_element.find( 'input[name="state"]' ).val( state );

	var this_html = jQuery( this ).html();
	parent_element.find( '.dropdown-toggle' ).html( this_html );

	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}
	data.action   = 'task_state';
	data.task_id  = parent_element.data( 'id' );
	data.state = state;
	window.eoxiaJS.loader.display( element );
	window.eoxiaJS.request.send( element, data );
};

window.eoxiaJS.taskManager.newTask.taskStateSuccess = function( element, response ) {
	jQuery( element ).closest( '.table-column' ).replaceWith( response.data.view );
};

window.eoxiaJS.taskManager.newTask.stickyAction = function( e ) {
	var tables = jQuery( '.list-task' );

	tables.each( function( i ) {
		var scrollPos = jQuery( this ).scrollLeft();
		var finalPos = scrollPos + jQuery( this ).width() - 102;
		var finalPosHeader = scrollPos + jQuery( this ).width() - 30;

		jQuery( this ).find( '.cell-sticky' ).css({left: finalPos});
		jQuery( this ).find( '.table-header-edit' ).css({left: finalPosHeader });
	});
}

window.eoxiaJS.taskManager.newTask.editedColumnSuccess = function (triggeredElement, response) {
	jQuery( '.table-header .wpeo-util-hidden' ).removeClass( 'wpeo-util-hidden' );

	triggeredElement.removeClass( 'button-blue' ).addClass( 'button-green' );
	triggeredElement.find( 'i' ).removeClass( 'fa-pencil-alt' ).addClass( 'fa-save' );
	triggeredElement.attr( 'data-action', 'tm_save_columns' );

	jQuery( '.table-header .table-cell' ).attr( 'draggable', true );

	for (var key in response.data.user_columns_def) {
		if (! response.data.user_columns_def[key].displayed) {
			jQuery( '.table-cell[data-key=' + key + ']' ).css({display: 'flex' ,opacity: 0.3});
			jQuery( '.table-cell[data-key=' + key + '] input[type=checkbox]' ).attr( 'checked', false );
		}
	}
};


window.eoxiaJS.taskManager.newTask.refreshKey = function( event ) {
	jQuery( '.table-header .table-cell' ).each( function( key ) {
		jQuery( this ).find( 'input[type="hidden"]' ).val(key);
	} );
};
