<?php
/**
 * Display on header of task.
 * Estimated time part.
 *
 * @package HistoryTime
 */

?>
<?php if ( isset( $history_time ) ) { ?>
	<li class="wpeo-task-estimated">
		<span class="estimated"><?php esc_html_e( 'Time estimated : ', 'task-manager' ); ?><span><?php echo esc_html( $history_time->option['estimated_time'] ); ?></span></span>
	</li>
<?php } ?>
