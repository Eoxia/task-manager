<?php
/**
 * La vue d'une tâche dans le backend.
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

<div class="wpeo-project-task <?php echo ! empty( $task->front_info['display_color'] ) ? $task->front_info['display_color'] : 'white'; ?>" data-id="<?php echo esc_attr( $task->id ); ?>">
	<div class="wpeo-project-task-container">

		<!-- En tête de la tâche -->
		<ul class="wpeo-task-header">
			<li class="wpeo-task-author"><?php do_shortcode( '[task_manager_owner_task task_id="' . $task->id . '" owner_id="' . $task->user_info['owner_id'] . '"]' ); ?></li>

			<li class="wpeo-task-id">#<?php echo esc_html( $task->id ); ?></li>

			<li class="wpeo-task-title">
				<input type="text" name="task[title]" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title' ) ); ?>" class="wpeo-project-task-title" value="<?php echo esc_html( ! empty( $task->title ) ? $task->title : 'New task' ); ?>" />
			</li>

			<li class="toggle wpeo-task-setting"
					data-parent="toggle"
					data-target="content"
					data-mask="wpeo-project-task">

				<div class="action">
					<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Task options', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
				</div>

				<div class="content task-header-action">
					<?php \eoxia\View_Util::exec( 'task-manager', 'task', 'backend/toggle-content', array(
						'task' => $task,
					) ); ?>
				</div>
			</li>
		</ul>
		<!-- Fin en tête de la tâche -->

		<!-- Sous en tête pour gérer le temps -->
		<?php
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header', array(
			'task' => $task,
		) );
		?>
		<!-- Fin de sous en tête -->

		<!-- Historique de la tâche -->
		<?php \eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/main', array() ); ?>

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->id ); ?>
		<!-- Fin corps de la tâche -->

		<!-- Les tags -->
		<?php echo do_shortcode( '[task_manager_task_tag task_id=' . $task->id . ']' ); ?>
		<!-- Fin des tags -->

		<!-- Les followers -->
		<?php echo do_shortcode( '[task_manager_task_follower task_id=' . $task->id . ']' ); ?>
		<!-- Fin des followers -->

		<!-- Popup -->
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
