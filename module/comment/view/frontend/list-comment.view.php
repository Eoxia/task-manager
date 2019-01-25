<?php
/**
 * La liste des commentaires dans le frontend.
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

if ( ! empty( $comments ) ) :
	foreach ( $comments as $comment ) :
		\eoxia\View_Util::exec(
			'task-manager',
			'comment',
			'frontend/comment',
			array(
				'comment' => $comment,
			)
		);
	endforeach;
endif;
