<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'task_controller_01' ) ) {
	class task_controller_01 extends post_ctr_01 {
		protected $model_name 	= 'task_model_01';
		protected $post_type	= 'wpeo-task';
		protected $meta_key		= 'wpeo_task';

		/** Défini la route par défaut permettant d'accèder aux tâches depuis WP Rest API  / Define the default route for accessing to task from WP Rest API */
		protected $base = 'task';
		protected $version = '0.1';

		public function __construct() {
			parent::__construct();

			/** Inclus le modèle */
			include_once( WPEO_TASK_PATH . '/model/task.model.01.php' );

			add_action( 'init', array( &$this, 'callback_init' ), 1, 0 );

			add_filter( 'task_manager_dashboard_title', array( $this, 'callback_dashboard_title' ) );
			add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_dashboard_filter' ) );
			add_filter( 'task_manager_dashboard_content', array( $this, 'callback_dashboard_content' ), 10, 2 );

			add_filter( 'task_header_button', array( $this, 'callback_task_header_button' ), 10, 2 );

			/** Window */
			add_filter( 'task_window_sub_header_task_controller', array( $this, 'callback_task_window_sub_header' ), 10, 2 );
			add_filter( 'task_window_information_task_controller', array( $this, 'callback_task_window_information' ), 10, 2 );
			add_filter( 'task_window_action_task_controller', array( $this, 'callback_task_window_action' ), 10, 2 );
			add_filter( 'task_window_footer_task_controller', array( $this,' callback_task_window_footer' ), 10, 2 );
		}

		public function callback_init() {
			register_post_type( $this->post_type );
			register_post_status( 'archive' );
		}

		public function callback_dashboard_title( $string ) {
			$url = wp_nonce_url( add_query_arg( array( 'action' => 'create_task' ), admin_url( 'admin-post.php' ) ), 'wpeo_nonce_create_task' );

			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'button-add' ) );
			$string .= ob_get_clean();

			return $string;
		}

		public function callback_dashboard_filter( $string ) {
			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'filter' ) );
			$string .= ob_get_clean();

			return $string;
		}

		public function callback_dashboard_content( $string, $post_parent ) {
			global $task_controller;
			$list_task = $task_controller->index( array( 'post_parent' => 0,
			 	'meta_query' => array(
						array(
							'key' => 'wpeo_task',
							'value' => '{"user_info":{"owner_id":' . get_current_user_id(),
							'compare' => 'like',
						)
					)
				)
			);

			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'main' ) );
			$string .= ob_get_clean();

			return $string;
		}

		public function callback_task_header_button( $string, $task ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'task-header-button' ) );

			$string .= ob_get_clean();
			return $string;
		}

		public function render_task( $task, $class = '', $need_information = true ) {
			$disabled_filter = apply_filters( 'task_header_disabled', '' );
			require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'task' ) );
		}

		public function render_list_task( $name, $list_task ) {
			global $task_controller;
			$disabled_filter = apply_filters( 'task_header_disabled', '' );
			require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
		}

		public function update_time( $id ) {
			$task = $this->show( $id );

			$time_elapsed = 0;

			/** Récupères tous les points */
			global $point_controller;

			$list_point = $point_controller->index( $task->id, array( 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );

			if( !empty( $list_point ) ) {
				foreach( $list_point as $point ) {
					$time_elapsed += $point->option['time_info']['elapsed'];
				}
			}

			$task->option['time_info']['elapsed'] = $time_elapsed;
			$this->update( $task );

			return $task;
		}

		public function callback_task_window_sub_header( $string, $element ) {
			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend/window', 'sub-header' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_information( $string, $element ) {
			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend/window', 'information' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_action( $string, $element ) {
			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend/window', 'action' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_footer( $string, $element ) {
			ob_start();
			require_once( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend/window', 'footer' ) );
			$string .= ob_get_clean();
			return $string;
		}
	}


	global $task_controller;
	$task_controller = new task_controller_01();
}
?>
