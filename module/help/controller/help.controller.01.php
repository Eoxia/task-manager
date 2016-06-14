<?php

if ( !defined( 'ABSPATH' ) ) exit;

class help_controller_01 {
	public function __construct() {
    add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );
	}

  public function callback_admin_menu() {
    add_submenu_page( 'wpeomtm-dashboard', __( 'Help', 'task-manager' ), __( 'Help', 'task-manager' ), 'manage_options', 'wpeo-project-help', array( &$this, 'callback_submenu_page' ) );
  }

  public function callback_submenu_page() {
    require_once( wpeo_template_01::get_template_part( WPEO_TASK_HELP_DIR, WPEO_TASK_HELP_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
  }
}

new help_controller_01();
