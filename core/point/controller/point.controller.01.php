<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'point_controller_01' ) ) {
	class point_controller_01 extends comment_ctr_01 {
		protected $model_name 	= 'point_model_01';
		protected $meta_key		= 'wpeo_point';

		/** Défini la route par défaut permettant d'accèder aux temps pointés depuis WP Rest API  / Define the default route for accessing to point time from WP Rest API */
		protected $base = 'point';
		protected $version = '0.1';

		/**
		 * Constructeur qui inclus le modèle des points et également des les scripts
		 * JS et CSS nécessaire pour le fonctionnement des points
		 *
		 * @return void
		 */
		public function __construct() {
			parent::__construct();

			include_once( WPEO_POINT_PATH . '/model/point.model.01.php' );

			add_filter( 'task_content', array( $this, 'callback_task_content' ), 10, 2 );
			add_filter( 'task_export', array( $this, 'callback_task_export' ), 10, 2 );

			add_filter( 'point_action_before', array( $this, 'callback_point_action_before' ), 10, 2 );
			add_filter( 'point_action_after', array( $this, 'callback_point_action_after' ), 10, 2 );

			add_filter( 'point_list_add', array( $this, 'callback_point_list_add' ), 10, 2 );

			/** Window */
			add_filter( 'task_window_sub_header_point_controller', array( $this, 'callback_task_window_sub_header' ), 10, 2 );
			add_filter( 'task_window_information_point_controller', array( $this, 'callback_task_window_information' ), 10, 2 );
			add_filter( 'task_window_action_point_controller', array( $this, 'callback_task_window_action' ), 10, 2 );
			add_filter( 'task_window_footer_point_controller', array( $this, 'callback_task_window_footer' ), 10, 2 );
		}

		public function callback_task_content( $string, $task ) {
			$list_point_completed = array();
			$list_point_uncompleted = array();

			if ( !empty( $task->option['task_info']['order_point_id'] ) ) {
				$list_point = $this->index( $task->id, array( 'orderby' => 'comment__in', 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
				$list_point_completed = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === true; } );
				$list_point_uncompleted = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === false; } );
			}

			ob_start();
			$this->render_point( $task->id, $list_point_completed, $list_point_uncompleted );
			$string .= ob_get_clean();

			return $string;
		}

		public function callback_task_export( $string, $task ) {
			$list_point_completed = array();
			$list_point_uncompleted = array();

			if ( !empty( $task->option['task_info']['order_point_id'] ) ) {
				$list_point = $this->index( $task->id, array( 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
				$list_point_completed = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === true; } );
				$list_point_uncompleted = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === false; } );
			}

			$string .= __( 'Uncompleted', 'task-manager' ) . "\r\n";

			if ( !empty( $list_point_uncompleted ) ) {
				foreach ( $list_point_uncompleted as $point ) {
					$string .= '     ' . $point->id . ' - ' . $point->content . "\r\n";
				}
			}

			$string .= __( 'Completed', 'task-manager' ) . "\r\n";

			if ( !empty( $list_point_completed ) ) {
				foreach ( $list_point_completed as $point ) {
					$string .= '     ' . $point->id . ' - ' . $point->content . "\r\n";
				}
			}

			return $string;
		}

		public function callback_point_list_add( $string, $object_id ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point-add' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_point_action_before( $string, $point ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'action-before' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_point_action_after( $string, $point ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'action-after' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function increase_time( $point_id, $time_elapsed ) {
			$point = $this->show( $point_id );
			$point->option['time_info']['elapsed'] += $time_elapsed;
			$this->update($point);

			global $task_controller;
			$task = $task_controller->update_time( $point->post_id );

			return $task;
		}

		public function decrease_time( $point_id, $elapsed_time = 0 ) {
			$point = $this->show( $point_id );

			if ( $elapsed_time == 0 )
				$point->option['time_info']['elapsed'] = $elapsed_time;
			else
				$point->option['time_info']['elapsed'] -= $elapsed_time;

			$this->update( $point );

			global $task_controller;
			$task = $task_controller->update_time( $point->post_id );

			return $task;
		}

		public function send_comment_to( $old_task_id, $task_id, $point_id ) {
			global $time_controller;
			$list_time = $time_controller->index( $old_task_id, array( 'parent' => $point_id, 'status' => -34070 ) );

			if ( !empty( $list_time ) ) {
				foreach ( $list_time as $time ) {
					$time->post_id = $task_id;
					$time_controller->update( $time, false );
				}
			}
		}

		public static function render_point( $object_id, $list_point_completed, $list_point_uncompleted ) {
			$disabled_filter = apply_filters( 'point_disabled', '' );
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'list', 'point' ) );
		}

		public function callback_task_window_sub_header( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'sub-header' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_information( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'information' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_action( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'action' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_footer( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'footer' ) );
			$string .= ob_get_clean();
			return $string;
		}
	}

	global $point_controller;
	$point_controller = new point_controller_01();

}
