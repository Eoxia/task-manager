<?php
/**
 * Display on header of task.
 *
 * @package HistoryTime
 */

?>
<span class="task-history-time">
	<?php if ( isset( $history_time ) ) { ?>
		<span class="dashicons dashicons-calendar-alt"></span>
		<?php echo esc_html( mysql2date( get_option( 'date_format' ), $history_time->option['due_date'], true ) ); // $interval = nb of diff days (may be negative value) ?>
		<span class="dashicons dashicons-clock"></span>
		<?php echo esc_html( __( 'Time elapsed : ', 'task-manager' ) . $history_time->option['estimated_time'] ); ?>
	<?php } ?>
</span>
