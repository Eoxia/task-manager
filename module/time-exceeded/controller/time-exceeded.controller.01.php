<?php
/**
 * Controller file.
 *
 * @package TimeExceeded
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Time_Exceeded_Controller' ) ) {
	/**
	 * Manage all history_time.
	 * History time define due time and estimated time on task.
	 */
	class Time_Exceeded_Controller_01 {
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ) );
		}

		/**
		 * Scripts declare for admin.
		 *
		 * @return void
		 */
		public function callback_admin_enqueue_scripts() {
			if ( WPEO_TASKMANAGER_DEBUG ) {
				wp_enqueue_script( 'wpeo-task-time-exceeded-backend-js', WPEOMTM_TIME_EXCEEDED_ASSETS_URL . '/js/backend.js', array( 'jquery', 'jquery-form', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'jquery-ui-autocomplete', 'suggest' ), WPEO_TASKMANAGER_VERSION );
			}
		}
	}
}

global $time_exceeded_controller;
$time_exceeded_controller = new Time_Exceeded_Controller_01();
