<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Point_Helper {
	public static function update_task( $data ) {
		$task = Task_Class::g()->get( array( 'post__in' => array( $data->post_id ) ) );
		if ( ! empty( $task ) ) {
			Task_Class::g()->update( $task[0] );
		}
		return $data;
	}
}
