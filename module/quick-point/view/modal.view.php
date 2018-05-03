<?php
/**
 * Vu principale de la modal qui charge la vu point.view dans le module Point.
 *
 * @author ||||||||
 * @since 1.6.1
 * @version 1.6.1
 * @copyright 2018+
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
	'point'      => $point,
	'parent_id'  => $task_id,
	'comment_id' => 0,
	'point_id'   => $point->data['id'],
) );
