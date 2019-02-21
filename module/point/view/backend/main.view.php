<?php
/**
 * La vue principale des points dans le backend.
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

<div class="points sortable">
	<?php
	\eoxia\View_Util::exec(
		'task-manager',
		'point',
		'backend/point',
		array(
			'point'      => $point_schema,
			'comment_id' => $comment_id,
			'point_id'   => $point_id,
			'parent_id'  => $task_id,
		)
	);

	\eoxia\View_Util::exec(
		'task-manager',
		'point',
		'backend/points',
		array(
			'comment_id' => $comment_id,
			'point_id'   => $point_id,
			'parent_id'  => $task_id,
			'points'     => $points_uncompleted,
		)
	);
	?>
</div>
