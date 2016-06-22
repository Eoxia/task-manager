<?php

if ( !defined( 'ABSPATH' ) ) exit;

class user_action_01  {
	public function __construct() {
		add_action( 'wp_ajax_wpeo-view-user', array( &$this, 'ajax_view_user' ) );
		add_action( 'wp_ajax_wpeo-update-user', array( &$this, 'ajax_update_user' ) );
		add_action( 'wp_ajax_wpeo-render-edit-owner-user', array( &$this, 'ajax_render_edit_owner_user' ) );
	}

	/**
	 * AJAX - Get all users in this task, get all users in wordpress and display the users views backend/users.php
	 * @param int $_GET['id'] - The task id
	 * @return JSON response
	 */
	public function ajax_view_user() {
		global $task_controller;
		global $wp_project_user_controller;

		$object_id = !empty( $_POST['object_id'] ) ? (int) $_POST['object_id'] : 0;

		if ( $object_id == 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for view user', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_view_user_' . $object_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for view user: invalid nonce', 'task-manager' ) ) );
		}

		$task = $task_controller->show( $object_id );
		$list_user = $wp_project_user_controller->list_user;

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'list', 'user' ) );
		$template = ob_get_clean();

		wp_send_json_success( array( 'template' => $template ) );
	}

	/**
	 * AJAX - Insert the user in the $_POST['user_id'] checked by the form for this task
	 * @param int $_POST['object_id'] The post id
	 * @param int $_POST['user_id'] The user id
	 * @return JSON Response
	 */
	public function ajax_update_user() {
		/** Permet de savoir si l'utilisateur est affecté à la tâche ou pas */
		$affected_to_task = false;
		global $task_controller;

		$object_id = !empty( $_POST['object_id'] ) ? (int) $_POST['object_id'] : 0;
		$user_id_selected = !empty( $_POST['user_id'] ) ? (int) $_POST['user_id'] : 0;
		$selected = !empty( $_POST['selected'] && $_POST['selected'] == 'true' ) ? (bool) $_POST['selected'] : false;

		if ( $object_id == 0 || $user_id_selected == 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for update user', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_update_user_' . $user_id_selected, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for update user: invalid nonce', 'task-manager' ) ) );
		}

		$task = $task_controller->show( $object_id );
		$user_id = get_current_user_id();

		/** Convert all value to integer */
		if ( !empty( $user_id_selected ) ) {
			if ( $selected ) {
				$task->option['user_info']['affected_id'][] = $user_id_selected;

				if ( $user_id == $user_id_selected )
					$affected_to_task = true;
			}
			else {
				$key = array_search( $user_selected_id, $task->option['user_info']['affected_id'] );
				if( $key >= 0 ) {
					array_splice( $task->option['user_info']['affected_id'], $key, 1 );
				}
			}
		}

		$task_controller->update( $task );

		wp_send_json_success( array( 'affected_to_task' => $affected_to_task, 'message' => __( 'User changed', 'task-manager' ) ) );
	}

	/**
	 * AJAX - Si le nonce est correcte, charges la liste de tous les utilisateurs dont le role est
	 * administrateur expecté l'utilisateur dont l'ID correspond à l'ID de $_POST['owner_id'].
	 * Renvoie le template avec la liste des utilisateurs.
	 *
	 * @param int $_POST['task_id'] ID de la tâche
	 * @param int $_POST['owner_id'] ID du responsable
	 * @param string $_POST['_wpnonce'] Sécurité par WordPress
	 * @return JSON Object { 'success': true|false, 'data': { 'template': '' } }
	 */
	public function ajax_render_edit_owner_user() {
		$task_id = !empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		global $task_controller;
		global $wp_project_user_controller;

		if ( $task_id == 0 ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit owner user', 'task-manager' ) ) );
		}

		if ( !check_ajax_referer( 'ajax_render_edit_owner_user_' . $task_id, array(), false ) ) {
			wp_send_json_error( array( 'message' => __( 'Error for edit owner user: invalid nonce', 'task-manager' ) ) );
		}

		$task = $task_controller->show( $task_id );

		$list_user = $wp_project_user_controller->list_user;

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'list', 'user-owner' ) );
		wp_send_json_success( array( 'template' => ob_get_clean() ) );
	}
}

new user_action_01();
