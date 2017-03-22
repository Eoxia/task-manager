<?php
/**
 * La vue d'une tâche dans le backend.
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

<div class="wpeo-project-task" data-id="<?php echo esc_attr( $task->id ); ?>">
	<div class="wpeo-project-task-container">
		<!-- En tête de la tâche -->
		<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
			<ul class="wpeo-task-header">
				<li class="wpeo-task-author"><?php do_shortcode( '[task_manager_owner_task task_id="' . $task->id . '" owner_id="' . $task->user_info['owner_id'] . '"]' ); ?></li>

				<li class="wpeo-task-id">#<?php echo esc_html( $task->id ); ?></li>

				<li class="wpeo-task-title">
					<input type="text" name="task[title]" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title' ) ); ?>" class="wpeo-project-task-title" value="<?php echo esc_html( ! empty( $task->title ) ? $task->title : 'New task' ); ?>" />
				</li>

				<li class="toggle"
						data-parent="wpeo-project-task"
						data-target="content">

					<div class="action">
						<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Task options', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
					</div>

					<ul class="content">
						<li class="action-delete"
								data-action="delete_task"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
								data-id="<?php echo esc_attr( $task->id ); ?>">
							<span><?php esc_html_e( 'Delete task', 'task-manager' ); ?></span>
						</li>

						<li class="action-attribute"
								data-action="to_archive"
								data-nonce="<?php echo esc_attr( wp_create_nonce( 'to_archive' ) ); ?>"
								data-id="<?php echo esc_attr( $task->id ); ?>">
							<span><?php esc_html_e( 'Archive', 'task-manager' ); ?></span>
						</li>
					</ul>
				</li>
			</ul>
		</form>
		<!-- Fin en tête de la tâche -->

		<!-- Sous en tête pour gérer le temps -->
		<ul class="wpeo-task-time-manage">
			<li class="wpeo-task-date">
				<i class="dashicons dashicons-calendar-alt"></i>
				<span><?php echo esc_html( $task->last_history_time->due_date ); ?></span>
			</li>

			<li class="wpeo-task-elapsed">
				<i class="dashicons dashicons-clock"></i>
				<span class="elapsed"><?php echo esc_html( $task->time_info['elapsed'] ); ?></span>
			</li>
			<li class="wpeo-task-estimated">
				<span class="estimated">/ <?php echo esc_html( $task->last_history_time->estimated_time ); ?></span>
			</li>

			<li class="open-popup-ajax"
					data-parent="wpeo-project-task"
					data-target="popup"
					data-action="load_time_history"
					data-task-id="<?php echo esc_attr( $task->id ); ?>">
				<span class="fa fa-history dashicons-image-rotate"></span>
			</li>
		</ul>
		<!-- Fin de sous en tête -->

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->id ); ?>
		<!-- Fin corps de la tâche -->

		<!-- Les tags -->
		<?php echo do_shortcode( '[task_manager_task_tag task_id=' . $task->id . ']' ); ?>
		<!-- Fin des tags -->

		<!-- Les followers -->
		<?php echo do_shortcode( '[task_manager_task_follower task_id=' . $task->id . ']' ); ?>
		<!-- Fin des followers -->

		<!-- Popup history time -->
		<div class="popup">
			<div class="container">
				<div class="header">
					<h2 class="title">Titre de la popup</h2>
					<i class="close fa fa-times"></i>
				</div>

				<div class="content">
				</div>
			</div>
		</div>
		<!-- Fin popup -->
	</div>
</div>
