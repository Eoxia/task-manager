<?php
/**
 * La classe gérant les temps dépassées.
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
 * La classe gérant les temps dépassées.
 */
class Time_Exceeded_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Appelle la méthode display.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_submenu_page() {
		$min_exceeded_time = \eoxia\Config_Util::$init['task-manager']->time_exceeded->default_time_exceeded;
		$start_date = new \DateTime( 'first day of this month' );
		$start_date = $start_date->format( 'Y-m-d' );
		$end_date = new \DateTime( 'last day of this month' );
		$end_date = $end_date->format( 'Y-m-d' );

		\eoxia\View_Util::exec( 'task-manager', 'time_exceeded', 'backend/main', array(
			'start_date' => $start_date,
			'end_date' => $end_date,
			'min_exceeded_time' => $min_exceeded_time,
		) );
	}

	/**
	 * Fait le rendu des tâches dont le temps passé dépasse le temps estimé.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @param string  $start_date        La date de début au format mysql.
	 * @param string  $end_date          La date de fin au format mysql.
	 * @param integer $min_time_exceeded Le temps dépassé minimum.
	 *
	 * @return void
	 */
	public function display( $start_date, $end_date, $min_time_exceeded, $require_time_history = false ) {
		$tasks = Task_Class::g()->get( array(
			'date_query' => array(
				array(
					'column' => 'post_date_gmt',
					'after' => $start_date,
					'inclusive' => true,
				),
				array(
					'column' => 'post_modified_gmt',
					'before' => $end_date,
					'inclusive' => true,
				),
			),
			'posts_per_page' => -1,
		) );
		$tasks_exceed_time = array();

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $key => $task ) {

				if ( $require_time_history ) {

					if ( ! empty( $task->last_history_time ) && ! empty( $task->last_history_time->id ) && ( $task->time_info['elapsed'] - $task->last_history_time->estimated_time ) > $min_time_exceeded ) {
						$tasks_exceed_time[] = $task;
					}

					$task->time_displayed = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->time_info['elapsed'] ) . ' / ' . \eoxia\Date_Util::g()->convert_to_custom_hours( $task->last_history_time->estimated_time );
					$task->diff_time = $task->time_info['elapsed'] - $task->last_history_time->estimated_time;

				} else {
					if ( $task->time_info['elapsed'] > $min_time_exceeded ) {
						$tasks_exceed_time[] = $task;

						$task->time_displayed = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->time_info['elapsed'] );
						$task->diff_time = $task->time_info['elapsed'] - $min_time_exceeded;
					}
				}

				$task->time_exceeded_displayed = '';

				if ( ! empty( $task->diff_time ) ) {
					$task->time_exceeded_displayed = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->diff_time );
				}
			}
		}

		if ( ! empty( $tasks_exceed_time ) ) {
			foreach ( $tasks_exceed_time as $task ) {
				$task->task_parent = __( 'No parent', 'task-manager' );

				if ( ! empty( $task->parent_id ) ) {
					$task->task_parent  = get_post( $task->parent_id );
				}
			}
		}

		usort( $tasks_exceed_time, function( $a, $b ) {
			if ( $a->diff_time == $b->diff_time ) {
				return 0;
			}

			return ( $a->diff_time > $b->diff_time ) ? -1 : 1;
		} );

		\eoxia\View_Util::exec( 'task-manager', 'time_exceeded', 'backend/list', array(
			'tasks_exceed_time' => $tasks_exceed_time,
		) );
	}
}

Time_Exceeded_Class::g();
