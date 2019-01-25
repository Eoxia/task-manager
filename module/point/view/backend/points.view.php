<?php
/**
 * La vue des points.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $points ) ) :
	foreach ( $points as $point ) :
		\eoxia\View_Util::exec(
			'task-manager',
			'point',
			'backend/point',
			array(
				'point'      => $point,
				'comment_id' => $comment_id,
				'point_id'   => $point_id,
				'parent_id'  => $point->data['post_id'],
			)
		);
	endforeach;
endif;
