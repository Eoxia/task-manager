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
	public function get_activity( $tasks_id, $offset, $date_end = '', $date_start = '', $nb_per_page = 0, $activities_type = array( 'created-point', 'completed-point', 'created-comment' ) ) {
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
		}
		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		
		$query_string = 
		"SELECT TASK.post_title AS T_title, TASK.ID as T_ID,
			POINT.comment_content AS POINT_title, POINT.comment_ID AS POINT_ID,
			CREATED_COMMENT.comment_content AS COM_title, CREATED_COMMENT.comment_ID as COM_ID,
			COMMENTMETA.meta_value AS COM_DETAILS, CREATED_COMMENT.comment_date AS COM_DATE,
			CREATED_COMMENT.user_id AS COM_author_id
		FROM {$GLOBALS['wpdb']->comments} AS CREATED_COMMENT
			INNER JOIN {$GLOBALS['wpdb']->commentmeta} AS COMMENTMETA ON COMMENTMETA.comment_id = CREATED_COMMENT.comment_ID
			INNER JOIN {$GLOBALS['wpdb']->comments} AS POINT ON POINT.comment_ID = CREATED_COMMENT.comment_parent
			INNER JOIN {$GLOBALS['wpdb']->posts} AS TASK ON TASK.ID = POINT.comment_post_ID
		WHERE CREATED_COMMENT.comment_date >= %s
			AND CREATED_COMMENT.comment_date <= %s
			AND CREATED_COMMENT.comment_approved != 'trash'
			AND TASK.ID IN( " . implode( ',', $tasks_id ) . " ) 
			AND TASK.post_status IN ( 'archive', 'publish', 'inherit' )";
			
		$query_string .= "ORDER BY CREATED_COMMENT.comment_date DESC";
		
		$query = $GLOBALS['wpdb']->prepare( $query_string, $date_end . ' 23:59:59', $date_start . ' 00:00:00' );
		$datas = $GLOBALS['wpdb']->get_results( $query );
		return $datas;
	}

	/**
	 * Récupères l'activité d'un utilisateur entre deux dates.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param  integer $user_id    L'ID de l'utilisateur.
	 * @param  string  $date_end Date de fin.
	 * @param  string  $date_start Date de début.
	 *
	 * @return array
	 */
	public function display_user_activity_by_date( $user_id, $date_end = '', $date_start = '', $customer_id = 0 ) {
		if ( empty( $date_end ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		if ( empty( $date_start ) ) {
			$date_start = current_time( 'Y-m-d' );
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

		$query = $GLOBALS['wpdb']->prepare( $query_string, $date_end . ' 23:59:59', $date_start . ' 00:00:00', 'wpeo_time' );
		$datas = $GLOBALS['wpdb']->get_results( $query );
		return $datas;
	}

}

Activity_Class::g();
