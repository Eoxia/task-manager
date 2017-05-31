<?php
/**
 * Display line history time.
 *
 * @package HistoryTime
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;
?>

<div class="history-time-container">
	<div class="history-time-new">

		<input type="hidden" name="action" value="create_history_time" />
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task_id ); ?>" />
		<?php wp_nonce_field( 'create_history_time' ); ?>

		<i class="dashicons dashicons-calendar-alt"></i>
		<input name="due_date" class="date" type="text" placeholder="<?php esc_html_e( 'Nouvelle date de fin', 'task-manager' ); ?>"/>

		<i class="dashicons dashicons-clock"></i>
		<input name="estimated_time" type="text" placeholder="<?php esc_html_e( 'Temps estimé (min)', 'task-manager' ); ?>"/>

		<span data-parent="history-time-new" class="action-input dashicons dashicons-plus-alt"></span>
	</div>

	<ul class="history-time-list">
		<?php
		if ( ! empty( $history_times ) ) :
			foreach ( $history_times as $history_time ) :
				if ( ! empty( $history_time->id ) ) :
					View_Util::exec( 'history-time', 'backend/history-time', array(
						'history_time' => $history_time,
					) );
				endif;
			endforeach;
		endif;
		?>
	</ul>
</div>
