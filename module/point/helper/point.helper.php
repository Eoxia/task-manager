<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

function update_post_order( $point ) {
	$task = Task_Class::g()->get( array(
		'post__in' => array( $point->post_id ),
	), true );

	$task->task_info['order_point_id'][] = $point->id;

	Task_Class::g()->update( $task );

	return $point;
}
