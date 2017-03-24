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
		add_action( 'init', array( $this, 'callback_init' ) );

		add_action( 'wp_ajax_create_task', array( $this, 'callback_create_task' ) );
		add_action( 'wp_ajax_delete_task', array( $this, 'callback_delete_task' ) );

		add_action( 'wp_ajax_edit_title', array( $this, 'callback_edit_title' ) );
		add_action( 'wp_ajax_change_color', array( $this, 'callback_change_color' ) );

		add_action( 'wp_ajax_load_all_task', array( $this, 'callback_load_all_task' ) );

		add_action( 'wp_ajax_notify_by_mail', array( $this, 'callback_notify_by_mail' ) );
	}

	/**
	 * Initialise le post status "archive".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_init() {
		register_post_status( 'archive' );
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
		View_Util::exec( 'task', 'backend/task', array(
			'task' => $task,
		) );

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

	/**
	 * Changes la couleur de la tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_change_color() {
		check_ajax_referer( 'change_color' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$color = ! empty( $_POST['color'] ) ? sanitize_text_field( $_POST['color'] ) : '';

		$task = Task_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$task->front_info['display_color'] = $color;

		Task_Class::g()->update( $task );

		wp_send_json_success();
	}

	/**
	 * Charges toutes les tâches expecté celles qui sont archivées.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_load_all_task() {
		check_ajax_referer( 'load_all_task' );

		ob_start();
		echo do_shortcode( '[task_manager_dashboard_content]' );
		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'module' => 'task',
			'callback_success' => 'loadedAllTask',
		) );
	}

	/**
	 * Envoie une notification par email au responsable et followers de la tâche avec en contenant du mail:
	 * Le nom de la tâche, les points, et des liens rapides pour y accéder.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_notify_by_mail() {
		check_ajax_referer( 'notify_by_mail' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$sender_data = wp_get_current_user();
		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name = get_bloginfo( 'name' );
		$owner_info = get_userdata( $task->user_info['owner_id'] );

		$recipients = array();
		$recipients[] = $owner_info->user_email;

		if ( ! empty( $task->user_info['affected_id'] ) ) {
			foreach ( $task->user_info['affected_id'] as $user_id ) {
				$user_info = get_userdata( $user_id );
				$recipients[] = $user_info->user_email;
			}
		}

		$subject = 'Task Manager: ';
		$subject .= __( 'The task #' . $task->id . ' ' . $task->title, 'task-manager' );

		$body = __( '<p>This mail has been send automatically</p>', 'task-manager' );
		$body .= '<h2>#' . $task->id . ' ' . $task->title . ' send by ' . $sender_data->user_login . ' (' . $sender_data->user_email . ')</h2>';
		$body = apply_filters( 'task_points_mail', $body, $task );
		$body .= '<ul>';
		if ( ! empty( $task->parent_id ) ) {
			$body .= '<li><a href="' . admin_url( 'post.php?action=edit&post=' . $task->parent_id ) . '">Lien vers le client</a></li>';
		}
		$body .= '<li><a href="' . admin_url( 'admin.php?page=wpeomtm-dashboard&s=' . $task->id ) . '">Lien vers la tâche</a></li>';
		$body .= '</ul>';

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		wp_mail( $recipients, $subject, $body, $headers );

		wp_send_json_success();
	}
}

new Task_Action();
