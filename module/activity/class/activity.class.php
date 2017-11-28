<?php
/**
 * Gestion des activitées.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des activitées.
 */
class Activity_Class extends \eoxia\Singleton_Util {
	/**
	 * Constructeur obligatoire pour Singleton_Util.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Récupères les commentaires et les points dans l'ordre de date décroissante.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param array   $tasks_id L'ID des tâches parents.
	 * @param integer $offset   Le nombre de résultat à passer.
	 * @return array            La liste des commentaires et points.
	 */
	public function get_activity( $tasks_id, $offset ) {
		$points = Point_Class::g()->get( array(
			'post__in' => $tasks_id,
			'status' => -34070,
			'number' => \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page,
			'offset' => $offset,
		) );

		$datas = array();

		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				if ( 'trash' !== $point->status && Point_Class::g()->get_type() === $point->type && 0 !== $point->id ) {
					if ( 0 === $point->parent_id ) {
						$point->view = 'created-point';
						$point->displayed_author_id = $point->author_id;
						$point->userdata = get_userdata( $point->author_id );
						$point->displayed_username = $point->userdata->display_name;

						if ( $point->point_info['completed'] ) {
							$cloned_point = clone $point;
							$cloned_point->view = 'completed-point';
							$cloned_point->displayed_author_id = $cloned_point->time_info['last_completed']['user_id'];
							$sql_date = substr( $cloned_point->time_info['last_completed']['date'], 0, strlen( $cloned_point->time_info['last_completed']['date'] ) - 9 );
							$time = substr( $cloned_point->time_info['last_completed']['date'], 11, strlen( $cloned_point->time_info['last_completed']['date'] ) );
							$datas[ $sql_date ][ $time ][] = $cloned_point;
						}

						$sql_date = substr( $point->date['date_input']['date'], 0, strlen( $point->date['date_input']['date'] ) - 9 );
						$time = substr( $point->date['date_input']['date'], 11, strlen( $point->date['date_input']['date'] ) );
						$datas[ $sql_date ][ $time ][] = $point;
					} else {
						$comment = Task_Comment_Class::g()->get( array(
							'id' => $point->id,
						), true );
						$comment->view = 'created-comment';
						$comment->parent = Point_Class::g()->get( array(
							'id' => $comment->parent_id,
						), true );
						$comment->displayed_author_id = $comment->author_id;
						$comment->userdata = get_userdata( $comment->author_id );
						$comment->displayed_username = $comment->userdata->display_name;

						$sql_date = substr( $comment->date['date_input']['date'], 0, strlen( $comment->date['date_input']['date'] ) - 9 );
						$time = substr( $comment->date['date_input']['date'], 11, strlen( $comment->date['date_input']['date'] ) );
						$datas[ $sql_date ][ $time ][] = $comment;
					}
				}
			}
		}

		$datas['last_date'] = ! empty( $sql_date ) ? $sql_date : '';
		return $datas;
	}

	/**
	 * Récupères l'activité d'un utilisateur entre deux dates.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param  integer $user_id    L'ID de l'utilisateur.
	 * @param  string  $date_start Date de début.
	 * @param  string  $date_end   Date de fin.
	 *
	 * @return array
	 */
	public function display_user_activity_by_date( $user_id, $date_start = '', $date_end = '' ) {
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}
		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}

		$query = $GLOBALS['wpdb']->prepare(
			"SELECT TASK_PARENT.post_title as PT_title, TASK_PARENT.ID as PT_ID,
							TASK.post_title AS T_title, TASK.ID as T_ID,
							POINT.comment_content AS POINT_title, POINT.comment_ID AS POINT_ID,
							COMMENT.comment_content AS COM_title, COMMENT.comment_ID as COM_ID,
							COMMENTMETA.meta_value AS COM_DETAILS, COMMENT.comment_date AS COM_DATE
			FROM {$GLOBALS['wpdb']->comments} AS COMMENT
				INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_ID
				INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = COMMENT.comment_parent
				INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
					LEFT JOIN {$GLOBALS['wpdb']->posts} AS TASK_PARENT ON TASK_PARENT.ID = TASK.post_parent
			WHERE COMMENT.user_id = %d
				AND COMMENT.comment_date >= %s
				AND COMMENT.comment_date <= %s
				AND COMMENTMETA.meta_key = %s
				AND COMMENT.comment_approved != 'trash'
				AND POINT.comment_approved != 'trash'
				AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )
			ORDER BY COMMENT.comment_date DESC",
			$user_id, $date_start . ' 00:00:00', $date_end . ' 23:59:59', 'wpeo_time'
		);
		$datas = $GLOBALS['wpdb']->get_results( $query );

		return $datas;
	}

}

Activity_Class::g();
