<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<ul>
	<li>
		<span class="wpeo-send-task-to-trash" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_task_' . $task->id ); ?>"><?php _e( 'Delete task', 'task-manager' ); ?></span>
	</li>
</ul>
