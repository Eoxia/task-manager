<?php
/**
 * Fonctions helpers des commentaires
 *
 * @since 1.3.6
 * @version 1.5.0
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Compile le temps du commentaire dans le point parent et la tâche parente.
 *
 * @param Task_Comment_Class $data Les données de l'objet.
 *
 * @return Task_Comment_Class Les données de l'objet modifié.
 *
 * @since 1.3.6
 * @version 1.5.0
 */
function compile_time( $data ) {
	$point = Point_Class::g()->get( array(
		'id' => $data->parent_id,
	), true );

	$task = Task_Class::g()->get( array(
		'id' => $data->post_id,
	), true );

	if ( 'trash' === $data->status ) {
		$point->time_info['elapsed'] -= $data->time_info['elapsed'];
		$task->time_info['elapsed'] -= $data->time_info['elapsed'];
	} else {
		if ( 0 !== $data->id ) {
			$comment = Task_Comment_Class::g()->get( array(
				'id' => $data->id,
			), true );

			$point->time_info['elapsed'] -= $comment->time_info['elapsed'];
			$task->time_info['elapsed'] -= $comment->time_info['elapsed'];
		}

		$point->time_info['elapsed'] += $data->time_info['elapsed'];
		$task->time_info['elapsed'] += $data->time_info['elapsed'];
	}

	$point->content = addslashes( $point->content );
	$data->point = Point_Class::g()->update( $point );
	$data->task = Task_Class::g()->update( $task );

	$data->point = $point;
	$data->task = $task;

	return $data;
}

/**
 * CAlcul le temps écoulé depuis le dernier commentaire ajouté pour ne pas avoir a faire le calcul dans le commentaire a chaque changement de projet.
 *
 * @param Task_Comment_Class $data Les données de l'objet.
 *
 * @return Task_Comment_Class Les données de l'objet modifié.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
function calcul_elapsed_time( $data ) {
	if ( in_array( get_current_user_id(), array( 286, 381 ), true ) ) {
		// Récupération du dernier commentaire ajouté dans la base.
		$query = $GLOBALS['wpdb']->prepare(
			"SELECT COMMENT.comment_date AS COM_DATE
			FROM {$GLOBALS['wpdb']->comments} AS COMMENT
				INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_ID
				INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = COMMENT.comment_parent
				INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
			WHERE COMMENT.user_id = %d
				AND COMMENT.comment_date >= %s
				AND COMMENTMETA.meta_key = %s
				AND COMMENT.comment_approved != 'trash'
				AND POINT.comment_approved != 'trash'
				AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )
			ORDER BY COMMENT.comment_date DESC
			LIMIT 1",
			get_current_user_id(), current_time( 'Y-m-d 00:00:00' ), 'wpeo_time'
		);
		$last_comment_date = $GLOBALS['wpdb']->get_var( $query );
		if ( ! empty( $last_comment_date ) ) {
			$datetime1 = new \DateTime( $last_comment_date );
			$datetime2 = new \DateTime( current_time( 'mysql' ) );
			$interval = $datetime2->diff( $datetime1 );
			$data->time_info['calculed_elapsed'] = $interval->format( '%I' );
		}
	}

	return $data;
}
