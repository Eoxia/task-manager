<?php
/**
 * Fonctions "helper" des points
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package point
 * @subpackage helper
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Met à jour l'ordre des points
 *
 * @param  Point_Model $point Les données du point.
 *
 * @return Point_Model Les données du point modifié
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
function update_post_order( $point ) {
	if ( ! empty( $point->post_id ) ) {
		$task = Task_Class::g()->get( array(
			'post__in' => array( $point->post_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$task->task_info['order_point_id'][] = $point->id;

		Task_Class::g()->update( $task );
	}

	return $point;
}

/**
 * Récupères toutes les données du point
 *
 * @param  Point_Model $point Les données du point.
 *
 * @return Point_Model Les données du point modifié
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */
function get_full_point( $point ) {
	$point->count_comments = count( \task_manager\Task_Comment_Class::g()->get( array(
		'post_id' => $point->post_id,
		'parent' => $point->id,
		'status' => '-34070',
	) ) );

	return $point;
}
