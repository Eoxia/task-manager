<?php
/**
 * La vue du header d'une tâche dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="wpeo-task-time-manage">
	<?php if ( 0 !== $task->last_history_time->id ) : ?>
		<li class="wpeo-task-date tooltip hover" aria-label="<?php echo esc_html_e( 'Dead line', 'task-manager' ); ?>">
			<i class="dashicons dashicons-calendar-alt"></i>
			<span><?php echo esc_html( $task->last_history_time->due_date['date_input']['fr_FR']['date'] ); ?></span>
		</li>
	<?php endif; ?>

	<li class="wpeo-task-elapsed">
		<i class="dashicons dashicons-clock"></i>
		<span class="elapsed tooltip hover" aria-label="<?php echo esc_html_e( 'Elapsed time', 'task-manager' ); ?>"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->time_info['elapsed'] ) ); ?></span>
	</li>
	<li class="wpeo-task-estimated">
		<?php if ( ! empty( $task->last_history_time->estimated_time ) ) : ?>
			<span class="estimated tooltip hover" aria-label="<?php echo esc_html_e( 'Estimated time', 'task-manager' ); ?>">/ <?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->last_history_time->estimated_time ) ); ?></span>
		<?php endif; ?>
	</li>

	<li class="wpeo-task-time-history open-popup-ajax"
			data-parent="wpeo-project-task"
			data-target="popup"
			data-action="load_time_history"
			data-title="<?php echo sprintf( __( '#%1$s History time', 'task-manager' ), esc_attr( $task->id ) ); ?>"
			data-task-id="<?php echo esc_attr( $task->id ); ?>">
		<span class="fa fa-history dashicons-image-rotate"></span>
	</li>

	<li class="display-method-buttons">
		<span class="dashicons dashicons-editor-ul list-display active"></span>
		<span class="action-attribute dashicons dashicons-screenoptions grid-display"
					data-action="load_last_activity"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>"
					data-tasks-id="<?php echo esc_attr( $task->id ); ?>"></span>
	</li>
</ul>
