<?php
/**
 * Users actions
 *
 * @since 0.1
 * @version 1.3.6.0
 *
 * @package module/user
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Users actions
 */
class User_Action {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_edit_mode_user', array( $this, 'ajax_load_edit_mode_user' ) );
		add_action( 'wp_ajax_save_user', array( $this, 'ajax_save_user' ) );
	}

	/**
	 * Switching the view "user" as edit mode.
	 *
	 * @since 0.1
	 * @version 1.3.6.0
	 */
	public function ajax_load_edit_mode_user() {
		check_ajax_referer( 'load_edit_mode_user' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'include' => array( $task_id ),
		), true );

		$users = Task_Manager_User_Class::g()->get( array(
			'role' => 'administrator',
		) );

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$user->custom['class'] = in_array( $user->id, $task->user_info['affected_id'], true ) ? 'active': '';
			}
		}

		ob_start();
		View_Util::exec( 'user', 'footer/list', array(
			'users' => $users,
			'task' => $task,
		) );
		$template = ob_get_clean();

		wp_send_json_success( array(
			'module' => 'user',
			'callback_success' => 'loadedEditModeUserSuccess',
			'template' => $template,
		) );
	}

	/**
	 * Remplaces le contenu du tableau affected_id de la tâche par celui envoyé.
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function ajax_save_user() {
		check_ajax_referer( 'save_user' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$affected_id = ! empty( $_POST['affected_id'] ) ? (array) $_POST['affected_id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'include' => array( $task_id ),
		), true );

		$task->user_info['affected_id'] = $affected_id;

		wp_send_json_success();
	}
}

new User_Action();
