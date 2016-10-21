<?php _e( 'Estimated time', 'task-manager' ); ?>
<input name="estimated_time" type="text" placeholder="<?php _e( 'Enter a new estimated time', 'task-manager' ); ?>"/><span class="add-estimated-time dashicons dashicons-plus-alt" data-task-id="<?php echo $task->id; ?>"></span>
<ul class="estimated-time-list">
	<?php
	foreach ( $list_estimated_time as $estimated_time ) {
		require( wpeo_template_01::get_template_part( WPEO_ESTIMATED_DIR, WPEO_ESTIMATED_TEMPLATES_MAIN_DIR, 'backend', 'estimated' ) );
	}
	?>
</ul>
