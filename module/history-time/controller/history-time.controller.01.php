<?php
/**
 * Controller file.
 *
 * @package HistoryTime
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'History_time_controller_01' ) ) {
	/**
	 * Manage all history_time.
	 * History time define due time and estimated time on task.
	 */
	class History_time_controller_01 extends comment_ctr_01 {
		/**
		 * Class name of model.
		 *
		 * @var string
		 */
		protected $model_name	= 'History_time_mdl_01';
		/**
		 * Key to use on meta DataBase.
		 *
		 * @var string
		 */
		protected $meta_key	= 'wpeo_history_time';
		/**
		 * Type to use on DataBase.
		 *
		 * @var string
		 */
		protected $comment_type	= 'history_time';
		/**
		 * API REST base.
		 *
		 * @var string
		 */
		protected $base		= 'history_time';
		/**
		 * Version of controller.
		 *
		 * @var string
		 */
		protected $version	= '0.1';

		/**
		 * Include model, declare hooks.
		 */
		public function __construct() {
			include_once( WPEO_HISTORY_TIME_PATH . '/model/history-time.model.01.php' );
			add_filter( 'task_time_history', array( $this, 'callback_task_time_history' ), 10, 2 );
			add_filter( 'task_header_information', array( $this, 'callback_task_header_information_due' ), 10, 2 );
			add_filter( 'task_header_information', array( $this, 'callback_task_header_information_estimated' ), 12, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ) );
		}

		/**
		 * Scripts declare for admin.
		 *
		 * @return void
		 */
		public function callback_admin_enqueue_scripts() {
			if ( WPEO_TASKMANAGER_DEBUG ) {
				wp_enqueue_script( 'wpeo-task-history-time-backend-js', WPEOMTM_HISTORY_TIME_ASSETS_URL . '/js/backend.js', array( 'jquery', 'jquery-form', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'jquery-ui-autocomplete', 'suggest' ), WPEO_TASKMANAGER_VERSION );
			}
		}

		/**
		 * Add model entity to collection.
		 *
		 * @param  array/obj $data $_POST or model.
		 * @return obj             Model from collection.
		 */
		public function create( $data ) {
			global $task_controller;
			$history_time = parent::create( $data );
			$task = $task_controller->show( $history_time->post_id );
			$task->option['time_info']['history_time'] = $history_time->id;
			$task_controller->update( $task );
			return $history_time;
		}

		/**
		 * Delete model entity from collection.
		 *
		 * @param  integer $id The entity identifier.
		 * @return void
		 */
		public function delete( $id ) {
			global $task_controller;
			$history_time = $this->show( $id );
			$task = $task_controller->show( $history_time->post_id );
			parent::delete( $id );
			$last_history_time = $this->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070, 'number' => 1 ) );
			if ( ! empty( $last_history_time ) && count( $last_history_time ) === 1 ) {
				$task->option['time_info']['history_time'] = $last_history_time[0]->id;
			} else {
				$task->option['time_info']['history_time'] = 0;
			}
			$task_controller->update( $task );
		}

		/**
		 * FormatDate convert string to date object,
		 * you can use 12-12-12 or 2012 december 12.
		 *
		 * @param  string $val Many writes can be used (report to PHP manual).
		 * @return DateTime
		 */
		public function formatDate( $val ) {
			$date = date_create( $val );
			if ( false !== $date ) {
				$date = date_format( $date, 'Y-m-d H:i:s' );
			}
			return $date;
		}

		/**
		 * Callback for popup manager
		 *
		 * @param  string        $string Text before call.
		 * @param  task_model_01 $task   Model of actual task.
		 * @return string
		 */
		public function callback_task_time_history( $string, $task ) {
			global $wp_project_user_controller;
			$list_history_time = $this->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070 ) );
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_HISTORY_TIME_DIR, WPEO_HISTORY_TIME_TEMPLATES_MAIN_DIR, 'backend', 'history-time', 'task-list' ) );
			$string .= ob_get_clean();
			return $string;
		}

		/**
		 * Callback for head task (under title).
		 * Due date part.
		 *
		 * @param  string        $string Test before call.
		 * @param  task_model_01 $task   Model of actual task.
		 * @return string
		 */
		public function callback_task_header_information_due( $string, $task ) {
			if ( ! empty( $task->option['time_info']['history_time'] ) ) {
				$history_time = $this->show( $task->option['time_info']['history_time'] );
				$interval = date_diff( new DateTime( current_time( 'Y-m-d' ) ), new DateTime( $history_time->option['due_date'] ) );
				$interval = (int) $interval->format( '%R%a' );
			}
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_HISTORY_TIME_DIR, WPEO_HISTORY_TIME_TEMPLATES_MAIN_DIR, 'backend', 'history-time', 'task-header-due' ) );
			$string .= ob_get_clean();
			return $string;
		}

		/**
		 * Callback for head task (under title).
		 * Estimated time part.
		 *
		 * @param  string        $string Test before call.
		 * @param  task_model_01 $task   Model of actual task.
		 * @return string
		 */
		public function callback_task_header_information_estimated( $string, $task ) {
			if ( ! empty( $task->option['time_info']['history_time'] ) ) {
				$history_time = $this->show( $task->option['time_info']['history_time'] );
			}
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_HISTORY_TIME_DIR, WPEO_HISTORY_TIME_TEMPLATES_MAIN_DIR, 'backend', 'history-time', 'task-header-estimated' ) );
			$string .= ob_get_clean();
			return $string;
		}
	}
}

global $history_time_controller;
$history_time_controller = new History_time_controller_01();
