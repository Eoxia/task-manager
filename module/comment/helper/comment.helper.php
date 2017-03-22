<?php
/**
 * Fonctions helpers des commentaires
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 * @package Task-Manager\comment
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Compile le temps du commentaire dans le point parent et la tâche parente.
 *
 * @param Task_Comment_Class $data Les données de l'objet.
 *
 * @return Task_Comment_Class Les données de l'objet modifié.
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
function compile_time( $data ) {
	$point = Point_Class::g()->get( array(
		'status' => '-34070',
		'comment__in' => array( $data->parent_id ),
	), true );

	$task = Task_Class::g()->get( array(
		'include' => array( $data->post_id ),
	), true );

	if ( 'trash' === $data->status ) {
		$point->time_info['elapsed'] -= $data->time_info['elapsed'];
		$task->time_info['elapsed'] -= $data->time_info['elapsed'];
	} else {
		$point->time_info['elapsed'] += $data->time_info['elapsed'];
		$task->time_info['elapsed'] += $data->time_info['elapsed'];
	}

	$data->point = Point_Class::g()->update( $point );
	$data->task = Task_Class::g()->update( $task );

	$data->point = $point;
	$data->task = $task;

	return $data;
}
