<?php
/**
 * Ajax file.
 *
 * @package TimeExceeded
 */

if ( ! defined( 'ABSPATH' ) ) {exit;}
/**
 * Class for Ajax actions on history_time.
 */
class Time_Exceeded_Action_01 {
	/**
	 * Define hooks.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );
	}

	public function callback_admin_menu() {
		add_submenu_page( 'wpeomtm-dashboard', __( 'Time exceeded', 'task-manager' ), __( 'Time exceeded', 'task-manager' ), 'manage_options', 'task-manager-time-exceeded', array( &$this, 'callback_submenu_page' ) );
	}

	public function callback_submenu_page() {
		global $task_controller;
		global $history_time_controller;
		global $wpdb;

		$date_start = new DateTime( 'first day of this month' );
		$date_start = $date_start->format( 'Y-m-d' );

		$date_end = new DateTime( 'last day of this month' );
		$date_end = $date_end->format( 'Y-m-d' );

		$comments = $wpdb->get_results(
			"SELECT
			FROM $wpdb->comments as TIME
				JOIN $wpdb->comments AS POINT ON TIME.comment_parent=POINT.comment_ID
				JOIN $wpdb->posts AS TASK 		ON POINT.comment_post_id=TASK.id
				JOIN $wpdb->commentmeta AS POINTMETA ON POINT.comment_ID=POINTMETA.comment_id
				JOIN $wpdb->users AS USER ON TIME.user_id=USER.ID
				JOIN $wpdb->usermeta AS USERMETA ON USER.ID=USERMETA.user_id
			WHERE TASK.post_type='wpeo-task'
				AND	POINTMETA.meta_key='wpeo_point'
				AND POINTMETA.meta_value LIKE '%completed\":false%'
				AND TIME.comment_content != ''
				AND USERMETA.meta_key='wp_user_level'
				AND USERMETA.meta_value=0
			ORDER BY TIME.comment_date DESC"
		);

		$tasks = $task_controller->index( array() );
		$tasks_exceed_time = array();

		if ( ! empty( $tasks ) ) {
			foreach ( $tasks as $key => $task ) {
				if ( ! empty( $task->option['time_info']['history_time'] ) ) {
					$task->history_time = $history_time_controller->show( $task->option['time_info']['history_time'] );

					if ( $task->option['time_info']['elapsed'] > $task->history_time->option['estimated_time'] ) {
						$tasks_exceed_time[] = $task;
					}

					$task->display_estimated = $task->history_time->option['estimated_time'];
					$task->diff_time = $task->option['time_info']['elapsed'] - $task->history_time->option['estimated_time'];
				}

				if ( ! empty( $task->option['time_info']['estimated'] ) ) {
					if ( $task->option['time_info']['elapsed'] > $task->option['time_info']['estimated'] ) {
						$tasks_exceed_time[] = $task;
					}

					$task->display_estimated = $task->option['time_info']['estimated'];
					$task->diff_time = $task->option['time_info']['elapsed'] - $task->option['time_info']['estimated'];
				}

				$task->task_parent = 'Aucune';

				if ( ! empty( $task->parent_id ) ) {
					$task->task_parent  = get_post( $task->parent_id );
				}
			}
		}

		usort( $tasks_exceed_time, function( $a, $b ) {
			if ( $a->diff_time == $b->diff_time ) {
				return 0;
			}

			return ( $a->diff_time > $b->diff_time ) ? -1 : 1;
		} );

		require_once( wpeo_template_01::get_template_part( WPEO_TIME_EXCEEDED_DIR, WPEO_TIME_EXCEEDED_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
	}
}

new Time_Exceeded_Action_01();
