<?php
/**
 * La vue d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-project-task <?php echo ! empty( $task->data['front_info']['display_color'] ) ? esc_attr( $task->data['front_info']['display_color'] ) : 'white'; ?>" data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
	<div class="wpeo-project-task-container">

		<!-- En tête de la tâche -->
		<ul class="wpeo-task-header">
			<li class="wpeo-task-author"><?php echo do_shortcode( '[task_manager_owner_task task_id="' . $task->data['id'] . '" owner_id="' . $task->data['user_info']['owner_id'] . '"]' ); ?></li>

			<li class="wpeo-task-id">#<?php echo esc_html( $task->data['id'] ); ?></li>

			<li class="wpeo-task-title">
				<input type="text" name="task[title]" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_title' ) ); ?>" class="wpeo-project-task-title" value="<?php echo esc_html( $task->data['title'] ); ?>" />
			</li>

			<li class="wpeo-dropdown wpeo-task-setting"
					data-parent="toggle"
					data-target="content"
					data-mask="wpeo-project-task">

				<span class="wpeo-button button-transparent dropdown-toggle"
					><i class="fa fa-ellipsis-v"></i></span>

				<div class="dropdown-content task-header-action">
					<?php
					\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/toggle-content', array(
						'task' => $task,
					) );
					?>
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
		<?php Point_Class::g()->display( $task->data['id'] ); ?>
		<!-- Fin corps de la tâche -->

		<!-- Les tags -->
		<?php echo do_shortcode( '[task_manager_task_tag task_id=' . $task->data['id'] . ']' ); ?>
		<!-- Fin des tags -->

		<!-- Les followers -->
		<?php echo do_shortcode( '[task_manager_task_follower task_id=' . $task->data['id'] . ']' ); ?>
		<!-- Fin des followers -->

	</div>
</div>
