<?php if ( !defined( 'ABSPATH' ) ) exit;

class time_action_01 {
	public function __construct() {
    add_action( 'wp_ajax_create_point_time', array( &$this, 'ajax_create_point_time' ) );
    add_action( 'wp_ajax_get_point_time', array( &$this, 'ajax_get_point_time' ) );
    add_action( 'wp_ajax_nopriv_get_point_time', array( &$this, 'ajax_get_point_time' ) );
    add_action( 'wp_ajax_delete_point_time', array( &$this, 'ajax_delete_point_time' ) );
	}

	public function ajax_create_point_time() {
		// wpeo_check_01::check( 'wpeo_nonce_create_point_time_' . $_POST['point_time']['parent_id'] );

		global $time_controller;
		global $point_controller;

		$response = array();

		$_POST['point_time']['date'] .= ' ' . current_time( 'H:i:s' ); //$_POST['point_time']['time']

		if ( !empty( $_POST['point_time_id'] ) ) {
			/** Edit the point */
			$point_time = $time_controller->show( $_POST['point_time_id'] );
			$point_time->option['time_info']['old_elapsed'] = $point_time->option['time_info']['elapsed'];
			$point_time->date = $_POST['point_time']['date'];
			$point_time->option['time_info']['elapsed'] = $_POST['point_time']['option']['time_info']['elapsed'];
			$point_time->content = $_POST['point_time']['content'];

			$list_object = $time_controller->update($point_time);
		}
		else {
			/** Add the point */
			$_POST['point_time']['status'] = '-34070';
			$list_object = $time_controller->create( $_POST['point_time'] );
		}

		$point = $point_controller->show( $_POST['point_time']['parent_id'] );
		$time = $list_object['time'];
		$task = $list_object['task'];

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'time' ) );
		wp_send_json_success( array( 'template' => ob_get_clean(), 'task' => $task, 'point' => $point, 'time' => $time, 'task_header_information' => apply_filters( 'task_header_information', '', $task ) ) );
	}

	public function ajax_get_point_time() {
		// wpeo_check_01::check( 'wpeo_nonce_get_point_time_' . $_POST['point_time_id'] );

		if (  0 === is_int( ( int )$_POST['point_time_id'] ) )
			wp_send_json_error();
		else
			$point_time_id = $_POST['point_time_id'];

		global $time_controller;
		$point_time = $time_controller->show( $point_time_id );

		$date = explode( ' ', $point_time->date );

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'time-edit' ) );
		wp_send_json_success( array( 'point_time_id' => $point_time_id, 'template' => ob_get_clean() ) );
	}

	public function ajax_delete_point_time() {
		// wpeo_check_01::check( 'wpeo_nonce_delete_point_time_' . $_POST['point_time_id'] );

		global $time_controller;
		global $point_controller;

		$response = array();
		$task = $time_controller->delete( $_POST['point_time_id'] );
		$point = $point_controller->show( $_POST['point_id'] );

		wp_send_json_success( array( 'task' => $task, 'point' => $point, 'task_header_information' => apply_filters( 'task_header_information', '', $task ) ) );
	}
}

global $time_action;
$time_action = new time_action_01();
