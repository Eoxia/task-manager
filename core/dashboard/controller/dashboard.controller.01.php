<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'dashboard_controller_01' ) ) {

	class dashboard_controller_01 {

		public function __construct() {
	      	add_action( 'admin_menu', array( &$this, 'callback_admin_menu' ), 1 );
		}

		public function callback_admin_menu() {
	     		add_menu_page( __( 'Task management dashboard', 'task-manager' ), __( 'Tasks manager', 'task-manager' ), 'publish_pages', 'wpeomtm-dashboard', array( &$this, 'callback_menu_page_dashboard' ), 'dashicons-layout' );
	     		add_submenu_page( 'wpeomtm-dashboard', __( 'Task management dashboard', 'task-manager' ), __( 'Tasks manager', 'task-manager' ), 'publish_pages', 'wpeomtm-dashboard', array( &$this, 'callback_menu_page_dashboard' ) );
	   	}

	   	public function callback_menu_page_dashboard() {
   			add_thickbox();
	     		require_once( wpeo_template_01::get_template_part( WPEO_DASHBOARD_DIR, WPEO_DASHBOARD_TEMPLATES_MAIN_DIR, 'backend', 'dashboard' ) );
	   	}

	}

	global $dashboard_controller;
	$dashboard_controller = new dashboard_controller_01();
}
?>
