<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'window_action_01' ) ) {
	class window_action_01 {
		public function __construct() {
      add_action( 'wp_ajax_load_dashboard', array( $this, 'ajax_load_dashboard' ) );
      add_action( 'wp_ajax_nopriv_load_dashboard', array( $this, 'ajax_load_dashboard' ) );
		}

    public function ajax_load_dashboard() {
			$global = !empty( $_POST['global'] ) ? sanitize_text_field( $_POST['global'] ) : '';
			$element_id = !empty( $_POST['element_id'] ) ? (int) $_POST['element_id'] : 0;

			global ${$global};

			if ( $global === '' || $element_id === 0 ) {
				wp_send_json_error( array( 'message' => __( 'Error for load dashboard', 'task-manager' ) ) );
			}

			if ( !check_ajax_referer( 'ajax_load_dashboard_' . $element_id, array(), false ) ) {
				wp_send_json_error( array( 'message' => __( 'Error for load dashboard: invalid nonce', 'task-manager' ) ) );
			}

			$element = ${$global}->show( $element_id );

      ob_start();
      require_once( wpeo_template_01::get_template_part( WPEO_WINDOW_DIR, WPEO_WINDOW_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
      wp_send_json_success( array( 'template' => ob_get_clean() ) );
    }
  }

	global $window_action;
	$window_action = new window_action_01();
}
?>
