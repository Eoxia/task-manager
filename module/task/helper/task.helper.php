<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Task_Helper {
	public static function update_points( $data ) {
		$compiled_time = 0;
		$points = Point_Class::g()->get( array( 'post_id' => $data->id, 'parent' => 0, 'order' => 'ASC' ) );
		$points_ids = $new_points_ids = array();
		foreach ( $points as $point ) {
			$compiled_time += (int) $point->time_info['elapsed'];
			if ( ( $key = array_search( $point->id, $data->task_info['order_point_id'], true ) ) !== false ) {
				$points_ids[ $key ] = $point->id;
			} else {
				$new_points_ids[] = $point->id;
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
