<?php
/**
 * La liste des followers en mode édition.
 *
 * @package Task Manager
 * @subpackage Module/Follower
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $users ) ) :
	foreach ( $users as $user ) :
		\eoxia\View_Util::exec(
			'task-manager',
			'follower',
			'backend/follower-edit',
			array(
				'user'    => $user,
				'task_id' => $task->id,
			)
		);
	endforeach;
endif;
