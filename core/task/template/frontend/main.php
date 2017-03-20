<input type="text" class="task-search" placeholder="<?php esc_html_e( 'Search', 'task-manager' ); ?>" />

<div class="wpeo-project-wrap">
	<?php require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'frontend', 'tasks' ) ); ?>
	<div class="wpeo-window-dashboard"></div>
</div>
