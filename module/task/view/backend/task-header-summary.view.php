<?php
/**
 * Vue des informations supplémentaire dans le sommaire des tâches.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   TaskManager\Templates
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<li class="wpeo-task-id"><i class="fas fa-hashtag"></i> <?php echo esc_html( $task->data['id'] ); ?></li>

<li class="wpeo-task-time-history wpeo-modal-event"
	data-class="history-time wpeo-wrap tm-wrap"
	data-action="load_time_history"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_time_history' ) ); ?>"
	data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Time history', 'task-manager' ), $task->data['id'] ) ); ?>"
	data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">

	<?php if ( 0 !== $task->data['last_history_time']->data['id'] ) : ?>
		<?php if ( 'recursive' === $task->data['last_history_time']->data['custom'] ) : ?>
			<i class="fas fa-repeat wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Repeated time', 'task-manager' ); ?>"></i>
		<?php else : ?>
			<span class="wpeo-task-date tooltip hover" aria-label="<?php echo esc_html_e( 'Dead line', 'task-manager' ); ?>">
				<i class="fas fa-calendar-alt"></i>
				<span><?php echo esc_html( $task->data['last_history_time']->data['due_date']['rendered']['date'] ); ?></span>
			</span>
		<?php endif; ?>
	<?php endif; ?>

	<span class="wpeo-task-time-info wpeo-tooltip-event" aria-label="<?php echo esc_attr( $task_time_info_human_readable ); ?>">
		<i class="fas fa-clock"></i>
		<span class="elapsed" ><?php echo esc_html( $task_time_info ); ?></span>min
	</span>
</li>

<?php if ( 'archive' === $task->data['status'] ) : ?>
	<li>
		<i class="fas fa-archive"></i>
		<?php esc_html_e( 'Archived task', 'task-manager' ); ?>
	</li>
<?php endif; ?>
