<?php _e( 'Due time', 'task-manager' ); ?>
<input name="due_time" type="text" placeholder="<?php _e( 'Enter a new due time', 'task-manager' ); ?>"/><span class="add-due-time dashicons dashicons-plus-alt" data-task-id="<?php echo $task->id; ?>"></span>
<ul class="due-time-list">
	<?php
	foreach ( $list_due_time as $due_time ) {
		require( wpeo_template_01::get_template_part( WPEO_DUE_DIR, WPEO_DUE_TEMPLATES_MAIN_DIR, 'backend', 'due' ) );
	}
	?>
</ul>
