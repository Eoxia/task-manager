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
		<span class="estimated">/ <?php echo esc_html( $history_time->option['estimated_time'] ); ?></span>
	</li>
<?php } ?>
