<?php if ( !defined( 'ABSPATH' ) ) exit;

class point_action_01 {
	public function __construct() {
		add_action( 'wp_ajax_create_point', 				array( $this, 'ajax_create_point' ) );
		add_action( 'wp_ajax_delete_point', 				array( $this, 'ajax_delete_point' ) );
		add_action( 'wp_ajax_edit_point', 					array( $this, 'ajax_edit_point' ) );
		add_action( 'wp_ajax_edit_order_point', 		array( $this, 'ajax_edit_order_point' ) );
		add_action( 'wp_ajax_load_dashboard_point', array( $this, 'ajax_load_dashboard_point' ) );
		add_action( 'wp_ajax_send_point_to_task', 	array( $this, 'ajax_send_point_to_task' ) );
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
		global $task_controller;
		global $point_controller;

		$point = !empty( $_POST['point'] ) ? (array) $_POST['point'] : array();
		$point['post_id'] = (int) $point['post_id'];

		if ( !check_ajax_referer( 'ajax_create_point_' . $point['post_id'], array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create a point: invalid nonce', 'task-manager' ) ) );
		}

		if ( empty( $point ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create a point', 'task-manager' ) ) );
		}

		if ( empty( $point['post_id'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create a point', 'task-manager' ) ) );
		}

		if ( empty( $point['content'] ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for create a point', 'task-manager' ) ) );
		}

		$point['content'] = sanitize_text_field( $point['content'] );
		$point['author_id'] = get_current_user_id();
		$point['status'] = '-34070';
		$point['date'] = current_time( 'mysql' );

		$point = $point_controller->create( $point );

		$task = $task_controller->show( $point->post_id );
		$task->option['task_info']['order_point_id'][] = (int) $point->id;
		$task_controller->update( $task );

		/** Log la création du point / Log the creation of point */
		taskmanager\log\eo_log( 'wpeo_project',
			array(
				'object_id' => $point->post_id,
				'message' => sprintf( __( 'Create the point #%d with the content : %s for the task #%d', 'task-manager'), $point->id, $point->content, $point->post_id ),
			), 0 );

		$custom_class = 'wpeo-task-point-sortable';
		ob_start();
		require_once( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point' ) );

		wp_send_json_success( array( 'template' => ob_get_clean(), 'message' => __( 'Point created', 'task-manager' ) ) );
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
		global $task_controller;
		global $point_controller;

		$point_id = !empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		if ( $point_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for delete a point', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_delete_point_' . $point_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for delete a point: invalid nonce', 'task-manager' ) ) );
		}

		$point 	= $point_controller->show( $point_id );
		$task 	= $point_controller->decrease_time( $point_id );

		if( ( $key = array_search( $point_id, $task->option['task_info']['order_point_id'] ) ) !== false ) {
			unset( $task->option['task_info']['order_point_id'][$key] );
		}
		else {
			wp_send_json_error( array( 'message' => __( 'Error for delete a point: the point does not exist', 'task-manager' ) ) );
		}

		$task_controller->update( $task );

		/** Log la suppression du point / Log the deletion of point */
		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $point_id,
			'message' => sprintf( __( 'The point #%d was deleted for the task #%d. The elapsed time for this point was %d minute(s). The elapsed time for this task is now %d minute(s)', 'task-manager'), $point->id, $task->id, $point->option['time_info']['elapsed'], $task->option['time_info']['elapsed'] ),
		), 0 );

		wp_send_json_success( array( 'task' => $task, 'message' => __( 'Point deleted', 'task-manage' ) ) );
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
		global $point_controller;

		$point_edit_data = !empty( $_POST['point'] ) ? (array) $_POST['point'] : array();

		if ( empty( $point_edit_data ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit a point', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_edit_point_' . $point_edit_data['id'], array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit a point: invalid nonce', 'task-manager' ) ) );
		}

		$point = $point_controller->show( $point_edit_data['id'] );
		$point->id = (int) $point_edit_data['id'];
		$point->content = sanitize_text_field( $point_edit_data['content'] );

		$point->option['point_info']['completed'] = (int) $point_edit_data['option']['point_info']['completed'];

		if ( $point->option['point_info']['completed'] ) {
			$point->option['time_info']['completed_point'][get_current_user_id()][] = current_time( 'mysql' );
		}
		else {
			$point->option['time_info']['uncompleted_point'][get_current_user_id()][] = current_time( 'mysql' );
		}

		$point_controller->update( $point );

		taskmanager\log\eo_log( 'wpeo_project',
		array(
			'object_id' => $point_id,
			'message' => sprintf( __( 'The point #%d was updated with the content : %s and set to completed : %s', 'task-manager'), $point_id, $point->content, $point->option['point_info']['completed'] ),
		), 0 );

		wp_send_json_success( array( 'message' => __( 'Point edited', 'task-manager' ) ) );
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

    if ( !check_ajax_referer( 'ajax_edit_order_point_' . $object_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit order point: invalid nonce', 'task-manager' ) ) );
		}

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

		wp_send_json_success( array( 'message' => __( 'Point order edited', 'task-manager' ) ) );
	}

	/**
	 * Charge le tableau de bord du point
	 */
	public function ajax_load_dashboard_point() {
		global $task_controller;
		global $point_controller;
		global $time_controller;

		$point_id = !empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;
		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( $point_id === 0 || $task_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for load dashboard point', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_load_dashboard_point_' . $point_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for load dashboard point: invalid nonce', 'task-manager' ) ) );
		}

		$task 				= $task_controller->show( $task_id );
		$point 				= $point_controller->show( $point_id );
		$list_time 		=	$time_controller->index( $taks->id, array( 'parent' => $point->id, 'status' => -34070 ) );

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'window-point' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}

	public function ajax_send_point_to_task() {
		global $task_controller;

		$element_id = !empty( $_POST['element_id'] ) ? (int) $_POST['element_id'] : 0;
		$current_task_id = !empty( $_POST['current_task_id'] ) ? (int) $_POST['current_task_id'] : 0;
		$point_id = !empty( $_POST['point_id'] ) ? (int) $_POST['point_id'] : 0;

		if ( $element_id === 0 || $current_task_id === 0 || $point_id === 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for send point to task', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_send_point_to_task_' . $point_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for load dashboard point: invalid nonce', 'task-manager' ) ) );
		}

		$current_task = $task_controller->show( $current_task_id );
		$task = $task_controller->show( $element_id );

		if( $current_task->id == 0 || $task->id == 0 || $current_task->status == 'trash' || $task->status == 'trash' )
			wp_send_json_error();

		if( ( $key = array_search( $point_id, $current_task->option['task_info']['order_point_id'] ) ) !== false ) {
			unset( $current_task->option['task_info']['order_point_id'][$key] );
		}

		$task->option['task_info']['order_point_id'][] = $point_id;

		$task_controller->update( $current_task );
		$task_controller->update( $task );

		global $point_controller;
		$point = $point_controller->show( $point_id );
		$point->post_id = $task->id;
		$point_controller->update( $point );

		$point_controller->send_comment_to( $current_task->id, $task->id, $point_id );

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
				'point_id' => $point_id,
				'current_task_time' => $current_task->option['time_info']['elapsed'],
				'task_time' => $task->option['time_info']['elapsed'],
				'message' => __( 'Point sended', 'task-manager' ),
			)
		);
	}
}

global $point_action;
$point_action = new point_action_01();
