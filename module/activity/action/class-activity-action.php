<?php
/**
 * Les actions relatives aux activitées.
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
 * Les actions relatives aux activitées.
 */
class Activity_Action {

	/**
	 * Initialise les actions liées aux activitées.
	 *
	 * @since 1.5.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_last_activity', array( $this, 'callback_load_last_activity' ) );
		add_action( 'wp_ajax_open_popup_user_activity', array( $this, 'load_customer_activity' ) );
		add_action( 'wp_ajax_export_activity', array( $this, 'callback_export_activity' ) );
	}

	/**
	 * Charges les évènements liés à la tâche puis renvoie la vue.
	 *
	 * @since 1.5.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_load_last_activity() {
		// check_ajax_referer( 'load_last_activity' );

		$tasks_id               = ! empty( $_POST['tasks_id'] ) ? sanitize_text_field( $_POST['tasks_id'] ) : '';
		$task_id                = $tasks_id;
		$offset                 = ! empty( $_POST['offset'] ) ? ( (int) $_POST['offset'] + \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page ) : 0;
		$last_date              = ! empty( $_POST['last_date'] ) ? sanitize_text_field( $_POST['last_date'] ) : '';
		$term                   = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$categories_id_selected = ! empty( $_POST['categories_id_selected'] ) ? sanitize_text_field( $_POST['categories_id_selected'] ) : '';
		$follower_id_selected   = ! empty( $_POST['follower_id_selected'] ) ? (int) $_POST['follower_id_selected'] : 0;
		$frontend               = ! empty( $_POST['frontend'] ) ? true : false;
		$date_end               = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_end'] ) ? $_POST['tm_abu_date_end'] : current_time( 'Y-m-d' );
		$date_start             = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_start'] ) ? $_POST['tm_abu_date_start'] : current_time( 'Y-m-d' );

		if ( empty( $_POST['tm_abu_date_end'] ) ) {
			$date_end = current_time( 'Y-m-d' );
		}
		
		if ( empty( $_POST['tm_abu_date_start'] ) ) {
			$date_start = date( 'Y-m-d', strtotime( '-1 month', strtotime( $date_end ) ) );
		}

		// On récupère les éléments pour lesquels il faut afficher l'historique.
		if ( empty( $tasks_id ) ) {
			$tasks = Task_Class::g()->get_tasks( array(
				'posts_per_page' => \eoxia\Config_Util::$init['task-manager']->task->posts_per_page,
				'categories_id'  => $categories_id_selected,
				'term'           => $term,
				'users_id'       => $follower_id_selected,
			) );

			$tasks_id = array_map( function( $e ) {
				return $e->data['id'];
			}, $tasks );
		} else {
			$tasks_id = explode( ',', $tasks_id );
		}
		
		$datas = Activity_Class::g()->get_activity( $tasks_id, 0, $date_start, $date_end );

		ob_start();
		if ( ! empty( $tasks_id ) ) {
			\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/post-last-activity', array(
				'tasks_id'   => implode( ',', $tasks_id ),
				'datas'      => $datas,
				'date_start' => $date_start,
				'date_end'   => $date_end,
			) );
		}
		$view = ob_get_clean();

		wp_send_json_success( array(
			'namespace'        => ! $frontend ? 'taskManager' : 'taskManagerFrontend',
			'module'           => 'activity',
			'callback_success' => 'loadedLastActivity',
			'view'             => $view,
			'offset'           => $offset,
			'last_date'        => $last_date,
			'buttons_view'     => '',
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

		$user_id     = ! empty( $_POST['user_id_selected'] ) ? (int) $_POST['user_id_selected'] : 0;
		$customer_id = ! empty( $_POST['user']['customer_id'] ) ? (int) $_POST['user']['customer_id'] : 0;
		$date_end  = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_end'] ) ? $_POST['tm_abu_date_end'] : current_time( 'Y-m-d' );
		$date_start    = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_start'] ) ? $_POST['tm_abu_date_start'] : current_time( 'Y-m-d' );

		$datas = Activity_Class::g()->display_user_activity_by_date( $user_id, $date_end, $date_start, $customer_id );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'indicator', 'backend/daily-activity', array(
			'date_start'  => $date_end,
			'date_end'    => $date_start,
			'user_id'     => $user_id,
			'customer_id' => $customer_id,
			'datas'       => $datas,
		) );

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'indicator',
			'callback_success' => 'loadedCustomerActivity',
			'view'             => ob_get_clean(),
		) );
	}

	/**
	 * Export au format CSV les activités d'une personne.
	 *
	 * @since 1.7.1
	 */
	public function callback_export_activity() {
		$user_id     = ! empty( $_POST['user_id_selected'] ) ? (int) $_POST['user_id_selected'] : 0;
		$customer_id = ! empty( $_POST['user']['customer_id'] ) ? (int) $_POST['user']['customer_id'] : 0;
		$date_end  = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_start'] ) ? $_POST['tm_abu_date_start'] : current_time( 'Y-m-d' );
		$date_start    = ! empty( $_POST ) && ! empty( $_POST['tm_abu_date_end'] ) ? $_POST['tm_abu_date_end'] : current_time( 'Y-m-d' );

		$datas = Activity_Class::g()->display_user_activity_by_date( $user_id, $date_end, $date_start, $customer_id );

		$upload_dir   = wp_upload_dir();
		$current_time = current_time( 'YmdHis' );
		$directory    = $upload_dir['basedir'] . '/task-manager/export/';

		wp_mkdir_p( $directory );

		$filepath    = $directory . $current_time . '_activity.csv';
		$url_to_file = $upload_dir['baseurl'] . '/task-manager/export/' . $current_time . '_activity.csv';

		$csv_file = fopen( $filepath, 'a' );

		fputcsv( $csv_file, array(
			'date'     => 'Date du commentaire',
			'user'     => 'Auteur du commentaire',
			'customer' => 'Client',
			'task'     => 'Tâche',
			'point'    => 'Point',
			'comment'  => 'Contenu du commentaire',
			'time'     => 'Temps passé (minutes)',
		), ',' );

		if ( ! empty( $datas ) ) {
			foreach ( $datas as $data ) {
				$date = \eoxia\Date_Util::g()->fill_date( $data->COM_DATE );
				$com_details = ( ! empty( $data->COM_DETAILS ) ? json_decode( $data->COM_DETAILS ) : '' );
				$user_data   = get_userdata( $data->COM_author_id );

				$search = array( '<br>', '&nbsp;', '&gt;', '&quot;', '&amp;', '&#039;' );
				$replace = array( PHP_EOL, ' ', '>', '"', 'é', '\'' );

				$data_to_export = array(
					'date'     => $date['date_time'],
					'user'     => $user_data->user_nicename,
					'customer' => $data->PT_title,
					'task'     => '#' . $data->T_ID . ' - ' . str_replace( $search, $replace, $data->T_title ),
					'point'    => '#' . $data->POINT_ID . ' - ' . str_replace( $search, $replace, $data->POINT_title ),
					'comment'  => str_replace( $search, $replace, $data->COM_title ),
					'time'     => ! empty( $com_details->time_info->elapsed ) ? $com_details->time_info->elapsed : 0,
				);

				fputcsv( $csv_file, $data_to_export, ',' );
			}
		}

		fclose( $csv_file );

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'activity',
			'callback_success' => 'exportedActivity',
			'url_to_file'      => $url_to_file,
			'filename'         => $current_time . '_activity.csv',
		) );
	}
}

new Activity_Action();
