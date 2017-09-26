<?php
/**
 * Affiches l'historique des 'dates estimées'.
 * Affiches le formulaire pour ajouter une date estimé.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.4.0
 * @copyright 2015-2017
 * @package Task_Manager
 */

namespace task_manager;
?>

<div class="history-time-container">
	<div class="history-time-new">

		<input type="hidden" name="action" value="create_history_time" />
		<input type="hidden" name="task_id" value="<?php echo esc_attr( $task_id ); ?>" />
		<?php wp_nonce_field( 'create_history_time' ); ?>

		<div class="group-date">
			<i class="dashicons dashicons-calendar-alt"></i>
			<input type="text" class="mysql-date" style="width: 0px; padding: 0px; border: none;" name="due_date" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" />
			<input class="date" type="text" placeholder="<?php esc_html_e( 'New due time', 'task-manager' ); ?>"/>
		</div>

		<i class="dashicons dashicons-clock"></i>
		<input name="estimated_time" type="text" placeholder="<?php esc_html_e( 'Estimated time (min)', 'task-manager' ); ?>"/>

		<span data-parent="history-time-new" class="action-input dashicons dashicons-plus-alt"></span>
	</div>

	<ul class="history-time-list">
		<?php
		if ( ! empty( $history_times ) ) :
			foreach ( $history_times as $history_time ) :
				if ( ! empty( $history_time->id ) ) :
					\eoxia\View_Util::exec( 'task-manager', 'history-time', 'backend/history-time', array(
						'history_time' => $history_time,
					) );
				endif;
			endforeach;
		endif;
		?>
	</ul>
</div>
