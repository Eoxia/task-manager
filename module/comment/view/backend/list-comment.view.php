<?php
/**
 * La liste des commentaires dans le backend.
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

if ( ! empty( $comments ) ) :
	foreach ( $comments as $comment ) :
		if ( 0 !== $comment->id ) :
			\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/comment', array(
				'comment' => $comment,
				'comment_selected_id' => $comment_selected_id,
			) );
		endif;
	endforeach;
endif;
