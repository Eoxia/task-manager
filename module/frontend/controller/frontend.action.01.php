<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'frontend_action_01' ) ) {
	class frontend_action_01 {
		public function __construct() {
			add_action( 'wp_ajax_load_dashboard_frontend', array( $this, 'ajax_load_dashboard_frontend' ) );
			add_action( 'wp_ajax_nopriv_load_dashboard_frontend', array( $this, 'ajax_load_dashboard_frontend' ) );
		}

		public function ajax_load_dashboard_frontend() {
			$global = !empty( $_POST['global'] ) ? sanitize_text_field( $_POST['global'] ) : '';
			$element_id = !empty( $_POST['element_id'] ) ? (int) $_POST['element_id'] : 0;

			global ${$global};

			if ( $global === '' || $element_id === 0 ) {
				wp_send_json_error( array( 'message' => __( 'Error for load dashboard frontend', 'task-manager' ) ) );
			}

			if ( !check_ajax_referer( 'ajax_load_dashboard_frontend_' . $element_id, array(), false ) ) {
				wp_send_json_error( array( 'message' => __( 'Error for load dashboard frontend: invalid nonce', 'task-manager' ) ) );
			}

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
