<?php
/**
* Display of time manager popup.
*
* @package HistoryTime
*/

?>
<div class="history-time-container">
	<div class="history-time-new">
		<i class="dashicons dashicons-calendar-alt"></i>
		<input name="due_date" type="text" placeholder="<?php esc_html_e( 'Enter a new due time', 'task-manager' ); ?>"/>
		<i class="dashicons dashicons-clock"></i>
		<input name="estimated_time" type="text" placeholder="<?php esc_html_e( 'Enter a new estimated time', 'task-manager' ); ?>"/>

		<span class="add-history-time dashicons dashicons-plus-alt" data-task-id="<?php echo esc_attr( $task->id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_history_time' ) ); ?>"></span>
	</div>

	<ul class="history-time-list">
		<?php
		foreach ( $list_history_time as $history_time ) {
			require( wpeo_template_01::get_template_part( WPEO_HISTORY_TIME_DIR, WPEO_HISTORY_TIME_TEMPLATES_MAIN_DIR, 'backend', 'history-time' ) );
		}
		?>
	</ul>
</div>
