window.task_manager.task = {};

window.task_manager.task.init = function() {
	window.task_manager.task.event();
};

window.task_manager.task.event = function() {
	jQuery( '.wpeo-project-wrap' ).on( 'blur', '.wpeo-project-task-title', window.task_manager.task.edit_title );
};

window.task_manager.task.edit_title = function( event ) {
	var taskId = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	var data = {
		action: 'edit_title',
		_wpnonce: jQuery( this ).data( 'nonce' ),
		task_id: taskId,
		title: jQuery( this ).val()
	};

	window.task_manager.request.send( this, data );
};

window.task_manager.task.create_task_success = function( element, response ) {
	jQuery( '.list-task' ).prepend( response.data.template );
};
