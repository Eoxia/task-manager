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
}
$wp_kses_args = array(
	'span' => array(
		'class' => array(),
	),
);
?><li class="point-type-display-buttons" >
	<div class="wpeo-button button-grey active button-radius-3" data-point-state="uncompleted"
		data-action="load_point"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">
		<i class="button-icon fas fa-square"></i>
		<span><?php /* Translators: %s stands for uncompleted points number. */ echo wp_kses( sprintf( __( 'Uncompleted (%s)', 'task-manager' ), '<span class="point-uncompleted" >' . $task->data['count_uncompleted_points'] . '</span>' ), $wp_kses_args ); ?></span>
	</div>
	<div class="wpeo-button button-grey button-radius-3 action-input" data-point-state="completed"
		data-action="load_point"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_point' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>" >
		<i class="button-icon fas fa-check-square"></i>
		<span><?php /* Translators: %s stands for completed points number. */ echo wp_kses( sprintf( __( 'Completed (%s)', 'task-manager' ), '<span class="point-completed" >' . $task->data['count_completed_points'] . '</span>' ), $wp_kses_args ); ?></span>
	</div>
</li>
