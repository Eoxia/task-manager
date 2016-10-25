<ul class="wpeo-task-time-manage">

	<li class="wpeo-task-date">
		<span class="dashicons dashicons-calendar-alt"></span>
		<span>10 octobre 2015</span>
	</li>

	<li class="wpeo-task-time">
		<span class="dashicons dashicons-clock"></span>
		<span class="elapsed">Time elapsed : <span>900</span></span><span class="estimated">Time estimated : <span>600</span></span>
	</li>

	<li class="wpeo-task-time-history">
		<span class="wpeo-time-history-task fa fa-history dashicons-image-rotate" data-title="<?php echo '#' . $task->id . ' ' . __( 'Time history', 'task-manager' ); ?>" data-url="<?php echo admin_url( 'admin-post.php?action=task_manager_time_history&task_id=' . $task->id ); ?>"></span>
	</li>
</ul>
