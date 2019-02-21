<?php
/**
 * Les actions relatives aux temps dépassées.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.7.1
 * @copyright 2015-2018 Eoxia
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
	 * @version 1.7.1
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'callback_admin_init' ) );
		add_action( 'wp_ajax_load_time_exceeded', array( $this, 'callback_load_time_exceeded' ) );
	}

	/**
	 * Initialise la metabox pour afficher les temps excedes.
	 *
	 * @since 1.6.0
	 * @version 1.6.1
	 *
	 * @return void
	 */
	public function callback_admin_init() {
		add_meta_box( 'tm-indicator-time-exceeded', __( 'Time exceeded', 'task-manager' ), array( Time_Exceeded_Class::g(), 'display' ), 'task-manager-indicator', 'normal' );
	}

	/**
	 * Appelle la méthode display de la classe Time_Exceeded_Class.
	 *
	 * @since 1.5.0
	 * @version 1.6.1
	 *
	 * @return void
	 */
	public function callback_load_time_exceeded() {
		check_ajax_referer( 'load_time_exceeded' );

		$min_exceeded_time     = ! empty( $_POST['min_exceeded_time'] ) ? (int) $_POST['min_exceeded_time'] : \eoxia\Config_Util::$init['task-manager']->$module_name->default_time_exceeded;
		$start_date            = ! empty( $_POST['start_date'] ) ? sanitize_text_field( $_POST['start_date'] ) : date( 'Y-m-d', strtotime( 'first day of this month' ) );
		$end_date              = ! empty( $_POST['end_date'] ) ? sanitize_text_field( $_POST['end_date'] ) : date( 'Y-m-d', strtotime( 'last day of this month' ) );
		$tm_filter_exceed_type = ! empty( $_POST['tm_filter_exceed_type'] ) ? sanitize_text_field( $_POST['tm_filter_exceed_type'] ) : \eoxia\Config_Util::$init['task-manager']->$module_name->default_filter_type;

		ob_start();
		Time_Exceeded_Class::g()->display_exceeded_elements( $start_date, $end_date, $min_exceeded_time, $tm_filter_exceed_type );
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'timeExceeded',
				'callback_success' => 'loadedTimeExceeded',
				'view'             => ob_get_clean(),
			)
		);
	}

}

new Time_Exceeded_Action();
