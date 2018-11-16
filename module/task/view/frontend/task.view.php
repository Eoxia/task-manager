<?php
/**
 * La vue d'une tâche dans le frontend.
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

<div class="wpeo-project-task <?php echo esc_attr( $task->data['front_info']['display_color'] ); ?>" data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
	<div class="wpeo-project-task-container">

		<!-- En tête de la tâche -->
		<div class="wpeo-task-header">
			<div class="wpeo-task-main-header">
				<div class="wpeo-task-main-info" >
					<div class="wpeo-task-title">
						<div contenteditable="false" class="wpeo-project-task-title"><?php echo esc_html( $task->data['title'] ); ?></div>
					</div>
					<ul class="wpeo-task-summary" >
						<li class="wpeo-task-id"><i class="far fa-hashtag"></i> <?php echo esc_html( $task->data['id'] ); ?></li>

						<li class="wpeo-task-time-history">
							<i class="far fa-clock"></i>
							<span class="elapsed"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'], false ) ); ?></span> /
							<span class="estimated"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['last_history_time']->data['estimated_time'], false ) ); ?></span>
						</li>

						<?php echo apply_filters( 'tm_task_header_summary', '', $task ); // WPCS: XSS ok. ?>
					</ul>
				</div>
			</div>
			<ul class="wpeo-task-filter" >
				<?php echo apply_filters( 'tm_task_header', '', $task ); // WPCS: XSS ok. ?>
			</ul>
		</div>

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->data['id'], true ); ?>
		<!-- Fin corps de la tâche -->
	</div>
</div>
