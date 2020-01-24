<?php
/**
 * Les actions relatives à l'historique de temps.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.7.0
 * @copyright 2015-2018 Eoxia
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
	 * @since 1.0.0
	 * @version 1.3.6
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_time_history', array( $this, 'callback_load_time_history' ) );

		add_action( 'wp_ajax_create_history_time', array( $this, 'callback_create_history_time' ) );
		add_action( 'wp_ajax_delete_history_time', array( $this, 'callback_delete_history_time' ) );
	}

	/**
	 * Charges les historiques de temps de la tâche puis renvoie la vue à la requête AJAX.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_load_time_history() {
		check_ajax_referer( 'load_time_history' );
		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		ob_start();
		History_Time_Class::g()->display_histories_time( $task_id );
		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'buttons_view'     => '',
			)
		);
	}

	/**
	 * Créer un historique de temps et renvoie le contenu complet de la popup.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_create_history_time() {
		check_ajax_referer( 'create_history_time' );

		$task_id        = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$due_date       = ! empty( $_POST['due_date'] ) ? $_POST['due_date'] : '';
		$custom         = ! empty( $_POST['custom'] ) ? sanitize_text_field( $_POST['custom'] ) : '';
		$estimated_time = ! empty( $_POST['estimated_time'] ) ? (int) $_POST['estimated_time'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$history_time_created = History_Time_Class::g()->update(
			array(
				'post_id'        => $task_id,
				'due_date'       => $due_date,
				'estimated_time' => $estimated_time,
				'custom'         => $custom,
			),
			true
		);

		do_action( 'tm_created_history_time', $history_time_created, $task_id, $due_date, $estimated_time );

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$key = 'time';
		ob_start();
		$data_def = array(
			'value'   => '',
			'classes' => 'table-100',
			'attrs'   => array(),
			'type'    => 'wpeo-task',
		);

		$data = apply_filters( 'tm_projects_content_wpeo-task_time_def', $data_def, $task );
		?>
		<div class="table-cell <?php echo esc_attr( $data['classes'] ); ?>"
			<?php echo ! empty( $data['attrs'] ) ? implode( ' ', $data['attrs'] ) : ''; ?>>
			<?php
			\eoxia\View_Util::exec( 'task-manager', 'task', 'New/render/wpeo-task-time', array(
				'data_def' => $data_def,
				'data'     => $data,
				'key'      => $key,
			) );
			?>
		</div>
		<?php

		$task_header_view = ob_get_clean();

		ob_start();
		History_Time_Class::g()->display_histories_time( $task_id );
		$history_time_view = ob_get_clean();

		wp_send_json_success(
			array(
				'task_id'           => $task_id,
				'history_time_view' => $history_time_view,
				'task_header_view'  => $task_header_view,
				'namespace'         => 'taskManager',
				'module'            => 'historyTime',
				'callback_success'  => 'createdHistoryTime',
			)
		);
	}

	/**
	 * Passes le status en "trash" d'un "history time".
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.7.0
	 */
	public function callback_delete_history_time() {
		check_ajax_referer( 'delete_history_time' );

		$history_time_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $history_time_id ) ) {
			wp_send_json_error();
		}

		$history_time = History_Time_Class::g()->get(
			array(
				'id' => $history_time_id,
			),
			true
		);

		$history_time->data['status'] = 'trash';

		History_Time_Class::g()->update( $history_time->data );

		$task = Task_Class::g()->get(
			array(
				'id' => $history_time->data['post_id'],
			),
			true
		);

		$key = 'time';
		ob_start();
		$data_def = array(
			'value'   => '',
			'classes' => 'table-100',
			'attrs'   => array(),
			'type'    => 'wpeo-task',
		);

		$data = apply_filters( 'tm_projects_content_wpeo-task_time_def', $data_def, $task );
		?>
		<div class="table-cell <?php echo esc_attr( $data['classes'] ); ?>"
			<?php echo ! empty( $data['attrs'] ) ? implode( ' ', $data['attrs'] ) : ''; ?>>
			<?php
			\eoxia\View_Util::exec( 'task-manager', 'task', 'New/render/wpeo-task-time', array(
				'data_def' => $data_def,
				'data'     => $data,
				'key'      => $key,
			) );
			?>
		</div>
		<?php

		$task_header_view = ob_get_clean();

		do_action( 'tm_deleted_history_time', $history_time_id );

		wp_send_json_success(
			array(
				'task_id'          => $history_time->data['post_id'],
				'task_header_view' => $task_header_view,
				'namespace'        => 'taskManager',
				'module'           => 'historyTime',
				'callback_success' => 'deletedHistoryTime',
			)
		);
	}
}

new History_Time_Action();
