<?php if ( ! defined( 'ABSPATH' ) ) exit;

class timeline_action_01 {
	public function __construct() {
		add_action( 'wp_ajax_load_timeline_user', array( $this, 'ajax_load_timeline_user' ) );
	}

	public function ajax_load_timeline_user() {
		global $task_timeline;

		$user_id = !empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0;

		if ( $user_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for load timeline user', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_load_timeline_user_' . $user_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for load timeline user: invalid nonce', 'task-manager' ) ) );
		}

		$list_year = $task_timeline->generate_year();
		$current_month = date( 'n' );
		$current_day = date( 'd' );

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'year' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}
}

new timeline_action_01();
