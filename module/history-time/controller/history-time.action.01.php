<?php
/**
 * Ajax file.
 *
 * @package HistoryTime
 */

if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Class for Ajax actions on history_time.
 */
class History_time_action_01 {
	/**
	 * Define hooks.
	 */
	public function __construct() {
		add_action( 'wp_ajax_create_history_time', array( &$this, 'ajax_create_history_time' ) );
		add_action( 'wp_ajax_delete_history_time', array( &$this, 'ajax_delete_history_time' ) );
	}

	/**
	 * Ajax function to create history_time
	 *
	 * @return void JSON
	 */
	public function ajax_create_history_time() {
		check_ajax_referer( 'create_history_time' );

		global $history_time_controller;
		global $task_controller;
		global $wp_project_user_controller;

		if ( empty( $_POST['task_id'] ) || ! $task_id = absint( $_POST['task_id'] ) ) { // Input var okay.
			wp_send_json_error();
		}
		if ( empty( $_POST['due_date'] ) || ! $due_date = $history_time_controller->formatDate( sanitize_text_field( wp_unslash( $_POST['due_date'] ) ) ) ) { // Input var okay.
			wp_send_json_error();
		}
		if ( empty( $_POST['estimated_time'] ) || ! $estimated_time = absint( $_POST['estimated_time'] ) ) { // Input var okay.
			wp_send_json_error();
		}
		$return = array();

		$history_time = $history_time_controller->create( array(
			'post_id' => $task_id,
			'author_id' => get_current_user_id(),
			'date' => current_time( 'mysql' ),
			'option' => array(
				'due_date' => $due_date,
				'estimated_time' => $estimated_time,
			),
		) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_HISTORY_TIME_DIR, WPEO_HISTORY_TIME_TEMPLATES_MAIN_DIR, 'backend', 'history-time' ) );
		$return['template'] = ob_get_clean();

		$task = $task_controller->show( $history_time->post_id );
		if ( ! empty( $task ) ) {
			$return['task_header_information'] = apply_filters( 'task_header_information', '', $task );
		}

		wp_send_json_success( $return );
	}

	/**
	 * Ajax function delete history_time
	 *
	 * @return void JSON
	 */
	public function ajax_delete_history_time() {
		check_ajax_referer( 'delete_history_time' );

		global $history_time_controller;
		global $task_controller;

		if ( empty( $_POST['history_time'] ) || ! $history_time_id = absint( $_POST['history_time'] ) ) { // Input var okay.
			wp_send_json_error();
		}
		$return = array();

		$history_time = $history_time_controller->show( $history_time_id );
		$history_time_controller->delete( $history_time_id );

		$task = $task_controller->show( $history_time->post_id );
		if ( ! empty( $task ) ) {
			$return['to_task_id'] = $task->id;
			$return['task_header_information'] = apply_filters( 'task_header_information', '', $task );
		}

		wp_send_json_success( $return );
	}
}

new History_time_action_01();
