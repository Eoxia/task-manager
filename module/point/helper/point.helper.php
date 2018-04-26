<?php
/**
 * Fonctions "helper" des points
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Récupères toutes les données du point
 *
 * @param  Point_Model $point Les données du point.
 *
 * @return Point_Model Les données du point modifié
 *
 * @since 1.0.0
 * @version 1.6.0
 */
function get_full_point( $point ) {
	// $point->content = parse_content_tooltip( $point->content );

	$last_completed = array(
		'date'    => '',
		'user_id' => 0,
	);

	if ( ! empty( $point->data['time_info']['completed_point'] ) ) {
		foreach ( $point->data['time_info']['completed_point'] as $user_id => $element ) {
			if ( ! empty( $element ) ) {
				foreach ( $element as $date ) {
					if ( $date > $last_completed['date'] ) {
						$last_completed['date']    = $date;
						$last_completed['user_id'] = $user_id;
					}
				}
			}
		}
	}

	$point->data['time_info']['last_completed'] = $last_completed;

	return $point;
}

function parse_content_tooltip( $content ) {
	preg_match_all( '/#(\d*)/', $content, $matches );

	if ( ! empty( $matches[1] ) ) {
		$comments = \task_manager\Task_Comment_Class::g()->get( array(
			'comment__in' => $matches[1],
			'status' => 1,
		) );

		if ( ! empty( $comments ) ) {
			foreach ( $comments as $comment ) {
				if ( ! empty( $comment->id ) ) {
					$content = preg_replace( '/#' . $comment->id . '/', "<b contenteditable='false' class='tooltip hover' aria-label='" . htmlspecialchars( substr( $comment->content, 0, 100 ) ) . "'>#" . $comment->id . "</b>", $content );
				}
			}
		}
	}

	return $content;
}
