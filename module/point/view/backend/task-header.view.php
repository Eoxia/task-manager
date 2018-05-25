<?php
/**
 * La vue permettant d'afficher les bouttons de choix du type de points affichés dans une tâche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?><li class="point-type-display-buttons" >
	<button class="wpeo-button button-grey active button-radius-3" data-points-loaded="true" >
		<i class="button-icon fal fa-square"></i>
		<span><?php echo esc_html( sprintf( __( 'Uncompleted (%d)', 'task-manager' ), $task->data['count_uncompleted_points'] ) ); ?></span>
	</button>
	<button class="wpeo-button button-grey button-radius-3" data-points-loaded="false" >
		<i class="button-icon fal fa-check-square"></i>
		<span><?php echo esc_html( sprintf( __( 'Completed (%d)', 'task-manager' ), $task->data['count_completed_points'] ) ); ?></span>
	</button>
</li>
