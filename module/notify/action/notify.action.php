<?php
/**
 * Les actions relatives aux notifications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux notifications.
 */
class Notify_Action {

	/**
	 * Initialise les actions liées aux notifications.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'wp_ajax_load_notify_popup', array( $this, 'callback_load_notify_popup' ) );
		add_action( 'wp_ajax_send_notification', array( $this, 'callback_send_notification' ) );
	}

	/**
	 * Récupères la vue 'backend/main' ainsi que les utilisateurs dont le role est 'administrateur'.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_load_notify_popup() {
		check_ajax_referer( 'load_notify_popup' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'id' => $id,
		), true );

		$followers = Follower_Class::g()->get( array(
			'role' => 'administrator',
		) );

		$affected_id = $task->user_info['affected_id'];
		$affected_id[] = $task->user_info['owner_id'];

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/main', array(
			'followers' => $followers,
			'task' => $task,
			'affected_id' => $affected_id,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'notify',
			'callback_success' => 'loadedNotifyPopup',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Envoie une notification par email au responsable et followers de la tâche avec en contenant du mail:
	 * Le nom de la tâche, les points, et des liens rapides pour y accéder.
	 *
	 * @return void
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function callback_send_notification() {
		check_ajax_referer( 'send_notification' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$users_id = ! empty( $_POST['users_id'] ) ? sanitize_text_field( $_POST['users_id'] ) : '';

		if ( empty( $id ) || empty( $users_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'id' => $id,
		), true );

		$sender_data = wp_get_current_user();
		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name = get_bloginfo( 'name' );

		$recipients = array();

		$users_id = explode( ',', $users_id );

		if ( ! empty( $users_id ) ) {
			foreach ( $users_id as $user_id ) {
				$user_info = get_userdata( $user_id );
				$recipients[] = $user_info->user_email;
			}
		}


		$subject = 'Task Manager: ';
		$subject .= __( 'The task #' . $task->id . ' ' . $task->title, 'task-manager' );

		$body = __( '<p>This mail has been send automaticly</p>', 'task-manager' );
		$body .= '<h2>#' . $task->id . ' ' . $task->title . ' send by ' . $sender_data->user_login . ' (' . $sender_data->user_email . ')</h2>';
		$body = apply_filters( 'task_points_mail', $body, $task );
		$body .= '<ul>';
		if ( ! empty( $task->parent_id ) ) {
			$body .= '<li><a href="' . admin_url( 'post.php?action=edit&post=' . $task->parent_id ) . '">Customer link</a></li>';
		}
		$body .= '<li><a href="' . admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $task->id ) . '">Task link</a></li>';
		$body .= '</ul>';

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		$recipients = apply_filters( 'task_manager_notify_send_notification_recipients', $recipients, $task, $_POST );

		if ( wp_mail( $recipients, $subject, $body, $headers ) ) {
			\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d to %2$s success', $task->id, implode( ',', $recipients ) ), 'task-manager' );
		} else {
			\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d to %2$s failed', $task->id, implode( ',', $recipients ) ), 'task-manager', 'EO_ERROR' );
		}

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'notify',
			'callback_success' => 'sendedNotification',
		) );
	}

}

new Notify_Action();
