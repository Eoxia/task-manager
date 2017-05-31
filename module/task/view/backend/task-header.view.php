<?php
/**
 * La vue du header d'une tâche dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<ul class="wpeo-task-time-manage">
	<li class="wpeo-task-date">
		<i class="dashicons dashicons-calendar-alt"></i>
		<span><?php echo esc_html( Date_Util::g()->mysqldate2wordpress( $task->last_history_time->due_date, false ) ); ?></span>
	</li>

	<li class="wpeo-task-elapsed">
		<i class="dashicons dashicons-clock"></i>
		<span class="elapsed"><?php echo esc_html( $task->time_info['time_display'] . ' (' . $task->time_info['elapsed'] . 'min)' ); ?></span>
	</li>
	<li class="wpeo-task-estimated">
		<?php if ( ! empty( $task->last_history_time->estimated_time ) ) : ?>
			<span class="estimated">/ <?php echo esc_html( $task->time_info['estimated_time_display'] . ' (' . $task->last_history_time->estimated_time . 'min)' ); ?></span>
		<?php endif; ?>
	</li>

	<li class="wpeo-task-time-history open-popup-ajax"
			data-parent="wpeo-project-task"
			data-target="popup"
			data-action="load_time_history"
			data-title="<?php echo esc_attr( '#' . $task->id . ' Historique du temps' ); ?>"
			data-task-id="<?php echo esc_attr( $task->id ); ?>">
		<span class="fa fa-history dashicons-image-rotate"></span>
	</li>
</ul>
