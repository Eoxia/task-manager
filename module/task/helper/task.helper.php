<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Task_Helper {
	public static function update_points( $data ) {
		$compiled_time = 0;
		$points = Point_Class::g()->get( array(
			'post_id' => $data->id,
			'parent' => 0,
			'order' => 'ASC',
		) );
		$points_ids = $new_points_ids = array();
		foreach ( $points as $point ) {
			if ( 0 !== $point->id || 'trash' !== $point->status ) {
				$compiled_time += (int) $point->time_info['elapsed'];
				if ( ! empty( $data->task_info ) && ( $key = array_search( $point->id, $data->task_info['order_point_id'], true ) ) !== false ) {
					$points_ids[ $key ] = $point->id;
				} else {
					$new_points_ids[] = $point->id;
				}
			}
		}
		ksort( $points_ids );
		$points_ids = array_values( $points_ids );
		foreach ( $new_points_ids as $point_id ) {
			$points_ids[] = $point_id;
		}
		$data->task_info['order_point_id'] = $points_ids;
		$data->time_info['elapsed'] = $compiled_time;

		return $data;
	}
}

/**
 * Récupères toutes les données essentielles d'une tâche
 * -Tache
 * -Dernier history time
 *
 * @param Task_Model $data Les données de la tâche.
 *
 * @return Task_Model     Les données de la tâche modifié.
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
function get_full_task( $data ) {
	$data->last_history_time = History_Time_Class::g()->get( array(
		'post_id' => $data->id,
		'number' => 1,
	), true );

	if ( empty( $data->last_history_time->id ) ) {
		$data->last_history_time = History_Time_Class::g()->get( array(
			'schema' => true,
		), true );
	}

	$format = '%hh %imin';
	$dtf = new \DateTime( '@0' );

	/** Gestion de l'affichage du temps passé en jours/heures */
	$dtt = new \DateTime( '@' . ( $data->time_info['elapsed'] * 60 ) );
	if ( 1440 <= $data->time_info['elapsed'] ) {
		$format = '%aj %hh %imin';
	}
	$data->time_info['time_display'] = $dtf->diff( $dtt )->format( $format );

	/** Gestion de l'affichage du temps estimé en jours/heures */
	$dtt = new \DateTime( '@' . ( $data->last_history_time->estimated_time * 60 ) );
	if ( 1440 <= $data->last_history_time->estimated_time ) {
		$format = '%aj %hh %imin';
	}
	$data->time_info['estimated_time_display'] = $dtf->diff( $dtt )->format( $format );

	// Fix TMP.
	if ( ! isset( $data->user_info['affected_id'] ) ) {
		$data->user_info['affected_id'] = array();
	}

	return $data;
}
