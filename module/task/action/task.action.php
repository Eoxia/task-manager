<?php
/**
 * Les actions relatives aux tâches.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage action
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Les actions relatives aux tâches.
 */
class Task_Action {

	/**
	 * Initialise les actions liées au tâche.
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_create_task', array( $this, 'callback_create_task' ) );
		add_action( 'wp_ajax_delete_task', array( $this, 'callback_delete_task' ) );

		add_action( 'wp_ajax_edit_title', array( $this, 'callback_edit_title' ) );
	}

	/**
	 * Créer une tâche en utilisant le modèle Task_Model.
	 * Renvoie la vue dans la réponse de la requête XHR.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_create_task() {
		check_ajax_referer( 'create_task' );

		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;

		$task = Task_Class::g()->create( array(
			'title' 		=> __( 'New task', 'task-manager' ),
			'parent_id' => $parent_id,
		) );

		ob_start();
		Task_Class::g()->render_task( $task );
		wp_send_json_success( array(
			'module' => 'task',
			'callback_success' => 'createdTaskSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Met le status de la tâche en "trash".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_delete_task() {
		check_ajax_referer( 'delete_task' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array(
				$task_id
			),
		), true );

		$task->status = 'trash';

		Task_Class::g()->update( $task );
		wp_send_json_success( array(
			'module' => 'task',
			'callback_success' => 'deletedTaskSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Changes le titre de la tâche
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_edit_title() {
		check_ajax_referer( 'edit_title' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$title = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		$task->title = $title;
		$task->slug = sanitize_title( $title );

		Task_Class::g()->update( $task );
		wp_send_json_success();
	}
}

new Task_Action();
