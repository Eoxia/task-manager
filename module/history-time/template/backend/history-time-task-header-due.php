<?php
/**
 * Display on header of task.
 * Due date part.
 *
 * @package HistoryTime
 */

?>
<?php if ( isset( $history_time ) ) { ?>
	<li class="wpeo-task-date">
		<i class="dashicons dashicons-calendar-alt"></i>
		<span><?php echo esc_html( mysql2date( get_option( 'date_format' ), $history_time->option['due_date'], true ) ); // $interval = nb of diff days (may be negative value) ?></span>
	</li>
<?php } ?>
