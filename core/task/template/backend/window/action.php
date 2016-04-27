<div id="wpeo-task-option">
	<ul>
		<li>
			<i class="dashicons dashicons-migrate"></i><?php _e( 'Send the task to element <strong>#</strong>', 'task-manager' ); ?>
		</li>
		<li>
			<form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
				 <!-- For admin-ajax -->
			 	<?php wp_nonce_field( 'wpeo_nonce_send_to_task_' . $element->id ); ?>
				<input type="text" name="element_id" placeholder="162" />
				<input type="hidden" name="task_id" value="<?php echo $element->id; ?>" />
				<input type="button" class="wpeo-send-task-to-element" value="<?php _e( 'Send', 'task-manager' ); ?>" />
			</form>
		</li>
	</ul>

	<ul>
		<li>
			<?php _e( 'Due date', 'task-manager' ); ?>
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
