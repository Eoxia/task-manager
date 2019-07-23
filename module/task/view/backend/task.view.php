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
<div class="wpeo-project-task <?php echo ! empty( $task->data['front_info']['display_color'] ) ? esc_attr( $task->data['front_info']['display_color'] ) : 'white'; ?>"
	data-id="<?php echo esc_attr( $task->data['id'] ); ?>"
	data-status="<?php echo esc_html( $task->data[ 'status' ] ) ?>">

	<div class="wpeo-project-task-container">

		<!-- En tête de la tâche -->
		<?php \eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header', array( 'task' => $task ) ); ?>
		<!-- Fin en tête de la tâche -->

		<div class="bloc-activities"></div>

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->data['id'] ); ?>
		<!-- Fin corps de la tâche -->
		<?php if( ! isset( $hide_footer ) || ! $hide_footer ): ?>
			<div class="wpeo-task-footer">
				<!-- Les tags -->
				<?php echo do_shortcode( '[task_manager_task_tag task_id=' . $task->data['id'] . ']' ); ?>
				<!-- Fin des tags -->

				<!-- Les followers -->
				<?php echo do_shortcode( '[task_manager_task_follower task_id=' . $task->data['id'] . ']' ); ?>
				<!-- Fin des followers -->

				<?php echo apply_filters( 'tm_task_footer', '', $task ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
