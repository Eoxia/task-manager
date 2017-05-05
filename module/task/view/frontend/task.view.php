<?php
/**
 * La vue d'une tâche dans le frontend.
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

<div class="wpeo-project-task <?php echo $task->front_info['display_color']; ?>" data-id="<?php echo esc_attr( $task->id ); ?>">
	<div class="wpeo-project-task-container">

		<!-- En tête de la tâche -->
		<ul class="wpeo-task-header">
			<li class="wpeo-task-id">#<?php echo esc_html( $task->id ); ?></li>

			<li class="wpeo-task-title">
				<h2><?php echo esc_html( $task->title ); ?></h2>
			</li>

			<li class="wpeo-task-elapsed">
				<i class="dashicons dashicons-clock"></i>
				<span class="elapsed"><?php echo esc_html( $task->time_info['time_display'] . ' (' . $task->time_info['elapsed'] . 'min)' ); ?></span>
			</li>
		</ul>
		<!-- Fin en tête de la tâche -->

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->id, true ); ?>
		<!-- Fin corps de la tâche -->
	</div>
</div>
