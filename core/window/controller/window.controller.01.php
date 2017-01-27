<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'window_controller_01' ) ) {
	class window_controller_01 {
		public function __construct() {
      add_filter( 'task_window', array( $this, 'callback_task_window' ) );
		}

    public function callback_task_window( $string ) {
      ob_start();
      require( wpeo_template_01::get_template_part( WPEO_WINDOW_DIR, WPEO_WINDOW_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
      $string .= ob_get_clean();
      return $string;
    }
  }

	global $window_controller;
	$window_controller = new window_controller_01();
}
?>
