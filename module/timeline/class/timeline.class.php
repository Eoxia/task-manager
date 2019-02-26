<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Timeline_Class extends \eoxia\Singleton_Util {
	private $min_year = 2016;
	public $array_color = array( 'e15352', '46e58a', 'eaad4a' );

	public function construct() {}

	public function callback_submenu_page() {
		// $list_user = Follower_Class::g()->get();

		// $list_year = $this->generate_year();
		$list_year = array( '2017' );
		$current_month = date( 'n' );

		\eoxia\View_Util::exec( 'task-manager', 'timeline', 'backend/main', array(
			'list_user' => array(),
			'list_year' => $list_year,
			'current_month' => $current_month,
		) );
	}

	/**
	 * Tant que $current_year est différent de $min_year, ajoutes un élément dans
	 * le tableau $list_year de $current_year - 1.
	 *
	 * @return array string
	 */
	public function generate_year() {
		$list_year = array( date( 'Y' ) );
		// $current_year = $list_year[0];
		//
		// // if( $current_year < $this->min_year ) {
		// // 	do {
		// // 		$current_year--;
		// 		$list_year[] = $current_year;
		//
		// 	} while( $current_year != $this->min_year );
		// }
		return $list_year;
	}

	public function render_month( $user_id, $year, $month ) {
		$start_date_time = new \DateTime( $year . '-' . $month );
		$end_date_time = clone( $start_date_time );
		$start_date_time->modify( 'this week' );
		if ( $month === date( 'm' ) && $year === date( 'Y' ) ) {
			$end_date_time->setISODate( $year, date( 'W' ) );
			$end_date_time->modify( 'next week' );
		} else {
			$end_date_time->modify( 'next month' );
		}
		$end_date_time->modify( 'this week, -1 seconds' );

		$week_date_period = array_reverse( iterator_to_array( new \DatePeriod( $start_date_time, \DateInterval::createFromDateString( '+1 week' ), $end_date_time ) ) );

		$results = Timeline_Class::g()->get_all_data( $user_id );
		\eoxia\View_Util::exec( 'task-manager', 'timeline', 'backend/month', array(
			'results' => $results,
			'week_date_period' => $week_date_period,
			'month' => $month,
			'year' => $year,
		) );
	}

	public function render_week( $year, $month, $week, $w, $results ) {
		$year = '2018';
		$month = '08';
		$start_date_time = new \DateTime( $year . '-' . $month );
		$start_date_time->setISODate( $year, $week );
		if( $week == date( 'W' ) && $month == date( 'm' ) && $year == date( 'Y' ) ) {
			$end_date_time = new \DateTime( date( 'Y' ) . '-' . date( 'm' ) . '-' . date( 'd' ) );
			$end_date_time->modify('+1 day, -1 seconds');
		} else {
			$end_date_time = clone( $start_date_time );
			$end_date_time->modify('+1 week, -1 seconds');
		}
		$day_date_period = new \DatePeriod( $start_date_time, new \DateInterval('P1D'), $end_date_time );

		\eoxia\View_Util::exec( 'task-manager', 'timeline', 'backend/week', array(
			'week' => $w,
			'results' => $results,
			'day_date_period' => $day_date_period,
		) );
	}

	public function render_day( $user_id, $year, $month, $day, $results ) {
		echo "<pre>"; print_r($results['list_messages']); echo "</pre>";exit;
		\eoxia\View_Util::exec( 'task-manager', 'timeline', 'backend/day', array(
			'list_message' => $results['list_messages'],
			'day' => $day,
			'year' => $year,
			'month' => $month,
		) );
	}

	public function get_all_data( $user_id ) {
		// Récupères les tâches créé (par l'user)
		// Récupères les points créer (par l'user)
		// Récupères les points complétés (par l'user)
		// Récupères le temps travaillé (par l'user)
		global $wpdb;

		$query = "SELECT task.ID as task_id, task.post_date as task_create_date, point.comment_ID as point_id, point.comment_date as point_create_date, comment.comment_ID as comment_id, comment.comment_date as comment_create_date
								FROM {$wpdb->posts} as task
									LEFT JOIN {$wpdb->comments} AS point ON task.ID=point.comment_post_ID
									LEFT JOIN {$wpdb->comments} AS comment ON task.ID=comment.comment_post_ID AND point.comment_ID=comment.comment_parent
								WHERE task.post_type='wpeo-task'
								ORDER BY task.post_date, point.comment_date, comment.comment_date";

		$query_results = $wpdb->get_results( $query, 'ARRAY_A' );

		$results = array(
			'list_messages' => array(),
			'worked_time' => 0,
			'number_task_created' => 0,
			'number_point_created' => 0,
			'number_point_completed' => 0,
		);

		if ( ! empty( $query_results ) ) {
			foreach ( $query_results as $element ) {
				if ( ! empty( $element['task_id'] ) ) {
					$results['list_messages'][ $element['task_create_date'] ][] = __( 'Create the task #', 'task-manager' ) . $element['task_id'];
				}

				if ( ! empty( $element['point_id'] ) ) {
					$results['list_messages'][ $element['point_create_date'] ][] = __( 'Create the point #', 'task-manager' ) . $element['point_id'];
				}

				if ( ! empty( $element['comment_id'] ) ) {
					$results['list_messages'][ $element['comment_create_date'] ][] = __( 'Create the comment #', 'task-manager' ) . $element['comment_id'];
				}
			}
		}

		return $results;
	}
}

Timeline_Class::g();
