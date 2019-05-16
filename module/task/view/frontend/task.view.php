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
						<li class="wpeo-task-id"><i class="fas fa-hashtag"></i> <?php echo esc_html( $task->data['id'] ); ?></li>

						<li class="wpeo-task-time-history">
							<i class="fas fa-clock"></i>
							<span class="elapsed"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'], false ) ); ?></span> /
							<span class="estimated"><?php echo esc_html( \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['last_history_time']->data['estimated_time'], false ) ); ?></span>
						</li>
					</ul>
				</div>
			</div>
			<ul class="wpeo-task-filter" >
				<li class="point-type-display-buttons" >
					<button class="wpeo-button button-grey active button-radius-3" data-point-state="uncompleted"
						data-action="load_point"
						data-frontend="true"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point' ) ); ?>"
						data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">
						<i class="button-icon fas fa-square"></i>
						<span><?php /* Translators: %s stands for uncompleted points number. */ echo sprintf( __( 'Uncompleted (%s)', 'task-manager' ), '<span class="point-uncompleted" >' . $task->data['count_uncompleted_points'] . '</span>' ); ?></span>
					</button>
					<button class="wpeo-button button-grey button-radius-3 action-input" data-point-state="completed"
						data-action="load_point"
						data-frontend="true"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point' ) ); ?>"
						data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>" >
						<i class="button-icon fas fa-check-square"></i>
						<span><?php /* Translators: %s stands for completed points number. */ echo sprintf( __( 'Completed (%s)', 'task-manager' ), '<span class="point-completed" >' . $task->data['count_completed_points'] . '</span>' ); ?></span>
					</button>
				</li>

				<li class="tm-task-display-method-buttons">
					<button class="wpeo-button button-grey button-radius-3 list-display active wpeo-tooltip-event"
						aria-label="<?php echo esc_attr_e( 'Edit display', 'task-manager' ); ?>">

						<i class="button-icon fas fa-list"></i>
					</button>

					<button class="wpeo-button button-grey button-radius-3 action-attribute grid-display wpeo-tooltip-event"
						data-action="load_last_activity"
						data-frontend="true"
						aria-label="<?php echo esc_attr_e( 'Activity display', 'task-manager' ); ?>"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_last_activity' ) ); ?>"
						data-tasks-id="<?php echo esc_attr( $task->data['id'] ); ?>">

						<i class="button-icon fas fa-align-left"></i>
					</button>
				</li>

			</ul>
		</div>

		<div class="bloc-activities"></div>

		<!-- Corps de la tâche -->
		<?php Point_Class::g()->display( $task->data['id'], true ); ?>
		<!-- Fin corps de la tâche -->
	</div>
</div>
