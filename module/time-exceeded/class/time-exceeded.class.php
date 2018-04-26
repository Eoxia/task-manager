<?php
/**
 * La classe gérant les temps dépassées.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.1
 * @copyright 2015-2018 Eoxia
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
	 * Fait le rendu des tâches dont le temps passé dépasse le temps estimé.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function display() {
		$module_name = 'time-exceeded';

		$require_time_history = ! empty( $_POST['require_time_history'] ) && ( 'true' === $_POST['require_time_history'] ) ? true : false; // Toujours sur ON. A corrigé après manger.
		$min_exceeded_time    = ! empty( $_POST['min_exceeded_time'] ) ? (int) $_POST['min_exceeded_time'] : \eoxia\Config_Util::$init['task-manager']->$module_name->default_time_exceeded;
		$start_date           = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : date( 'Y-m-d', strtotime( 'first day of this month' ) );
		$end_date             = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : date( 'Y-m-d', strtotime( 'last day of this month' ) );

		\eoxia\View_Util::exec( 'task-manager', 'time-exceeded', 'backend/main', array(
			'start_date'           => $start_date,
			'end_date'             => $end_date,
			'min_exceeded_time'    => $min_exceeded_time,
			'require_time_history' => $require_time_history,
		) );
	}

	/**
	 * Récupère la liste des temps dépassé à afficher
	 *
	 * @since 1.5.0
	 * @version 1.6.1
	 *
	 * @param string  $start_date           La date de début au format mysql.
	 * @param string  $end_date             La date de fin au format mysql.
	 * @param integer $min_time_exceeded    Le temps dépassé minimum.
	 * @param integer $require_time_history Faut il vérifier absolument que le temps soit indiqué dans le prévisionnel.
	 *
	 * @return void
	 */
	public function display_exceeded_elements( $start_date, $end_date, $min_time_exceeded, $require_time_history = false ) {
		$tasks_exceed_time = array();
		$tasks             = Task_Class::g()->get( array(
			'date_query'     => array(
				array(
					'column'    => 'post_date_gmt',
					'after'     => $start_date,
					'inclusive' => true,
				),
				array(
					'column'    => 'post_modified_gmt',
					'before'    => $end_date,
					'inclusive' => true,
				),
			),
			'posts_per_page' => -1,
		) );

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $key => $task ) {
				$task_data = $task->data;

				if ( $require_time_history ) {

					if ( ! empty( $task_data['last_history_time'] ) && ! empty( $task_data['last_history_time']->id ) && ( $task_data['time_info']['elapsed'] - $task_data['last_history_time']->estimated_time ) > $min_time_exceeded ) {
						$tasks_exceed_time[] = $task;
					}

					$task_data['time_displayed'] = \eoxia\Date_Util::g()->convert_to_custom_hours( $task_data['time_info']['elapsed'] ) . ' / ' . \eoxia\Date_Util::g()->convert_to_custom_hours( $task_data['last_history_time']->estimated_time );
					$task_data['diff_time']      = $task_data['time_info']['elapsed'] - $task_data['last_history_time']->estimated_time;

				} else {
					if ( $task_data['time_info']['elapsed'] > $min_time_exceeded ) {
						$tasks_exceed_time[] = $task;

						$task_data['time_displayed'] = \eoxia\Date_Util::g()->convert_to_custom_hours( $task_data['time_info']['elapsed'] );
						$task_data['diff_time']      = $task_data['time_info']['elapsed'] - $min_time_exceeded;
					}
				}

				$task_data['time_exceeded_displayed'] = '';

				if ( ! empty( $task_data['diff_time'] ) ) {
					$task_data['time_exceeded_displayed'] = \eoxia\Date_Util::g()->convert_to_custom_hours( $task_data['diff_time'] );
				}
			}
		}

		if ( ! empty( $tasks_exceed_time ) ) {
			foreach ( $tasks_exceed_time as $task ) {
				$task_data['task_parent'] = __( 'No parent', 'task-manager' );

				if ( ! empty( $task_data['parent_id'] ) ) {
					$task_data['task_parent'] = get_post( $task_data['parent_id'] );
				}
			}
		}

		usort( $tasks_exceed_time, function( $a, $b ) {
			if ( $a->diff_time === $b->diff_time ) {
				return 0;
			}

			return ( $a->diff_time > $b->diff_time ) ? -1 : 1;
		} );

		\eoxia\View_Util::exec( 'task-manager', 'time-exceeded', 'backend/list', array(
			'tasks_exceed_time' => $tasks_exceed_time,
		) );
	}

}

Time_Exceeded_Class::g();
