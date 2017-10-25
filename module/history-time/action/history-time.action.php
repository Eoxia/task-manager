<?php
/**
 * Les actions relatives à l'historique de temps.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives à l'historique de temps.
 */
class History_Time_Action {

	/**
	 * Initialise les actions liées à l'historique de temps.
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_time_history', array( $this, 'callback_load_time_history' ) );

		add_action( 'wp_ajax_create_history_time', array( $this, 'callback_create_history_time' ) );
		add_action( 'wp_ajax_delete_history_time', array( $this, 'callback_delete_history_time' ) );
	}

	/**
	 * Charges les historiques de temps de la tâche puis renvoie la vue à la requête AJAX.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 *
	 * @todo nonce
	 */
	public function callback_load_time_history() {
		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$history_times = History_Time_Class::g()->get( array(
			'post_id' => $task_id,
			'orderby' => 'ASC',
			'comment_approved' => '-34070',
		) );

		if ( ! empty( $history_times ) ) {
			foreach ( $history_times as $history_time ) {
				$history_time->author = get_userdata( $history_time->author_id );
			}
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'history-time', 'backend/main', array(
			'task_id' => $task_id,
			'history_times' => $history_times,
		) );

		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'namespace' => 'taskManager',
			'module' => 'historyTime',
			'callback_success' => 'loadedTimeHistorySuccess',
		) );
	}

	/**
	 * Créer un historique de temps et renvoie le contenu complet de la popup.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_create_history_time() {
		check_ajax_referer( 'create_history_time' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$due_date = ! empty( $_POST['due_date'] ) ? $_POST['due_date'] : '';
		$estimated_time = ! empty( $_POST['estimated_time'] ) ? (int) $_POST['estimated_time'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$history_time_created = History_Time_Class::g()->create( array(
			'post_id' => $task_id,
			'due_date' => $due_date,
			'estimated_time' => $estimated_time,
		) );

		$history_times = History_Time_Class::g()->get( array(
			'post_id' => $task_id,
			'orderby' => 'ASC',
			'comment_approved' => '-34070',
		) );

		if ( ! empty( $history_times ) ) {
			foreach ( $history_times as $history_time ) {
				$history_time->author = get_userdata( $history_time->author_id );
			}
		}

		do_action( 'tm_created_history_time', $history_time_created, $task_id, $due_date, $estimated_time );

		$task = Task_Class::g()->get( array(
			'id' => $task_id,
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header', array(
			'task' => $task,
		) );
		$task_header_view = ob_get_clean();

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'history-time', 'backend/main', array(
			'task_id' => $task_id,
			'history_times' => $history_times,
		) );
		$history_time_view = ob_get_clean();

		wp_send_json_success( array(
			'task_id' => $task_id,
			'history_time_view' => $history_time_view,
			'task_header_view' => $task_header_view,
			'namespace' => 'taskManager',
			'module' => 'historyTime',
			'callback_success' => 'createdHistoryTime',
		) );
	}

	/**
	 * Passes le status en "trash" d'un "history time".
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_delete_history_time() {
		check_ajax_referer( 'delete_history_time' );

		$history_time_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $history_time_id ) ) {
			wp_send_json_error();
		}

		$history_time = History_Time_Class::g()->get( array(
			'comment__in' => array( $history_time_id ),
		), true );

		$history_time->status = '-34071';

		History_Time_Class::g()->update( $history_time );

		$task = Task_Class::g()->get( array(
			'post__in' => array( $history_time->post_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header', array(
			'task' => $task,
		) );
		$task_header_view = ob_get_clean();

		do_action( 'tm_deleted_history_time', $history_time_id );

		wp_send_json_success( array(
			'task_id'						=> $history_time->post_id,
			'task_header_view'	=> $task_header_view,
			'namespace' => 'taskManager',
			'module' => 'historyTime',
			'callback_success' => 'deletedHistoryTime',
		) );
	}
}

new History_Time_Action();
