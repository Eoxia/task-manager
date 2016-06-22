<?php if ( !defined( 'ABSPATH' ) ) exit;

class time_action_01 {
	public function __construct() {
    add_action( 'wp_ajax_create_point_time', array( &$this, 'ajax_create_point_time' ) );
    add_action( 'wp_ajax_get_point_time', array( &$this, 'ajax_get_point_time' ) );
    add_action( 'wp_ajax_nopriv_get_point_time', array( &$this, 'ajax_get_point_time' ) );
    add_action( 'wp_ajax_delete_point_time', array( &$this, 'ajax_delete_point_time' ) );
	}

	public function ajax_create_point_time() {
		global $time_controller;

		if ( !check_ajax_referer( 'ajax_create_point_time', array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create point time: invalid nonce', 'task-manager' ) ) );
		}

		$point_time_data = !empty( $_POST['point_time'] ) ? (array) $_POST['point_time'] : array();
		$point_time_data['post_id'] = !empty( $_POST['point_time']['post_id'] ) ? (int) $_POST['point_time']['post_id'] : 0;
		$point_time_data['parent_id'] = !empty( $_POST['point_time']['parent_id'] ) ? (int) $_POST['point_time']['parent_id'] : 0;
		$point_time_data['author_id'] = !empty( $_POST['point_time']['author_id'] ) ? (int) $_POST['point_time']['author_id'] : 0;
		$point_time_data['content'] = !empty( $_POST['point_time']['content'] ) ? sanitize_text_field( $_POST['point_time']['content'] ) : '';
		$point_time_data['time'] = !empty( $_POST['point_time']['time'] ) ? sanitize_text_field( $_POST['point_time']['time'] ) : '';
		$point_time_data['date'] = !empty( $_POST['point_time']['date'] ) ? sanitize_text_field( $_POST['point_time']['date'] ) : '';
		$point_time_data['option']['time_info']['elapsed'] = !empty( $_POST['point_time']['option']['time_info']['elapsed'] ) ? (int) $_POST['point_time']['option']['time_info']['elapsed'] : 0;
		$point_time_id = !empty( $_POST['point_time_id'] ) ? (int) $_POST['point_time_id'] : 0;


		if ( $point_time_data['post_id'] === 0 || $point_time_data['parent_id'] === 0 ||
			empty( $point_time_data ) || $point_time_data['date'] == '' || $point_time_data['option']['time_info']['elapsed'] === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for create point time', 'task-manager' ) ) );
		}

	 	$point_time_data['date'] .= ' ' . current_time( 'h:i:s' );

		if ( $point_time_id !== 0 ) {
			/** Edit the point */
			$time 																	= $time_controller->show( $point_time_id );
			$time->option['time_info']['old_elapsed'] = $time->option['time_info']['elapsed'];
			$time->date 								= $point_time_data['date'];
			$time->option['time_info']['elapsed'] 	= $point_time_data['option']['time_info']['elapsed'];
			$time->content 							= $point_time_data['content'];

			$response = $time_controller->update( $time );
		}
		else {
			/** Add the point */
			$point_time_data['status'] = '-34070';
			$response = $time_controller->create( $point_time_data );
			$time = $time_controller->show( $response['time']->id );
		}

		$list_user_in = array();

		if ( !empty( $point_time ) ) {
			$list_user_in[$point_time->author_id] = get_userdata( $time->author_id );
		}

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'time' ) );
		$response['template'] = ob_get_clean();
		$response['message'] = __( 'Comment created', 'task-manager' );

		wp_send_json_success( $response );
	}

	public function ajax_get_point_time() {
		global $time_controller;

		$point_time_id = !empty( $_POST['point_time_id'] ) ? (int) $_POST['point_time_id'] : 0;

		if ( $point_time_id == 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for get point time', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_get_point_time_' . $point_time_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for get point time: invalid nonce', 'task-manager' ) ) );
		}

		$point_time = $time_controller->show( $point_time_id );

		$date = explode( ' ', $point_time->date );

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_TIME_DIR, WPEO_TIME_TEMPLATES_MAIN_DIR, 'backend', 'time-edit' ) );
		wp_send_json_success( array( 'point_time_id' => $point_time_id, 'template' => ob_get_clean() ) );
	}

	public function ajax_delete_point_time() {
		global $time_controller;
		global $point_controller;

		$time_id = !empty( $_POST['point_time_id'] ) ? (int) $_POST['point_time_id'] : 0;
		$point_id = !empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		if ( $time_id == 0 || $point_id == 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for delete a point time', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_delete_point_time_' . $time_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for delete point time: invalid nonce', 'task-manager' ) ) );
		}

		$response = array();

		$task = $time_controller->delete( $time_id );
		$point = $point_controller->show( $point_id );

		wp_send_json_success( array( 'task' => $task, 'point' => $point, 'message' => __( 'Comment deleted', 'task-manager' ) ) );
	}
}

global $time_action;
$time_action = new time_action_01();
