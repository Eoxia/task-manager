<?php if ( !defined( 'ABSPATH' ) ) exit;

class point_action_01 {
	public function __construct() {
		/** Point */
		add_action( 'wp_ajax_create_point', array( &$this, 'ajax_create_point' ) );
		add_action( 'wp_ajax_delete_point', array( &$this, 'ajax_delete_point' ) );
		add_action( 'wp_ajax_edit_point', array( &$this, 'ajax_edit_point' ) );
		add_action( 'wp_ajax_edit_order_point', array( &$this, 'ajax_edit_order_point' ) );
		add_action( 'wp_ajax_load_completed_point', array( &$this, 'ajax_load_completed_point' ) );

		/** Dashboard */
		add_action( 'wp_ajax_load_dashboard_point', array( &$this, 'ajax_load_dashboard_point' ) );
		add_action( 'wp_ajax_load_frontend_dashboard_point', array( &$this, 'ajax_load_frontend_dashboard_point' ) );

		add_action( 'wp_ajax_send_point_to_task', array( &$this, 'ajax_send_point_to_task' ) );
		add_action( 'wp_ajax_load_last_comment', array( &$this, 'ajax_load_last_comment') );
	}

	/**
	 * Créer un point avec le status -34070 et assignes le à une tâche. Ensuite renvoie
	 * le template backend-point. / Create an point with the status -34070 and assign it
	 * to a task. Then send the template backend-point.
	 *
	 * @param string $_GET['_wpnonce'] Le code de sécurité crée par la fonction wp_create_nonce de
	 * WordPress
	 * @param int $_POST['point']['post_id'] ID de la tâche / The task ID
	 * @param string $_POST['content'] Le contenu du point / The point content
	 *
	 * @return string Le template d'un point / The template of point
	 */
	public function ajax_create_point() {
		if (  0 === is_int( ( int )$_POST['point']['post_id'] ) )
		  wp_send_json_error();
		else
			$object_id = $_POST['point']['post_id'];

		wpeo_check_01::check( 'wpeo_nonce_create_point_' . $object_id );

		global $task_controller;
		global $point_controller;

		$_POST['point']['author_id'] = get_current_user_id();
		$_POST['point']['status'] = '-34070';
		$_POST['point']['date'] = current_time( 'mysql' );
		$point = $point_controller->create( $_POST['point'] );

		/** Add to the order point */
		$task = $task_controller->show( $object_id );
		$task->option['task_info']['order_point_id'][] = (int) $point->id;
		$task_controller->update( $task );

		/** Log la création du point / Log the creation of point */
		taskmanager\log\eo_log( 'wpeo_project',
			array(
				'object_id' => $object_id,
				'message' => sprintf( __( 'Create the point #%d with the content : %s for the task #%d', 'task-manager'), $point->id, $point->content, $object_id ),
			), 0 );

		$custom_class = 'wpeo-task-point-sortable';
		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point' ) );

		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	 * Supprimes un point grâce à son point ID. Supprimes également le temps passé dans
	 * la tâche en rapport avec ce point. Enlèves le point de order_point_id dans
	 * la tâche et met à jours la tâche. / Delete an point by its point ID. Also delete
	 * the elapsed time in the task in connection with this point. Take off the point
	 * in order_point_id in the task and update this.
	 *
	 * @param int $_POST['post_id'] ID de la tâche / The task ID
	 * @param int $_POST['point_id'] ID du point / The point ID
	 *
	 * @return json task_mdl_01 Object
	 */
	public function ajax_delete_point() {
		wpeo_check_01::check( 'wpeo_nonce_delete_point_' . $_POST['point_id'] );

		global $task_controller;
		global $point_controller;

		$point 	= $point_controller->show( $_POST['point_id'] );
		$task 	= $point_controller->decrease_time( $_POST['point_id'] );

		if( ( $key = array_search( $_POST['point_id'], $task->option['task_info']['order_point_id'] ) ) !== false ) {
			unset( $task->option['task_info']['order_point_id'][$key] );
		}

		$task_controller->update( $task );

		/** Log la suppression du point / Log the deletion of point */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $_POST['point_id'],
			'message' => sprintf( __( 'The point #%d was deleted for the task #%d. The elapsed time for this point was %d minute(s). The elapsed time for this task is now %d minute(s)', 'task-manager'), $point->id, $task->id, $point->option['time_info']['elapsed'], $task->option['time_info']['elapsed'] ),
		), 0 );

