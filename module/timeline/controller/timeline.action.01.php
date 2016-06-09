<?php if ( ! defined( 'ABSPATH' ) ) exit;

class timeline_action_01 {
	public function __construct() {
		add_action( 'wp_ajax_load_timeline_user', array( $this, 'ajax_load_timeline_user' ) );
	}

	public function ajax_load_timeline_user() {
		ob_start();

		global $task_timeline;

		$list_year = $task_timeline->generate_year();
		$current_month = date( 'n' );
		$current_day = date( 'd' );
		$user_id = $_POST['user_id'];

		require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'year' ) );

		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}
}

new timeline_action_01();
