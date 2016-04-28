<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'window_action_01' ) ) {
	class window_action_01 {
		public function __construct() {
      add_action( 'wp_ajax_load_dashboard', array( $this, 'ajax_load_dashboard' ) );
      add_action( 'wp_ajax_nopriv_load_dashboard', array( $this, 'ajax_load_dashboard' ) );
		}

    public function ajax_load_dashboard() {
			$global = $_POST['global'];
			global ${$global};
			$element = ${$global}->show( $_POST['element_id'] );

      ob_start();
      require_once( wpeo_template_01::get_template_part( WPEO_WINDOW_DIR, WPEO_WINDOW_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
      wp_send_json_success( array( 'template' => ob_get_clean() ) );
    }
  }

	global $window_action;
	$window_action = new window_action_01();
}
?>
