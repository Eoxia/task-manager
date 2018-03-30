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
	 * @return array La liste des tâches/points ayant dépassé le temps par rapport aux critères.
	 */
	public function get_exceeded_elements( $start_date, $end_date, $min_time_exceeded, $require_time_history = false ) {
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

				if ( $require_time_history ) {

					if ( ! empty( $task->last_history_time ) && ! empty( $task->last_history_time->id ) && ( $task->time_info['elapsed'] - $task->last_history_time->estimated_time ) > $min_time_exceeded ) {
						$tasks_exceed_time[] = $task;
					}

					$task->time_displayed = convert_to_custom_hours( $task->time_info['elapsed'] ) . ' / ' . convert_to_custom_hours( $task->last_history_time->estimated_time );
					$task->diff_time      = $task->time_info['elapsed'] - $task->last_history_time->estimated_time;

				} else {
					if ( $task->time_info['elapsed'] > $min_time_exceeded ) {
						$tasks_exceed_time[] = $task;

						$task->time_displayed = convert_to_custom_hours( $task->time_info['elapsed'] );
						$task->diff_time      = $task->time_info['elapsed'] - $min_time_exceeded;
					}
				}

				$task->time_exceeded_displayed = '';

				if ( ! empty( $task->diff_time ) ) {
					$task->time_exceeded_displayed = convert_to_custom_hours( $task->diff_time );
				}
			}
		}

		if ( ! empty( $tasks_exceed_time ) ) {
			foreach ( $tasks_exceed_time as $task ) {
				$task->task_parent = __( 'No parent', 'task-manager' );

				if ( ! empty( $task->parent_id ) ) {
					$task->task_parent = get_post( $task->parent_id );
				}
			}
		}

		usort( $tasks_exceed_time, function( $a, $b ) {
			if ( $a->diff_time === $b->diff_time ) {
				return 0;
			}

			return ( $a->diff_time > $b->diff_time ) ? -1 : 1;
		} );

		return $tasks_exceed_time;
	}

	/**
	 * Fait le rendu des tâches dont le temps passé dépasse le temps estimé.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function display() {
		\eoxia\View_Util::exec( 'task-manager', 'time_exceeded', 'backend/list', array(
			'tasks_exceed_time' => $this->get_exceeded_elements(),
		) );
	}

}

Time_Exceeded_Class::g();
