<span class="task-due-time">
	<?php if( isset( $due_time ) ) { ?>
		<span class="dashicons dashicons-calendar-alt"></span>
		<?php echo mysql2date( get_option( 'date_format' ), $due_time->option['due_date'], true ); // $interval = nb of diff days (may be negative value) ?>
	<?php } ?>
</span>
