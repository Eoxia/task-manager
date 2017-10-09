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
}

Activity_Class::g();
