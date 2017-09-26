<?php

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Task_Filter {

	public function __construct() {
		add_filter( 'task_manager_dashboard_title', array( $this, 'callback_dashboard_title' ) );
		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_dashboard_filter' ), 12 );
		add_filter( 'task', array( $this, 'callback_dashboard_content' ), 10, 2 );

		add_filter( 'task_header_action', array( $this, 'callback_task_header_action' ), 10, 2 );
		add_filter( 'task_header_information', array( $this, 'callback_task_header_information_elapsed' ), 11, 2 );
		add_filter( 'task_header_information', array( $this, 'callback_task_header_information_button' ), 20, 2 );

		/** Window */
		add_filter( 'task_window_sub_header_task_controller', array( $this, 'callback_task_window_sub_header' ), 10, 2 );
		add_filter( 'task_window_information_task_controller', array( $this, 'callback_task_window_information' ), 10, 2 );
		add_filter( 'task_window_action_task_controller', array( $this, 'callback_task_window_action' ), 10, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_window_footer' ), 10, 2 );
	}

	public function callback_dashboard_title( $string ) {
		$url = wp_nonce_url( add_query_arg( array( 'action' => 'create_task' ), admin_url( 'admin-post.php' ) ), 'wpeo_nonce_create_task' );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/button-add', array(
			'url' => $url,
		) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_dashboard_filter( $string ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/filter-tab' );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_dashboard_content( $string, $post_parent ) {
		if ( $post_parent == 0 ) {
			$list_task = Task_Class::g()->get( array( 'post_parent' => 0,
			'meta_query' => array(
				array(
					'key' => 'wpeo_task',
					'value' => '{"user_info":{"owner_id":' . get_current_user_id(),
						'compare' => 'like',
					)
				)
				)
			);
		}
		else {
			$list_task = Task_Class::g()->get( array( 'post_parent' => $post_parent ) );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/list-task', array(
			'list_task' => $list_task,
		) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_header_action( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task-header-button', array(
			'task' => $task,
		) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_header_information_elapsed( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/time-elapsed', array(
			'task' => $task,
		) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_header_information_button( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/information-button', array(
			'task' => $task,
		) );
		$string .= ob_get_clean();
		return $string;
	}



	public function render_list_task( $name, $list_task ) {
		global $task_controller;
		$disabled_filter = apply_filters( 'task_header_disabled', '' );
		require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'list-task' ) );
	}

	public function get_task_by_comment_user_id_and_date( $user_id, $start_date, $end_date ) {
		global $point_controller;

		$list_point = $point_controller->get_list_point_by_comment_user_id_and_date( $user_id, $start_date, $end_date );
		$list_task = array();

		if ( ! empty( $list_point ) ) {
			foreach ( $list_point as $point ) {
				$list_task[ $point->post_id ] = $this->show( $point->post_id );
			}
		}

		return $list_task;
	}

	public function get_task_created_by_user_id_and_date( $user_id, $start_date, $end_date ) {
		if ( empty( $user_id ) || empty( $start_date ) || empty( $end_date ) ) {
			return 0;
		}

		global $wpdb;

		$query =
		"SELECT ID
		FROM {$wpdb->posts}
		WHERE	post_author = %d AND
		post_date BETWEEN %s AND %s AND
		post_type = %s";

		$list_task = $wpdb->get_results( $wpdb->prepare( $query, array( $user_id, $start_date, $end_date, 'wpeo-task' ) ) );
		$list_task_model = array();

		if ( ! empty( $list_task ) ) {
			foreach ( $list_task as $task ) {
				$list_task_model[] = $this->show( $task->ID );
			}
		}

		return $list_task_model;
	}

	public static function get_task_title_by_id( $task_id ) {
		if ( empty( $task_id ) ) {
			return __( 'Task not found', 'wpeotask-i18n' );
		}

		global $task_controller;
		$task = $task_controller->show( $task_id );

		if ( empty( $task ) ) {
			return __( 'Task not found', 'wpeotask-i18n' );
		}

		return $task->title;
	}

	public function update_time( $id ) {
		$task = $this->show( $id );

		$time_elapsed = 0;

		/** Récupères tous les points */
		global $point_controller;

		$list_point = $point_controller->index( $task->id, array( 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );

		if ( ! empty( $list_point ) ) {
			foreach ( $list_point as $point ) {
				$time_elapsed += $point->option['time_info']['elapsed'];
			}
		}

		$task->option['time_info']['elapsed'] = $time_elapsed;
		$this->update( $task );

		return $task;
	}

	public function update_due_time( $id ) {
		$task = $this->show( $id );

		/** Récupères le dernier temps voulu */
		global $due_controller;
		$list_due = $due_controller->index( $task->id, array( 'number' => 1, 'orderby' => 'comment_date', 'parent' => 0, 'status' => -34070 ) );

		if ( ! empty( $list_due ) ) {
			$task->option['date_info']['due'] = $list_due[0]->id;
			$this->update( $task );
		}

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

new Task_Filter();
