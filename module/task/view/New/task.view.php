<?php
/**
 * La vue d'une ligne de la page "EPI".
 *
 * @package   TheEPI
 * @author    Evarisk <dev@evarisk.com>
 * @copyright 2019 Evarisk
 * @since     0.1.0
 * @version   0.7.0
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var EPI_Model $epi Les données d'un EPI.
 */

?>

<div class="task table-column" data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
	<div class="table-row" data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
		<div class="table-cell table-25 project-toggle-task" data-id="<?php echo esc_attr( $task->data['id'] ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point' ) ); ?>">
			<div class="table-cell-container">
				<i class="fas fa-angle-right"></i>
			</div>
		</div>

		<div class="table-cell table-300 project-name" data-title="<?php echo esc_attr_e( 'Project Name', 'task-manager' ); ?>">
			<div class="table-cell-container project-title" contenteditable="true">
				<?php echo esc_html( $task->data['title'] ); ?>
			</div>
		</div>

		<div class="table-cell table-50 project-id" data-title="<?php echo esc_attr_e( 'ID', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo esc_html( $task->data['id'] ); ?>
			</div>
		</div>

		<div class="table-cell table-100 project-last-update" data-title="<?php echo esc_attr_e( 'Last Update', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo Task_Class::g()->get_task_last_update( $task->data['id'] ); ?>
			</div>
		</div>

		<div class="table-cell table-100 wpeo-modal-event project-time"
			data-class="history-time wpeo-wrap tm-wrap"
			data-action="load_time_history"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_time_history' ) ); ?>"
			data-title="<?php /* Translators: 1. The task ID. */ echo esc_attr( sprintf( __( '#%1$s Time history', 'task-manager' ), $task->data['id'] ) ); ?>"
			data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">
			<div class="table-cell-container">
				<span class="elapsed"><?php echo esc_html( $task->data['time_info']['elapsed'] ); ?></span>
				<?php
				if ( isset( $task->data['last_history_time']->data['estimated_time'] ) ) :
					?>
					<span class="separator">/</span>
					<span class="estimated"><?php echo esc_html( $task->data['last_history_time']->data['estimated_time'] ); ?></span>
					<?php
				endif;
				?>
				<span class="unit">min</span>
			</div>
		</div>

		<div class="table-cell table-150 project-created-date" data-title="<?php echo esc_attr_e( 'Creation Date', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo esc_html( $task->data['date']['rendered']['date_time'] ); ?>
			</div>
		</div>

		<div class="table-cell table-150 project-due-time" data-title="<?php echo esc_attr_e( 'End Date', 'task-manager' ); ?>">
			<div class="table-cell-container group-date">
				<input type="hidden" class="mysql-date" name="due_date" value="<?php echo esc_attr( $history_time_schema->data['due_date']['raw'] ); ?>" />
				<input class="date form-field" type="text" value="<?php echo esc_attr( $history_time_schema->data['due_date']['rendered']['date'] ); ?>" />
			</div>
		</div>

		<div class="table-cell table-100 project-affiliated" data-title="<?php echo esc_attr_e( 'Affiliated With', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo apply_filters( 'tm_task_footer', '', $task ); ?>
			</div>
		</div>

		<div class="table-cell table-150 project-categories" data-title="<?php echo esc_attr_e( 'Categories', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[task_manager_task_tag task_id=' . $task->data['id'] . ']' ); ?>
			</div>
		</div>

		<div class="table-cell table-100 project-state" data-title="<?php echo esc_attr_e( 'State', 'task-manager' ); ?>" data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
			<div class="table-cell-container">
				<div class="wpeo-dropdown dropdown-right">
					<div class="dropdown-toggle wpeo-button button-transparent">
					<?php if ( $task->data['task_info']['state'] == 'deadline') : ?>
						<i class="fas fa-circle"></i>
						<span><?php echo esc_html( 'Without deadline' ); ?></span>
					<?php elseif ( $task->data['task_info']['state'] == 'planned') : ?>
						<i class="fas fa-circle"></i>
						<span><?php echo esc_html( 'Planned' ); ?></span>
					<?php elseif ( $task->data['task_info']['state'] == 'late') : ?>
						<i class="fas fa-circle"></i>
						<span><?php echo esc_html( 'Late' ); ?></span>
					<?php elseif ( $task->data['task_info']['state'] == 'completed') : ?>
						<i class="fas fa-circle"></i>
						<span><?php echo esc_html( 'Completed' ); ?></span>
					<?php else : ?>
						<i class="fas fa-circle"></i>
						<span><?php echo esc_html( 'Default' ); ?></span>
					<?php endif; ?>
					</div>
					<input type="hidden" name="state" value="">
					<ul class="dropdown-content">
						<li class="dropdown-item wpeo-tooltip-event wpeo-button button-transparent"
						    data-state="deadline"
						    aria-label="<?php esc_html_e( 'Without deadline', 'task-manager' ); ?>">
							<i class="fas fa-circle"></i>
							<span><?php echo esc_html( 'Without deadline' ); ?></span>
						</li>

						<li class="dropdown-item wpeo-tooltip-event wpeo-button button-transparent"
						    data-state="planned"
						    aria-label="<?php esc_html_e( 'Planned', 'task-manager' ); ?>">
							<i class="fas fa-circle"></i>
							<span><?php echo esc_html( 'Planned' ); ?></span>
						</li>

						<li class="dropdown-item wpeo-tooltip-event wpeo-button button-transparent"
						    data-state="late"
						    aria-label="<?php esc_html_e( 'Late', 'task-manager' ); ?>">
							<i class="fas fa-circle"></i>
							<span><?php echo esc_html( 'Late' ); ?></span>
						</li>

						<li class="dropdown-item wpeo-tooltip-event wpeo-button button-transparent"
						    data-state="completed"
						    aria-label="<?php esc_html_e( 'Completed', 'task-manager' ); ?>">
							<i class="fas fa-circle"></i>
							<span><?php echo esc_html( 'Completed' ); ?></span>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="table-cell table-75 project-attachment" data-title="<?php echo esc_attr_e( 'Attachment', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[wpeo_upload id="' . $task->data['id'] . '" single="false" model_name="/task_manager/Task_Class" field_name="image"]' ); ?>
			</div>
		</div>

		<div class="table-cell table-100 project-author" data-title="<?php echo esc_attr_e( 'Project-author', 'task-manager' ); ?>">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[task_avatar ids="' . $task->data['author_id'] . '" size="40"]' ); ?>
			</div>
		</div>

		<div class="table-cell table-200 project-users" data-title="<?php esc_attr_e( 'Associated Users', 'task-manager' ); ?>" data-id="<?php echo esc_attr( $task->data['id'] ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_followers' ) ); ?>">
			<div class="table-cell-container">
				<?php echo do_shortcode( '[task_manager_task_follower task_id=' . $task->data['id'] . ']' ); ?>
			</div>
		</div>

		<div class="table-cell table-50 table-padding-0 project-option">
			<div class="table-cell-container">
				<div class="wpeo-dropdown dropdown-right">
					<div class="dropdown-toggle wpeo-button button-square-50 button-transparent"><i class="fas fa-ellipsis-v"></i></div>
					<ul class="dropdown-content">
						<li class="dropdown-item action-attribute wpeo-tooltip-event wpeo-button button-transparent"
							data-direction="left"
							aria-label="<?php esc_html_e( 'Recompile the task', 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
							data-action="recompile_task"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'recompile_task' ) ); ?>">
							<i class="fas fa-redo"></i>
							<span><?php echo esc_html( 'Recompile the task' ); ?></span>
						</li>

						<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event wpeo-button button-transparent"
							data-direction="left"
							aria-label="<?php esc_html_e( 'Notify the team', 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
							data-action="load_notify_popup"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_notify_popup' ) ); ?>">
							<i class="fas fa-bell"></i>
							<span><?php echo esc_html( 'Notify the team' ); ?></span>
						</li>

						<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event wpeo-button button-transparent"
							data-direction="left"
							aria-label="<?php esc_html_e( 'Upload', 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
							data-action="load_export_popup"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_export_popup' ) ); ?>">
							<i class="fas fa-upload"></i>
							<span><?php echo esc_html( 'Upload' ); ?></span>
						</li>

						<li class="dropdown-item wpeo-modal-event wpeo-tooltip-event wpeo-button button-transparent"
							data-direction="left"
							aria-label="<?php esc_html_e( 'Download', 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
							data-action="load_import_modal"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_import_modal' ) ); ?>">
							<i class="fas fa-download"></i>
							<span><?php echo esc_html( 'Download' ); ?></span>
						</li>

						<li class="dropdown-item action-attribute wpeo-tooltip-event wpeo-button button-transparent"
							data-direction="left"
							aria-label="<?php esc_html_e( 'Archive', 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
							data-action="to_archive"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'to_archive' ) ); ?>">
							<i class="fas fa-archive"></i>
							<span><?php echo esc_html( 'Archive' ); ?></span>
						</li>

						<li class="dropdown-item action-delete wpeo-tooltip-event wpeo-button button-transparent"
							data-direction="left"
							aria-label="<?php esc_html_e( 'Delete', 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
							data-action="delete_task"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
							data-message-delete="<?php echo esc_attr_e( 'Are you sure you want to remove this task ?', 'task-manager' ); ?>">
							<i class="fas fa-trash"></i>
							<span><?php echo esc_html( 'Delete' ); ?></span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

