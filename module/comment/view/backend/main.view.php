<?php
/**
 * La vue principale des commentaires dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/edit', array(
	'task_id' => $task_id,
	'point_id' => $point_id,
	'comment' => $comment_schema,
) );

\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/list-comment', array(
	'comments' => $comments,
	'comment_selected_id' => $comment_selected_id,
) );
