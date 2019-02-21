<?php
/**
 * Check si un utilisateur est connecté
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
}

if ( true === is_user_logged_in() ) :
	\eoxia\View_Util::exec(
		'task-manager',
		'comment',
		'frontend/edit',
		array(
			'task_id'  => $task_id,
			'point_id' => $point_id,
			'comment'  => $comment_schema,
		)
	);
endif;

\eoxia\View_Util::exec(
	'task-manager',
	'comment',
	'frontend/list-comment',
	array(
		'comments' => $comments,
	)
);
