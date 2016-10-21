<?php if ( ! defined( 'ABSPATH' ) ) exit;

class estimated_action_01 {
	public function __construct() {
		add_action( 'wp_ajax_create_estimated_time', array( &$this, 'ajax_create_estimated_time' ) );
		add_action( 'wp_ajax_delete_estimated_time', array( &$this, 'ajax_delete_estimated_time' ) );
	}

	public function ajax_create_estimated_time() {
		global $estimated_controller;
		global $task_controller;
		global $wp_project_user_controller;

		if( empty( $_POST['task_id'] ) || !ctype_digit( $_POST['task_id'] ) || empty( $_POST['estimated_time'] ) || !ctype_digit( $_POST['estimated_time'] ) ) {
			wp_send_json_error();
		}
		$return = array();

		$task_id = (int) $_POST['task_id'];

		$estimated_time = $estimated_controller->create( array(
			'post_id' => $task_id,
			'author_id' => get_current_user_id(),
			'date' => current_time( 'mysql' ),
			'option' => array(
				'estimated_time' => (int) $_POST['estimated_time']
			)
		) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_ESTIMATED_DIR, WPEO_ESTIMATED_TEMPLATES_MAIN_DIR, 'backend', 'estimated' ) );
		$return['template'] = ob_get_clean();

		$task = $task_controller->show( $estimated_time->post_id );
		if( !empty( $task ) ) {
			$return['task_estimated_time'] = $estimated_controller->callback_task_header_information( '', $task );
		}

		wp_send_json_success( $return );
	}

	public function ajax_delete_estimated_time() {
		global $estimated_controller;
		global $task_controller;

		if( empty( $_POST['estimated_time'] ) || !ctype_digit( $_POST['estimated_time'] ) ) {
			wp_send_json_error();
		}
		$return = array();

		$estimated_time = $estimated_controller->show( (int) $_POST['estimated_time'] );
		$estimated_controller->delete( (int) $_POST['estimated_time'] );

		$task = $task_controller->show( $estimated_time->post_id );
		if( !empty( $task ) ) {
			$return['to_task_id'] = $task->id;
			$return['task_estimated_time'] = $estimated_controller->callback_task_header_information( '', $task );
		}

		wp_send_json_success( $return );
	}
}

new estimated_action_01();
