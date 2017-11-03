<?php
/**
 * Les actions relatives aux activitées.
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
 * Les actions relatives aux activitées.
 */
class Activity_Action {

	/**
	 * Initialise les actions liées aux activitées.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_last_activity', array( $this, 'callback_load_last_activity' ) );
		add_action( 'wp_ajax_open_popup_user_activity', array( $this, 'load_customer_activity' ) );
	}

	/**
	 * Charges les évènements liés à la tâche puis renvoie la vue.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_load_last_activity() {
		$title = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
		$tasks_id = ! empty( $_POST['tasks_id'] ) ? sanitize_text_field( $_POST['tasks_id'] ) : '';
		$offset = ! empty( $_POST['offset'] ) ? (int) $_POST['offset'] : 0;
		$last_date = ! empty( $_POST['last_date'] ) ? sanitize_text_field( $_POST['last_date'] ) : '';
		$term = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$categories_id_selected = ! empty( $_POST['categories_id_selected'] ) ? sanitize_text_field( $_POST['categories_id_selected'] ) : '';
		$follower_id_selected = ! empty( $_POST['follower_id_selected'] ) ? (int) $_POST['follower_id_selected'] : 0;
		$frontend = ! empty( $_POST['frontend'] ) ? true : false;

		if ( empty( $tasks_id ) ) {
			$tasks = Task_Class::g()->get_tasks( array(
				'posts_per_page' => \eoxia\Config_Util::$init['task-manager']->task->posts_per_page,
				'categories_id' => $categories_id_selected,
				'term' => $term,
				'users_id' => $follower_id_selected,
			) );

			$tasks_id = array_map( function( $e ) {
				return $e->id;
			}, $tasks );
		} else {
			$tasks_id = explode( ',', $tasks_id );
		}

		$datas = Activity_Class::g()->get_activity( $tasks_id, $offset );

		if ( ! empty( $offset ) ) {
			$offset += \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page;
		} else {
			$offset = \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page;
		}

		$last_date = $datas['last_date'];
		unset( $datas['last_date'] );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/list', array(
			'datas' => $datas,
			'last_date' => $last_date,
		) );

		$view = ob_get_clean();

		$data_search = Navigation_Class::g()->get_search_result( $term, $categories_id_selected, $follower_id_selected );
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/title', array(
			'term' => $data_search['term'],
			'categories_searched' => $data_search['categories_searched'],
			'follower_searched' => $data_search['follower_searched'],
			'have_search' => $data_search['have_search'],
		) );
		$title_popup = ob_get_clean();

		if ( ! empty( $title_popup ) ) {
			$title_popup = ':' . $title_popup;
		}

		wp_send_json_success( array(
			'namespace' => ! $frontend ? 'taskManager' : 'taskManagerFrontendWPShop',
			'module' => ! $frontend ? 'activity' : 'frontendSupport',
			'callback_success' => 'loadedLastActivity',
			'view' => $view,
			'title_popup' => $title . $title_popup,
			'offset' => $offset,
			'last_date' => $last_date,
			'end' => ( 0 === count( $datas ) ) ? true : false,
		) );
	}

	/**
	 * Load user activity by date
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function load_customer_activity() {
		check_ajax_referer( 'load_user_activity' );

		$customer_id = get_current_user_id();
		$date_start = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_start'] ) ? $_POST['tm_abu_date_start'] : current_time( 'Y-m-d' );
		$date_end = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_end'] ) ? $_POST['tm_abu_date_end'] : current_time( 'Y-m-d' );

		$datas = Activity_Class::g()->display_user_activity_by_date( $customer_id, $date_start, $date_end );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'indicator', 'backend/daily-activity', array(
			'date_start' => $date_start,
			'date_end' => $date_end,
			'datas' => $datas,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'indicator',
			'callback_success' => 'loadedCustomerActivity',
			'view' => ob_get_clean(),
		) );
	}

}

new Activity_Action();
