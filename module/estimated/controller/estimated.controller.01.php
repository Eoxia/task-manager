<?php
if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'estimated_controller_01' ) ) {
	class estimated_controller_01 extends comment_ctr_01 {
		protected $model_name	= 'estimated_mdl_01';
		protected $meta_key	= 'wpeo_estimated';

		/** Défini la route par défaut permettant d'accèder aux temps voulu depuis WP Rest API  / Define the default route for accessing to task from WP Rest API */
		protected $base		= 'estimated';
		protected $version	= '0.1';
		protected $comment_type	= 'estimated_time';

		public function  __construct() {
 			include_once( WPEO_ESTIMATED_PATH . '/model/estimated.model.01.php' );
			add_filter( 'task_time_history', array( $this, 'callback_task_time_history' ), 10, 2 );
			add_filter( 'task_header_information', array( $this, 'callback_task_header_information' ), 10, 2 );
		}

		/** Error 500 */

		public function create( $data ) {
			global $task_controller;
			$due_time = parent::create( $data );
			$task = $task_controller->show( $due_time->post_id );
			$task->option['time_info']['estimated'] = $due_time->id;
			$task_controller->update( $task );
			return $due_time;
		}

		public function delete( $id ) {
			global $task_controller;
			$estimated = $this->show( $id );
			$task = $task_controller->show( $estimated->post_id );
			parent::delete( $id );
			$last_estimated = $this->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070, 'number' => 1 ) );
			if( !empty( $last_estimated ) && count( $last_estimated ) == 1 ) {
				$task->option['time_info']['estimated'] = $last_estimated[0]->id;
			} else {
				$task->option['time_info']['estimated'] = 0;
			}
			$task_controller->update( $task );
		}

		public function callback_task_time_history( $string, $task ) {
			global $wp_project_user_controller;
			$list_estimated_time = $this->index( $task->id, array( 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070 ) );
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_ESTIMATED_DIR, WPEO_ESTIMATED_TEMPLATES_MAIN_DIR, 'backend', 'estimated', 'task-history' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_header_information( $string, $task ) {
			if( !empty( $task->option['time_info']['estimated'] ) ) {
				$estimated_time = $this->show( $task->option['time_info']['estimated'] );
			}
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_ESTIMATED_DIR, WPEO_ESTIMATED_TEMPLATES_MAIN_DIR, 'backend', 'estimated', 'task-header' ) );
			$string .= ob_get_clean();
			return $string;
		}
	}
}

global $estimated_controller;
$estimated_controller = new estimated_controller_01();

?>
