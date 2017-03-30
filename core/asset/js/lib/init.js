'use strict';

window.eoxiaJS = {};
window.task_manager = {};

window.eoxiaJS.init = function() {
	window.eoxiaJS.load_list_script();
	window.eoxiaJS.init_array_form();
};

window.eoxiaJS.load_list_script = function() {
	for ( var key in window.task_manager ) {
		window.task_manager[key].init();
	}
};

window.eoxiaJS.init_array_form = function() {
	 window.eoxiaJS.arrayForm.init();
};

window.eoxiaJS.refresh = function() {
	for ( var key in window.task_manager ) {
		if ( window.task_manager[key].refresh ) {
			window.task_manager[key].refresh();
		}
	}
};

jQuery(document).ready(window.eoxiaJS.init);
