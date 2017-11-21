<?php
/**
 * La classe gérant Les indications.
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
 * La classe gérant Les indications.
 */
class Indicator_Class extends \eoxia\Singleton_Util {

	protected function construct() {}

	public function callback_submenu_page() {
		$closed_meta_box = get_user_meta( get_current_user_id(), 'closedpostboxes_tache_page_task-manager-indicator' );
		$order_meta_box = get_user_meta( get_current_user_id(), 'meta-box-order_tache_page_task-manager-indicator' );

		\eoxia\View_Util::exec( 'task-manager', 'indicator', 'backend/main', array(
			'closed_meta_box' => $closed_meta_box,
			'order_meta_box' => $order_meta_box,
		) );
	}

	public function callback_my_daily_activity() {
		$customer_id = get_current_user_id();
		$date_start = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_start'] ) ? $_POST['tm_abu_date_start'] : current_time( 'Y-m-d' );
		$date_end = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_end'] ) ? $_POST['tm_abu_date_end'] : current_time( 'Y-m-d' );
		$first_load = ! empty( $_GET ) && ! empty( $_GET['first_load'] ) ? $_GET['first_load'] : false;

		$datas = Activity_Class::g()->display_user_activity_by_date( $customer_id, $date_start, $date_end );

		\eoxia\View_Util::exec( 'task-manager', 'indicator', 'backend/daily-activity', array(
			'date_start' => $date_start,
			'date_end' => $date_end,
			'datas' => $datas,
		) );
	}
}

new Indicator_Class();
