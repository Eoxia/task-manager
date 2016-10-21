<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'due_controller_01' ) ) {
	class due_controller_01 extends comment_ctr_01 {
		protected $model_name	= 'due_mdl_01';
		protected $meta_key	= 'wpeo_due';

		/** Défini la route par défaut permettant d'accèder aux temps voulu depuis WP Rest API  / Define the default route for accessing to task from WP Rest API */
		protected $base		= 'due';
		protected $version	= '0.1';
		protected $comment_type	= 'due_time';

		public function  __construct() {
 			include_once( WPEO_DUE_PATH . '/model/due.model.01.php' );
			add_filter( 'task_time_history', array( $this, 'callback_task_time_history' ), 10, 2 );
			add_filter( 'task_header_information', array( $this, 'callback_task_header_information' ), 10, 2 );
		}

		/** Error 500 */

		public function create( $data ) {
			global $task_controller;
			$due_time = parent::create( $data );
			$task = $task_controller->show( $due_time->post_id );
			$task->option['date_info']['due'] = $due_time->id;
			$task_controller->update( $task );
			return $due_time;
		}

		public function delete( $id ) {
			global $task_controller;
			$due = $this->show( $id );
			$task = $task_controller->show( $due->post_id );
			parent::delete( $id );
			$last_due = $this->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070, 'number' => 1 ) );
			if( !empty( $last_due ) && count( $last_due ) == 1 ) {
				$task->option['date_info']['due'] = $last_due[0]->id;
			} else {
				$task->option['date_info']['due'] = 0;
			}
			$task_controller->update( $task );
		}

		public function formatDate( $val ) {
			$date = date_create( $val );
			if( $date !== false ) {
				$date = date_format($date, 'Y-m-d H:i:s');
			}
			return $date;
		}

		public function callback_task_time_history( $string, $task ) {
			global $wp_project_user_controller;
			$list_due_time = $this->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070 ) );
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_DUE_DIR, WPEO_DUE_TEMPLATES_MAIN_DIR, 'backend', 'due', 'task-history' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_header_information( $string, $task ) {
			if( !empty( $task->option['date_info']['due'] ) ) {
				$due_time = $this->show( $task->option['date_info']['due'] );
				$interval = date_diff( new DateTime( current_time( 'Y-m-d' ) ), new DateTime( $due_time->option['due_date'] ) );
				$interval = (int) $interval->format('%R%a');
			}
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_DUE_DIR, WPEO_DUE_TEMPLATES_MAIN_DIR, 'backend', 'due', 'task-header' ) );
			$string .= ob_get_clean();
			return $string;
		}
	}
}

global $due_controller;
$due_controller = new due_controller_01();

?>
