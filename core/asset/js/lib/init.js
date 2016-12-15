"use strict";

window.eoxia_lib = {};
window.task_manager = {};

window.eoxia_lib.init = function() {
	window.eoxia_lib.load_list_script();
	window.eoxia_lib.init_array_form();
}

window.eoxia_lib.load_list_script = function() {
	for ( var key in window.task_manager ) {
		window.task_manager[key].init();
	}
}

window.eoxia_lib.init_array_form = function() {
	 window.eoxia_lib.array_form.init();
}

jQuery(document).ready(window.eoxia_lib.init);
