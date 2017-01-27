<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="wpeo-task-option" class="wpeo-display-option">
	<ul class="wpeo-send-element">
		<li>
			<i class="dashicons dashicons-migrate"></i><?php _e( 'Send the task to element <strong>#</strong>', 'task-manager' ); ?>
		</li>
		<li>
			<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
				 <!-- For admin-ajax -->
			 	<?php wp_nonce_field( 'wpeo_nonce_send_to_task_' . $element->id ); ?>
				<div>
					<input type="text" class="wpeo-task-auto-complete" />
					<input type="hidden" name="element_id" value="" />
					<input type="hidden" name="task_id" value="<?php echo $element->id; ?>" />
				</div>
				<input type="button" class="wpeo-send-task-to-element" value="<?php _e( 'Send', 'task-manager' ); ?>" />
			</form>
		</li>
	</ul>

	<ul class="wpeo-end-time">
		<li>
			<i class="dashicons dashicons-calendar-alt"></i><?php _e( 'Due date', 'task-manager' ); ?>
		</li>
		<li>
			<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
				 <!-- For admin-ajax -->
			 	<?php wp_nonce_field( 'wpeo_nonce_due_date_' . $element->id ); ?>
				<input type="text" class="isDate" name="due_date" value="<?php echo !empty( $element->option['date_info']['due'] ) ? $element->option['date_info']['due'] : current_time( 'Y-m-d' ); ?>" />
				<input type="hidden" name="task_id" value="<?php echo $element->id; ?>" />
				<input type="button" class="wpeo-update-due-date" value="<?php _e( 'Update', 'task-manager' ); ?>" />
			</form>
		</li>
	</ul>
</div>
