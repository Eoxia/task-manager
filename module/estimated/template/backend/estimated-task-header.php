<span class="task-estimated-time">
	<?php if( isset( $estimated_time ) ) { ?>
		<span class="dashicons dashicons-clock"></span>
		<?php echo __( 'Time elapsed : ', 'task-manager' ) . $estimated_time->option['estimated_time']; ?>
	<?php } ?>
</span>
