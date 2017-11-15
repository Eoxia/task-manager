<?php
/**
 * Les actions relatives aux temps dépassées.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux temps dépassées.
 */
class Time_Exceeded_Action {

	/**
	 * Le constructeur.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 30 );
		add_action( 'wp_ajax_load_time_exceeded', array( $this, 'callback_load_time_exceeded' ) );
	}

	/**
	 * Ajoutes le sous menu dans le menu 'Task Manager'.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_admin_menu() {
		add_submenu_page( 'wpeomtm-dashboard', __( 'Time exceeded', 'task-manager' ), __( 'Time exceeded', 'task-manager' ), 'manage_task_manager', 'task-manager-time-exceeded', array( Time_Exceeded_Class::g(), 'callback_submenu_page' ) );
	}

	/**
	 * Appelle la méthode display de la classe Time_Exceeded_Class.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_load_time_exceeded() {
		check_ajax_referer( 'load_time_exceeded' );

		$require_time_history = ( ! empty( $_POST['require_time_history'] ) && $_POST['require_time_history'] === 'true' ) ? true : false; // Toujours sur ON. A corrigé après manger.
		$min_exceeded_time = ! empty( $_POST['min_exceeded_time'] ) ? (int) $_POST['min_exceeded_time'] : \eoxia\Config_Util::$init['task-manager']->time_exceeded->default_time_exceeded;
		$start_date = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : '';
		$end_date = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : '';

		ob_start();
		Time_Exceeded_Class::g()->display( $start_date, $end_date, $min_exceeded_time, $require_time_history );
		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'timeExceeded',
			'callback_success' => 'loadedTimeExceeded',
			'view' => ob_get_clean(),
		) );
	}
}

new Time_Exceeded_Action();
