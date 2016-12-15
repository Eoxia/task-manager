window.task_manager.render = {};

window.task_manager.render.init = function() {
	window.task_manager.render.event();
};

window.task_manager.render.event = function() {
};

window.task_manager.render.call_render_changed = function() {
	for ( var key in window.task_manager ) {
		if (window.task_manager[key].render_changed) {
			window.task_manager[key].render_changed();
		}
	}
}
