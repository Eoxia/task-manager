window.task_manager.point = {};

window.task_manager.point.init = function() {
	window.task_manager.point.event();
};

window.task_manager.point.event = function() {
	jQuery( document ).on( 'blur click keyup paste keydown', '.wpeo-add-point .wpeo-point-new-contenteditable', window.task_manager.point.add_point );
};

window.task_manager.point.add_point = function( event ) {
	var element = jQuery( this );
	var parentBloc = element.closest( 'wpeo-add-point' );
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
		btnNewPoint.removeClass( 'submit-form' );
	} else {
		placeholderNewPoint.hide();
		btnNewPoint.css( 'opacity', 1 );
		btnNewPoint.addClass( 'submit-form' );
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
