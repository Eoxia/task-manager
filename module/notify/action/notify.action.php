<?php
/**
 * Les actions relatives aux notifications.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

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
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 13 );

		add_action( 'init', array( $this, 'register_notification_type' ) );

		add_action( 'wp_ajax_load_notify_popup', array( $this, 'callback_load_notify_popup' ) );
		add_action( 'wp_ajax_send_notification', array( $this, 'callback_send_notification' ) );

		add_action( 'wp_ajax_tm_close_notification', array( $this, 'close_notification' ) );

		add_action( 'wp_ajax_tm_notification_all_read', array( $this, 'mark_all_as_read' ) );
	}

	/**
	 * Définition du menu "Notification" dans l'administration de WordPress.
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function callback_admin_menu() {
		CMH::register_menu( 'wpeomtm-dashboard', __( 'Notification', 'task-manager' ), __( 'Notification', 'task-manager' ), 'manage_task_manager', 'tm-notification', array( Notify_Class::g(), 'display' ), 'fas fa-bell', '' );
	}

	public function register_notification_type() {
		register_post_type( 'wpeo-notification' );
	}

	/**
	 * Récupères la vue 'backend/main' ainsi que les utilisateurs dont le role est 'administrateur'.
	 *
	 * @since 1.5.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_load_notify_popup() {
		check_ajax_referer( 'load_notify_popup' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);

		$followers = Follower_Class::g()->get(
			array(
				'role' => 'administrator',
			)
		);

		$affected_id = $task->data['user_info']['affected_id'];

		$owner_data = get_userdata( $task->data['user_info']['owner_id'] );

		if ( ! in_array( $task->data['user_info']['owner_id'], $affected_id, true ) && in_array( 'administrator', (array) $owner_data->roles, true ) ) {
			$affected_id[] = $task->data['user_info']['owner_id'];
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'notify',
			'backend/main',
			array(
				'followers'   => $followers,
				'task'        => $task,
				'affected_id' => $affected_id,
			)
		);
		$popup_view = ob_get_clean();
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'notify',
			'backend/button',
			array(
				'task' => $task,
			)
		);
		$buttons_view = ob_get_clean();
		wp_send_json_success(
			array(
				'view'         => $popup_view,
				'buttons_view' => $buttons_view,
			)
		);
	}

	/**
	 * Envoie une notification par email au responsable et followers de la tâche avec en contenant du mail:
	 * Le nom de la tâche, les points, et des liens rapides pour y accéder.
	 *
	 * @return void
	 *
	 * @since 1.5.0
	 * @version 1.6.0
	 */
	public function callback_send_notification() {
		check_ajax_referer( 'send_notification' );

		$id           = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$users_id     = ! empty( $_POST['users_id'] ) ? sanitize_text_field( $_POST['users_id'] ) : '';
		$customers_id = ! empty( $_POST['customers_id'] ) ? sanitize_text_field( $_POST['customers_id'] ) : '';
		$data         = ! empty( $_POST ) ? (array) $_POST : array();

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);

		$sender_data = wp_get_current_user();
		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name   = get_bloginfo( 'name' );

		$recipients = array();

		if ( ! empty( $users_id ) ) {
			$users_id = explode( ',', $users_id );

			foreach ( $users_id as $user_id ) {
				$user_info    = get_userdata( $user_id );
				$recipients[] = $user_info;
			}
		}

		$subject = 'Task Manager: ';

		// translators: La tâche #150 Nouvelle tâche.
		$subject .= sprintf( __( 'The task #%1$d %2$s', 'task-manager' ), $task->data['id'], $task->data['title'] );

		$body = '<p>' . __( 'This mail has been send automaticly', 'task-manager' ) . '</p>';

		$body .= '<h2>';
		// translators: #150 Nouvelle tâche send by username (username@domain.com).
		$body .= sprintf( __( '#%1$d %2$s send by %3$s (%4$s)', 'task-manager' ), $task->data['id'], $task->data['title'], $sender_data->user_login, $sender_data->user_email );
		$body .= '</h2>';

		$body  = apply_filters( 'task_points_mail', $body, $task );
		$body .= '<ul>';
		if ( ! empty( $task->data['parent_id'] ) ) {
			$body .= '<li><a href="' . admin_url( 'post.php?action=edit&post=' . $task->data['parent_id'] ) . '">' . __( 'Customer link', 'task-manager' ) . '</a></li>';
		}
		$body .= '<li><a href="' . admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $task->data['id'] ) . '">' . __( 'Task link', 'task-manager' ) . '</a></li>';
		$body .= '</ul>';

		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );
		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		$recipients = apply_filters( 'task_manager_notify_send_notification_recipients', $recipients, $task, $data );
		$subject    = apply_filters( 'task_manager_notify_send_notification_subject', $subject, $task, $data );
		$body       = apply_filters( 'task_manager_notify_send_notification_body', $body, $task, $data );

		if ( ! empty( $recipients ) && ! empty( $subject ) && ! empty( $body ) ) {
			if ( ! empty( $recipients ) ) {
				foreach ( $recipients as $recipient ) {
					$cloned_body = $body;
					if ( in_array( 'administrator', $recipient->roles, true ) ) {
						$cloned_body = apply_filters( 'task_manager_notify_send_notification_body_administrator', $body, $task, $data );
					}

					if ( wp_mail( $recipient->user_email, $subject, $cloned_body, $headers ) ) {
						\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d to %2$s success', $task->data['id'], $recipient->user_email ), 'task-manager' );
					} else {
						\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d to %2$s failed', $task->data['id'], $recipient->user_email ), 'task-manager', 'EO_ERROR' );
					}
				}
			}
		} else {
			\eoxia\LOG_Util::log( sprintf( 'Send the task %1$d failed', $task->data['id'] ), 'task-manager', 'EO_ERROR' );
		}

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'notify',
				'callback_success' => 'sendedNotification',
			)
		);
	}

	public function close_notification() {
		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		update_post_meta( $id, 'read', true );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module'    => 'notify',
			'callback_success' => 'closedNotification'
		) );
	}

	public function mark_all_as_read() {
		$notifications = get_posts( array(
			'post_type'    => 'wpeo-notification',
			'numberposts'  => -1,
			'post_status'  => 'publish',
			'author'       => get_current_user_id(),
			'meta_key'     => 'read',
			'meta_compare' => '!=',
			'meta_value'   => 1,
		) );

		if ( ! empty( $notifications ) ) {
			foreach ( $notifications as $notification ) {
				update_post_meta( $notification->ID, 'read', true );
			}
		}

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module'    => 'notify',
			'callback_success' => 'markedAllAsRead'
		) );
	}

}

new Notify_Action();
