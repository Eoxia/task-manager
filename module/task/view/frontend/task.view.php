<?php
/**
 * La vue d'une tâche dans le frontend.
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

<div class="wpeo-project-task <?php echo esc_attr( $task->front_info['display_color'] ); ?>" data-id="<?php echo esc_attr( $task->id ); ?>">
	<div class="wpeo-project-task-container">

		<!-- En tête de la tâche -->
		<ul class="wpeo-task-header">
			<li class="wpeo-task-id">#<?php echo esc_html( $task->id ); ?></li>

			<li class="wpeo-task-title">
				<h2><?php echo esc_html( $task->title ); ?></h2>
			</li>

			<li class="wpeo-task-elapsed">
				<i class="dashicons dashicons-clock"></i>
				<span class="elapsed"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->time_info['elapsed'], false ) ); ?></span>/
				<span class="estimated"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->last_history_time->estimated_time, false ) ); ?></span>
			</li>
		</ul>
		<!-- Fin en tête de la tâche -->

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->id, true ); ?>
		<!-- Fin corps de la tâche -->
	</div>
</div>
