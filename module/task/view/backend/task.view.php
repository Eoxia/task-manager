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
					data-target="content">

				<div class="action">
					<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Options de la tâche', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
				</div>

				<ul class="content task-header-action">
					<li class="task-color">
						<?php
						if ( ! empty( Task_Class::g()->colors ) ) :
							foreach ( Task_Class::g()->colors as $color ) :
								?>
								<span class="action-attribute <?php echo esc_attr( $color ); ?>" data-action="change_color"
											data-nonce="<?php echo esc_attr( wp_create_nonce( 'change_color' ) ); ?>"
											data-id="<?php echo esc_attr( $task->id ); ?>"
											data-color="<?php echo esc_attr( $color ); ?>"
											data-namespace="taskManager"
											data-module="task"
											data-before-method="beforeChangeColor"></span>
								<?php
							endforeach;
						endif;
						?>
					</li>

					<li class="open-popup-ajax"
							data-action="load_task_properties"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_task_properties' ) ); ?>"
							data-title="<?php esc_attr_e( 'Propriété de la tâche: #' . $task->id . ' ' . $task->title, 'task-manager' ); ?>"
							data-id="<?php echo esc_attr( $task->id ); ?>"
							data-parent="wpeo-project-task"
							data-target="popup">
						<span><?php esc_html_e( 'Propriété de la tâche', 'task-manager' ); ?></span>
					</li>

					<li class="action-attribute"
							data-action="notify_by_mail"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'notify_by_mail' ) ); ?>"
							data-id="<?php echo esc_attr( $task->id ); ?>">
						<span><?php esc_html_e( 'Notifier le responsable et les abonnés', 'task-manager' ); ?></span>
					</li>

					<li class="action-delete"
							data-action="delete_task"
							data-message-delete="<?php echo esc_attr( 'Supprimer cette tâche', 'task-manager' ); ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_task' ) ); ?>"
							data-id="<?php echo esc_attr( $task->id ); ?>">
						<span><?php esc_html_e( 'Supprimer la tâche', 'task-manager' ); ?></span>
					</li>

					<li class="action-attribute"
							data-action="<?php echo ( 'archive' !== $task->status ) ? 'to_archive' : 'to_unarchive'; ?>"
							data-nonce="<?php echo esc_attr( wp_create_nonce( ( 'archive' === $task->status ) ? 'to_archive' : 'to_unarchive' ) ); ?>"
							data-id="<?php echo esc_attr( $task->id ); ?>">
						<span><?php esc_html_e( ( 'archive' !== $task->status ) ? 'Archiver' : 'Désarchiver', 'task-manager' ); ?></span>
					</li>

					<li class="action-attribute"
							data-action="export_task"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'export_task' ) ); ?>"
							data-id="<?php echo esc_attr( $task->id ); ?>">
						<span><?php esc_html_e( 'Exporter', 'task-manager' ); ?></span>
					</li>
				</ul>
			</li>
		</ul>
		<!-- Fin en tête de la tâche -->

		<!-- Sous en tête pour gérer le temps -->
		<?php \eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header', array(
			'task' => $task,
		) ); ?>
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
