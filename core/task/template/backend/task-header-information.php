<ul>
	<li class="wpeo-task-time">
		<?php echo apply_filters( 'task_header_information', '', $task ); ?>
		<span class="wpeo-time-history-task dashicons dashicons-image-rotate" data-title="<?php echo '#' . $task->id . ' ' . __( 'Time history', 'task-manager' ); ?>" data-url="<?php echo admin_url( 'admin-post.php?action=task_manager_time_history&task_id=' . $task->id ); ?>"></span>
	</li>
</ul>
