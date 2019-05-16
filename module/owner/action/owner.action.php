<?php
/**
 * Owners actions
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Users actions
 */
class Owner_Action {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_edit_mode_owner', array( $this, 'ajax_load_edit_mode_owner' ) );
		add_action( 'wp_ajax_switch_owner', array( $this, 'ajax_switch_owner' ) );
	}

	/**
	 * Switching the view "owner" as edit mode.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function ajax_load_edit_mode_owner() {
		check_ajax_referer( 'load_edit_mode_owner' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$users = Follower_Class::g()->get(
			array(
				'role' => 'administrator',
			)
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'owner',
			'backend/dropdown-main',
			array(
				'users'   => $users,
				'task_id' => $task_id,
			)
		);
		$view = ob_get_clean();

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'owner',
				'callback_success' => 'loadedEditModeOwnerSuccess',
				'view'             => $view,
			)
		);
	}

	/**
	 * Changes la valeur de "owner_id" par l'ID reçu.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function ajax_switch_owner() {
		check_ajax_referer( 'switch_owner' );

		$task_id  = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$owner_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) || empty( $owner_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['user_info']['owner_id'] = $owner_id;

		Task_Class::g()->update( $task->data, true );

		ob_start();
		echo do_shortcode( '[task_manager_owner_task task_id=' . $task->data['id'] . ' owner_id=' . $owner_id . ']' );
		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'owner',
				'callback_success' => 'switchedOwnerSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}
}

new Owner_Action();
