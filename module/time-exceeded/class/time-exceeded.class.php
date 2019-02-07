<?php
/**
 * La classe gérant les temps dépassées.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.7.1
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
	 * @version 1.7.1
	 *
	 * @return void
	 */
	public function display() {
		$module_name = 'time-exceeded';

		$start_date        = date( 'Y-m-d', strtotime( 'first day of this month' ) );
		$end_date          = date( 'Y-m-d', strtotime( 'last day of this month' ) );
		$min_exceeded_time = \eoxia\Config_Util::$init['task-manager']->$module_name->default_time_exceeded;
		$filter_type       = \eoxia\Config_Util::$init['task-manager']->$module_name->default_filter_type;

		\eoxia\View_Util::exec(
			'task-manager',
			'time-exceeded',
			'backend/main',
			array(
				'start_date'        => $start_date,
				'end_date'          => $end_date,
				'min_exceeded_time' => $min_exceeded_time,
				'filter_type'       => $filter_type,
			)
		);
	}

	/**
	 * Récupère la liste des temps dépassé à afficher
	 *
	 * @since 1.5.0
	 * @version 1.6.1
	 *
	 * @param string  $start_date        La date de début au format mysql.
	 * @param string  $end_date          La date de fin au format mysql.
	 * @param integer $min_time_exceeded Le temps dépassé minimum.
	 * @param integer $differential_type Faut il vérifier les tâches avec un prévisionnel ou uniquement les tâches dépassant le temps indiqué.
	 *
	 * @return void
	 */
	public function display_exceeded_elements( $start_date, $end_date, $min_time_exceeded, $differential_type = '' ) {
		$tasks_exceed_time = array();
		$tasks             = Task_Class::g()->get(
			array(
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
			)
		);

		if ( empty( $differential_type ) ) {
			$differential_type = \eoxia\Config_Util::$init['task-manager']->$module_name->default_filter_type;
		}

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $key => $task ) {
				$task_exceed = false;

				// Check time between define time.
				$diff_time = 0;
				if ( ! empty( $task->data['last_history_time'] ) && ! empty( $task->data['last_history_time']->data['id'] ) ) {
					$diff_time = $task->data['last_history_time']->data['estimated_time'] - $task->data['time_info']['elapsed'];
				}

				if ( 'history_time' === $differential_type ) {
					$diff_time = $task->data['last_history_time']->data['estimated_time'] - $task->data['time_info']['elapsed'];
					if ( ! empty( $task->data['last_history_time'] ) && ! empty( $task->data['last_history_time']->data['id'] ) && ( $diff_time < 0 ) ) {
						$task_exceed = true;
					}

					$task->data['time_displayed'] = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'] ) . ' / ' . \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['last_history_time']->data['estimated_time'] );
					$task->data['diff_time']      = \eoxia\Date_Util::g()->convert_to_custom_hours( abs( $diff_time ) );
				} else {
					if ( ( empty( $task->data['last_history_time'] ) || ( ! empty( $task->data['last_history_time'] ) && empty( $task->data['last_history_time']->data['id'] ) ) ) && $task->data['time_info']['elapsed'] > $min_time_exceeded ) {
						$task_exceed = true;
					}

					$task->data['time_displayed'] = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'] );
					$task->data['diff_time']      = \eoxia\Date_Util::g()->convert_to_custom_hours( abs( $task->data['time_info']['elapsed'] - $min_time_exceeded ) );
				}

				$task->data['time_exceeded_displayed'] = '';
				if ( ! empty( $task->data['diff_time'] ) ) {
					$task->data['time_exceeded_displayed'] = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['diff_time'] );
				}

				$task->data['task_parent'] = __( 'No parent', 'task-manager' );
				if ( ! empty( $task->data['parent_id'] ) ) {
					$task->data['task_parent'] = get_post( $task->data['parent_id'] );
				}

				if ( $task_exceed ) {
					$tasks_exceed_time[] = $task;
				}
			}
		}

		usort(
			$tasks_exceed_time,
			function( $a, $b ) {
				if ( $a->diff_time === $b->diff_time ) {
					return 0;
				}

				return ( $a->diff_time > $b->diff_time ) ? -1 : 1;
			}
		);

		\eoxia\View_Util::exec(
			'task-manager',
			'time-exceeded',
			'backend/list',
			array(
				'tasks_exceed_time' => $tasks_exceed_time,
			)
		);
	}

}

Time_Exceeded_Class::g();
