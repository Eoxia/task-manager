<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<!-- Task header : Pour modifier le titre, le temps estimé et ouvrir le dashboard à droite -->
<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
	<?php wp_nonce_field( 'ajax_edit_task_' . $task->id ); ?>
	<ul class="wpeo-task-header">
		<li class="wpeo-task-id">#<?php echo $task->id; ?></li>

		<li class="wpeo-task-title">
			<input <?php echo $disabled_filter; ?> type="text" name="task[title]" class="wpeo-project-task-title" data-nonce="<?php echo wp_create_nonce( 'ajax_load_dashboard_' . $task->id ); ?>" value="<?php echo htmlspecialchars( !empty( $task->title ) ? $task->title : 'New task' ); ?>" />
		</li>

		<li class="wpeo-task-time">
			<span class="dashicons dashicons-clock"></span>
			<span class="wpeo-project-task-time"><?php echo $task->option['time_info']['elapsed']; ?></span> /
			<input <?php echo $disabled_filter; ?> name="task[option][time_info][estimated]" class="wpeo-project-task-time-estimated" type="text" value="<?php echo $task->option['time_info']['estimated']; ?>" />

			<?php echo apply_filters( 'task_header_button', '', $task ); ?>
		</li>
	</ul>
</form>
