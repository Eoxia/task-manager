<?php
/**
 * Gestion des activitées.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
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
	 * @version 1.6.0
	 *
	 * @param array   $tasks_id        L'ID des tâches parents.
	 * @param integer $offset          Le nombre de résultat à passer.
	 * @param integer $nb_per_page     Le nombre d'élément à afficher par page.
	 * @param array   $activities_type Le type des éléments que l'on souhaite afficher.
	 *
	 * @return array            La liste des commentaires et points.
	 */
	public function get_activity( $tasks_id, $offset, $date_start = '', $date_end = '', $nb_per_page = 0, $activities_type = array( 'created-point', 'completed-point', 'created-comment' ) ) {
		$points = Point_Class::g()->get( array(
			'post__in' => $tasks_id,
			'number'   => empty( $nb_per_page ) ? \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page : $nb_per_page,
			'offset'   => $offset,
			'type__in' => array(
				Point_Class::g()->get_type(),
				Task_Comment_Class::g()->get_type(),
			),
		) );

		$datas              = array();
		$datas['count']     = 0;
		$datas['last_date'] = '';

		if ( ! empty( $points ) ) {
			foreach ( $points as $point ) {
				if ( 'trash' !== $point->data['status'] && ( Point_Class::g()->get_type() === $point->data['type'] || Task_Comment_Class::g()->get_type() === $point->data['type'] ) && 0 !== $point->data['id'] ) {
					if ( 0 === $point->data['parent_id'] ) {
						$point->data['view']                = 'created-point';
						$point->data['displayed_author_id'] = $point->data['author_id'];
						$point->data['userdata']            = get_userdata( $point->data['author_id'] );
						$point->data['displayed_username']  = $point->data['userdata']->display_name;

						if ( $point->data['point_info']['completed'] ) {
							$cloned_point                              = clone $point;
							$cloned_point->data['view']                = 'completed-point';
							$cloned_point->data['displayed_author_id'] = $cloned_point->data['time_info']['last_completed']['user_id'];
							$datas['last_date']                        = $cloned_point->data['date']['raw'];
							$sql_date                                  = substr( $cloned_point->data['time_info']['last_completed']['date'], 0, strlen( $cloned_point->data['time_info']['last_completed']['date'] ) - 9 );
							$time                                      = substr( $cloned_point->data['time_info']['last_completed']['date'], 11, strlen( $cloned_point->data['time_info']['last_completed']['date'] ) );
							if ( in_array( $cloned_point->data['view'], $activities_type, true ) ) {
								if ( $date_start >= $sql_date && $date_end <= $sql_date ) {
									$datas[ $sql_date ][ $time ][] = $cloned_point;
								}
							}
							$datas['count']++;
						}

						if ( empty( $datas['last_date'] ) || ( ! empty( $datas['last_date'] ) && strtotime( $datas['last_date'] ) < $point->data['date']['raw'] ) ) {
							$datas['last_date'] = $point->data['date']['raw'];
						}
						
						$sql_date           = substr( $point->data['date']['raw'], 0, strlen( $point->data['date']['raw'] ) - 9 );
						$time               = substr( $point->data['date']['raw'], 11, strlen( $point->data['date']['raw'] ) );
						
						if ( in_array( $point->data['view'], $activities_type, true ) ) {
							if ( $date_start >= $sql_date && $date_end <= $sql_date ) {
								$datas[ $sql_date ][ $time ][] = $point;
							}
						}
						$datas['count']++;
					} else {
						$comment = Task_Comment_Class::g()->get( array(
							'id' => $point->data['id'],
						), true );

						$comment->data['view'] = 'created-comment';

						$comment->data['parent'] = Point_Class::g()->get( array(
							'id' => $comment->data['parent_id'],
						), true );

						if ( empty( $comment->data['parent'] ) ) {
							continue;
						}

						$comment->data['displayed_author_id'] = $comment->data['author_id'];

						if ( ! empty( $comment->data['author_id'] ) ) {
							$comment->data['userdata']           = get_userdata( $comment->data['author_id'] );
							$comment->data['displayed_username'] = $comment->data['userdata']->display_name;
						} else {
							$comment->data['displayed_username'] = '-';
						}
						
						if ( empty( $datas['last_date'] ) || ( ! empty( $datas['last_date'] ) && strtotime( $datas['last_date'] ) < $point->data['date']['raw'] ) ) {
							if ( $date_start >= $sql_date && $date_end <= $sql_date ) {
								$datas['last_date'] = $comment->data['date']['raw'];
							}
						}
						$sql_date           = substr( $comment->data['date']['raw'], 0, strlen( $comment->data['date']['raw'] ) - 9 );
						$time               = substr( $comment->data['date']['raw'], 11, strlen( $comment->data['date']['raw'] ) );
						if ( in_array( $comment->data['view'], $activities_type, true ) ) {
							if ( $date_start >= $sql_date && $date_end <= $sql_date ) {
								$datas[ $sql_date ][ $time ][] = $comment;
							}
						}
						$datas['count']++;
					}
				}

				if ( empty( $datas['last_date'] ) ) {
					$datas['last_date'] = ! empty( $sql_date ) ? $sql_date : '';
				}
			}
		}
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
	public function display_user_activity_by_date( $user_id, $date_start = '', $date_end = '', $customer_id = 0 ) {
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}
		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		
		$query_string = 
		"SELECT TASK_PARENT.post_title as PT_title, TASK_PARENT.ID as PT_ID,
			TASK.post_title AS T_title, TASK.ID as T_ID,
			POINT.comment_content AS POINT_title, POINT.comment_ID AS POINT_ID,
			COMMENT.comment_content AS COM_title, COMMENT.comment_ID as COM_ID,
			COMMENTMETA.meta_value AS COM_DETAILS, COMMENT.comment_date AS COM_DATE,
			COMMENT.user_id AS COM_author_id
		FROM {$GLOBALS['wpdb']->comments} AS COMMENT
			INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = COMMENT.comment_ID
			INNER JOIN {$GLOBALS['wpdb']->users} AS USER ON COMMENT.user_id = USER.ID
			INNER JOIN {$GLOBALS['wpdb']->usermeta} AS USERMETA ON USER.ID = USERMETA.user_id AND USERMETA.meta_key = 'wp_user_level'
			INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = COMMENT.comment_parent
			INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
				LEFT JOIN {$GLOBALS['wpdb']->posts} AS TASK_PARENT ON TASK_PARENT.ID = TASK.post_parent
		WHERE COMMENT.comment_date >= %s
			AND USERMETA.meta_value = 10
			AND COMMENT.comment_date <= %s
			AND COMMENTMETA.meta_key = %s
			AND COMMENT.comment_approved != 'trash'
			AND POINT.comment_approved != 'trash'
			AND TASK.post_status IN ( 'archive', 'publish', 'inherit' ) ";
			
		if ( ! empty( $user_id ) ) {
			$query_string .= "AND COMMENT.user_id = " . $user_id . " ";
		}
			
		if ( ! empty( $customer_id ) ) {
			$query_string .= "AND TASK.post_parent = " . $customer_id . " ";
		}
			
		$query_string .= "ORDER BY COMMENT.comment_date DESC";
		
		$query = $GLOBALS['wpdb']->prepare( $query_string, $date_start . ' 00:00:00', $date_end . ' 23:59:59', 'wpeo_time' );
		$datas = $GLOBALS['wpdb']->get_results( $query );
		return $datas;
	}

}

Activity_Class::g();
