<?php
/**
 * Vu principale de la modal qui charge la vue point.view dans le module Point.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\eoxia\View_Util::exec(
	'task-manager',
	'point',
	'backend/point',
	array(
		'point'       => $point,
		'parent_id'   => $task_id,
		'comment_id'  => 0,
		'point_id'    => $point->data['id'],
		'quick_point' => true,
	)
); ?>
<input type="hidden" name="tm_point_is_quick_point" value="true" />
