<li data-id="<?php echo $due_time->id; ?>">
	<?php echo get_avatar( $due_time->author_id, 20, 'blank' ); ?>
	<?php
	 	$user = $wp_project_user_controller->show( $due_time->author_id );
		echo $user->displayname;
	?>,
     	<span class="dashicons dashicons-calendar-alt"></span>
	<?php echo mysql2date( get_option( 'date_format' ), $due_time->option['due_date'], true ); ?>
	<span class="delete-due-time dashicons dashicons-dismiss"></span>
</li>
