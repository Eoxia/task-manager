window.task_manager.point = {};

window.task_manager.point.init = function() {
	window.task_manager.point.event();
};

window.task_manager.point.event = function() {
	jQuery( document ).on( 'blur click keyup paste keydown', '.wpeo-add-point .wpeo-point-new-contenteditable', window.task_manager.point.add_point );
	jQuery( document ).on( 'blur paste', '.wpeo-edit-point .wpeo-point-contenteditable', window.task_manager.point.edit_point );
	jQuery( document ).on( 'click', '.wpeo-send-point-to-trash', window.task_manager.point.delete_point );
	jQuery( document ).on( 'click', '.wpeo-task-point-use-toggle p', window.task_manager.point.toggle_completed );
	jQuery( '.wpeo-project-wrap .wpeo-task-point-sortable' ).sortable( {
		handle: '.dashicons-screenoptions',
		items: '.wpeo-edit-point',
		update: function( event, ui ) {
			window.task_manager.point.edit_point_order( ui.item, jQuery( this ).find( '.wpeo-edit-point' ).index( ui.item ) );
		}
	} );
};

window.task_manager.point.add_point = function( event ) {
	var element = jQuery( this );
	var parentBloc = element.closest( '.wpeo-add-point' );
	var taskBloc = element.closest( '.wpeo-project-task' );
	var newPointInput = parentBloc.find( '*[name="point[content]"]' );
	var btnNewPoint = parentBloc.find( '.wpeo-point-new-btn' );
	var placeholderNewPoint = parentBloc.find( '.wpeo-point-new-placeholder' );
	var point = window.task_manager.point;
	newPointInput.val( element.html() );
	if ( 0 == element.text().length ) {
		placeholderNewPoint.show();
		if ( 'undefined' == typeof point.add_point_opacity_btn ) {
			point.add_point_opacity_btn = btnNewPoint.css( 'opacity' );
		} else {
			btnNewPoint.css( 'opacity', point.add_point_opacity_btn );
		}
		btnNewPoint.removeClass( 'submit-form-point-add_point_callback' );
	} else {
		placeholderNewPoint.hide();
		btnNewPoint.css( 'opacity', 1 );
		btnNewPoint.addClass( 'submit-form-point-add_point_callback' );
		if ( 13 == event.which && point.add_point_ctrl_hold ) {
			btnNewPoint.click();
		}
	}
	if ( 17 == event.which ) {
		point.add_point_ctrl_hold = true;
	} else {
		point.add_point_ctrl_hold = false;
	}
};

window.task_manager.point.add_point_callback = function( element ) {
	var parentBloc = element.closest( '.wpeo-add-point' );
	var contentEditable = parentBloc.find( '.wpeo-point-new-contenteditable' );
	contentEditable.html( '' );
};

window.task_manager.point.add_point_callback_success = function( element, response ) {
	var blocTask = element.closest( '.wpeo-project-task' );
	blocTask.find( '.wpeo-task-point:first' ).append( response.data.template );
	blocTask.find( '.wpeo-task-li-point:last' ).css( { 'opacity': 0, 'left': -20 } ).animate( {
		opacity: 1,
		left: 0
	}, 300 );
};

window.task_manager.point.edit_point = function( event ) {
	var element = jQuery( this );
	var parentBloc = element.closest( '.wpeo-edit-point' );
	var editPointInput = parentBloc.find( 'input[name="point[content]"]' );
	var editPointBtn = parentBloc.find( '*[class*="submit-form"]' );
	editPointInput.val( element.html() );
	editPointBtn.trigger( 'submit_form' );
};

window.task_manager.point.edit_point_order = function( element, index ) {
	var parentBloc = element.closest( '.wpeo-edit-point' );
	var input = document.createElement( 'input' );
	input.type = 'hidden';
	input.name = 'point[order]';
	input.value = index;
	parentBloc.append( input );
	parentBloc.find( '.wpeo-point-contenteditable' ).each( window.task_manager.point.edit_point );
	jQuery( input ).remove();
};

window.task_manager.point.delete_point = function( event ) {
	var element = jQuery( this );
	var pointBloc = element.closest( '.wpeo-task-li-point' );
	if ( confirm( 'Delete point' ) ) { // TODO Add translated text
		window.task_manager.request.send( this, element.data() );
		pointBloc.animate( {
			opacity: 0,
			left: '-20'
		}, 300, function() {
			pointBloc.remove();
		} );
	}
};

window.task_manager.point.toggle_completed = function( event ) {
	var element = jQuery( this );
	element.find( '.wpeo-point-toggle-arrow' ).toggleClass( 'dashicons-plus dashicons-minus' );
	element.closest( '.wpeo-task-point-use-toggle' ).find( 'ul:first' ).toggle( 200 );
};

window.task_manager.point.toggle_completed_callback_success = function( element, response ) {
	element.closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).html( response.data.template );
};

window.task_manager.point.toggle_completed_callback_error = function( element, response ) {
	element.closest( '.wpeo-project-task' ).find( '.wpeo-task-point-completed' ).html( response.data.template );
};
