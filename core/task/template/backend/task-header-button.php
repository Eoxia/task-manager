<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<ul>
	<li class="task-color">
		<span class="white"></span><span class="red"></span><span class="yellow"></span><span class="green"></span><span class="blue"></span><span class="purple"></span>
	</li>
	<li class="wpeo-send-mail" data-nonce="<?php echo wp_create_nonce( 'wpeo_send_mail_task_' . $task->id ); ?>">
		<span><?php _e( 'Notify affected users', 'task-manager' ); ?></span>
	</li>
	<li class="wpeo-export" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_export_task_' . $task->id ); ?>">
		<span><?php _e( 'Export task', 'task-manager' ); ?></span>
	</li>
	<li class="wpeo-send-task-to-<?php echo ( 'archive' !== $task->status ) ? '' : 'un'; ?>archive" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_archive_task_' . $task->id ); ?>">
		<span>
			<?php
			if ( 'archive' !== $task->status ) :
				esc_html_e( 'Archive task', 'task-manager' );
			else :
				esc_html_e( 'Unarchive task', 'task-manager' );
			endif;
			?>
		</span>
	</li>
	<li class="wpeo-send-task-to-trash" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_task_' . $task->id ); ?>">
		<span><?php _e( 'Delete task', 'task-manager' ); ?></span>
	</li>
</ul>
