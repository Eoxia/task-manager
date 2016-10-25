<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<ul>
	<li class="task-color">
		<span class="white"></span><span class="red"></span><span class="yellow"></span><span class="green"></span><span class="blue"></span><span class="purple"></span>
	</li>
	<li class="wpeo-send-task-to-trash" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_task_' . $task->id ); ?>">
		<span><?php _e( 'Delete task', 'task-manager' ); ?></span>
	</li>
</ul>
