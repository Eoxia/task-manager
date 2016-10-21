<?php if ( ! defined( 'ABSPATH' ) ) exit;

class due_action_01 {
	public function __construct() {
		add_action( 'wp_ajax_create_due_time', array( &$this, 'ajax_create_due_time' ) );
		add_action( 'wp_ajax_delete_due_time', array( &$this, 'ajax_delete_due_time' ) );
	}

	public function ajax_create_due_time() {
		global $due_controller;
		global $task_controller;

		if( empty( $_POST['task_id'] ) || !ctype_digit( $_POST['task_id'] ) || empty( $_POST['due_time'] ) || !( $due_date = $due_controller->formatDate( $_POST['due_time'] ) ) ) {
			wp_send_json_error();
		}
		$return = array();

		$task_id = (int) $_POST['task_id'];

		$due_time = $due_controller->create( array(
			'post_id' => $task_id,
			'author_id' => get_current_user_id(),
			'date' => current_time( 'mysql' ),
			'option' => array(
				'due_date' => $due_date
			)
		) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_DUE_DIR, WPEO_DUE_TEMPLATES_MAIN_DIR, 'backend', 'due' ) );
		$return['template'] = ob_get_clean();

		$task = $task_controller->show( $due_time->post_id );
		if( !empty( $task ) ) {
			$return['task_due_time'] = $due_controller->callback_task_header_information( '', $task );
		}

		wp_send_json_success( $return );
	}

	public function ajax_delete_due_time() {
		global $due_controller;
		global $task_controller;

		if( empty( $_POST['due_time'] ) || !ctype_digit( $_POST['due_time'] ) ) {
			wp_send_json_error();
		}
		$return = array();

		$due_time = $due_controller->show( (int) $_POST['due_time'] );
		$due_controller->delete( (int) $_POST['due_time'] );

		$task = $task_controller->show( $due_time->post_id );
		if( !empty( $task ) ) {
			$return['to_task_id'] = $task->id;
			$return['task_due_time'] = $due_controller->callback_task_header_information( '', $task );
		}

		wp_send_json_success( $return );
	}
}

new due_action_01();
