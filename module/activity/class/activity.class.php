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
	 * [get_activity description]
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param  array $task_id [description]
	 * @param  [type] $offset  [description]
	 * @return [type]          [description]
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

						if ( $point->point_info['completed'] ) {
							$point->view = 'completed-point';
						}

						$sql_date = substr( $point->date['date_input']['date'], 0, strlen( $point->date['date_input']['date'] ) - 9 );
						$time = substr( $point->date['date_input']['date'], 11, strlen( $point->date['date_input']['date'] ) );
						$datas[ $sql_date ][ $time ][] = $point;
					} else {
						$point->view = 'created-comment';
						$point->parent = Point_Class::g()->get( array(
							'id' => $point->parent_id,
						), true );

						$sql_date = substr( $point->date['date_input']['date'], 0, strlen( $point->date['date_input']['date'] ) - 9 );
						$time = substr( $point->date['date_input']['date'], 11, strlen( $point->date['date_input']['date'] ) );
						$datas[ $sql_date ][ $time ][] = $point;
					}
				}
			}
		}

		$datas['last_date'] = ! empty( $sql_date ) ? $sql_date : '';
		return $datas;
	}
}

Activity_Class::g();
