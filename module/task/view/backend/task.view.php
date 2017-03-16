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

<div class="wpeo-project-task">
	<div class="wpeo-project-task-container">
		<!-- En tête de la tâche -->
		<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
			<ul class="wpeo-task-header">
				<li class="wpeo-task-author"><?php echo get_avatar( $task->author_id, 32 ); ?></li>

				<li class="wpeo-task-id">#<?php echo esc_html( $task->id ); ?></li>

				<li class="wpeo-task-title">
					<input type="text" name="task[title]" class="wpeo-project-task-title" value="<?php echo esc_html( ! empty( $task->title ) ? $task->title : 'New task' ); ?>" />
				</li>

				<li class="wpeo-task-setting">
					<span class="wpeo-task-open-action" title="<?php esc_html_e( 'Task options', 'task-manager' ); ?>"><i class="fa fa-ellipsis-v"></i></span>
					<div class="task-header-action">
					</div>
				</li>
			</ul>
		</form>
		<!-- Fin en tête de la tâche -->

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->id ); ?>
		<!-- Fin corps de la tâche -->

		<!-- Les tags -->
		<!-- Fin des tags -->

		<!-- Les followers -->
		<!-- Fin des followers -->
	</div>
</div>
