<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'frontend_action_01' ) ) {
	class frontend_action_01 {
		public function __construct() {
			add_action( 'wp_ajax_load_dashboard_frontend', array( $this, 'ajax_load_dashboard_frontend' ) );
			add_action( 'wp_ajax_nopriv_load_dashboard_frontend', array( $this, 'ajax_load_dashboard_frontend' ) );
		}

		public function ajax_load_dashboard_frontend() {
			$global = $_POST['global'];
			global ${$global};
			$element = ${$global}->show( $_POST['element_id'] );

			add_filter( 'task_window_time_date', function( $string ) { return ''; }, 11, 1 );
			add_filter( 'task_window_time',  function( $string ) { return ''; }, 11, 1 );

			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_FRONTEND_DIR, WPEO_FRONTEND_TEMPLATES_MAIN_DIR, 'backend', 'window' ) );
			wp_send_json_success( array( 'template' => ob_get_clean() ) );
		}
	}

	global $frontend_action;
 $frontend_action = new frontend_action_01();
}
?>