		wp_send_json_success( array( 'task' => $task, 'task_header_information' => apply_filters( 'task_header_information', '', $task ) ) );
	}

	/**
	 * Met à jour le contenu d'un point. / Update the content of point.
	 *
	 * @param int $_POST['point']['post_id'] L'ID du post / The post ID
	 * @param int $_POST['point']['id'] L'ID du point / The point ID
	 * @param bool $_POST['point']['option']['point_info']['completed'] Le point est-il fait ? / The point is done ?
	 * @param string $_POST['point']['content'] Le contenu du point / The point content
	 *
	 * @return void
	 */
	public function ajax_edit_point() {
		wpeo_check_01::check( 'wpeo_nonce_edit_point_' . $_POST['point']['id'] );
			global $point_controller;

			$point = $point_controller->show( $_POST['point']['id'] );

			if( $_POST['point']['option']['point_info']['completed'] ) {
				$point->option['time_info']['completed_point'][get_current_user_id()][] = current_time( 'mysql' );
			}
			else {
				$point->option['time_info']['uncompleted_point'][get_current_user_id()][] = current_time( 'mysql' );
			}

			$_POST['point']['option']['time_info']['completed_point'] = $point->option['time_info']['completed_point'];
			$_POST['point']['option']['time_info']['uncompleted_point'] = $point->option['time_info']['uncompleted_point'];

			$point_controller->update( $_POST['point'] );

			taskmanager\log\eo_log( 'wpeo_project',
			array(
				'object_id' => $_POST['point']['id'],
				'message' => sprintf( __( 'The point #%d was updated with the content : %s and set to completed : %s', 'task-manager'), $_POST['point']['id'], $_POST['point']['content'], $_POST['point']['option']['point_info']['completed'] ),
			), 0 );

		wp_send_json_success( );
	}

	/**
	 * Met à jour la meta order_point_id d'une tâche / Update the meta order_point_id
	 * on a task.
	 *
	 * @param int $_POST['object_id'] L'ID de la tâche / The task ID
	 * @param array $_POST['order_point_id'] Le tableau avec les id des points / The array of
	 * id points
	 *
	 * @return void
	 */
	public function ajax_edit_order_point() {
		global $task_controller;
		global $point_controller;

		$object_id = (int) $_POST['object_id'];

		$task = $task_controller->show( $object_id );
		$list_point = $point_controller->index( $task->id, array( 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );

		/** Get all completed point in the task for don't forget it in the order point id */
		if( !empty( $list_point ) ) {
			foreach( $list_point as $point ) {
				if( $point->option['point_info']['completed'] )
					$_POST['order_point_id'][] = (int)$point->id;
			}
		}

		if( !empty( $_POST['order_point_id'] ) ){
			foreach( $_POST['order_point_id'] as $key => $id ) {
				$_POST['order_point_id'][$key] = (int) $id;
			}
		}

		$task->option['task_info']['order_point_id'] = $_POST['order_point_id'];
		$task_controller->update( $task );

		wp_die();
	}

	/**
	 * Charges les points complétés d'une tâche et renvoie la vue.
	 *
	 * @return void
	 */
	public function ajax_load_completed_point() {
		check_ajax_referer( 'load_completed_point' );
		global $task_controller, $point_controller;

		$object_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$task = $task_controller->show( $object_id );
		$list_point_completed = array();

		if ( ! empty( $task->option['task_info']['order_point_id'] ) ) {
			$list_point = $point_controller->index( $task->id, array( 'orderby' => 'comment__in', 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
			$list_point_completed = array_filter( $list_point, function( $point ) {
				return true === $point->option['point_info']['completed'];
			} );
		}

		ob_start();
		if ( ! empty( $list_point_completed ) ) {
			foreach ( $list_point_completed as $point ) {
				require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point' ) );
			}
		}
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	/**
	 * Charge le tableau de bord du point
	 */
	public function ajax_load_dashboard_point() {
		wpeo_check_01::check( 'wpeo_nonce_load_dashboard_point_' . $_POST['point_id'] );

		global $task_controller;
		global $point_controller;
		global $time_controller;

		$task 				= $task_controller->show( $_POST['task_id'] );
		$point 				= $point_controller->show( $_POST['point_id'] );
		$list_time 		=	$time_controller->index( $taks->id, array( 'parent' => $point->id, 'status' => -34070 ) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'window-point' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	public function ajax_create_point_time() {
		wpeo_check_01::check( 'wpeo_nonce_create_point_time_' . $_POST['point_time']['parent_id'] );

		global $point_time_controller;

		$response = array();

		$_POST['point_time']['date'] .= ' ' . current_time( 'h:i:s' );

		if ( !empty( $_POST['point_time_id'] ) ) {
			/** Edit the point */
			$point_time 									= $point_time_controller->show( $_POST['point_time_id'] );
			$point_time->option['time_info']['old_elapsed'] = $point_time->option['time_info']['elapsed'];
			$point_time->date 								= $_POST['point_time']['date'];
			$point_time->option['time_info']['elapsed'] 	= $_POST['point_time']['option']['time_info']['elapsed'];
			$point_time->content 							= $_POST['point_time']['content'];

			$response = $point_time_controller->update($point_time);
		}
		else {
			/** Add the point */
			$_POST['point_time']['status'] = '-34070';

			$response 	= $point_time_controller->create( $_POST['point_time'] );
			$point_time = $point_time_controller->show( $response['point_time_id'] );
		}

		$list_user_in = array();

		if ( !empty( $point_time ) ) {
			$list_user_in[$point_time->author_id] = get_userdata( $point_time->author_id );
		}

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point', 'time' ) );
		$response['template'] = ob_get_clean();

		wp_send_json_success( $response );
	}

	/**
	 * Create point time for WPShop client
	 */
	public function ajax_create_point_time_client() {
		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'wpeo_nonce_create_point_time_frontend_' . $_POST['point_time']['parent_id'] ) ) {
			wp_send_json_error();
		}

		$edit = false;

		global $point_time_controller;

		if ( !empty( $_POST['point_time']['id'] ) ) {
			$point_time 			= $point_time_controller->show( $_POST['point_time']['id'] );
			$point_time->content 	= $_POST['point_time']['content'];

			$point_time_controller->update( $point_time );
			$edit = true;
			taskmanager\log\eo_log( 'wpeo_project',
			array(
			'object_id' => $_POST['point']['id'],
			'message' => sprintf( __( 'The point #%d was updated with the content : %s and set to completed : %s', 'task-manager'), $_POST['point']['id'], $_POST['point']['content'], $_POST['point']['option']['point_info']['completed'] ),
			), 0 );
		}
		else {
			$_POST['point_time']['status'] = '-34070';
			$_POST['point_time']['date'] = current_time( 'mysql' );

			$response 	= $point_time_controller->create( $_POST['point_time'] );
			$point_time = $point_time_controller->show( $response['point_time_id'] );
		}

		$list_user_in 		= array();

		if ( empty( $list_user_in[$point_time->author_id] ) ) {
			$list_user_in[$point_time->author_id] = get_userdata( $point_time->author_id );
		}

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'frontend', 'point', 'time' ) );
		wp_send_json_success( array( 'template' => ob_get_clean(), 'edit' => $edit, ) );
	}

	public function ajax_get_point_time() {
		wpeo_check_01::check( 'wpeo_nonce_get_point_time_' . $_POST['point_time_id'] );

		if (  0 === is_int( ( int )$_POST['point_time_id'] ) )
			wp_send_json_error();
		else
			$point_time_id = $_POST['point_time_id'];

		global $point_time_controller;
		$point_time = $point_time_controller->show( $point_time_id );

		$date = explode( ' ', $point_time->date );

		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'edit', 'point-time' ) );
		wp_send_json_success( array( 'point_time_id' => $point_time_id, 'template' => ob_get_clean() ) );
	}

	public function ajax_delete_point_time() {
		wpeo_check_01::check( 'wpeo_nonce_delete_point_time_' . $_POST['point_time_id'] );

		global $point_time_controller;

		$response = array();
		$task = $point_time_controller->delete( $_POST['point_time_id'] );

		wp_send_json_success( array( 'task' => $task ) );
	}

	public function ajax_send_point_to_task() {
		if( empty( $_POST['element_id'] ) )
			wp_send_json_error();

		global $task_controller;
		$current_task = $task_controller->show( $_POST['current_task_id'] );
		$task = $task_controller->show( $_POST['element_id'] );

		if( $current_task->id == 0 || $task->id == 0 || $current_task->status == 'trash' || $task->status == 'trash' )
			wp_send_json_error();

		if( ( $key = array_search( $_POST['point_id'], $current_task->option['task_info']['order_point_id'] ) ) !== false ) {
			unset( $current_task->option['task_info']['order_point_id'][$key] );
		}

		$task->option['task_info']['order_point_id'][] = $_POST['point_id'];

		$task_controller->update( $current_task );
		$task_controller->update( $task );

		global $point_controller;
		$point = $point_controller->show( $_POST['point_id'] );
		$point->post_id = $task->id;
		$point_controller->update( $point );

		$point_controller->send_comment_to( $current_task->id, $task->id, $_POST['point_id'] );

		$object_id = $task->id;

		$current_task = $task_controller->update_time( $current_task->id );
		$task = $task_controller->update_time( $task->id );

		$custom_class = ' wpeo-task-point-sortable ';



		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point' ) );
		wp_send_json_success(
			array(
				'template' => ob_get_clean(),
				'current_task_id' => $current_task->id,
				'to_task_id' => $task->id,
				'point_id' => $_POST['point_id'],
				'current_task_time' => $current_task->option['time_info']['elapsed'],
				'task_time' => $task->option['time_info']['elapsed']
			)
		);
	}

	public function ajax_load_last_comment( $page ) {
		$_POST['page'] = !( empty( $page ) ) ? $page : $_POST['page'];

		if ( empty( $_POST['page'] ) )
			wp_send_json_error();

		global $point_time_controller;
		global $point_controller;

		$list_comment = $point_time_controller->get_all_comment( 'comment.comment_date', 'DESC', 5, $_POST['page'] - 1 );
		$count_comment = $point_time_controller->get_count_comment();

		$list_user_in = array();

		if ( !empty( $list_comment ) ) {
			foreach ( $list_comment as $comment ) {
				if ( empty( $list_user_in[$comment->author_id] ) ) {
					$list_user_in[$comment->author_id] = get_userdata( $comment->author_id );
				}
			}
		}

		$current_page = $_POST['page'];
		$number_paginate = ceil( $count_comment / 5 );

		ob_start();
		if ( !empty( $count_comment ) ) {
			require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'last', 'comment' ) );
		}
		if( !empty( $page ) ) {
			return ob_get_clean();
		}
		else {
			wp_send_json_success( array( 'template' => ob_get_clean() ) );
		}
	}
}

global $point_action;
$point_action = new point_action_01();
